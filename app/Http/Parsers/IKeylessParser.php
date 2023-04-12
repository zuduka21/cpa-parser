<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 06.09.2019
 * Time: 15:12
 */

namespace App\Http\Parsers;

use App\Partner;

interface IKeylessParser
{
    /**
     * Data is transferred after pars, converted and return array (Model).
     *
     * @param  array()  $partner
     * @param  string  $model_key
     * @return array(Brands - Model)
     */
    function getNewModel($data,$model_key);
    /**
     * The function contains and return all the brands that will be selected for this parser.
     *
     * @return array('brand' => ['merchant_id' => ''])
     */
    function getBrands();
    /**
     * The function accepts at the input, the model object with
     * a password and login to the parser and the selected brand, and begin parser date.
     * As a result, it returns all the information after pars as an array.
     *
     * @param  array(merchant_id => '')  $get_brand
     * @param  string  $report_name
     * @param  date()  $shift_date
     * @param  date()  $finish_date
     * @param  string  $login
     * @param  string  $password
     * @return array()
     */
    function getInfo($get_brand,$report_name, $shift_date, $finish_date, $login, $password);

}