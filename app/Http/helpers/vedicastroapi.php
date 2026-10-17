<?php
// vedicastroapi

function dailyPrediction($signId = 0, $date = ''){
    $token = config('vrdcms.vedicastroapi_token');
    $url = sprintf("https://api.vedicastroapi.com/json/prediction/daily?zodiac=%d&show_same=true&date=%s&type=big&api_key=%s", $signId, $date, $token);
    return file_get_contents($url);
}

function panchangData($date = '', $time = ''){
    $apiDate = date('d/m/Y', strtotime($date));
    $token = config('vrdcms.vedicastroapi_token');
    $url = sprintf("https://api.vedicastroapi.com/json/panchang/getpanchang?date=%s&time=%s&tz=5.5&api_key=%s", $apiDate, $time, $token);
    $response = file_get_contents($url);
    \App\Model\PanchangApi::updateOrCreate(['date'=>$date, 'time'=>$time, 'timezone'=>'5.5'], ['response'=>$response]);
    return $response;
}
