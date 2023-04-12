<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartnersUpdateRequest;
use App\Http\Requests\PartnersStoreRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PartnersRepository;
use App\Http\Parsers\CoreParser;
use App\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ParserController extends Controller
{
    private $partnersRepository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->partnersRepository = app(PartnersRepository::class);
    }

    public function index()
    {
        return view('admin.partners.index', array());
    }

    public function getPartners(){
        $partners = $this->partnersRepository->getPartners();
        return DataTables::of($partners)->
        addColumn('buttons', 'components.partner_buttons')->
        addColumn('status', 'components.partner_status_buttons')->
        rawColumns(['buttons','status'])->
        toJson();
    }

    public function create()
    {
        $partner = new Partner();
        return view('admin.partners.create', array('partner' => $partner));
    }

    public function store(PartnersStoreRequest $request)
    {
        $partner = (new Partner())->create($request->all());
        if (!empty($partner)) {
            Log::info('partner', [$partner['indication'] => 'Has be created','userId'=>Auth::id()]);
            return redirect()->route('admin_partners_index');
        }
        return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $partner = $this->partnersRepository->getModel($id);
            if (!empty($partner)) {
                Log::info('partner', [$partner['indication'] => 'Has be deleted','userId'=>Auth::id()]);
                $partner->delete();
            }
        }
        return redirect()->route('admin_partners_index');
    }

    public function edit($indication)
    {
        $partner = $this->partnersRepository->getEditByIndication($indication);
        if (empty($partner)) {
            return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
        }
        return view('admin.partners.edit', array('partner' => $partner));
    }

    public function update(PartnersUpdateRequest $request)
    {
        $inputeData = $request->all();
        if(!isset($inputeData['working']) || empty($inputeData['working']) || $inputeData['working'] == false){
            $inputeData['working'] = false;
        }else{
            $inputeData['working'] = true;
        }
        $partner = $this->partnersRepository->getEdit($inputeData['id']);
        if (!empty($partner)) {
            $partner->fill($inputeData);
            $partner->save();
            Log::info('partner', [$partner['indication'] => 'Has be changed','userId'=>Auth::id()]);
            return redirect()->route('admin_partners_index');
        }
        return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
    }

    public function updateAll(){
        $partners = $this->partnersRepository->getAllPartners();
        $error = array();
        foreach ($partners as $partner){
            $parser = new CoreParser($partner['indication']);
            $response = $parser->updateInfo();
            if(is_array($response)){
                Log::error('parsers', [$partner['indication'] => $response]);
                $error = array_merge($response, $error);
            }else{
                Log::info('parsers', [$partner['indication'] => 'Information was be update']);
            }
        }
        return redirect()->route('admin_partners_index')->withErrors($error);
    }
}
