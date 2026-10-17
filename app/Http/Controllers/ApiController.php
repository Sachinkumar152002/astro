<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Model\{ApiLog, ApiUrl};
use App\{Product, Order, OrderItem, LocationHistory};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller {

    public function getResponse($order_id){
      $download_url = "";
      $file_url = "";
      $orderItem = OrderItem::where("order_id", $order_id)->first();

      if($orderItem){
        $apiLog = ApiLog::where("table_name", "order_item")
        ->where("table_id", $orderItem->id)
        ->where("api_status", 1)
        ->first();

        if($apiLog){
          $data = json_decode($apiLog->response, true);
          $download_url = isset($data["data"]) && isset($data["data"]["download_url"]) ? $data["data"]["download_url"] : "";
          $file_url = isset($data["data"]) && isset($data["data"]["file_url"]) ? $data["data"]["file_url"] : "";
        }else{
          $placeNames = ["birth_place", "p1_birth_place", "p2_birth_place"];
          $birthDateNames = ["birth_date", "p1_birth_date", "p2_birth_date"];
          $birthTimeNames = ["birth_time", "p1_birth_time", "p2_birth_time"];
          $langugageValue = [
            "english" => "en",
            "hindi" => "hi",
          ];
          $options = $orderItem->options?json_decode($orderItem->options, true):[];
          $api_url_id = isset($options["api_url_id"]) ? $options["api_url_id"] : 0;
          $api_url = isset($options["api_url"]) ? $options["api_url"] : "";
          $report_code = isset($options["api_url_report_code"]) ? $options["api_url_report_code"] : "";
          $body = [];
          if(isset($options["formdata"])){
            foreach($options["formdata"] as $index => $item){
              if(!isset($item["name"]) && isset($item["key"])){
                $item["name"] = $item["key"];
              }
              if(in_array($item["name"], $placeNames)){
                $value = $item["value"];
                $lattitude = (double) "28.7041";
                $longitude = (double) "77.1025";
                $place = $value;
                $lH=LocationHistory::where('state', "like", "%$value%")->first();
                if($lH){
                  $lattitude = (double) $lH->latDeg;
                  $longitude = (double) $lH->longDeg;
                  $place = $lH->place.", ".$lH->country;
                }
                if($item["name"] == "birth_place"){
                  $body["place"] = $place;
                  $body["lat"] = $lattitude;
                  $body["lon"] = $longitude;
                  $body["tzone"] = (float) "5.5";
                }elseif($item["name"] == "p1_birth_place"){
                  $body["p1_place"] = $place;
                  $body["p1_lat"] = $lattitude;
                  $body["p1_lon"] = $longitude;
                  $body["p1_tzone"] = (float) "5.5";
                }elseif($item["name"] == "p2_birth_place"){
                  $body["p2_place"] = $place;
                  $body["p2_lat"] = $lattitude;
                  $body["p2_lon"] = $longitude;
                  $body["p2_tzone"] = (float) "5.5";
                }
              }elseif(in_array($item["name"], $birthDateNames)){
                $valueInSeconds = strtotime($item["value"]);
                $day = date("d", $valueInSeconds);
                $month = date("m", $valueInSeconds);
                $year = date("Y", $valueInSeconds);

                if($item["name"] == "birth_date"){
                  $body["day"] = (int) $day;
                  $body["month"] = (int) $month;
                  $body["year"] = (int) $year;
                }elseif($item["name"] == "p1_birth_date"){
                  $body["p1_day"] = (int) $day;
                  $body["p1_month"] = (int) $month;
                  $body["p1_year"] = (int) $year;
                }elseif($item["name"] == "p2_birth_date"){
                  $body["p2_day"] = (int) $day;
                  $body["p2_month"] = (int) $month;
                  $body["p2_year"] = (int) $year;
                }
              }elseif(in_array($item["name"], $birthTimeNames)){
                $valueInSeconds = strtotime($item["value"]);
                $hour = date("H", $valueInSeconds);
                $min = date("i", $valueInSeconds);
                $sec = date("s", $valueInSeconds);

                if($item["name"] == "birth_time"){
                  $body["hour"] = (int) $hour;
                  $body["min"] = (int) $min;
                  $body["sec"] = (int) $sec;
                }elseif($item["name"] == "p1_birth_time"){
                  $body["p1_hour"] = (int) $hour;
                  $body["p1_min"] = (int) $min;
                  $body["p1_sec"] = (int) $sec;
                }elseif($item["name"] == "p2_birth_time"){
                  $body["p2_hour"] = (int) $hour;
                  $body["p2_min"] = (int) $min;
                  $body["p2_sec"] = (int) $sec;
                }
              }elseif($item["name"] == "language"){
                $body["lan"] = isset($langugageValue[$item["value"]]) ? $langugageValue[$item["value"]] : "en";
              }else{
                $body[$item["name"]] = $item["value"];
              }
            }
          }
          if($api_url!="" && count($body)>0){
            $body["company_name"] = config("app.name");
            $body["company_url"] = config("app.url");
            $body["company_email"] = config("vrdcms.admin_email");
            $body["company_mobile"] = config("vrdcms.admin_number");
            $body["company_bio"] = config("app.name");
            $body["logo_url"] = url("/images/logo.png");
            $body["footer_text"] = config("app.name");
            if($report_code){
              $body["report_code"] = $report_code;
              $body["theme"] = "001";
            }

            $api_key = fromSetting("divine_api_key");
            $api_token = fromSetting("divine_api_token");

            $body["api_key"] = $api_key;

            $response = Http::withHeaders([
              'Authorization' => "Bearer $api_token",
              'Content-Type' => 'application/json',
            ])
            ->post($api_url, $body);

            $data = $response->json();

            $apiLog = new ApiLog();
            $apiLog->table_name = "order_item";
            $apiLog->table_id = $orderItem->id;
            $apiLog->name = $api_url;
            $apiLog->request = json_encode($body);
            $apiLog->response = $response;
            $apiLog->api_status = 0;
            $apiLog->save();
            if(!isset($data['error'])){
              $apiLog->api_status=1;
              $apiLog->save();
              $download_url = isset($data["data"]) && isset($data["data"]["download_url"]) ? $data["data"]["download_url"] : "";
            } else {
               Log::alert($data['error']['details']);
               $recipientEmail = 'vishal@dixinfotech.com';
                $subject = 'Report Generate API '.$data['error_code']. ' For Order ID - '.$order_id;
                $messageBody = implode('<br>', $data['error']['details']);

                Mail::raw($messageBody, function ($message) use ($recipientEmail, $subject) {
                    $message->to($recipientEmail)
                            ->subject($subject);
                });

            }
            if($download_url){
              $fileName = time().'.pdf';
              $filePath = public_path('pdfs/divine/'.$orderItem->id.'/'.$fileName);

              if(!file_exists($filePath)){
                $filePath = public_path('pdfs');
                if(!file_exists($filePath)){
                    mkdir(public_path('pdfs'), 0755, true);
                }

                $filePath = public_path('pdfs/divine');
                if(!file_exists($filePath)){
                    mkdir(public_path('pdfs/divine'), 0755, true);
                }

                $filePath = public_path('pdfs/divine/'.$orderItem->id);
                if(!file_exists($filePath)){
                    mkdir(public_path('pdfs/divine/'.$orderItem->id), 0755, true);
                }

                $filePath = public_path('pdfs/divine/'.$orderItem->id.'/'.$fileName);
                $ch = curl_init($download_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $pdfContent = curl_exec($ch);
                curl_close($ch);
                if($pdfContent!==false){
                  file_put_contents($filePath, $pdfContent);
                  $data = json_decode($apiLog->response, true);
                  if(isset($data["data"])){
                    $file_url=url("pdfs/divine/{$orderItem->id}/{$fileName}");
                    $data["data"]["file_url"]=$file_url;
                  }
                }
              }
              return $file_url;
            }
          }
        }
      }
      return $download_url;
    }
}
