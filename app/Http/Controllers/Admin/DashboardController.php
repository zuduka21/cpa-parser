<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Parsers\ParsersConfig;
use Illuminate\Http\Request;
use App\Repositories\PartnersRepository;
use App\Repositories\BrandsRepository;
use App\Helpers\DashBoard;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
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
        return view('admin.dashboard.index', array(
            'partners' => $partners->reverse()
        ));
    }

    public function getInfo()
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
            if ($this->request['partner_id'] == 0) {
                $partner = 0;
            } else {
                $partner = $this->partnersRepository->getModel($this->request['partner_id']);
            }
            //$model_config = ParsersConfig::BrandModels($partner['indication']);
            //$reports = array_keys($model_config);
            $response = DashBoard::AllBrandsForPartner($partner, $first_date, $last_date);
            //Clicks, Total Profit, Signups, FTDC
            return response()->json($response);

        }
        return 'false';
    }
}
