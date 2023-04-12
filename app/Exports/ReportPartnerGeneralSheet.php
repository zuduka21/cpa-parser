<?php
namespace App\Exports;

use App\Helpers\ParserFieldEmpty;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportPartnerGeneralSheet implements FromArray, WithTitle, WithHeadings
{
    private $partner;
    private $first_date;
    private $last_date;
    private $columns;
    private $group_id;
    private $tracker_id;
    private $creative_id;
    private $brands_id;

    public function __construct($partner,$first_date,$last_date,$group_id,$tracker_id,$creative_id,$brands_id){
        $this->partner = $partner;
        $this->brands_id = $brands_id;
        $this->creative_id = $creative_id;
        $this->group_id = $group_id;
        $this->tracker_id = $tracker_id;
        $this->first_date = $first_date;
        $this->last_date = $last_date;
        $this->columns = config('parser.all_fields_for_partner');

    }

    /**
     * @return string
     */
    public function title(): string
    {
        if(is_numeric($this->partner) && $this->partner == 0){
            return 'Partner - All';
        }
        return 'Partner - ' . $this->partner['name'];
    }

    public function array(): array
    {
       $response = ParserFieldEmpty::AllBrandsForPartner(
           $this->partner,
           $this->first_date,
           $this->last_date,
           $this->tracker_id,
           $this->group_id,
           $this->creative_id,
           $this->brands_id
       );
       if(empty($response) || $response==false){
           return array();
       }
      return $response;
    }

    public function headings(): array
    {
        return $this->columns;
    }
}
