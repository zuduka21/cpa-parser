<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 06.09.2019
 * Time: 14:15
 */

namespace App\Http\Parsers;

use App\Http\Parsers\Models\Detailed_activity;

class MyAffiliatesParser implements IKeylessParser
{
    public function getNewModel($data,$model_key){
        return new Detailed_activity($data);
    }

    public function getBrands()
    {
        return [
            'betsson' => ['merchant_id' => '45'],
        ];
    }

    public function getInfo($get_brand, $report_name, $shift_date, $finish_date, $login, $password)
    {
        $merchant_id = $get_brand['merchant_id'];
        $shift_date  = date('Y-m-d', strtotime($shift_date));
        $finish_date = date('Y-m-d', strtotime($finish_date));
        $url = "https://affiliates.betssongroupaffiliates.com/statistics.php?p=$merchant_id&d1=$shift_date&d2=$finish_date&cg=&c=&m=&o=&s=&sd=1&mode=xml&sbm=1&auth=basic&dnl=1";
        $tera = $this->login($url,$login,$password);
        if($tera == false){
            return false;
        }
        preg_match_all('#<colDefs>(.+?)</colDefs>#su', $tera, $fields);
        preg_match_all('#<row>(.+?)</row>#su', $tera, $result);

        if(empty($result[0]) || empty($fields[0])){
            return false;
        }
        $xml_fields = new \SimpleXMLElement($fields[0][0]);
        $temp_fields = json_decode(json_encode((array)$xml_fields), TRUE);
        $fields = array();

        foreach ($temp_fields['col'] as $field){
            $fields[] = str_replace(' ','_',$field['def']);
        }

        foreach ($result[0] as $item){
            $xml[] = new \SimpleXMLElement($item);
        }
        $result = array();
        $temp_values = json_decode(json_encode((array)$xml), TRUE);
        foreach ($temp_values as $temp_key=>$temp_value){
            foreach ($temp_value['cell'] as $key=>$value){
                $result[$temp_key][$fields[$key]] = $value['@attributes']['value'];
            }
        }
        return $result;
    }
    function login($url,$login, $pass){
        $ch = curl_init();
        if (strtolower((substr($url, 0, 5)) == 'https')) { // если соединяемся с https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($ch, CURLOPT_REFERER, 'https://affiliates.betssongroupaffiliates.com');
        // cURL будет выводить подробные сообщения о всех производимых действиях
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=" . $login . "&password=" . $pass);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //сохранять полученные COOKIE в файл
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . '/cookie.txt');
        $result = curl_exec($ch);
        curl_close($ch);
        $result = $this->Read($url);
        if(empty($result)){
            return null;
        }else if($result == false){
            return false;
        }
        return $result;
    }
    function Read($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($ch, CURLOPT_REFERER, $url);
        //запрещаем делать запрос с помощью POST и соответственно разрешаем с помощью GET
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //отсылаем серверу COOKIE полученные от него при авторизации
        curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . '/cookie.txt');
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


}