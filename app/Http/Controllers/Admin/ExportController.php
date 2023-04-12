<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PartnerExport;
use App\Exports\PartnerExportGeneral;
use App\Helpers\ParserFieldEmpty;
use App\Http\Parsers\ParsersConfig;
use App\Repositories\BrandsRepository;
use App\Repositories\PartnersRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    private $partnersRepository;
    private $brandsRepository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('export.access')->except(['index']);
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function index()
    {
        return view('admin.export.index', array());
    }

    public function export(Request $request)
    {
        $report = '';
        $first_date = '';
        $last_date = '';
        $group_id = '';
        $tracker_id = '';
        $creative_id = '';
        $brands_id = '';
        if ($request->has('brands_id')) {
            $brands_id = $request['brands_id'];
        }
        if ($request->has('creative_id')) {
            $creative_id = $request['creative_id'];
        }
        if ($request->has('tracker_id') && !empty($request['tracker_id'])) {
            $tracker_id = $request['tracker_id'];
        }
        if ($request->has('group_id')) {
            $group_id = $request['group_id'];
        }
        if ($request->has('report_id')) {
            $report = $request['report_id'];
        }
        if ($request->has('first_date')) {
            $first_date = $request['first_date'];
        }
        if ($request->has('last_date')) {
            $last_date = $request['last_date'];
        }
        if ($request->has('id')) {
            $partner_id = $request['id'];
            $partner = $this->partnersRepository->getModel($partner_id);
            Log::info('export', [$partner['indication'] => 'The data was exported', 'userId' => Auth::id()]);
            return Excel::download(new PartnerExport($partner, $report, $first_date, $last_date,$group_id,$tracker_id,$creative_id,$brands_id), $partner['name'] . '.xlsx');
        }
        return false;
    }

    public function exportGeneral(Request $request)
    {
        $first_date = '';
        $last_date = '';
        $group_id = '';
        $tracker_id = '';
        $creative_id = '';
        $brands_id = '';
        if ($request->has('brands_id')) {
            $brands_id = $request['brands_id'];
        }
        if ($request->has('creative_id')) {
            $creative_id = $request['creative_id'];
        }
        if ($request->has('tracker_id') && !empty($request['tracker_id'])) {
            $tracker_id = $request['tracker_id'];
        }
        if ($request->has('group_id')) {
            $group_id = $request['group_id'];
        }
        if ($request->has('first_date')) {
            $first_date = $request['first_date'];
        }
        if ($request->has('last_date')) {
            $last_date = $request['last_date'];
        }
        if ($request->has('id')) {
            $partner_id = $request['id'];
            if ($partner_id == 0) {
                $partner = 0;
                $file_name = 'all';
                Log::info('export', ['all' => 'The data was exported', 'userId' => Auth::id()]);
            } else {
                $partner = $this->partnersRepository->getModel($partner_id);
                if(empty($partner)){
                    return redirect()->back();
                }
                $file_name = $partner['name'];
                Log::info('export', [$partner['indication'] => 'The data was exported', 'userId' => Auth::id()]);
            }
            return Excel::download(new PartnerExportGeneral($partner, $first_date, $last_date,$group_id,$tracker_id,$creative_id,$brands_id), $file_name . '.xlsx');
        }
        return redirect()->back();
    }
}
