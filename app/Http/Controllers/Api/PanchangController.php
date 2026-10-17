<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Panchang,ApiLog};
use Illuminate\Support\Facades\Http;

class PanchangController extends Controller
{


  public function detail(Request $request){
      $request->validate([
        "date"=>"required|date_format:Y-m-d"
      ]);
      $jsonResponse=[];
      $date=$request->date;
      $timeInSeconds=strtotime($date);
      $apiKey=fromSetting('astro_api_key');
      $panchangDate=date("d/m/Y", strtotime($timeInSeconds));
      $lat=config('vrdcms.delhi_lat');
      $lon=config('vrdcms.delhi_lon');
      $lang=$request->lang?:"en";

      $panchang=Panchang::where("status",1)->where("date",$date)->first();
      if($panchang){
        if($lang=="hi" && $panchang->hindi){
          $jsonResponse=json_decode($panchang->hindi,true);
        }elseif($panchang->english){
          $jsonResponse=json_decode($panchang->english,true);
        }
        if(isset($jsonResponse["response"])){
          $jsonResponse=$jsonResponse["response"];
        }
      }
      if(count($jsonResponse)==0){
        $url="https://api.vedicastroapi.com/v3-json/panchang/panchang?api_key=$apiKey&date=$panchangDate&lat=$lat&lon=$lon&tz=5.5&lang=$lang";
        $response=Http::get($url);
        $statusCode=$response->status();
        $jsonResponse=$response->json();
        $apiLog=new ApiLog();
        $apiLog->table_name="panchangs";
        $apiLog->table_id=0;
        $apiLog->name="panchangs";
        $apiLog->request=$url;
        $apiLog->response=$response;
        $apiLog->api_status=0;
        $apiLog->save();
        if($jsonResponse["status"]===200){
          if(!$panchang){
            $panchang=new Panchang();
            $panchang->date=$date;
          }
          if($lang=="hi"){
            $panchang->hindi=$response;
          }else{
            $panchang->english=$response;
          }
          $panchang->status=1;
          $panchang->save();
          $apiLog->table_id=$panchang->id;
          $apiLog->api_status=1;
          $apiLog->save();
          if(isset($jsonResponse["response"])){
            $jsonResponse=$jsonResponse["response"];
          }
        }
      }
      $tithi=isset($jsonResponse["tithi"]) ? $jsonResponse["tithi"] : [];
      $nakshatra=isset($jsonResponse["nakshatra"]) ? $jsonResponse["nakshatra"] : [];
      $karana=isset($jsonResponse["karana"]) ? $jsonResponse["karana"] : [];
      $advanced_details=isset($jsonResponse["advanced_details"]) ? $jsonResponse["advanced_details"] : [];
      $yoga=isset($jsonResponse["yoga"]) ? $jsonResponse["yoga"] : [];
      $day=isset($jsonResponse["day"]) ? $jsonResponse["day"] : [];
      $response=[];
      $response["panchang"]=[
        "tithi"=>(isset($tithi['name']) ? $tithi['name'] : "")." "."upto"." ".(isset($tithi['end']) ? $tithi['end'] : ""),
        "nakshatra"=>(isset($nakshatra['name']) ? $nakshatra['name'] : "")." "."next"." ".(isset($nakshatra['next_nakshatra']) ? $nakshatra['next_nakshatra'] : "")." "."upto"." ".(isset($nakshatra['end']) ? $nakshatra['end'] : ""),
        "karana"=>(isset($karana['name']) ? $karana['name'] : "")." "."next"." ".(isset($karana['next_karana']) ? $karana['next_karana'] : "")." "."upto"." ".(isset($karana['end']) ? $karana['end'] : ""),
        "karana"=>isset($advanced_details['masa']) && isset($advanced_details['masa']['paksha']) ? $advanced_details['masa']['paksha'] : "",
        "yoga"=>(isset($yoga['name']) ? $yoga['name'] : "")." "."upto"." ".(isset($yoga['end']) ? $yoga['end'] : ""),
        "day"=>isset($day['name']) ? $day['name'] : "",
      ];
      $rasi=isset($jsonResponse["rasi"]) ? $jsonResponse["rasi"] : "";
      $masa=isset($advanced_details["masa"]) ? $advanced_details["masa"] : "";
      $response["sun_and_moon"]=[
        "sun_rise"=>isset($advanced_details['sun_rise']) ? $advanced_details['sun_rise'] : "",
        "sun_set"=>isset($advanced_details['sun_set']) ? $advanced_details['sun_set'] : "",
        "moon_sign"=>isset($rasi['name']) ? $rasi['name'] : "",
        "moon_rise"=>isset($advanced_details['moon_rise']) ? $advanced_details['moon_rise'] : "",
        "moon_set"=>isset($advanced_details['moon_set']) ? $advanced_details['moon_set'] : "",
        "ritu"=>isset($masa['ritu']) ? $masa['ritu'] : "",
      ];
      $years=isset($advanced_details["years"]) ? $advanced_details["years"] : "";
      $response["month_and_year"]=[
        "purnimanta"=>isset($masa['purnimanta_name']) ? $masa['purnimanta_name'] : "",
        "amanta"=>isset($masa['amanta_name']) ? $masa['amanta_name'] : "",
        "shaka_samvat"=>(isset($years['saka']) ? $years['saka'] : "")." ".(isset($years['saka_samvaat_name']) ? $years['saka_samvaat_name'] : ""),
        "vikram_samvat"=>isset($years['vikram_samvaat']) ? $years['vikram_samvaat'] : "",
        "kali_samvat"=>isset($years['kali']) ? $years['kali'] : "",
      ];
      $response["inauspicious_timming"]=[
        "gulika"=>isset($jsonResponse['gulika']) ? $jsonResponse['gulika'] : "",
        "yamakanta"=>isset($jsonResponse['yamakanta']) ? $jsonResponse['yamakanta'] : "",
      ];
      $abhijit_muhurta=isset($advanced_details["abhijit_muhurta"]) ? $advanced_details["abhijit_muhurta"] : "";
      $response["auspicious_timming"]=[
        "abhijit"=>(isset($abhijit_muhurta['start']) ? $abhijit_muhurta['start'] : "")." "."to"." ".(isset($abhijit_muhurta['end']) ? $abhijit_muhurta['end'] : ""),
      ];
      $response["disha_shoola"]=[
        "disha_shoola"=>isset($advanced_details['disha_shool']) ? $advanced_details['disha_shool'] : "",
      ];
      $data=[
        "date"=>$date,
        "response"=>$response
      ];
      return response()->json(["success"=>true,"data"=>$data],200);
  }
}
