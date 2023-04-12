<?php

namespace App\Http\Controllers\Admin;

use App\Http\Parsers\ParsersConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ParserFieldEmpty;
use App\Http\Requests\PartnersSearchRequest;
use App\Partner;
use App\Repositories\PartnersRepository;
use App\Repositories\BrandsRepository;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    private $partnersRepository;
    private $brandsRepository;
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('check.admin');
        $this->request = $request;
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function index()
    {
        $partners = $this->partnersRepository->getAll();

        $partner = collect([
            'id' => 0,
            'name' => 'All'
        ]);


        $partners->push($partner);
        $groups = config('reports.groups');
        return view('admin.reports.general', array(
            'partners' => $partners->reverse(),
            'groups' => $groups,
        ));
    }

    public function detail()
    {
        $partners = $this->partnersRepository->getAll();
        $groups = config('reports.groups');
        return view('admin.reports.detailed', array(
            'partners' => $partners,
            'groups' => $groups,
        ));
    }

    public function getReports()
    {
        if ($this->request->has('id')) {
            $partner = $this->partnersRepository->getModel($this->request['id']);
            if (!empty($partner)) {
                $model_config = ParsersConfig::BrandModels($partner['indication']);
                $reports = array_keys($model_config);
                return response()->json($reports);
            }
        }
        return 'false';
    }

    public function getBrands()
    {
        if ($this->request->has('id')) {
            $partner = $this->partnersRepository->getModel($this->request['id']);
            if (!empty($partner)) {
                $brands = $partner->brands->toArray();
                return response()->json($brands);
            }
        }
        return 'false';
    }

    public function getTrackers()
    {
        $trackers = array();
        $temp = array();
        $report = '';
        if ($this->request->has('report_id')) {
            $report = $this->request['report_id'];
        }
        if ($this->request->has('id')) {
            $partner = $this->partnersRepository->getModel($this->request['id']);
            if (!empty($partner)) {
                foreach ($partner->brands as $brand) {
                    $models_config = $brand->brandModels($partner['indication']);
                    $temp_models_config = $brand->brandModels($partner['indication']);
                    if (!empty($temp_models_config)) {
                        if (empty($report)) {
                            $model_config = array_shift($temp_models_config);
                            $temp = array_keys($models_config);
                            $report = array_shift($temp);
                        } else {
                            $model_config = $temp_models_config[$report];
                        }
                    } else {
                        return 'false';
                    }
                    $all_fields = config('parser.' . $report);
                    if (!empty($all_fields['Tracker'])) {
                        $tracker_field = $all_fields['Tracker'];
                        $temps = $brand->brandTable($model_config['model'])->get($tracker_field)->unique()->toArray();
                        if(empty($temps)){
                            return 'false';
                        }
                        foreach ($temps as $item) {
                            $flip = array_flip($item);
                            $temp = array_keys($flip);
                        }
                        $trackers = array_merge($temp, $trackers);
                    }else{
                        return 'false';
                    }
                }
                return response()->json($trackers);
            }
        }
        return 'false';
    }

    public function getDetail()
    {
        $first_date = '';
        $last_date = '';
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {
            $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            if (!empty($partner)) {
                if ($this->request->has('report')) {
                    $report = $this->request['report'];
                } else {
                    $model_config = ParsersConfig::BrandModels($partner['indication']);
                    $reports = array_keys($model_config);
                    $report = array_shift($reports);
                }
                $result = ParserFieldEmpty::AllReportBrandsForPartner($partner, $report, $first_date, $last_date);
                $collect = collect($result);
                return DataTables::of($collect)->toJson();
            }
        }
        $result[] = ParserFieldEmpty::getEmpty();
        $collect = collect($result);
        return DataTables::of($collect)->toJson();
    }

    public function getCheckCreative(){
        if ($this->request->has('partner_id')) {
            $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            if (!empty($partner)) {
                if ($this->request->has('report') && !empty($this->request['report'])) {
                    $report = $this->request['report'];
                } else{
                    $model_config = ParsersConfig::BrandModels($partner['indication']);
                    $reports = array_keys($model_config);
                    $report = array_shift($reports);
                }
                $config = config('parser.'.$report);
                if(!empty($config['Creative'])){
                    return "true";
                }
            }
        }
        return "false";
    }

    public function getGeneral()
    {
        $first_date = '';
        $last_date = '';
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
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {
            if ($this->request['partner_id'] == 0) {
                $partner = 0;
            } else {
                $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            }
            $result = ParserFieldEmpty::AllBrandsForPartner($partner, $first_date, $last_date, $tracker_id, $group_id, $creative_id, $brands_id);
            if (empty($result) || $result === false) {
                $collect[] = ParserFieldEmpty::getEmpty();
                return DataTables::of($collect)->toJson();
            }
            $collect = collect($result);
            return DataTables::of($collect)->toJson();
        }
        $result[] = ParserFieldEmpty::getEmpty();
        $collect = collect($result);
        return DataTables::of($collect)->toJson();
    }

    public function getTotal()
    {
        $first_date = '';
        $last_date = '';
        $report = '';
        $tracker_id = '';
        $creative_id = '';
        if ($this->request->has('creative_id')) {
            $creative_id = $this->request['creative_id'];
        }
        if ($this->request->has('tracker_id')) {
            $tracker_id = $this->request['tracker_id'];
        }
        if ($this->request->has('report')) {
            $report = $this->request['report'];
        }
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {
            if ($this->request['partner_id'] == 0) {
                $partner = 0;
            } else {
                $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            }
            $result = ParserFieldEmpty::sumAllBrandsForPartner($partner, $report, $first_date, $last_date, $tracker_id, $creative_id);
            return response()->json($result);
        }
        $result = ParserFieldEmpty::getEmpty();
        $result['Brand'] = 'TOTAL';
        return response()->json($result);
    }

    public function getMargeCount(){
        $first_date = '';
        $last_date = '';
        $tracker_id = '';
        $creative_id = '';
        $brands_id = '';
        $report = '';
        if ($this->request->has('brands_id')) {
            $brands_id = $this->request['brands_id'];
        }
        if ($this->request->has('creative_id')) {
            $creative_id = $this->request['creative_id'];
        }
        if ($this->request->has('tracker_id')) {
            $tracker_id = $this->request['tracker_id'];
        }
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {
            //dd(1111);getMargeCount
            if ($this->request['partner_id'] == 0) {
                $partner = 0;
            } else {
                $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            }
            if ($this->request->has('report')) {
                $report = $this->request['report'];
            } else if(!empty($partner)){
                $model_config = ParsersConfig::BrandModels($partner['indication']);
                $reports = array_keys($model_config);
                $report = array_shift($reports);
            }
            $result = ParserFieldEmpty::GetMargeCount($partner,$report, $first_date, $last_date, $tracker_id, $creative_id, $brands_id);
            if (empty($result) || $result === false) {
                return response()->json('false');
            }
            return response()->json($result);
        }
        return response()->json('false');
    }

    public function getOriginalTotal()
    {
        $first_date = '';
        $last_date = '';
        $report = '';
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
        if ($this->request->has('report')) {
            $report = $this->request['report'];
        }
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {

            $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            if (!empty($partner)) {
                $result = ParserFieldEmpty::sumAllBrandsForOriginalPartner(
                    $partner,
                    $report,
                    $first_date,
                    $last_date,
                    $tracker_id,
                    $creative_id,
                    $brands_id);
                return response()->json($result);
            }
        }
        $result = collect('');
        return response()->json($result);
    }

    public function getOriginalDetail()
    {
        $first_date = '';
        $last_date = '';
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
        if ($this->request->has('partner_first_date')) {
            $first_date = $this->request['partner_first_date'];
        }
        if ($this->request->has('partner_last_date')) {
            $last_date = $this->request['partner_last_date'];
        }
        if ($this->request->has('partner_id')) {
            $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            if (!empty($partner)) {
                if ($this->request->has('report') && !empty($this->request['report'])) {
                    $report = $this->request['report'];
                } else {
                    $model_config = ParsersConfig::BrandModels($partner['indication']);
                    $reports = array_keys($model_config);
                    $report = array_shift($reports);
                }
                $result = ParserFieldEmpty::ReportPartner($partner, $report, $first_date, $last_date,$tracker_id,$group_id,$creative_id,$brands_id);
                $collect = collect($result);
                return DataTables::of($collect)->toJson();
            }
        }
        $collect = collect('');
        return DataTables::of($collect)->toJson();
    }

    public function checkColumnsValid()
    {
        if ($this->request->has('partner_id')) {
            $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            if (!empty($partner)) {
                if ($this->request->has('report')) {
                    $report = $this->request['report'];
                    $report_fields = config('parser.' . $report);
                    $flip = array_flip($report_fields);
                    $report_fields = array_keys($flip);
                    return response()->json($report_fields);
                }
            }
        }
        return 'true';
    }
}
