<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\Brand;
use App\Http\Requests\CountryStoreRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Repositories\CountriesRepository;
use App\Repositories\BrandsRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CountriesController extends Controller
{
    private $countriesRepository;
    private $brandsRepository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->countriesRepository = app(CountriesRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function index()
    {
        return view('admin.countries.index', array());
    }

    public function getCountries()
    {
        $countries = $this->countriesRepository->getAll();
        return DataTables::of($countries)->
        addColumn('buttons', 'components.country_buttons')->
        rawColumns(['buttons'])->
        setRowAttr(['align' => 'center'])->
        toJson();
    }

    public function create()
    {
        $country = new Country();
        $brands = $this->brandsRepository->getAll();

        return view('admin.countries.create', array(
            'country' => $country,
            'brands' => $brands
        ));
    }

    public function store(CountryStoreRequest $request)
    {
        $country = (new Country())->create($request->input());
        if (!empty($country)) {
            if (!empty($request['brands_id'])) {
                $country->brands()->attach($request['brands_id']);
            }
            Log::info('country', [$country['name'] => 'Has be created','userId'=>Auth::id()]);
            return redirect()->route('admin_countries_index');
        }
        return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $country = $this->countriesRepository->getModel($id);
            if (!empty($country)) {
                Log::info('country', [$country['name'] => 'Has be deleted','userId'=>Auth::id()]);
                $country->delete();
            }
        }
        return redirect()->route('admin_countries_index');
    }

    public function edit($id)
    {
        $country = $this->countriesRepository->getEdit($id);
        $brands = $this->brandsRepository->getAll();
        if (empty($country)) {
            return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
        }
        return view('admin.countries.edit', array(
            'country' => $country,
            'brands' => $brands
        ));
    }

    public function update(CountryUpdateRequest $request)
    {
        $inputData = $request->all();
        $country = $this->countriesRepository->FindById($request['id']);
        if (!empty($country)) {
            $country->brands()->detach();
            if (!empty($inputData['brands_id'])) {
                $country->brands()->attach($inputData['brands_id']);
            }
            Log::info('country', [$country['name'] => 'Has be changed','userId'=>Auth::id()]);
            $country->update($inputData);
            return redirect()->route('admin_countries_index');
        }
        return back()->withErrors(['msg' => 'Something went wrong!'])->withInput();
    }

}
