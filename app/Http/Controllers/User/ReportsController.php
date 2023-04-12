<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PartnersRepository;
use App\Repositories\BrandsRepository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Helpers\ParserFieldEmpty;
use App\Http\Parsers\ParsersConfig;

class ReportsController extends Controller
{
    private $partnersRepository;
    private $brandsRepository;
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function index()
    {
        $allow = array();
        $allowType = '';
        if(Auth::user()->NotEmptyCountries()){
            $allow = Auth::user()->countries()->get();
            $allowType = 'countries';
        }if(Auth::user()->NotEmptyPartners()) {
        $allow = Auth::user()->partners()->get();
        $allowType = 'partners';
    }
//        }if(Auth::user()->NotEmptyBrands()){
//            $allow = Auth::user()->brands()->get();
//            $allowType = 'brand';
//        }
        if(Auth::user()->checkRole('admin')){
            $allow =  $this->partnersRepository->getAll();
            $allowType = 'partners';
        }
        $groups = config('reports.groups');
        //dd($allow);
        return view('user.reports.index', array(
            'allow' => $allow,
            'allowType' => $allowType,
            'groups' => $groups,
        ));
    }
    public function getTotal(){
        $first_date = '';
        $last_date = '';
        $allow_id = '';
        $allow_type = '';
        $tracker_id = '';
        $creative_id = '';
        $brands_id = '';
        if ($this->request->has('brands_id')) {
            $brands_id = $this->request['brands_id'];
        }
        if ($this->request->has('creative_id')) {
            $creative_id = $this->request['creative_id'];
        }
        if ($this->request->has('tracker_id')) {
            $tracker_id = $this->request['tracker_id'];
        }
        if (\request()->has('partner_first_date') && !empty(\request('partner_first_date'))) {
            $first_date = \request('partner_first_date');
        }
        if (\request()->has('partner_last_date') && !empty(\request('partner_last_date'))) {
            $last_date = \request('partner_last_date');
        }
        if (\request()->has('allow_id') && !empty(\request('allow_id'))) {
            $allow_id = \request('allow_id');
        }
        if (\request()->has('allow_type') && !empty(\request('allow_type'))){
            $allow_type = \request('allow_type');
        }
        $result = ParserFieldEmpty::getBrandsSumForUser($allow_type,$allow_id, $first_date, $last_date,$tracker_id,$creative_id,$brands_id);
        if (empty($result) || $result === false) {
            $result = ParserFieldEmpty::getEmpty();
        }
        $result['Brand'] = 'TOTAL';
        return response()->json($result);
    }
    public function getReports(){
        $first_date = '';
        $last_date = '';
        $allow_id = '';
        $allow_type = '';
        $tracker_id = '';
        $group_id = '';
        $creative_id = '';
        $brands_id = '';
        if ($this->request->has('brands_id')) {
            $brands_id = $this->request['brands_id'];
        }
        if ($this->request->has('creative_id')) {
            $creative_id = $this->request['creative_id'];
        }
        if ($this->request->has('tracker_id')) {
            $tracker_id = $this->request['tracker_id'];
        }
        if ($this->request->has('group_id')) {
            $group_id = $this->request['group_id'];
        }
        if (\request()->has('partner_first_date') && !empty(\request('partner_first_date'))) {
            $first_date = \request('partner_first_date');
        }
        if (\request()->has('partner_last_date') && !empty(\request('partner_last_date'))) {
            $last_date = \request('partner_last_date');
        }
        if (\request()->has('allow_id') && !empty(\request('allow_id'))) {
            $allow_id = \request('allow_id');
        }
        if (\request()->has('allow_type') && !empty(\request('allow_type'))){
            $allow_type = \request('allow_type');
        }
            $result = ParserFieldEmpty::getBrandsForUser($allow_type,$allow_id, $first_date, $last_date,$tracker_id, $group_id, $creative_id,$brands_id);
        if (empty($result) || $result == false) {
                $result[] = ParserFieldEmpty::getEmpty();
            }
            $collect = collect($result);
        return DataTables::of($collect)->toJson();
    }

    public function getTrackers()
    {
        $trackers = array();
        $temp = array();
        $allow_id = '';
        $allow_type = '';
        if (\request()->has('allow_id') && !empty(\request('allow_id'))) {
            $allow_id = \request('allow_id');
        }
        if (\request()->has('allow_type') && !empty(\request('allow_type'))){
            $allow_type = \request('allow_type');
        }
        $brands = Auth::user()->getAllowBrands($allow_type, $allow_id);
        if(!empty($brands)) {
            foreach ($brands as $brand) {
                $partner = $brand->partner()->first();
                $models_config = $brand->brandModels($partner['indication']);
                $temp_models_config = $brand->brandModels($partner['indication']);

                if (!empty($temp_models_config)) {
                        $model_config = array_shift($temp_models_config);
                        $temp = array_keys($models_config);
                        $report = array_shift($temp);
                } else {
                    return false;
                }
                $all_fields = config('parser.' . $report);
                if (!empty($all_fields['Tracker'])) {
                    $tracker_field = $all_fields['Tracker'];
                    $temps = $brand->brandTable($model_config['model'])->get($tracker_field)->unique()->toArray();
                    foreach ($temps as $item) {
                        $flip = array_flip($item);
                        $temp = array_keys($flip);
                    }
                    $trackers = array_merge($temp, $trackers);
                }
            }
            return response()->json($trackers);
        }
        return 'false';
    }

    public function getBrands(){
        $allow_id = '';
        $allow_type = '';
        if (\request()->has('allow_id') && !empty(\request('allow_id'))) {
            $allow_id = \request('allow_id');
        }
        if (\request()->has('allow_type') && !empty(\request('allow_type'))){
            $allow_type = \request('allow_type');
        }
        $brands = Auth::user()->getAllowBrands($allow_type, $allow_id);
        if(!empty($brands)){
            return response()->json($brands->toArray());
        }
        return 'false';
    }
}
