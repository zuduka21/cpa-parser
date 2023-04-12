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
use App\Repositories\PartnersRepository;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Log;

class CoreParser
{
    protected $parser;
    protected $model;
    protected $indication;
    protected $partnersRepository;

    public function __construct($indication = '')
    {
        if (!empty($indication)) {
            $this->parser = $this->getParser($indication);
            $this->indication = $indication;
            $this->partnersRepository = app(PartnersRepository::class);
            $this->model = $this->partnersRepository->getByIndication($indication);
        }
    }

    /*path to the parser class, the key is the identifier (*)*/
    protected function getParser($indication)
    {
        $parser = ParsersConfig::Parsers($indication);
        return $parser;
    }

    /*path to the model - parser, and report name, the key is the identifier (*)*/
    public function getBrandModel($indication)
    {
        $brand = ParsersConfig::BrandModels($indication);
        return $brand;
    }

    public function startParser()
    {
        return $this->parser;
    }

    public function updateInfo()
    {

        $error = array();
        //look at the need for a key
        if ($this->startParser() instanceof IWithKeyParser) {
            $key = $this->saveKey();
            if ($key == false) {
                $error[] = $this->model['name'] . " ($this->indication) - login fail.";
                return $error;
            }
        } else {
            $key = null;
        }
        $current_data = date("Y/m/d");
        $finish_data = date('Y/m/d', strtotime($current_data . " - 1 days"));

        if (empty($this->getBrandModel($this->indication))) {
            $error[] = $this->model['name'] . " ($this->indication) - indication was not found in the parser configurations.";
            return $error;
        }
        foreach ($this->getBrandModel($this->indication) as $model_key => $r_model) {
            $brands = $this->startParser()->getBrands();//take all the parser brands that need parsing;
            $parserInfo = array();//create empty array for pars-information;
            $marge_info_array = array();//create empty array for pars-information;
            $shift_date = date("Y/m/d", strtotime($this->model['shift_date']));//a date is set to begin countdown the pars
            $modelBrands = $this->model->brands();//a take everyone attached to the parser (through the brand) model

            foreach ($brands as $brand_key => $brand) {
                //checks and adds a new brand if it is not in the database
                if (empty($modelBrands->where('name', $brand_key)->first())) {
                    $this->createBrand($brand_key);
                }

                $temp_shift_date = $shift_date;
                $while_date = true;
                while ($while_date == true) {
                    //Pars information occurs at 29-days intervals. to meet the demands of some partners.
                    $Date1 = date('Y/m/d', strtotime($temp_shift_date . " + 29 days"));
                    //if date less than 29-days go here
                    if ($Date1 > $current_data) {
                        $temp_shift_date = date('Y/m/d', strtotime($finish_data . " - 29 days"));
                        try {
                            if ($key == null) {
                                $result = $this->startParser()->getInfo($brand, $r_model['report_name'], $temp_shift_date, $finish_data, $this->model['login'], $this->model['password']);
                            } else {
                                $result = $this->startParser()->getInfo($this->model['key'], $brand, $r_model['report_name'], $temp_shift_date, $finish_data);
                            }
                        } catch (\Exception $exception) {
                            $result = false;
                        }
                        if (empty($result)) {
                            break;
                        } else if ($result === false) {
                            $error[] = $this->model['name'] . " ($this->indication) - parser did not return data for ($brand_key ).";
                            break;
                        }
                        $parserInfo[$brand_key] = $result;
                        $temp_shift_date = $current_data;
                        $while_date = false;
                    } else {
                        $Date2 = date('Y/m/d', strtotime($Date1 . " - 1 days"));
                        try {
                            if ($key == null) {
                                $result = $this->startParser()->getInfo($brand, $r_model['report_name'], $temp_shift_date, $Date2, $this->model['login'], $this->model['password']);
                            } else {
                                $result = $this->startParser()->getInfo($this->model['key'], $brand, $r_model['report_name'], $temp_shift_date, $Date2);
                            }
                        } catch (\Exception $exception) {
                            $result = false;
                        }
                        if (empty($result)) {
                            break;
                        } else if ($result == false) {
                            $error[] = $this->model['name'] . " ($this->indication) - parser did not return data for ($brand_key ).";
                            break;
                        }
                        $parserInfo[$brand_key] = $result;
                        $temp_shift_date = $Date1;
                    }
                    if(!empty($marge_info_array[$brand_key])){
                        $marge_info_array[$brand_key] = array_merge($parserInfo[$brand_key], $marge_info_array[$brand_key]);
                    }else{
                        $marge_info_array[$brand_key] = array_merge($parserInfo[$brand_key], $marge_info_array);
                    }
                }
            }
            if (!empty($marge_info_array)) {
                $saveResponse = $this->saveParserInfo($marge_info_array, $model_key);
                if (is_array($saveResponse)) {
                    $error = array_merge($saveResponse, $error);
                }
            }
        }
        $this->saveShiftDate($finish_data);
        if (!empty($error)) {
            return $error;
        }
        return true;
    }

