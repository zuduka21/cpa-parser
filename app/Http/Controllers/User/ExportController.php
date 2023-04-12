<?php

namespace App\Http\Controllers\User;

use App\Exports\AllowCountryExport;
use App\Http\Controllers\Controller;
use App\Repositories\CountriesRepository;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use App\Repositories\PartnersRepository;
use Illuminate\Support\Facades\Log;
use App\Exports\PartnerExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    private $partnersRepository;
    private $countriesRepository;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('auth');
        $this->middleware('export.access')->except(['index']);
        $this->partnersRepository = app(PartnersRepository::class);
        $this->countriesRepository = app(CountriesRepository::class);
    }

    public function export(){
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
        if (\request()->has('first_date') && !empty(\request('first_date'))) {
            $first_date = \request('first_date');
        }
        if (\request()->has('last_date') && !empty(\request('last_date'))) {
            $last_date = \request('last_date');
        }
        if (\request()->has('export_allow_id') && !empty(\request('export_allow_id'))) {
            $allow_id = \request('export_allow_id');
        }
        if (\request()->has('export_allow_type') && !empty(\request('export_allow_type'))){
            $allow_type = \request('export_allow_type');
        }
        if($allow_type === 'countries'){
            $country = $this->countriesRepository->getByIdOrFirst($allow_id);
            Log::info('export', [$country['name'] => 'The data was exported by ','userId'=>Auth::id()]);
            return Excel::download(new AllowCountryExport($country,  $first_date, $last_date,$group_id,$tracker_id,$creative_id,$brands_id), $country['name'] . '.xlsx');

        }else if($allow_type === 'partners'){
            $partner = $this->partnersRepository->getByIdOrFirst($allow_id);
            Log::info('export', [$partner['indication'] => 'The data was exported by ','userId'=>Auth::id()]);
            return Excel::download(new PartnerExport($partner, '', $first_date, $last_date,$group_id,$tracker_id,$creative_id,$brands_id), $partner['name'] . '.xlsx');
        }
        return '';
    }
}
