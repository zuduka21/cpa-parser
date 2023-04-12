<?php

namespace App\Exports;

use App\Helpers\ParserFieldEmpty;
use App\Http\Parsers\ParsersConfig;
use App\Repositories\BrandsRepository;
use App\Repositories\PartnersRepository;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PartnerExport implements WithMultipleSheets
{
    private $partnersRepository;
    private $brandsRepository;
    private $partner;
    private $report;
    private $first_date;
    private $last_date;
    private $group_id;
    private $tracker_id;
    private $creative_id;
    private $brands_id;

    public function __construct($partner, $report = '', $first_date = '', $last_date = '', $group_id = '', $tracker_id = '', $creative_id = '', $brands_id = '')
    {
        $this->partner = $partner;
        $this->group_id = $group_id;
        $this->tracker_id = $tracker_id;
        $this->creative_id = $creative_id;
        $this->brands_id = $brands_id;
        $this->report = $report;
        $this->first_date = $first_date;
        $this->last_date = $last_date;
        $this->partnersRepository = app(PartnersRepository::class);
        $this->brandsRepository = app(BrandsRepository::class);
    }

    public function sheets(): array
    {
        $sheets = array();
        $model_config = ParsersConfig::BrandModels($this->partner['indication']);
        $reports = array_keys($model_config);
        if (!empty($reports)) {
            if (!empty($this->report)) {
                $sheets[] = new ReportPartnerSheet(
                    $this->partner,
                    $this->report,
                    $this->first_date,
                    $this->last_date,
                    $this->group_id,
                    $this->tracker_id,
                    $this->creative_id,
                    $this->brands_id);
            } else {
                foreach ($reports as $report) {
                    $sheets[] = new ReportPartnerSheet(
                        $this->partner,
                        $this->report,
                        $this->first_date,
                        $this->last_date,
                        $this->group_id,
                        $this->tracker_id,
                        $this->creative_id,
                        $this->brands_id);
                }
            }
        }
        return $sheets;
    }
}
