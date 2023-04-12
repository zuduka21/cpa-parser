<?php

namespace App\Exports;

use App\Repositories\CountriesRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllowCountryExport implements WithMultipleSheets
{
    private $countriesRepository;
    private $country;
    private $first_date;
    private $last_date;
    private $group_id;
    private $tracker_id;
    private $creative_id;
    private $brands_id;

    public function __construct($country, $first_date = '', $last_date = '', $group_id = '', $tracker_id = '', $creative_id = '', $brands_id = '')
    {
        $this->country = $country;
        $this->group_id = $group_id;
        $this->tracker_id = $tracker_id;
        $this->creative_id = $creative_id;
        $this->brands_id = $brands_id;
        $this->first_date = $first_date;
        $this->last_date = $last_date;
        $this->countriesRepository = app(CountriesRepository::class);
    }

    public function sheets(): array
    {
        $sheets = array();
        $brands = $this->country->brands()->get();
        foreach ($brands as $brand){
            $sheets[] = new CountryBrandsSheet($brand,$this->first_date, $this->last_date);
        }
        return $sheets;
    }
}
