<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 14.09.2019
 * Time: 16:36
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Self_;
use App\Repositories\PartnersRepository;

class DashBoard
{

    public static function getFieldName($name, $field)
    {
        if (!empty(config("parser.$name.$field"))) {
            $field = config("parser.$name.$field");
            if (!empty($field)) {
                return $field;
            }
        }
        return '';
    }

    public static function ifEmptyField($field, $field_name, $brand, $item)
    {
        $response = '';
        if (!empty($field)) {
            $item_temp = $item[$field];
            if ($item_temp != null) {
                $response = $item_temp;
            } else if ($field_name == 'Brand') {
                $response = $brand['name'];
            } else {
                $response = 0;
            }
        } else if ($field_name == 'Brand') {
            $response = $brand['name'];
        } else {
            $response = 0;
        }
        return $response;
    }

    public static function AllBrandsForPartner($partner, $date, $last_date)
    {
        $compliteArray = array('Clicks' => 0,'Total_Profit' => 0, 'Signups' => 0, 'FTDC' => 0);
        if (is_numeric($partner) && $partner == 0) {
            $partnerRepository = app(PartnersRepository::class);
            $partners = $partnerRepository->getAll();
            foreach ($partners as $partner) {
                $temp = self::getPartnerInfo($partner, $date, $last_date);
                if($temp !== false){
                    $compliteArray['Clicks'] = $compliteArray['Clicks'] + $temp['Clicks'];
                    $compliteArray['Total_Profit'] = $compliteArray['Total_Profit'] + $temp['Total_Profit'];
                    $compliteArray['Signups'] = $compliteArray['Signups'] + $temp['Signups'];
                    $compliteArray['FTDC'] = $compliteArray['FTDC'] + $temp['FTDC'];
                }
            }
        } else {
            $compliteArray = self::getPartnerInfo($partner, $date, $last_date);
        }
        if (empty($compliteArray)) {
            return '';
        }
        return $compliteArray;
    }

    public
    static function checkNumber($item)
    {
        if ($item === '-') {
            return 0;
        }
        try {
            $temp = $item + 1;
        } catch (\Exception $exception) {
            return 0;
        }
        return $item;

    }

    private
    static function getPartnerInfo($partner, $date, $last_date)
    {
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = collect();
        $brands = $partner->brands;
        foreach ($brands as $brand) {
            $models_config = $brand->brandModels($partner['indication']);
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (!empty($temp_models_config)) {
                $model_config = array_shift($temp_models_config);
            } else {
                return false;
            }
            $flip = array_keys($models_config);
            $temp_report = array_shift($flip);
            $temp_all_fields = config('parser.' . $temp_report);
            $dateField = $model_config['date_field'];
            $model = $brand->brandTable($model_config['model']);
            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            $model_info = $model->get();
            foreach ($model_info as $item) {
                $tempArray = collect();
                foreach ($allFields as $field_name) {
                    $arrayKeys = array_keys($models_config);
                    $field = self::getFieldName(array_shift($arrayKeys), $field_name);
                    $tempArray[$field_name] = self::ifEmptyField($field, $field_name, $brand, $item);
                }
                $compliteArray->push($tempArray);
            }
        }
        //dd($compliteArray);
        $responseArray = array();
        $responseArray['Clicks'] = $compliteArray->sum('Clicks');
        $responseArray['Total_Profit'] = $compliteArray->sum('Total_Profit');
        $responseArray['Signups'] = $compliteArray->sum('Signups');
        $responseArray['FTDC'] = $compliteArray->sum('FTDC');
        return $responseArray;
    }

    private
    static function checkArrayOnSpecialSymbols($array, $key)
    {
        if (isset($array[$key]) && $array[$key] != '-') {
            return false;
        }
        return true;
    }
}