    public function saveKey()
    {
        $key = $this->model['key'];
        if (empty($key)) {
            try {
                $key = $this->startParser()->getApiKey($this->model['login'], $this->model['password']);
            } catch (\Exception $exception) {
                $key = null;
            }
            if (!empty($key) || $key != false || !isset($key) || $key != 0) {
                $this->model['key'] = $key;
                $this->model['working'] = true;
                $this->model->save();
            } else {
                return false;
            }
        }
        return true;
    }

    public function saveShiftDate($date)
    {
        $this->model['shift_date'] = $date;
        $this->model['working'] = true;
        $this->model->save();
    }

    public function createBrand($brand_name)
    {
        $brand = new Brand(['name' => $brand_name, 'partner_id' => $this->model['id']]);
        $this->model->brands()->save($brand);
    }

    public function deleteDuplicates($brand, $model_key)
    {
        $unique = [];
        $duplicates = [];
        $brand_info = $this->getBrandModel($this->indication)[$model_key];
        $model_delete = $brand_info['model'];
        $model = $brand->brandTable($brand_info['model']);
        $data = $model->get();
        try {
            $data->map(function ($brand) use (&$unique, &$duplicates, $brand_info) {
                $dates = sprintf("%s", $brand[$brand_info['date_field']]);
                if (in_array($dates, $unique)) {
                    // address is a duplicate
                    $duplicates[] = $brand['id'];
                } else {
                    $unique[] = $dates;
                }
            });
            $model_delete::destroy($duplicates);
        } catch (\Exception $exception) {
            $error[] = $this->model['name'] . " ($this->indication) - date field is not correct";
            return $error;
        }
        return true;
    }

    public function saveParserInfo($parserInfo, $model_key)
    {
        //dd($parserInfo);
        $date_filed = '';
        try {
            $date_filed = $this->getBrandModel($this->indication)[$model_key]['date_field'];
        } catch (\Exception $exception) {
            $error[] = $this->model['name'] . " ($this->indication) - date_field in config file not found";
            return $error;
        }
        foreach ($parserInfo as $key => $parser) {
            $brand = $this->model->brands()->where('name', $key)->first();
            if (empty($brand)) {
                $error[] = $this->model['name'] . " ($this->indication) - \"$key\" model not found";
                return $error;
            }
            $newBrand = array();
            foreach ($parser as $item) {
                if (!empty($item) && is_array($item)) {
                    try {
                        $change_date = date('Y-m-d', strtotime($item[$date_filed]));
                        $item[$date_filed] = $change_date;
                        $newBrand[] = $this->startParser()->getNewModel($item, $model_key);
                    } catch (\Exception $exception) {
                        $error[] = $this->model['name'] . " ($this->indication) - saving database error";
                        return $error;
                    }
                }
            }
            if (!empty($newBrand) && !empty($newBrand[0])) {
                $brand->brandTable($this->getBrandModel($this->indication)[$model_key]['model'])->saveMany($newBrand);
                $deleteResponse = $this->deleteDuplicates($brand, $model_key);
                if (is_array($deleteResponse)) {
                    return $deleteResponse;
                }
            }
        }
        return true;
    }
}