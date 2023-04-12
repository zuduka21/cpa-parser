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

class ParserFieldEmpty
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

    public static function getEmpty()
    {
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = array();
        if (empty($compliteArray)) {
            foreach ($allFields as $field_name) {
                $compliteArray[$field_name] = '-';
            }
        }
        return $compliteArray;
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
                $response = '-';
            }
        } else if ($field_name == 'Brand') {
            $response = $brand['name'];
        } else {
            $response = '-';
        }
        return $response;
    }

    public static function sumAllBrandsForPartner($partner, $report, $date, $last_date, $tracker = '', $creative_id = '')
    {
        $tempArray = array();

        if (is_numeric($partner) && $partner == 0) {
            $partnerRepository = app(PartnersRepository::class);
            $partners = $partnerRepository->getAll();
            foreach ($partners as $partner) {
                $temp = self::getSumOfPartner($partner, $report, $date, $last_date, $tracker, $creative_id);
                $tempArray = array_merge($temp, $tempArray);
            }
        } else {
            $tempArray = self::getSumOfPartner($partner, $report, $date, $last_date, $tracker, $creative_id);
        }
        $complete = array();

        foreach ($tempArray as $item) {
            foreach ($item as $key => $field) {
                if ('Transaction_date' === $key) {
                    $complete[$key] = $field;
                } else if ($field === '-' && self::checkArrayOnSpecialSymbols($complete, $key)) {
                    $complete[$key] = '-';
                } else {
                    $complete[$key] = (isset($complete[$key])) ? self::checkNumber($field) + self::checkNumber($complete[$key]) : $field;
                }
            }
        }
        if (empty($complete)) {
            return false;
        }
        $complete['Brand'] = 'TOTAL';
        return $complete;
    }

    public static function sumAllBrandsForOriginalPartner($partner, $report, $date, $last_date, $tracker, $creative_id, $brands_id)
    {
        $allFields = config('parser.' . $report);
        if (empty($allFields[0])) {
            $flip = array_flip($allFields);
            $allFields = array_keys($flip);
        }
        $compliteArray = array();
        $brands = $partner->brands;
        $dateField = '';
        foreach ($brands as $brand) {
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
            $models_config = $brand->brandModels($partner['indication']);
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (!empty($temp_models_config)) {
                if (empty($report)) {
                    $model_config = array_shift($temp_models_config);
                } else {
                    $model_config = $temp_models_config[$report];
                }
            } else {
                return false;
            }
            $dateField = $model_config['date_field'];
            $model = $brand->brandTable($model_config['model'])->select($allFields);
            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            if (!empty($tracker)) {
                if (!empty($all_fields['Tracker'])) {
                    $tracker_field = $all_fields['Tracker'];
                    $model = $model->where($tracker_field, '=', $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($all_fields['Creative'])) {
                    $tracker_field = $all_fields['Creative'];
                    $model = $model->where($tracker_field, '=', $creative_id);
                } else {
                    break;
                }
            }
            foreach ($allFields as $field) {
                if ($dateField == $field) {
                    $compliteArray[$brand['name']][$field] = $model->min($field) . ' - ' . $model->max($field);
                } else if (!empty($field)) {
                    $compliteArray[$brand['name']][$field] = $model->sum($field);
                } else {
                    $compliteArray[$brand['name']][$field] = '-';
                }
            }
        }
        $tempArray = array();
        foreach ($compliteArray as $item) {
            foreach ($item as $key => $field) {
                if ($dateField === $key) {
                    $tempArray[$key] = $field;
                } else if ($field === '-' && self::checkArrayOnSpecialSymbols($tempArray, $key)) {
                    $tempArray[$key] = '-';
                } else {
                    $tempArray[$key] = (isset($tempArray[$key])) ? self::checkNumber($field) + self::checkNumber($tempArray[$key]) : $field;
                }
            }
        }
        if (empty($tempArray)) {
            return false;
        }
        $response = array();
        foreach ($tempArray as $item) {
            $response[] = $item;
        }
        return $response;
    }

    public static function AllBrandsForPartner($partner, $date, $last_date, $tracker = '', $group_id = '', $creative_id = '', $brands_id = '')
    {
        $compliteArray = array();
        if (is_numeric($partner) && $partner == 0) {
            $partnerRepository = app(PartnersRepository::class);
            $partners = $partnerRepository->getAll();
            foreach ($partners as $partner) {
                $temp = self::getBrandsOfPartner($partner, $date, $last_date, $tracker, $group_id, $creative_id, $brands_id);
                $compliteArray = array_merge($temp, $compliteArray);
            }
        } else {
            $compliteArray = self::getBrandsOfPartner($partner, $date, $last_date, $tracker, $group_id, $creative_id, $brands_id);
        }
        if (empty($compliteArray)) {
            return '';
        }
        return $compliteArray;
    }

    public static function AllReportBrandsForPartner($partner, $report, $date, $last_date)
    {
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = array();
        $brands = $partner->brands;
        foreach ($brands as $brand) {
            $models_config = $brand->brandModels($partner['indication']);
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (!empty($temp_models_config)) {
                $model_config = $temp_models_config[$report];
            } else {
                return false;
            }
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
                $tempArray = array();
                foreach ($allFields as $field_name) {
                    $field = self::getFieldName($report, $field_name);
                    $tempArray[$field_name] = self::ifEmptyField($field, $field_name, $brand, $item);
                }
                $compliteArray[] = $tempArray;
            }
        }
        if (empty($compliteArray)) {
            return false;
        }

        return $compliteArray;
    }

    public static function ReportPartner($partner, $report, $date, $last_date, $tracker, $group_id, $creative_id, $brands_id)
    {
        $compliteArray = array();
        $brands = $partner->brands()->get();
        foreach ($brands as $brand) {
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (empty($report)) {
                // $tempConfig = array_shift($temp_models_config);
                $temp = array_keys($temp_models_config);
                $report = array_shift($temp);
            }
            $parser_config = config('parser.' . $report);
            if (!empty($temp_models_config)) {
                $model_config = $temp_models_config[$report];
            } else {
                return false;
            }
            $dateField = $model_config['date_field'];
            $brandField = '';
            $creativeField = '';
            $trackerField = '';
            if (!empty($parser_config['Brand'])) {
                $brandField = $parser_config['Brand'];
            }
            if (!empty($parser_config['Creative'])) {
                $creativeField = $parser_config['Creative'];
            }
            if (!empty($parser_config['Tracker'])) {
                $trackerField = $parser_config['Tracker'];
            }
            if (!empty($group_id)) {
                if ($group_id == 'Brand') {
                    $group_id = $brandField;
                } else if ($group_id == 'Creative') {
                    $group_id = $creativeField;
                } else if ($group_id == 'Tracker') {
                    $group_id = $trackerField;
                } else if ($group_id == 'Transaction_date') {
                    $group_id = $dateField;
                }
            }
            $model = $brand->brandTable($model_config['model']);
            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            $select_field = config('parser.' . $report);
            if (empty($select_field[0])) {
                $flip = array_flip($select_field);
                $select_field = array_keys($flip);
            }

            $temp_all_fields = config('parser.' . $report);
            //dd($temp_all_fields);
            $model_info = $model->select($select_field)->get();
            //dd($model_info);
            if (!empty($tracker)) {
                if (!empty($temp_all_fields['Tracker'])) {
                    $tracker_field = $temp_all_fields['Tracker'];
                    $model_info = $model_info->where($tracker_field, $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($temp_all_fields['Creative'])) {
                    $tracker_field = $temp_all_fields['Creative'];
                    $model_info = $model_info->where($tracker_field, $creative_id);
                } else {
                    break;
                }
            }
            if (!empty($group_id)) {
                $model_info = $model_info->groupBy($group_id);
                //dd($model_info);
                $temp_array = array();
                $collects = array();
                foreach ($model_info as $item) {
                    foreach ($temp_all_fields as $field_name) {
                        if ($dateField == $field_name) {
                            if ($field_name == $group_id) {
                                $temp_array[$field_name] = $item->max($field_name);
                            } else {
                                $temp_array[$field_name] = $item->min($field_name) . ' - ' . $item->max($field_name);
                            }
                        } elseif ($brandField == $field_name) {
                            $tempString = '';
                            $collection = $item->unique($brandField);
                            foreach ($collection as $key => $temp_item) {
                                if (empty($tempString)) {
                                    $tempString = $temp_item[$brandField];
                                } else {
                                    $tempString = $temp_item[$brandField] . " - " . $tempString;
                                }
                            }
                            $temp_array[$field_name] = $tempString;
                        } else {
                            try {
                                $tempSum = 0;
                                foreach ($item as $key => $temp_item) {
                                    if (is_numeric($temp_item[$field_name])) {
                                        $tempSum = $tempSum + $temp_item[$field_name];
                                    }
                                }
                                $temp_array[$field_name] = $tempSum;
                            } catch (\Exception $exception) {
                                $temp_array[$field_name] = '-';
                                continue;
                            }
                        }

                    }
                    $collects[] = $temp_array;
                }
                $compliteArray = array_merge($collects, $compliteArray);
            } else {
                $temp_arr = $model_info->toArray();
                $compliteArray = array_merge($temp_arr, $compliteArray);
            }
        }
        $changeArray = array();
        foreach ($compliteArray as $items) {
            $tempArray = $items;
            foreach ($items as $key => $item) {
                if ($item !== null) {
                    $tempArray[$key] = $item;
                } else {
                    $tempArray[$key] = '-';
                }
            }
            $changeArray[] = $tempArray;
        }
        //dd($changeArray);
        if (empty($changeArray)) {
            return false;
        }

        return $changeArray;
    }

    static function getBrandsForUser($allowType, $allowId, $date, $last_date, $tracker = '', $group_id = '', $creative_id = '', $brands_id = '')
    {
        $brands = Auth::user()->getAllowBrands($allowType, $allowId);
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = collect();
        foreach ($brands as $brand) {
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
            $partnerIndication = $brand->partner()->first()['indication'];
            $models_config = $brand->brandModels($partnerIndication);
            $temp_models_config = $brand->brandModels($partnerIndication);
            if (!empty($temp_models_config)) {
                $tempConfigKeys = array_keys($temp_models_config);
                $tempConfigKey = array_shift($tempConfigKeys);
                $allReportFields = config('parser.' . $tempConfigKey);
                $model_config = array_shift($temp_models_config);
            } else {
                return false;
            }
            $dateField = $model_config['date_field'];
            $model = $brand->brandTable($model_config['model']);
            $model_info = $model->get();
            if (!empty($date)) {
                $model_info = $model_info->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model_info = $model_info->where($dateField, '<=', $last_date);
            }
            if (!empty($tracker)) {
                //dd($allFields);
                if (!empty($allReportFields['Tracker'])) {
                    $tracker_field = $allReportFields['Tracker'];
                    $model_info = $model_info->where($tracker_field, $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($allReportFields['Creative'])) {
                    $tracker_field = $allReportFields['Creative'];
                    $model_info = $model_info->where($tracker_field, $creative_id);
                } else {
                    break;
                }
            }
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
        if (!empty($group_id)) {
            $compliteArray = $compliteArray->groupBy($group_id);
            $temp_array = array();
            $collects = array();
            foreach ($compliteArray as $item) {
                foreach ($allFields as $field_name) {
                    if ('Transaction_date' == $field_name) {
                        if ($field_name == $group_id) {
                            $temp_array[$field_name] = $item->max($field_name);
                        } else {
                            $temp_array[$field_name] = $item->min($field_name) . ' - ' . $item->max($field_name);
                        }
                    } elseif ('Brand' == $field_name) {
                        $tempString = '';
                        $collection = $item->unique('Brand');
                        foreach ($collection as $key => $temp_item) {
                            if (empty($tempString)) {
                                $tempString = $temp_item['Brand'];
                            } else {
                                $tempString = $temp_item['Brand'] . " - " . $tempString;
                            }
                        }
                        $temp_array[$field_name] = $tempString;
                    } else {
                        try {
                            $tempSum = 0;
                            foreach ($item as $key => $temp_item) {
                                if (is_numeric($temp_item[$field_name])) {
                                    $tempSum = $tempSum + $temp_item[$field_name];
                                }
                            }
                            $temp_array[$field_name] = $tempSum;
                        } catch (\Exception $exception) {
                            $temp_array[$field_name] = '-';
                            continue;
                        }
                    }
                }
                $collects[] = $temp_array;
            }
            return $collects;
        }
        if (empty($compliteArray)) {
            return false;
        }

        return $compliteArray->toArray();
    }

    public static function GetMargeCount($partner, $report, $date, $last_date, $tracker, $creative_id, $brands_id)
    {
        $compliteArray = ['name' => 'marge', 'count' => 0];
        if (is_numeric($partner) && $partner == 0) {
            $partnerRepository = app(PartnersRepository::class);
            $partners = $partnerRepository->getAll();
            foreach ($partners as $partner) {
                $temp = self::GetMarge($partner, $report, $date, $last_date, $tracker, $creative_id, $brands_id);
                if ($temp !== false) {
                    $compliteArray['name'] = $temp['name'];
                    $compliteArray['count'] = $temp['count'] + $compliteArray['count'];
                }
            }
        } else {
            $temp = self::GetMarge($partner, $report, $date, $last_date, $tracker, $creative_id, $brands_id);
            if ($temp !== false) {
                $compliteArray['name'] = $temp['name'];
                $compliteArray['count'] = $temp['count'] + $compliteArray['count'];
            }
        }
        if (empty($compliteArray)) {
            return false;
        }
        return $compliteArray;
    }

    public static function GetMarge($partner, $report, $date, $last_date, $tracker, $creative_id, $brands_id)
    {
        $brands = $partner->brands;
        foreach ($brands as $brand) {
            $models_config = $brand->brandModels($partner['indication']);
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (!empty($report)) {
                $model_config = $temp_models_config[$report];
            } else if (!empty($temp_models_config)) {
                $model_config = array_shift($temp_models_config);
            } else {
                return false;
            }
            if (!empty($model_config['marge_count'])) {
                $margeCountField = $model_config['marge_count'];
                $marge_config_model = $models_config[$margeCountField];
                if (empty($marge_config_model)) {
                    return false;
                }
            } else {
                return false;
            }
            $flip = array_keys($models_config);
            $temp_report = array_shift($flip);
            $temp_all_fields = config('parser.' . $temp_report);
            $dateField = $model_config['date_field'];
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
            $margeModel = $brand->brandTable($marge_config_model['model']);
            $model = $brand->brandTable($model_config['model']);
            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            $model_info = $model->get();
            if (!empty($tracker)) {
                if (!empty($temp_all_fields['Tracker'])) {
                    $tracker_field = $temp_all_fields['Tracker'];
                    $model_info = $model_info->where($tracker_field, $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($temp_all_fields['Creative'])) {
                    $tracker_field = $temp_all_fields['Creative'];
                    $model_info = $model_info->where($tracker_field, $creative_id);
                } else {
                    break;
                }
            }
            $tempArray = array();
            foreach ($model_info as $item) {
                $field_name = 'Transaction_date';
                $arrayKeys = array_keys($models_config);
                $field = self::getFieldName(array_shift($arrayKeys), $field_name);
                $tempArray[] = self::ifEmptyField($field, $field_name, $brand, $item);
            }
            if (!empty($marge_config_model['date_field'])) {
                $count = $margeModel->whereIn($marge_config_model['date_field'], $tempArray)->count();
                return ['name' => $margeCountField, 'count' => $count];
            } else {
                return false;
            }
        }
        return false;
    }

    public
    static function getBrandsSumForUser($allowType, $allowId, $date, $last_date, $tracker = '', $creative_id = '', $brands_id = '')
    {
        $brands = Auth::user()->getAllowBrands($allowType, $allowId);
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = array();
        $dateField = '';
        foreach ($brands as $brand) {
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
            $partnerIndication = $brand->partner()->first()['indication'];
            $models_config = $brand->brandModels($partnerIndication);
            $temp_models_config = $brand->brandModels($partnerIndication);
            if (!empty($temp_models_config)) {
                $tempConfigKeys = array_keys($temp_models_config);
                $tempConfigKey = array_shift($tempConfigKeys);
                $allReportFields = config('parser.' . $tempConfigKey);
                $model_config = array_shift($temp_models_config);
            } else {
                return false;
            }
            $dateField = $model_config['date_field'];
            $model = $brand->brandTable($model_config['model']);

            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            if (!empty($tracker)) {
                if (!empty($allReportFields['Tracker'])) {
                    $tracker_field = $allReportFields['Tracker'];
                    $model = $model->where($tracker_field, $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($allReportFields['Creative'])) {
                    $tracker_field = $allReportFields['Creative'];
                    $model = $model->where($tracker_field, $creative_id);
                } else {
                    break;
                }
            }
            foreach ($allFields as $field_name) {
                if (empty($report)) {
                    $arrayKeys = array_keys($models_config);
                    $report_key = array_shift($arrayKeys);
                } else {
                    $report_key = $report;
                }
                $field = self::getFieldName($report_key, $field_name);
                if ($field_name === 'Brand') {
                    $compliteArray[$brand['name']][$field_name] = 'TOTAL';
                } else if ($dateField == $field) {
                    $compliteArray[$brand['name']][$field_name] = $model->min($field) . ' - ' . $model->max($field);
                } else if (!empty($field)) {
                    $compliteArray[$brand['name']][$field_name] = $model->sum($field);
                } else {
                    $compliteArray[$brand['name']][$field_name] = '-';
                }
            }
        }
        $tempArray = array();
        //dd($compliteArray);
        foreach ($compliteArray as $item) {
            foreach ($item as $key => $field) {
                if ('Transaction_date' === $key) {
                    $tempArray[$key] = $field;
                } else if ($field === '-' && self::checkArrayOnSpecialSymbols($tempArray, $key)) {
                    $tempArray[$key] = '-';
                } else {
                    $tempArray[$key] = (isset($tempArray[$key])) ? self::checkNumber($field) + self::checkNumber($tempArray[$key]) : $field;
                }
            }
        }
        if (empty($tempArray)) {
            return false;
        }
        $tempArray['Brand'] = 'TOTAL';
        return $tempArray;
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
    static function getBrandsOfPartner($partner, $date, $last_date, $tracker, $group_id, $creative_id, $brands_id)
    {
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = collect();
        $brands = $partner->brands;
        foreach ($brands as $brand) {
            if (!empty($brands_id)) {
                if ($brands_id != $brand['id']) {
                    continue;
                }
            }
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
            $model_info = $model->get();
            if (!empty($date)) {
                $model_info = $model_info->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model_info = $model_info->where($dateField, '<=', $last_date);
            }
            if (!empty($tracker)) {
                if (!empty($temp_all_fields['Tracker'])) {
                    $tracker_field = $temp_all_fields['Tracker'];
                    $model_info = $model_info->where($tracker_field, $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($temp_all_fields['Creative'])) {
                    $tracker_field = $temp_all_fields['Creative'];
                    $model_info = $model_info->where($tracker_field, $creative_id);
                } else {
                    break;
                }
            }
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
        if (!empty($group_id)) {
            $compliteArray = $compliteArray->groupBy($group_id);
            $temp_array = array();
            $collects = array();
            foreach ($compliteArray as $item) {
                foreach ($allFields as $field_name) {
                    if ('Transaction_date' == $field_name) {
                        if ($field_name == $group_id) {
                            $temp_array[$field_name] = $item->max($field_name);
                        } else {
                            $temp_array[$field_name] = $item->min($field_name) . ' - ' . $item->max($field_name);
                        }
                    } elseif ('Brand' == $field_name) {
                        $tempString = '';
                        $collection = $item->unique('Brand');
                        foreach ($collection as $key => $temp_item) {
                            if (empty($tempString)) {
                                $tempString = $temp_item['Brand'];
                            } else {
                                $tempString = $temp_item['Brand'] . " - " . $tempString;
                            }
                        }
                        $temp_array[$field_name] = $tempString;
                    } else {
                        try {
                            $tempSum = 0;
                            foreach ($item as $key => $temp_item) {
                                if (is_numeric($temp_item[$field_name])) {
                                    $tempSum = $tempSum + $temp_item[$field_name];
                                }
                            }
                            $temp_array[$field_name] = $tempSum;
                        } catch (\Exception $exception) {
                            $temp_array[$field_name] = '-';
                            continue;
                        }
                    }
                }
                $collects[] = $temp_array;
            }
            return $collects;
        }
        return $compliteArray->toArray();
    }

    private
    static function getSumOfPartner($partner, $report, $date, $last_date, $tracker, $creative_id)
    {
        $allFields = config('parser.all_fields_for_partner');
        $compliteArray = array();
        $brands = $partner->brands;
        foreach ($brands as $brand) {
            $models_config = $brand->brandModels($partner['indication']);
            $temp_models_config = $brand->brandModels($partner['indication']);
            if (!empty($temp_models_config)) {
                if (empty($report)) {
                    $model_config = array_shift($temp_models_config);
                } else {
                    $model_config = $temp_models_config[$report];
                }
            } else {
                return ' ';
            }
            $flip = array_keys($models_config);
            $report = array_shift($flip);
            $all_fields = config('parser.' . $report);
            $dateField = $model_config['date_field'];
            $model = $brand->brandTable($model_config['model']);
            if (!empty($date)) {
                $model = $model->where($dateField, '>=', $date);
            }
            if (!empty($last_date)) {
                $model = $model->where($dateField, '<=', $last_date);
            }
            $model = $model->get();
            if (!empty($tracker)) {
                if (!empty($all_fields['Tracker'])) {
                    $tracker_field = $all_fields['Tracker'];
                    $model = $model->where($tracker_field, '=', $tracker);
                } else {
                    break;
                }
            }
            if (!empty($creative_id)) {
                if (!empty($all_fields['Creative'])) {
                    $tracker_field = $all_fields['Creative'];
                    $model = $model->where($tracker_field, '=', $creative_id);
                } else {
                    break;
                }
            }
            foreach ($allFields as $field_name) {
                if (empty($report)) {
                    $arrayKeys = array_keys($models_config);
                    $report_key = array_shift($arrayKeys);
                } else {
                    $report_key = $report;
                }
                $field = self::getFieldName($report_key, $field_name);
                if ($field_name === 'Brand') {
                    $compliteArray[$brand['name']][$field_name] = 'TOTAL';
                } else if ($dateField == $field) {
                    $compliteArray[$brand['name']][$field_name] = $model->min($field) . ' - ' . $model->max($field);
                } else if (!empty($field)) {
                    $compliteArray[$brand['name']][$field_name] = $model->sum($field);
                } else {
                    $compliteArray[$brand['name']][$field_name] = '-';
                }
            }
        }
        return $compliteArray;
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
