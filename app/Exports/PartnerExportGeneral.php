<?php

namespace App\Exports;

use App\Helpers\ParserFieldEmpty;
use App\Http\Parsers\ParsersConfig;
use App\Repositories\BrandsRepository;
use App\Repositories\PartnersRepository;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PartnerExportGeneral implements WithMultipleSheets
{
    private $partnersRepository;
    private $brandsRepository;
    private $partner;
    private $first_date;
    private $last_date;
    private $group_id;
    private $tracker_id;
    private $creative_id;
    private $brands_id;

    public function __construct($partner, $first_date = '', $last_date = '',$group_id='',$tracker_id='',$creative_id='',$brands_id='')
    {
        $this->partner = $partner;
        $this->brands_id = $brands_id;
        $this->creative_id = $creative_id;
        $this->tracker_id = $tracker_id;
        $this->group_id = $group_id;
        $this->first_date = $first_date;
        $this->last_date = $last_date;
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function sheets(): array
    {
        $sheets = $this->getPartnerSheet($this->partner);
        return $sheets;
    }

    private function getPartnerSheet($partner){
        $sheets[] = new ReportPartnerGeneralSheet(
            $partner,
            $this->first_date,
            $this->last_date,
            $this->group_id,
            $this->tracker_id,
            $this->creative_id,
            $this->brands_id);
        return $sheets;
    }
}
