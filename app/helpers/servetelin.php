<?php

function createAgent($name = '', $phone=''){
    $postData = [
        'name' => $name,
        'follow_me_number' => (int) $phone,
        'user_product_id' => config('vrdcms.servetel_product_id')
    ];
    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.servetel.in/v1/agent",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        // echo "cURL Error #:" . $err;
        return ['success'=>false, 'message'=>$err];
    } else {
        // echo $response;
        return json_decode($response, true);
    }
}

function blockUnblockAgent($agent_id = '', $status = 0){
    $blockUnblock = $status == 1 ? 'unblock' : 'block';
    $url = sprintf("https://api.servetel.in/v1/agent/%s/%s", $agent_id, $blockUnblock);
    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}

function clicktocall($userphone= '', $agentphone='', $caller_id = ''){
    $data = [
        'destination_number' => $userphone,
        'agent_number' => $agentphone,
        'caller_id' => $caller_id
    ];
    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.servetel.in/v1/click_to_call",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return json_decode($err, true);
    } else {
        return json_decode($response, true);
    }
}

function liveCalls(){

    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.servetel.in/v1/live_calls",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $token"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    // echo "cURL Error #:" . $err;
    return [];
    } else {
    return json_decode($response, true);
    }
}

function callhangup($call_id = ''){
    $data = [
        'call_id' => $call_id
    ];
    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.servetel.in/v1/call/hangup",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    // echo "cURL Error #:" . $err;
    } else {
    // echo $response;
    }
}

function cdrlogs($fromtime = '', $totime = '', $page = 1, $limit=500){
    $token = config('vrdcms.servetel_api_token');
    // $url = sprintf("https://api.servetel.in/v1/call/records?from_date=%s&to_date=%s&limit=%d&call_type=c&page=%d&direction=outbound", $fromtime, $totime, $limit, $page);
    $url = sprintf("https://api.servetel.in/v1/call/records?from_date=%s&to_date=%s&limit=%d&page=%d&direction=outbound", urlencode($fromtime), urlencode($totime), $limit, $page);

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
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token"
        ],
    ]);
    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    } else {
        return json_decode($response, true);
    }
}

function deleteAgent($agent_id = ''){
    $url = sprintf("https://api.servetel.in/v1/agent/%s/%s", $agent_id);
    $token = config('vrdcms.servetel_api_token');
    $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}



function sendSms($mobile = '', $text = ''){
    $token = config('vrdcms.servetel_smsapi_token');
    $sender = config('vrdcms.servetel_smsapi_senderid');
    $URL = sprintf("http://sms-alerts.servetel.in/api/v4/?api_key=%s&method=sms&message=%s&to=91%s&sender=%s",$token, urlencode($text), $mobile, $sender);
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_COOKIE => "AWSALB=IBEpj0jKv7M49IMW8Z0Rt24ve2YtPEqlfrqcxl3Sz6TLe%2BbZy5ZYdYgHmuHHr1xxUojPeNYgPAjWSvnEw3Ogjc4KNctAft3hInoKktrGcTx6VfjkL38OEKramaxy; AWSALBCORS=IBEpj0jKv7M49IMW8Z0Rt24ve2YtPEqlfrqcxl3Sz6TLe%2BbZy5ZYdYgHmuHHr1xxUojPeNYgPAjWSvnEw3Ogjc4KNctAft3hInoKktrGcTx6VfjkL38OEKramaxy",
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
      // echo "cURL Error #:" . $err;
      return ['status'=>0, 'message'=> 'Failed to send sms'];
    } else {
      // echo $response;
      return ['status'=>1, 'message'=> 'Successfully send'];
    }
  }
