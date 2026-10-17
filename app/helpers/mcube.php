<?php
function sendSms($mobile = '', $text = '', $fordlt=''){
    $dltIds = [
        'astrologer_not_logined_since'=> "1207161760839250821",
        'astrologer_call_success'=>"1207161760833653713",
        'astrologer_approve'=>"1207161760828796153",
        'otp_astrologer'=>"1207161760789223730",
        'user_call_success'=>"1207161760782652086",
        'mobile_recharge'=>"1207161760763017749",
        'otp_message'=>"1207161760747713573",
    ];
    if(isset($dltIds[$fordlt])){
        $dltContentId = $dltIds[$fordlt];
        $smsuser = config('vrdcms.mcube_sms_user');
        $smsuserpwd = config('vrdcms.mcube_sms_passwd');
        $sender = config('vrdcms.mcube_sender_id');

        echo $url  = sprintf("https://pgapi.vispl.in/fe/api/v1/send?ssl=false&username=%s&password=%s&unicode=false&from=%s&to=%s&dltContentId=%s&text=%s",$smsuser,$smsuserpwd, $sender, $mobile,$dltContentId, rawurlencode($text) );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            // CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return ['status'=>0, 'message'=> 'Failed to send sms'];
        } else {
            return ['status'=>1, 'message'=> 'Successfully send'];
        }
    }
    return ['status'=>0, 'message'=> 'No DLT template'];
  }


function clicktocall($userphone= '', $agentphone='', $caller_id = '', $maxSec = 0){

    $callApiKey = config('vrdcms.mcube_call_apikey');
    $returnurl  = route('mcube.callback');
    $url = sprintf("http://mcube.vmc.in/api/outboundcall?apikey=%s&exenumber=%s&custnumber=%s&mct=%d&wct=0&refid=%s&url=%s",$callApiKey,$agentphone,$userphone,$maxSec,$caller_id, rawurlencode($returnurl));
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        // CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return ['status'=>0, 'message'=> 'Failed to make call'];
    } else {
        return json_decode($response, true);
    }
}
