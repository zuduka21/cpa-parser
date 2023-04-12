<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 06.09.2019
 * Time: 14:20
 */

namespace App\Http\Parsers;

use App\Partner;
use App\Brand;
use App\Http\Parsers\Models;

class ParsersConfig
{
    /*path to the parser class, the key is the identifier (*)*/
    static public function Parsers($indication)
    {
        $parsers = [
            'income_access' => new IncomeAccessParser(),
            'my_affiliates' => new MyAffiliatesParser(),
        ];
        return $parsers[$indication];
    }

    /*path to the model - parser, and report name, the key is the identifier (*)*/
    static public function BrandModels($indication)
    {
        $parsers = [
            'income_access' => [
                'memberReport' => ['marge_count' => 'player_registrations','date_field'=>'period', 'report_name' => 'Member', 'model' => Models\MemberReport::class],
                'player_registrations' => ['date_field'=>'thedate', 'report_name' => 'PlayerRegistrations', 'model' => Models\Player_registrations::class],
            ],
            'my_affiliates' => [
                'detailed_activity' => ['date_field'=>'Date' ,'report_name' => '', 'model' => Models\Detailed_activity::class],
            ]
        ];
        return $parsers[$indication];
    }
}