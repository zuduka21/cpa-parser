<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 06.09.2019
 * Time: 14:15
 */

namespace App\Http\Parsers;

use App\Http\Parsers\Models\MemberReport;
use App\Http\Parsers\Models\Player_registrations;

class IncomeAccessParser implements IWithKeyParser
{
    public function getNewModel($data,$model_key){
        if($model_key == 'memberReport'){
            return new MemberReport($data);
        }else if($model_key == 'playerRegistrations'){
            return new Player_registrations($data);
        }
    }

    public function getBrands()
    {
        return [
            'parimatch' => ['merchant_id' => '1'],
        ];
    }

    public function getInfo($key, $get_brand, $report_name, $shift_date, $finish_date)
    {
        $merchant_id = $get_brand['merchant_id'];
        $startDate = $shift_date;
        $url = "https://partners.parimatch.com/api/affreporting.asp?key=$key&reportname=$report_name%20Report%20-%20Detailed&reportformat=xml&reportmerchantid=$merchant_id&reportstartdate=$startDate&reportenddate=$finish_date";
        //dd($url);
        $page = file_get_contents($url);
        //dd($page);
        preg_match_all('#<reportresponse>(.+?)</reportresponse>#su', $page, $res);
        if(empty($res[0])){
            return false;
        }
        $xml = new \SimpleXMLElement($res[0][0]);
        $xml = json_decode(json_encode((array)$xml), TRUE);
        if(empty($xml['row'])){
            return false;
        }
        //dd($xml['row']);
        return $xml['row'];
    }

    public function getApiKey($login, $password)
    {
        $url = 'https://partners.parimatch.com/Login.asp';
        $url2 = 'https://partners.parimatch.com/Members/DataFeedManagement.asp';

        $ch = curl_init();
        if (strtolower((substr($url, 0, 5)) == 'https')) { // если соединяемся с https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($ch, CURLOPT_REFERER, $url);
        // cURL будет выводить подробные сообщения о всех производимых действиях
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=" . $login . "&password=" . $password . "&UniqueUserLoginT=");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //сохранять полученные COOKIE в файл
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . '/cookie.txt');
        $result = curl_exec($ch);
        curl_close($ch);
        $response = $this->Read($url2);
        if(empty($response)){
                return false;
        }
        return $response;
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
        preg_match('/name="memberkey" value="(.*?)">/', $result, $regs);
        if (!empty($regs[1])) {
            $key = $regs[1];
            return $key;
        }
        return '';
    }
}