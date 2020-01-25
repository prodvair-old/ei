<?php
namespace console\models;

use Yii;
use \yii\base\Module;

class GetInfoFor extends Module
{
    public function address($addressStr)
    {
        $curl = curl_init();


        $search = [
            '"', // 1
            '\\' // 2
        ];

        $replace = [
            '',     // 1
            '\\\\'  // 2
        ];


        $address = trim($addressStr);
        $address = str_replace($search, $replace, $address);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n\t\"query\": \"".$address."\",\n\t\"count\": 10\n}",
            CURLOPT_HTTPHEADER => [
                "Authorization: Token 93f59f43648c75a49c3c7ce61f8cd3fb80ee6846",
                "Content-Type: application/json"
            ],
        ]);

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        if (empty($response->suggestions[0])) {
            return [
                'fullAddress' => $addressStr,
                'regionId' => 0,
                'address' => NULL
            ];
        }

        return [
            'fullAddress' => $response->suggestions[0]->unrestricted_value,
            'regionId' => (int)substr($response->suggestions[0]->data->region_kladr_id, 0, 2),
            'address' => [
                'district'  => $response->suggestions[0]->data->federal_district,
                'region'    => $response->suggestions[0]->data->region_with_type,
                'city'      => $response->suggestions[0]->data->city_with_type,
                'street'    => $response->suggestions[0]->data->street_with_type,
                'postalBox' => $response->suggestions[0]->data->postal_box,
                'geo_lat'   => $response->suggestions[0]->data->geo_lat,
                'geo_lon'   => $response->suggestions[0]->data->geo_lon,
                'flatArea'  => $response->suggestions[0]->data->flat_area,
                'flatPrice' => $response->suggestions[0]->data->flat_price,
                'squareMeterPrice' => $response->suggestions[0]->data->square_meter_price,
            ]
        ];
    }

    public function cadastr_address($cadastr)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://egrp365.ru/map_alpha/ajax/map.php?source=kadnum",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => ['kadnum' => $cadastr],
        ]);

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        
        return [
            'address'   => $response->address,
            'flatFloor' => $response->flatFloor,
            'flatName'  => $response->flatName,
        ];

    }
    public function date_check($date, $days = false)
    {
        if ($date == '0001-01-01 00:00:00 BC' || $date == '0001-01-01 00:00:00') {
            return null;
        } else {
            if ($days) {
                $newDate = new \DateTime($date);
                $newDate->modify("-$days day");
                return $newDate->format('Y-m-d H:i:s');
            }
            return $date;
        }
    }
    public function cadastr($str)
    {
        $kadastr_check = preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{6,7}:[0-9]{1,35}/", $str, $kadastr);
        return ($kadastr_check)? $kadastr[0] : false;
    }
    public function vin($str)
    {
        $vin_text = str_replace('VIN', '',$str);
        $vin_check = preg_match("/[ABCDEFGHJKLMNPRSTUVWXYZ,0-9]{17}/", $vin_text, $vin_t);
        if ($vin_check) {
            $vin_c = preg_match("/[\w\s\d]+/u", $vin_t[0], $vin);
            return ($vin_c)? $vin[0] : false;
        }
        return false;
    }
    public function title($str)
    {
        if (strlen($str) < 145) {
            return $str;
        } else {
            return mb_substr($str, 0, 145, 'UTF-8').'...';
        }
    }
    public function format($str)
    {
        return strtolower(@end(explode('.', $str)));
    }

    public function mb_ucfirst($str, $encoding='UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
			   mb_substr($str, 1, mb_strlen($str), $encoding);
		return $str;
    }
    public function mb_lcfirst($str, $encoding='UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding).
			   mb_substr($str, 1, mb_strlen($str), $encoding);
		return $str;
	}
}