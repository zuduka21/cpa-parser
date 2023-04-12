<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CountryBrandsSheet implements FromCollection, WithTitle, WithHeadings
{
    private $brand;
    private $first_date;
    private $last_date;
    private $group_id;
    private $tracker_id;
    private $creative_id;
    private $brands_id;

    public function __construct($brand, $first_date, $last_date, $group_id = '', $tracker_id = '', $creative_id = '', $brands_id = '')
    {
        $this->brand = $brand;
        $this->group_id = $group_id;
        $this->tracker_id = $tracker_id;
        $this->creative_id = $creative_id;
        $this->brands_id = $brands_id;
        $this->first_date = $first_date;
        $this->last_date = $last_date;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Brand - ' . $this->brand['name'];
    }

    public function collection()
    {
        $compliteArray = collect();
        $partnerIndication = $this->brand->partner()->first()['indication'];
        $models_config = $this->brand->brandModels($partnerIndication);
        $temp_models_config = $this->brand->brandModels($partnerIndication);
        if (!empty($temp_models_config)) {
            $model_config = array_shift($temp_models_config);
        } else {
            return '';
        }
        if (empty($report)) {
            // $tempConfig = array_shift($temp_models_config);
            $temp = array_keys($models_config);
            $report = array_shift($temp);
        }
        $dateField = $model_config['date_field'];
        $temp_all_fields = config('parser.' . $report);
        if (!empty($this->brands_id)) {
            if ($this->brands_id != $this->brand['id']) {
                return collect();
            }
        }
        $model = $this->brand->brandTable($model_config['model']);
        $brandField = '';
        $creativeField = '';
        $trackerField = '';
        if (!empty($temp_all_fields['Brand'])) {
            $brandField = $temp_all_fields['Brand'];
        }
        if (!empty($temp_all_fields['Creative'])) {
            $creativeField = $temp_all_fields['Creative'];
        }
        if (!empty($temp_all_fields['Tracker'])) {
            $trackerField = $temp_all_fields['Tracker'];
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

        if (!empty($this->first_date)) {
            $model = $model->where($dateField, '>=', $this->first_date);
        }
        if (!empty($this->last_date)) {
            $model = $model->where($dateField, '<=', $this->last_date);
        }
        $model = $model->select($temp_all_fields)->get();
        if (!empty($tracker)) {
            if (!empty($temp_all_fields['Tracker'])) {
                $tracker_field = $temp_all_fields['Tracker'];
                $model = $model->where($tracker_field, $tracker);
            } else {
                return " ";
            }
        }
        if (!empty($creative_id)) {
            if (!empty($temp_all_fields['Creative'])) {
                $tracker_field = $temp_all_fields['Creative'];
                $model = $model->where($tracker_field, $creative_id);
            } else {
                return " ";
            }
        }
        if (!empty($group_id)) {
            $model_info = $model->groupBy($group_id);
            //dd($model_info);
            $temp_array = collect();
            $collects = collect();
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
            $compliteArray = $compliteArray->merge($collects);
        } else {
            $compliteArray = $compliteArray->merge($model);
        }

        return $compliteArray;
    }

    public function headings(): array
    {
        $partnerIndication = $this->brand->partner()->first()['indication'];
        $models_config = $this->brand->brandModels($partnerIndication);
        $columns = config('parser.' . array_keys($models_config)[0]);
        return $columns;
    }
}
