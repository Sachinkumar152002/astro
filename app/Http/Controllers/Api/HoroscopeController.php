<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{ZodiacSign,ApiLog,HoroscopeDaily,HoroscopeWeekly,HoroscopeYearly};
use Illuminate\Support\Facades\Http;

class HoroscopeController extends Controller
{
  public function zodiacList(Request $request){
    $result=ZodiacSign::get();
    $data=$result->map(function($item){
      return [
        "id"=>$item->id,
        "name"=>$item->name,
        "url"=>url("assets/img/d-{$item->image_id}.png")
      ];
    });
    return response()->json(["success"=>true,"data"=>$data],200);
  }

  public function timeFrames(Request $request){
    $data=[
      [
        "name"=>"Yesterday",
        "value"=>"yesterday"
      ],
      [
        "name"=>"Today",
        "value"=>"today"
      ],
      [
        "name"=>"Tomorrow",
        "value"=>"tomorrow"
      ],
      [
        "name"=>"Weekly",
        "value"=>"weekly"
      ],
      [
        "name"=>"Yearly",
        "value"=>"yearly"
      ]
    ];
    return response()->json(["success"=>true,"data"=>$data],200);
  }

  public function zodiacDetail(Request $request){
    $request->validate([
      "zodiacId"=>"required|integer|exists:zodiac_signs,id",
      "timeFrame"=>"required|in:yesterday,today,tomorrow,weekly,yearly",
    ],[
      'zodiacId.integer' => 'The zodiac ID must be an integer.',
      'zodiacId.exists' => 'The selected zodiac ID is invalid.',
      'timeFrame.in' => 'The selected timeFrame is invalid.',
    ]);
    $zodiacId=$request->zodiacId;
    $zodiacSign=ZodiacSign::find($zodiacId);
    $zodiac=$zodiacSign->id;
    $timeFrame=$request->timeFrame;
    $date=date("d/m/Y");
    $apiKey=fromSetting('astro_api_key');
    $split=$request->split?:true;
    $type=$request->type?:"big";
    $lang=$request->lang?:"en";
    $horoscopeDate=date("d F Y");
    $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey";
    if($timeFrame=="today"){
      $date=date("d/m/Y");
      $horoscopeDate=date("d F Y");
      $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
      $date=date("Y-m-d");
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->first();
    }elseif($timeFrame=="tomorrow"){
      $date=date("d/m/Y", strtotime('now+24hours'));
      $horoscopeDate=date("d F Y", strtotime('now+24hours'));
      $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
      $date=date("Y-m-d", strtotime('now+24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->first();
    }elseif($timeFrame=="yesterday"){
      $date=date("d/m/Y", strtotime('now-24hours'));
      $horoscopeDate=date("d F Y", strtotime('now-24hours'));
      $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
      $date=date("Y-m-d", strtotime('now-24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->first();
    }elseif($timeFrame=="weekly"){
      $week="thisweek";
      $horoscopeDate=date("d F Y", strtotime('monday this week'))." - ".date("d F Y", strtotime('sunday this week'));
      $fromDate=date("Y-m-d", strtotime('monday this week'));
      $toDate=date("Y-m-d", strtotime('sunday this week'));
      $url="https://api.vedicastroapi.com/v3-json/prediction/weekly-moon?api_key=$apiKey&week=$week&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
      $horoscope=HoroscopeWeekly::where("zodiac_sign_id",$zodiacSign->id)->where("from_date",$fromDate)->where("to_date",$toDate)->first();
    }elseif($timeFrame=="yearly"){
      $timeFrameUrl="yearly";
      $horoscopeDate=$year=date("Y");
      $url="https://api.vedicastroapi.com/v3-json/prediction/yearly?api_key=$apiKey&year=$year&zodiac=$zodiac&lang=$lang";
      $horoscope=HoroscopeYearly::where("zodiac_sign_id",$zodiacSign->id)->where("year",$year)->first();
    }
    $data=[];
    if($horoscope){
      $jsonResponse=json_decode($horoscope->api_response,true);
      if(isset($jsonResponse["response"])){
        $data=$jsonResponse["response"];
      }
    }
    if(count($data)==0){
      $response=Http::get($url);
      $statusCode=$response->status();
      $jsonResponse=$response->json();
      $apiLog=new ApiLog();
      $apiLog->table_name="horoscope_dailies";
      $apiLog->table_id=0;
      $apiLog->name="horoscope_dailies";
      $apiLog->request=$url;
      $apiLog->response=$response;
      $apiLog->api_status=0;
      $apiLog->save();
      if($jsonResponse["status"]===200){
        if(!$horoscope){
          if($timeFrame=="yearly"){
            $horoscope=new HoroscopeYearly();
            $horoscope->zodiac_sign_id=$zodiac;
            $horoscope->year=$year;
            $apiLog->table_name="horoscope_yearlies";
            $apiLog->name="horoscope_yearlies";
          }elseif($timeFrame=="weekly"){
            $horoscope=new HoroscopeWeekly();
            $horoscope->zodiac_sign_id=$zodiac;
            $horoscope->from_date=$fromDate;
            $horoscope->to_date=$toDate;
            $apiLog->table_name="horoscope_weeklies";
            $apiLog->name="horoscope_weeklies";
          }else{
            $horoscope=new HoroscopeDaily();
            $horoscope->zodiac_sign_id=$zodiac;
            $horoscope->date=$date;
          }
        }
        $horoscope->api_response=$response;
        $horoscope->save();
        $apiLog->table_id=$horoscope->id;
        $apiLog->api_status=1;
        $apiLog->save();
        if(isset($jsonResponse["response"])){
          $data=$jsonResponse["response"];
        }
      }
    }
    if($timeFrame=="yearly"){
      $dataa=$data;
      $data=[];
      $data["bot_response"]=$dataa["phase_1"];
      $data["bot_response"]["relationship"]["split_response"]=$dataa["phase_1"]["relationship"]["prediction"];
      $data["bot_response"]["health"]["split_response"]=$dataa["phase_1"]["health"]["prediction"];
      $data["bot_response"]["physique"]["split_response"]=$dataa["phase_1"]["physique"]["prediction"];
      $data["bot_response"]["career"]["split_response"]=$dataa["phase_1"]["career"]["prediction"];
      $data["bot_response"]["finances"]["split_response"]=$dataa["phase_1"]["finances"]["prediction"];
      $data["bot_response"]["family"]["split_response"]=$dataa["phase_1"]["family"]["prediction"];
      $data["bot_response"]["friends"]["split_response"]=$dataa["phase_1"]["friends"]["prediction"];
      $data["bot_response"]["total_score"]["split_response"]=$dataa["phase_1"]["status"]["prediction"];
    }
    $bot_response=isset($data["bot_response"]) ? $data["bot_response"] : [];
    $prediction["love_and_relationships"]=[
      "name"=>"Love and Relationships",
      "value"=>[
        isset($bot_response["relationship"]) && isset($bot_response["relationship"]["split_response"]) ? $bot_response["relationship"]["split_response"] : ""
      ]
    ];
    $prediction["health_and_wellness"]=[
      "name"=>"Health and Wellness",
      "value"=>[
        isset($bot_response["health"]) && isset($bot_response["health"]["split_response"]) ? $bot_response["health"]["split_response"] : "",
        isset($bot_response["physique"]) && isset($bot_response["physique"]["split_response"]) ? $bot_response["physique"]["split_response"] : "",
      ]
    ];
    $prediction["career_and_education"]=[
      "name"=>"Career and Education",
      "value"=>[
        isset($bot_response["career"]) && isset($bot_response["career"]["split_response"]) ? $bot_response["career"]["split_response"] : "",
      ]
    ];
    $prediction["money_and_finances"]=[
      "name"=>"Money and Finances",
      "value"=>[
        isset($bot_response["finances"]) && isset($bot_response["finances"]["split_response"]) ? $bot_response["finances"]["split_response"] : "",
      ]
    ];
    $prediction["family_and_friends"]=[
      "name"=>"Family and Friends",
      "value"=>[
        isset($bot_response["family"]) && isset($bot_response["family"]["split_response"]) ? $bot_response["family"]["split_response"] : "",
          isset($bot_response["friends"]) && isset($bot_response["friends"]["split_response"]) ? $bot_response["friends"]["split_response"] : "",
      ]
    ];
    $prediction["total_score"]=[
      "name"=>"Tip of the Month for the Year:",
      "value"=>[
        isset($bot_response["total_score"]) && isset($bot_response["total_score"]["split_response"]) ? $bot_response["total_score"]["split_response"] : "",
      ]
    ];
    $year=date("Y");
    $zodiacSignName=$zodiacSign->name;
    $data=[
      "date"=>$horoscopeDate,
      "timeFrame"=>$timeFrame,
      "prediction"=>$prediction,
      "comment"=>"Wishing you a fantastic and fulfilling $year, $zodiacSignName! ✨"
    ];
    return response()->json(["success"=>true,"data"=>$data],200);
  }

}
