<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{KundaliMatchingEnquiry, KundaliMatching, KundaliEnquiry, Banner,PlanetDetail, ApiLog, DivisionChart, Dasha, KpPlanet, AkvargaChart, ZodiacSign,Yoga,Dosh, HoroscopeDaily,HoroscopeWeekly,HoroscopeYearly, Panchang, Gemsuggestion, NumeroSuggestion, RudrakshSuggestion};
use Session;
use App\LocationHistory;
use Illuminate\Support\Facades\Http;
use App\Services\{Meta};

class KundaliController extends Controller
{
  /**
  * Vedic Astro Api
  * @params call_api_string, table_name, api_params
  * @response null if get response, else json
  */
  protected function apiResp($call = '',$table_name = '', $params = []){

    $params['api_key'] = fromSetting('astro_api_key');
    $url  = "https://api.vedicastroapi.com/v3-json/$call?".http_build_query($params);
    if($table_name==''){
      $__resp = file_get_contents($url);
      $resp = json_decode($__resp, true);
      return isset($resp['response']) ? $resp['response'] : $__resp;
    }
    $response           = Http::withoutVerifying()->get($url);

    $statusCode         = $response->status();
    $jsonResponseMain   = $response->json();

    $apiLog             = new ApiLog();
    $apiLog->table_name = $table_name;
    $apiLog->table_id   = 0;
    $apiLog->name       = $table_name;
    $apiLog->request    = $url;
    $apiLog->response   = $response;
    $apiLog->api_status = 0;
    $apiLog->save();

    return $statusCode ==200 ? $apiLog : null;
  }

  /**
  * kundali
  * @params
  * @response view html
  */
  public function kundali(Request $request){
      $nav="kundali";
      $today = date('Y-m-d');
      $banners = Banner::where('status', 1)
      ->where('type', "kundali")
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_free_kundali");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "free_kundali_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_free_kundali", $user_type);
      }
      $breads[]=["name"=>"Kundali","url"=>route("kundali.index")];
      $kundaliId = session('kundli-report', 0);
      $kundali=KundaliEnquiry::find($kundaliId);
      return view(themeView().'kundali.index', compact('nav','breads', "banners", 'kundali'));
  }

  /**
  * kundali request submit
  * @params
  * @response json
  */
  public function kundaliEnquirySubmit(Request $request){
    $request->validate([
      "name"=>"required",
      "gender"=>"required",
      "date"=>"required|date_format:Y-m-d",
      "time"=>"required",
      "city"=>"required",
    ]);
    $name=$request->name;
    $gender=$request->gender;
    $date=$request->date;
    $time=$request->time;
    $city=$request->city;
    $lang=$request->lang?:'en';
    $kundaliEnquiryy=KundaliEnquiry::where("name",$name)->where("gender",$gender)->where("date",$date)->where("time",$time)->where("city",$city)->where('lang',$lang)->first();
    if(!$kundaliEnquiryy){
      $lH=LocationHistory::where('state',$city)->first();
      $kundaliEnquiryy=new KundaliEnquiry();
      $kundaliEnquiryy->name    = $name;
      $kundaliEnquiryy->gender  = $gender;
      $kundaliEnquiryy->date    = $date;
      $kundaliEnquiryy->time    = $time;
      $kundaliEnquiryy->city    = $city;
      $kundaliEnquiryy->lat     = $lH?$lH->latDeg:config('vrdcms.delhi_lat');
      $kundaliEnquiryy->lon     = $lH?$lH->longDeg:config('vrdcms.delhi_lon');
      $kundaliEnquiryy->lang    = $lang;
      $kundaliEnquiryy->tz      = 5.5;
      $kundaliEnquiryy->save();
    }
    if($request->return_type && $request->return_type="json"){
      return response()->json(['kundali_id'=>$kundaliEnquiryy->id], 200);
    }else{
      session(['kundli-report' =>$kundaliEnquiryy->id]);
      return response()->json(["suceess"=>true,"message"=>"Please wait. Kundali is generating.","redirectTo"=>route("kundali.report")]);
    }
  }

  /**
  * Kundali Details
  * @params kundaliId
  * @response view html
  */
  public function kundaliDetail(Request $request){

      $nav="kundali";
      $kundaliId = session('kundli-report', $request->kundali_id);
      $kundali=KundaliEnquiry::find($kundaliId);
      if(!$kundali) return redirect()->route('kundali.index');

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_free_kundali_detail");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "free_kundali_detail_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $kundali->id;
        $body["custom_data"]["content_name"] = $kundali->name;
        $body["custom_data"]["content_type"] = "Kundali";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_free_kundali_detail", $user_type);
      }
      $breads[]=["name"=>"Kundali","url"=>route("kundali.index")];
      $breads[]=["name"=>"Kundali Detail","url"=>route("kundali.report")];
      return view(themeView().'kundali.detail', compact('nav','breads',"kundali"));
  }

  /**
  * Basic Details
  * @params kundal id
  * @response json
  */
  public function basicDetails(Request $request){

    $kundaliId = session('kundli-report', $request->kundali_id);
    $kundaliEnquiry=KundaliEnquiry::find($kundaliId);
    $place=$kundaliEnquiry->city;
    $data=[];
    if($kundaliEnquiry){

      $dateSeconds=strtotime($kundaliEnquiry->date." ".$kundaliEnquiry->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$kundaliEnquiry->lat;
      $lon=$kundaliEnquiry->lon;
      $tz="5.5";
      $lang=$kundaliEnquiry->lang;
      $planetDetail=PlanetDetail::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      $date=date("d/m/Y",$dateSeconds);
      $jsonResponse = [];

      if(!$planetDetail){
        $params = [
          'dob' =>  $date,
          'tob' =>  $time,
          'lat' =>  $lat,
          'lon' =>  $lon,
          'tz'  =>  $tz,
          'lang'=>  $lang,
        ];
        $apiLog = $this->apiResp('horoscope/planet-details', 'planet_details', $params);
        if($apiLog){
          $planetDetail=new PlanetDetail();
          $planetDetail->date=date("Y-m-d",$dateSeconds);
          $planetDetail->time=$time;
          $planetDetail->lat=$lat;
          $planetDetail->lon=$lon;
          $planetDetail->tz=$tz;
          $planetDetail->lang=$lang;
          $planetDetail->response=$apiLog->response;
          $planetDetail->save();
          $apiLog->table_id=$planetDetail->id;
          $apiLog->api_status=1;
          $apiLog->save();

          $jsonResponseMain = json_decode($apiLog->response , true);

          $apiLogExtended = $this->apiResp('extended-horoscope/extended-kundli-details', 'planet_details', $params);
          if($apiLogExtended){
            $jsonResponseExtended = json_decode($apiLogExtended->response , true);
            if(isset($jsonResponseExtended["response"])){
              $jsonResponseMain["response"]["extended"]=$jsonResponseExtended["response"];
            }
          }

          $planetDetail->response=$jsonResponseMain;
          $planetDetail->save();
        }
      }

      $jsonResponse = is_array($planetDetail->response) ? $planetDetail->response : json_decode($planetDetail->response, true);

      $jsonResponse = $jsonResponse["response"];

      $data=[
        "name"=>$kundaliEnquiry->name,
        "date"=>$date,
        "time"=>date("h:i A", $dateSeconds),
        "place"=>$place,
        "latitude"=>$lat,
        "longitude"=>$lon,
        "timezone"=>"GMT+5.5",
        "response"=>$jsonResponse
      ];
      if($request->return_type && $request->return_type="json"){
        if($request->tab=='planet-positions') {
          $planets = [];
          for($i=0; $i<=9 ; $i++){
            $planets[$i] = $jsonResponse[$i];
          }
          return response()->json(['planets'=>$planets]);
        }
        return $data;
      }else{
        if($request->tab=='planet-positions') {
          $planets = [];
          for($i=0; $i<=9 ; $i++){
            $planets[$i] = $jsonResponse[$i];
          }
          return response()->json(["html"=>view(themeView().'.kundali.planet-details', ['planets'=>$planets])->render()]);
        }
        return response()->json(["html"=>view(themeView().'.kundali.basic', $data)->render()]);
      }
    }
    return response()->json(["html"=>"No data found"]);
  }

  /**
  * dasha Vimshottari Maha Dasha
  * @params kundal id
  * @response json
  */
  public function kundaliAntardasha(Request $request){

    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      // $dasha=$request->dasha;
      $dasha= 'antar-dasha';
      $birthYear = date("Y",$dateSeconds);
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/antar-dasha', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      $jsonResponse['birth_year'] = $birthYear;
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        if(isset($jsonResponse["antardashas"])){
          return response()->json(["html"=>view(themeView().'.kundali.dasha.antar-dasha', $jsonResponse)->render()]);
        }
        return response()->json(["html"=>"No data found"]);
      }
  }
  /**
  * Transit Table
  * @params kundal id
  * @response json
  */
  public function transitDates(Request $request){

    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $jsonResponse=[];
    if($planetDetail){
        $lang=$planetDetail->lang;
        $planets = ['Sun', 'Moon', 'Mars', 'Venus', 'Mercury', 'Jupiter', 'Saturn', 'Rahu', 'Ketu'];
      foreach($planets as $planet){
        $params = [
          'year' => date('Y'),
          'lang'=>  $lang,
          'planet'=> $planet
        ];

          $apiLog = $this->apiResp('panchang/transit-dates', '', $params);
          $jsonResponse[$planet] = $apiLog;
      }

      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        return response()->json(["html"=>view(themeView().'.kundali.transit-dates', ['planets'=>$jsonResponse])->render()]);
        return response()->json(["html"=>"No data found"]);
      }
  }
  }

  /**
  * dasha Yogini
  * @params kundal id
  * @response json
  */
  public function kundaliDashaYogini(Request $request){
    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      // $dasha=$request->dasha;
      $dasha='yogini-dasha-main';
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/yogini-dasha-main', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];

      }
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        if(isset($jsonResponse["dasha_list"])){
          return response()->json(["html"=>view(themeView().'.kundali.dasha.yogini', $jsonResponse)->render()]);
        }
        return response()->json(["html"=>"No data found"]);
        $html="";
        if(isset($jsonResponse["dasha_list"])){
          $dasha_list=$jsonResponse["dasha_list"];
          $dasha_end_dates=$jsonResponse["dasha_end_dates"];
          $html='<div class="table-responsive">';
          $html.='<table class="table table-bordered table-sm">';
          $html.='<thead class="bg-light">';
          $html.='<tr>';
          $html.='<th>Planet</th>';
          $html.='<th>Start Date</th>';
          $html.='<th>End Date</th>';
          $html.='</tr>';
          $html.='</thead>';
          $html.='<tbody>';
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($dasha_list);$i++){
            $current=explode(" ",$dasha_end_dates[$i]);
            $year=$current[3];
            if($year>$birthYear){
              $previous="Birth";
              if($birth){
                $previous=explode(" ",$dasha_end_dates[$i-1]);
                $previous=($previous[0].' '.$previous[1].' '.$previous[2].' '.$previous[3]);
              }
              $html.='<tr>';
              $html.='<td>'.$dasha_list[$i].'</td>';
              $html.='<td>'.$previous.'</td>';
              $html.='<td>'.($current[0].' '.$current[1].' '.$current[2].' '.$current[3]).'</td>';
              $html.='</tr>';
              $birth=true;
            }
          }
          $html.='</tbody>';
          $html.='</table>';
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }

  /**
  * dasha Vimshottari Current Mahadasha
  * @params kundal id
  * @response json
  */
  public function currentMahadasha(Request $request){
    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      // $dasha=$request->dasha;
      $dasha= 'current-mahadasha';
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/current-mahadasha', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        if(isset($jsonResponse["order_names"])){
          return response()->json(["html"=>view(themeView().'.kundali.dasha.current-mahadasha', $jsonResponse)->render()]);
        }
        return response()->json(["html"=>"No data found"]);
      }
  }

  /**
  * Dasha Maha Dasha
  * @params kundal id
  * @response json
  */
    public function kundaliMahadasha(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];

      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        // $dasha=$request->dasha;
        $dasha= 'maha-dasha';
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
          if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('dashas/maha-dasha', 'dashas', $params);
            if($apiLog){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$apiLog->response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
            }
          }
            $jsonResponse = json_decode($dashaModel->response,true);
            $jsonResponse = $jsonResponse["response"];
        }

        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
          if(isset($jsonResponse["mahadasha"])){
            return response()->json(["html"=>view(themeView().'.kundali.dasha.maha-dasha', $jsonResponse)->render()]);
          }
          return response()->json(["html"=>"No data found"]);
        }
    }

    /**
    * Dosha Manglik
    * @params kundal id
    * @response json
    */
    public function kundaliReportManglik(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];
      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('dosha/manglik-dosh', 'doshes', $params);
            if($apiLog){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$apiLog->response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();

            }
          }
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];

        }

        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
          if(isset($jsonResponse["factors"])){
            return response()->json(["html"=>view(themeView().'.kundali.dosha.manglik', $jsonResponse)->render()]);
          }
          return response()->json(["html"=>"No data found"]);
        }
    }

    /**
    * Dosha Kalsarpa
    * @params kundal id
    * @response json
    */
    public function kundaliReportKalsarpa(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];
      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('dosha/kaalsarp-dosh', 'doshes', $params);
            if($apiLog){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$apiLog->response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();

            }
          }
          $jsonResponse=json_decode($dosh->response,true);
          $jsonResponse=$jsonResponse["response"];

        }
        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
          if(isset($jsonResponse["remedies"])){
            return response()->json(["html"=>view(themeView().'.kundali.dosha.kalsarpa', $jsonResponse)->render()]);
          }
          return response()->json(["html"=>"No data found"]);
        }
    }
    /**
    * Dosha sadesati
    * @params kundal id
    * @response json
    */
    public function kundaliKarakas(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];
      $jsonResponseTable=[];
      $jsonResponseDetail=[];
      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $doshas = 'jaimini-karakas';
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $doshas)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('extended-horoscope/jaimini-karakas', 'doshes', $params);
            if($apiLog){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$apiLog->response;
              $dosh->dosh=$doshas;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
            }
          }
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];
        }
        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
            return response()->json(["html"=>view(themeView().'.kundali.jaimini-karakas', $jsonResponse)->render()]);
            return response()->json(["html"=>"No data found"]);
        }
    }
    /**
    * Dosha sadesati
    * @params kundal id
    * @response json
    */
    public function kundaliReportSadesati(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];
      $jsonResponseTable=[];
      $jsonResponseDetail=[];
      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('extended-horoscope/current-sade-sati', 'doshes', $params);
            if($apiLog){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$apiLog->response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
            }
          }
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];
        }
        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
          if(isset($jsonResponse["description"])){
            return response()->json(["html"=>view(themeView().'.kundali.dosha.sadesati', $jsonResponse)->render()]);
          }
          return response()->json(["html"=>"No data found"]);
        }
    }

    /**
    * Dosha mangal
    * @params kundal id
    * @response json
    */
    public function mangalDosh(Request $request){
      $planetDetail=KundaliEnquiry::find($request->kundali_id);
      $jsonResponse=[];
      if($planetDetail){

        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $params = [
              'dob' =>  $date,
              'tob' =>  $time,
              'lat' =>  $lat,
              'lon' =>  $lon,
              'tz'  =>  $tz,
              'lang'=>  $lang,
            ];
            $apiLog = $this->apiResp('dosha/mangal-dosh', 'doshes', $params);
            if($apiLog){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$apiLog->response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();

            }
          }
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];

        }

        if($request->return_type && $request->return_type="json"){
          return $jsonResponse;
        }else{
          if(isset($jsonResponse["factors"])){
            return response()->json(["html"=>view(themeView().'.kundali.dosha.mangal', $jsonResponse)->render()]);
          }
          return response()->json(["html"=>$html]);
        }
    }



  /**
  * Gem Sugggestion
  * @params planet id
  * @response json
  */
  public function gemSuggestions(Request $request){
    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dashaModel=Gemsuggestion::where("date",$date)->where("time",$time)->where("lat",$lat)
      ->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('extended-horoscope/gem-suggestion', 'gemsuggestions', $params);
          if($apiLog){
            $dashaModel=new Gemsuggestion();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        if(isset($jsonResponse["name"])){
          return response()->json(["html"=>view(themeView().'.kundali.gem', $jsonResponse)->render()]);
        }
        return response()->json(["html"=>"No data found"]);

      }
  }

  /**
  * Rudraksha Sugggestion
  * @params planet id
  * @response json
  */
  public function rudrakshSuggestion(Request $request){
    $planetDetail = KundaliEnquiry::find($request->kundali_id);
    $jsonResponse = [];
    if($planetDetail){

      $dateSeconds  = strtotime($planetDetail->date." ".$planetDetail->time);
      $date         = date("Y-m-d",$dateSeconds);
      $time         = date("H:i",$dateSeconds);
      $lat          = $planetDetail->lat;
      $lon          = $planetDetail->lon;
      $tz           = $planetDetail->tz;
      $lang         = $planetDetail->lang;

      $dashaModel   = RudrakshSuggestion::where("date",$date)->where("time",$time)->where("lat",$lat)
      ->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();

      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('extended-horoscope/rudraksh-suggestion', 'rudraksh_suggestions', $params);
          if($apiLog){
            $dashaModel=new RudrakshSuggestion();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"]??[];
      }
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        if(isset($jsonResponse["name"])){
          return response()->json(["html"=>view(themeView().'.kundali.rudraksha', $jsonResponse)->render()]);
        }
        return response()->json(["html"=>"No data found"]);
      }
  }
  /**
  * Ashkartavarga
  * @params planet id
  * @response json
  */
  public function kundaliAkvargaChart(Request $request){
    $planetDetail=KundaliEnquiry::find($request->kundali_id);
    $response="";
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $planet=$request->planet;
      $style="north";
      $akvargaChart=AkvargaChart::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("planet",$planet)->first();
      if(!$akvargaChart){
        $date=date("d/m/Y",$dateSeconds);
        $params = [
          'dob' =>  $date,
          'tob' =>  $time,
          'lat' =>  $lat,
          'lon' =>  $lon,
          'tz'  =>  $tz,
          'lang'=>  $lang,
          'planet'=>  $planet,
          'style'=>  $style,
        ];
        $apiLog = $this->apiResp('horoscope/ashtakvarga', 'akvarga_charts', $params);
        if($apiLog){
          $akvargaChart=new AkvargaChart();
          $akvargaChart->date=date("Y-m-d",$dateSeconds);
          $akvargaChart->time=$time;
          $akvargaChart->lat=$lat;
          $akvargaChart->lon=$lon;
          $akvargaChart->tz=$tz;
          $akvargaChart->lang=$lang;
          $akvargaChart->planet=$planet;
          $akvargaChart->style="north";
          $akvargaChart->response=$apiLog->response;
          $akvargaChart->save();
          $apiLog->table_id=$akvargaChart->id;
          $apiLog->api_status=1;
          $apiLog->save();
        }
    }
    $jsonResponse=json_decode($akvargaChart->response,true);
    $jsonResponse=$jsonResponse["response"]??[];
    if($request->return_type && $request->return_type="json"){
      return $jsonResponse;
    }else{
      if(isset($jsonResponse["ashtakvarga_order"])){
        return response()->json(["html"=>view(themeView().'.kundali.ashtakvarga', $jsonResponse)->render()]);
      }
      return response()->json(["html"=>"No data found"]);
    }

  }
}

/**
* Divisional Charts
* @params planet id
* @response json
*/
public function kundaliChart(Request $request){
  $planetDetail=KundaliEnquiry::find($request->kundali_id);
  $response="";

  if($planetDetail){

    $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
    $date=date("Y-m-d",$dateSeconds);
    $time=date("H:i",$dateSeconds);
    $lat=$planetDetail->lat;
    $lon=$planetDetail->lon;
    $tz=$planetDetail->tz;
    $lang=$planetDetail->lang;
    $division=$request->div;
    $style= $request->chart_type ? : "north";
    $divisionChart=DivisionChart::where("date",$date)
    ->where('style', $style)
    ->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("division",$division)->first();
    if($request->transit) {
      $divisionChart = null;
    }
    if(!$divisionChart){
      $date=date("d/m/Y",$dateSeconds);
      $params = [
        'dob' =>  $date,
        'tob' =>  $time,
        'lat' =>  $lat,
        'lon' =>  $lon,
        'tz'  =>  $tz,
        'lang'=>  $lang,
        'div' =>  $division,
        'style'=> $style,
        'font_size' => 14,
        'size' => 330,
        'color' => '%f00',
        'colorful_planets'=> true
      ];
      if($request->transit) {
        $params['transit_date'] = date('d/m/Y');
        $params['response_type'] = 'planet_object';
      }
      $apiLog = $this->apiResp('horoscope/chart-image', 'division_charts', $params);

      if($apiLog){
        $divisionChart=new DivisionChart();
        $divisionChart->date=date("Y-m-d",$dateSeconds);
        $divisionChart->time=$time;
        $divisionChart->lat=$lat;
        $divisionChart->lon=$lon;
        $divisionChart->tz=$tz;
        $divisionChart->lang=$lang;
        $divisionChart->division=$division;
        $divisionChart->style=$style;
        $divisionChart->response=$apiLog->response;
        $divisionChart->save();
        $apiLog->table_id=$divisionChart->id;
        $apiLog->api_status=1;
        $apiLog->save();
      }
    }

    $response=$divisionChart->response;

  }
  $svg="";
  // if(!str_contains($response, 'status')){
  //   $svg=html_entity_decode($response); // Decode HTML entities
  //   $svg=str_replace('\n','',$svg); // Remove newlines
  //   $svg=str_replace('\"','"',$svg); // Unescape double quotes
  //   $svg=str_replace('\\','',$svg); // Remove backslashes
  //   $svg = preg_replace('/<svg /', '<svg viewBox="0 0 500 500" ', $svg);
  //   $svg = preg_replace('/width="[^"]*"/', 'width="400"', $svg);
  //   $svg = preg_replace('/height="[^"]*"/', 'height="400"', $svg);
  // }
  if (preg_match('/<svg.*<\/svg>/s', $response, $matches)) {
      $svg = $matches[0];
  }
  if($request->return_type && $request->return_type="json"){
    return response()->json([$division=>$svg, 'name'=>$request->name], 200);
  }else{
      return response()->json(["html"=>view(themeView().'.kundali.chart', ["svg"=>$svg, 'name'=>$request->name])->render()]);
  }
}
/** Punchang **/
public function panchang(Request $request){
    $nav="panchang";
    $data=[];
    $date=$request->date?:date("Y-m-d");
    $panchangeDate=strtotime($date);

    $date=date("d/m/Y", strtotime($date));
    $lat=config('vrdcms.delhi_lat');
    $lon=config('vrdcms.delhi_lon');
    $city=$request->city??'';
    $lH= null;
    if($city){
      $lH = LocationHistory::where('state','like',"%$city%")->first();
      if($lH){
        $lat=$lH->latDeg;
        $lon=$lH->longDeg;
      }
    }
    $lang=$request->lang?:"en";

    $panchang=Panchang::where("status",1)->where("date",$date)->first();

    if(!$panchang){
      $params = [
        'date'=>$date,
        'lat'=>$lat,
        'lon'=>$lon,
        'tz'=>5.5,
        'lang'=>$lang,
      ];
      $apiLog = $this->apiResp('panchang/panchang', 'panchangs', $params);
      if($apiLog){
        if(!$panchang){
          $panchang=new Panchang();
          $panchang->date=$date;
        }
        if($lang=="hi"){
          $panchang->hindi=$apiLog->response;
        }else{
          $panchang->english=$apiLog->response;
        }
        $panchang->status=1;
        $panchang->save();
        $apiLog->table_id=$panchang->id;
        $apiLog->api_status=1;
        $apiLog->save();

      }
    }
    if($panchang){
      if($lang=="hi"){
        $jsonResponse=json_decode($panchang->hindi,true);
      }elseif($panchang->english){
        $jsonResponse=json_decode($panchang->english,true);
      }
      if(isset($jsonResponse["response"])){
        $data=$jsonResponse["response"];
      }
    }

    $sunrise = $data['advanced_details']['sun_rise'];
    $sunset  = $data['advanced_details']['sun_set'];
    $nextDate = date('Y-m-d', strtotime($request->date . ' +1 day'));
    $nextSunrise = date('H:i', strtotime($sunrise)); // usually same time next day
    $nextSunrise = $nextDate . ' ' . $nextSunrise;
    $dayHora = $this->generateDayHora($sunrise, $sunset, $request->date);
    $nightHora = $this->generateNightHora($sunset, $nextSunrise, $request->date);
    $dayChoghadiya   = $this->generateDayChoghadiya($sunrise, $sunset, $request->date);
    $nightChoghadiya = $this->generateNightChoghadiya($sunset, $nextSunrise, $request->date);

    if($request->return_type && $request->return_type=="json"){
      return response()->json(['panchang'=>$data, 'dayHora'=>$dayHora, 'nightHora'=>$nightHora, 'dayChoghadiya'=>$dayChoghadiya, 'nightChoghadiya'=>$nightChoghadiya, 'panchangeDate'=>$panchangeDate, 'location'=>$lH], 200);
    }else{

      $today = date('Y-m-d');
      $banners = Banner::where('status', 1)
      ->where('type', "panchang")
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_panchang");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "panchang_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $panchang ? $panchang->id : 0;
        $body["custom_data"]["content_name"] = $panchangeDate;
        $body["custom_data"]["content_type"] = "Panchang";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_panchang", $user_type);
      }
      $breads[]=["name"=>"Panchang","url"=>route("panchang")];

      return view(themeView().'kundali.panchang', compact('nav','breads',"panchangeDate","data","lang", "banners",'lH', 'dayHora', 'nightHora', 'dayChoghadiya', 'nightChoghadiya', 'date'));
    }
    // return $data;
}

public function generateDayHora($sunrise, $sunset, $date){
    $planetOrder = ["Saturn","Jupiter","Mars","Sun","Venus","Mercury","Moon"];

    $dayPlanetMap = [
        "Sunday" => "Sun",
        "Monday" => "Moon",
        "Tuesday" => "Mars",
        "Wednesday" => "Mercury",
        "Thursday" => "Jupiter",
        "Friday" => "Venus",
        "Saturday" => "Saturn",
    ];

    $dayName = date("l", strtotime($date));
    $firstPlanet = $dayPlanetMap[$dayName];

    $startIndex = array_search($firstPlanet, $planetOrder);

    $sunriseTs = strtotime($sunrise);
    $sunsetTs = strtotime($sunset);

    $horaDuration = ($sunsetTs - $sunriseTs) / 12;

    $horaList = [];

    for($i=0; $i<12; $i++){
        $start = $sunriseTs + ($horaDuration * $i);
        $end   = $start + $horaDuration;

        $planet = $planetOrder[($startIndex + $i) % 7];

        $horaList[] = [
            "planet" => $planet,
            "time" => date("H:i", $start)." - ".date("H:i", $end)
        ];
    }

    return $horaList;
}

function generateNightHora($sunset, $nextSunrise, $date)
{
    $planetOrder = ["Saturn","Jupiter","Mars","Sun","Venus","Mercury","Moon"];

    $dayPlanetMap = [
        "Sunday"    => "Sun",
        "Monday"    => "Moon",
        "Tuesday"   => "Mars",
        "Wednesday" => "Mercury",
        "Thursday"  => "Jupiter",
        "Friday"    => "Venus",
        "Saturday"  => "Saturn",
    ];

    // Day ke last Day Hora ka planet
    $dayName = date("l", strtotime($date));
    $dayStartIndex = array_search($dayPlanetMap[$dayName], $planetOrder);

    // Night ka first planet = day ke 12th hora ke baad wala
    $nightStartIndex = ($dayStartIndex + 12) % 7;

    $sunsetTs = strtotime($sunset);
    $nextSunriseTs = strtotime($nextSunrise);

    $horaDuration = ($nextSunriseTs - $sunsetTs) / 12;

    $horas = [];

    for ($i = 0; $i < 12; $i++) {
        $start = $sunsetTs + ($horaDuration * $i);
        $end   = $start + $horaDuration;

        $horas[] = [
            "planet" => $planetOrder[($nightStartIndex + $i) % 7],
            "time"   => date("H:i", $start) . " - " . date("H:i", $end)
        ];
    }

    return $horas;
}

// DaysChoghadiya
function generateDayChoghadiya($sunrise, $sunset, $date){
$dayName = date("l", strtotime($date));

$map = [
  "Sunday"    => ["Udveg","Char","Labh","Amrit","Kaal","Shubh","Rog","Udveg"],
  "Monday"    => ["Amrit","Kaal","Shubh","Rog","Udveg","Char","Labh","Amrit"],
  "Tuesday"   => ["Rog","Udveg","Char","Labh","Amrit","Kaal","Shubh","Rog"],
  "Wednesday" => ["Labh","Amrit","Kaal","Shubh","Rog","Udveg","Char","Labh"],
  "Thursday"  => ["Shubh","Rog","Udveg","Char","Labh","Amrit","Kaal","Shubh"],
  "Friday"    => ["Char","Labh","Amrit","Kaal","Shubh","Rog","Udveg","Char"],
  "Saturday"  => ["Kaal","Shubh","Rog","Udveg","Char","Labh","Amrit","Kaal"],
];

$start = strtotime($sunrise);
$end   = strtotime($sunset);

$slot = ($end - $start) / 8;

$result = [];

for($i=0; $i<8; $i++){
    $from = $start + ($slot * $i);
    $to   = $from + $slot;

    $result[] = [
        "name" => $map[$dayName][$i],
        "time" => date("h:i A", $from)." - ".date("h:i A", $to),
        "type" => $this->getChoghadiyaType($map[$dayName][$i])
    ];
}
return $result;
}

// NightChogadiya
function generateNightChoghadiya($sunset, $nextSunrise, $date){
$dayName = date("l", strtotime($date));

$map = [
  "Sunday"    => ["Shubh","Amrit","Char","Rog","Kaal","Labh","Udveg","Shubh"],
  "Monday"    => ["Char","Rog","Kaal","Labh","Udveg","Shubh","Amrit","Char"],
  "Tuesday"   => ["Labh","Udveg","Shubh","Amrit","Char","Rog","Kaal","Labh"],
  "Wednesday" => ["Udveg","Shubh","Amrit","Char","Rog","Kaal","Labh","Udveg"],
  "Thursday"  => ["Amrit","Char","Rog","Kaal","Labh","Udveg","Shubh","Amrit"],
  "Friday"    => ["Rog","Kaal","Labh","Udveg","Shubh","Amrit","Char","Rog"],
  "Saturday"  => ["Kaal","Labh","Udveg","Shubh","Amrit","Char","Rog","Kaal"],
 ];

$start = strtotime($sunset);
$end   = strtotime($nextSunrise);

$slot = ($end - $start) / 8;

$result = [];

for($i=0; $i<8; $i++){
    $from = $start + ($slot * $i);
    $to   = $from + $slot;

    $result[] = [
        "name" => $map[$dayName][$i],
        "time" => date("h:i A", $from)." - ".date("h:i A", $to),
        "type" => $this->getChoghadiyaType($map[$dayName][$i])
    ];
}
return $result;
}

function getChoghadiyaType($name){
  if(in_array($name, ["Amrit","Shubh","Labh"])) return "Auspicious";
  if(in_array($name, ["Rog","Kaal","Udveg"])) return "Inauspicious";
  return "Good";
}

  /** no use **/





  public function kundaliPratyantardasha(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/paryantar-dasha', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();

          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];

      }
      if($request->return_type && $request->return_type="json"){
        $pratyantardashaJson=[];
        if(isset($jsonResponse["paryantardasha"])){
          $paryantardasha=$jsonResponse["paryantardasha"][1][0];
          $paryantardasha_order=$jsonResponse["paryantardasha_order"][1][0];
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($paryantardasha);$i++){
            $year=explode(" ",$paryantardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $pratyantardashaJson[]=[
                "planet"=>$paryantardasha[$i],
                "start"=>$birth?$paryantardasha_order[($i-1)]:"Birth",
                "end"=>$paryantardasha_order[$i],
              ];
              $birth=true;
            }
          }
        }
        return $pratyantardashaJson;
      }else{
        $html="";
        if(isset($jsonResponse["paryantardasha"])){
          $paryantardasha=$jsonResponse["paryantardasha"][1][0];
          $paryantardasha_order=$jsonResponse["paryantardasha_order"][1][0];
          $html='<div class="table-responsive">';
          $html.='<table class="table table-bordered table-sm">';
          $html.='<thead class="bg-light">';
          $html.='<tr>';
          $html.='<th>Planet</th>';
          $html.='<th>Start Date</th>';
          $html.='<th>End Date</th>';
          $html.='</tr>';
          $html.='</thead>';
          $html.='<tbody>';
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($paryantardasha);$i++){
            $year=explode(" ",$paryantardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $html.='<tr>';
              $html.='<td>'.$paryantardasha[$i].'</td>';
              $html.='<td>'.($birth?$paryantardasha_order[($i-1)]:"Birth").'</td>';
              $html.='<td>'.$paryantardasha_order[$i].'</td>';
              $html.='</tr>';
              $birth=true;
            }
          }
          $html.='</tbody>';
          $html.='</table>';
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }

  public function kundaliKpPlanet(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    $planetJsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $kpPlanet=KpPlanet::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      if(!$kpPlanet){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('extended-horoscope/kp-planets', 'kp_planets', $params);
          if($apiLog){
            $kpPlanet=new KpPlanet();
            $kpPlanet->date=date("Y-m-d",$dateSeconds);
            $kpPlanet->time=$time;
            $kpPlanet->lat=$lat;
            $kpPlanet->lon=$lon;
            $kpPlanet->tz=$tz;
            $kpPlanet->lang=$lang;
            $kpPlanet->response=$apiLog->response;
            $kpPlanet->save();
            $apiLog->table_id=$kpPlanet->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($kpPlanet->response,true);
          $jsonResponse=$jsonResponse["response"];

        $planetJsonResponse=json_decode($planetDetail->response,true);
        $planetJsonResponse=$planetJsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $planetJson=[];
        for($i=0;$i<10;$i++){
          $planetItem=$planetJsonResponse[$i];
          $item=$jsonResponse[$i];
          $planetJson[]=[
            "planet"=>$planetItem["full_name"],
            "cups"=>$planetItem["house"],
            "zodiac"=>$planetItem["zodiac"],
            "zodiac_lord"=>$planetItem["zodiac_lord"],
            "nakshatra"=>$planetItem["nakshatra"],
            "nakshatra_lord"=>$planetItem["nakshatra_lord"],
            "sub_lord"=>$item["sub_lord"]
          ];
        }
        return $planetJson;
      }else{
        $html='<h5 class="mb-3">Planet Positions</h5>';
        $html.='<div class="table-responsive">';
        $html.='<table class="table table-bordered table-sm">';
        $html.='<thead class="bg-light">';
        $html.='<tr>';
        $html.='<th>Planet</th>';
        $html.='<th>Cups</th>';
        $html.='<th>Zodiac</th>';
        $html.='<th>Zodiac Lord</th>';
        $html.='<th>Start</th>';
        $html.='<th>Start Lord</th>';
        $html.='<th>Sub Lord</th>';
        $html.='</tr>';
        $html.='</thead>';
        $html.='<tbody>';
        for($i=0;$i<10;$i++){
          $planetItem=$planetJsonResponse[$i];
          $item=$jsonResponse[$i];
          $html.='<tr>';
          $html.='<td>'.$planetItem["full_name"].'</td>';
          $html.='<td>'.$planetItem["house"].'</td>';
          $html.='<td>'.$planetItem["zodiac"].'</td>';
          $html.='<td>'.$planetItem["zodiac_lord"].'</td>';
          $html.='<td>'.$planetItem["nakshatra"].'</td>';
          $html.='<td>'.$planetItem["nakshatra_lord"].'</td>';
          $html.='<td>'.$item["sub_lord"].'</td>';
          $html.='</tr>';
        }
        $html.='</tbody>';
        $html.='</table>';
        $html.='</div>';
        return response()->json(["html"=>$html]);
      }
  }



  public function kundaliDashaVimshottari(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/maha-dasha-predictions', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();

          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];

      }
      if($request->return_type && $request->return_type="json"){
        $vimshottariJson=[];
        if(isset($jsonResponse["dashas"])){
          $dashas=$jsonResponse["dashas"];
          for($i=0;$i<count($dashas);$i++){
            $vimshottariJson[]=[
              "full_name"=>$dashas[$i]["dasha"]." Mahadasha",
              "start_end"=>$dashas[$i]["dasha_start_year"]." - ".$dashas[$i]["dasha_end_year"],
              "prediction"=>$dashas[$i]["prediction"],
              "planet_in_zodiac"=>$dashas[$i]["planet_in_zodiac"],
            ];
          }
        }
        return $vimshottariJson;
      }else{
        $html="";
        if(isset($jsonResponse["dashas"])){
          $dashas=$jsonResponse["dashas"];
          for($i=0;$i<count($dashas);$i++){
            $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
            $html.='<div class="d-flex justify-content-between">';
            $html.='<strong>'.$dashas[$i]["dasha"].' Mahadasha</strong>';
            $html.='<small class="text-muted">('.$dashas[$i]["dasha_start_year"].' - '.$dashas[$i]["dasha_end_year"].')</small>';
            $html.='</div>';
            $html.='<p class="mt-2 mb-1">'.$dashas[$i]["prediction"].'</p>';
            $html.='<p>'.$dashas[$i]["planet_in_zodiac"].'</p>';
            $html.='</div>';
          }
        }
        return response()->json(["html"=>$html]);
      }
  }


  public function kundaliReportGeneral(Request $request){
    $zodiac=$request->zodiac;
    $zodiac=zodiacSignNameHindiEnglish($zodiac);
    $zodiacSign=ZodiacSign::where("name",$zodiac)->first();
    $zodiac=$zodiacSign->id;
    $timeFrame=$request->timeFrame;
    $date=date("d/m/Y");

    $split=$request->split?:true;
    $type=$request->type?:"big";
    $lang=$request->lang?:"en";
    $horoscopeDate=date("d F Y");
    $url="";
    $params = [];
    if($timeFrame=="today"){
      $date=date("d/m/Y");
      $horoscopeDate=date("d F Y");
      $params = [
        'date' =>  $date,
        'zodiac' =>  $zodiac,
        'split' =>  $split,
        'type' =>  $type,
        'lang'=>  $lang,
      ];
      $url="prediction/daily-moon";
      $date=date("Y-m-d");
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="tomorrow"){
      $date=date("d/m/Y", strtotime('now+24hours'));
      $horoscopeDate=date("d F Y", strtotime('now+24hours'));
      $params = [
        'date' =>  $date,
        'zodiac' =>  $zodiac,
        'split' =>  $split,
        'type' =>  $type,
        'lang'=>  $lang,
      ];
      $url="prediction/daily-moon";
      $date=date("Y-m-d", strtotime('now+24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="yesterday"){
      $date=date("d/m/Y", strtotime('now-24hours'));
      $horoscopeDate=date("d F Y", strtotime('now-24hours'));
      $params = [
        'date' =>  $date,
        'zodiac' =>  $zodiac,
        'split' =>  $split,
        'type' =>  $type,
        'lang'=>  $lang,
      ];
      $url="prediction/daily-moon";
      $date=date("Y-m-d", strtotime('now-24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="weekly"){
      $week="thisweek";
      $horoscopeDate=date("d F Y", strtotime('monday this week'))." - ".date("d F Y", strtotime('sunday this week'));
      $fromDate=date("Y-m-d", strtotime('monday this week'));
      $toDate=date("Y-m-d", strtotime('sunday this week'));
      $params = [
        'week' =>  $week,
        'zodiac' =>  $zodiac,
        'split' =>  $split,
        'type' =>  $type,
        'lang'=>  $lang,
      ];
      $url="prediction/weekly-moon";
      $horoscope=HoroscopeWeekly::where("zodiac_sign_id",$zodiacSign->id)->where("from_date",$fromDate)->where("to_date",$toDate)->where("lang",$lang)->first();
    }elseif($timeFrame=="yearly"){
      $timeFrameUrl="yearly";
      $horoscopeDate=$year=date("Y");
      $params = [
        'year' =>  $year,
        'zodiac' =>  $zodiac,
        'lang'=>  $lang,
      ];
      $url="prediction/yearly";
      $horoscope=HoroscopeYearly::where("zodiac_sign_id",$zodiacSign->id)->where("year",$year)->where("lang",$lang)->first();
    }
    $data=[];

    if(!$horoscope){
      $apiLog = $this->apiResp($url, 'horoscope_dailies', $params);
      if($apiLog){
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
        $horoscope->lang=$lang;
        $horoscope->api_response=$apiLog->response;
        $horoscope->save();
        $apiLog->table_id=$horoscope->id;
        $apiLog->api_status=1;
        $apiLog->save();
      }
    }
    if($horoscope){
      $jsonResponse=json_decode($horoscope->api_response,true);
      if(isset($jsonResponse["response"])){
        $jsonResponse=$jsonResponse["response"];
      }
    }
    // return response()->json(["html"=>$jsonResponse]);
    if($timeFrame=="yearly"){
      $dataa=$jsonResponse;
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
    if($request->return_type && $request->return_type="json"){
      $generalReportJson=[];
      if(isset($data["bot_response"])){
        $bot_response=$data["bot_response"];
        $generalReportJson[]=[
          "name"=>"Description",
          "prediction"=>$bot_response["prediction"]
        ];
        $generalReportJson[]=[
          "name"=>"Personality",
          "prediction"=>$bot_response["physique"]["split_response"]
        ];
        $generalReportJson[]=[
          "name"=>"Health",
          "prediction"=>$bot_response["health"]["split_response"]
        ];
        $generalReportJson[]=[
          "name"=>"Career",
          "prediction"=>$bot_response["career"]["split_response"]
        ];
        $generalReportJson[]=[
          "name"=>"Relationship",
          "prediction"=>$bot_response["relationship"]["split_response"]
        ];
        $generalReportJson[]=[
          "name"=>"Family",
          "prediction"=>$bot_response["family"]["split_response"]
        ];
      }
      return $generalReportJson;
    }else{
      $html="";
      if(isset($data["bot_response"])){
        $bot_response=$data["bot_response"];
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Description</h6>';
        $html.='<p>'.$bot_response["prediction"].'</p>';
        $html.='</div>';
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Personality</h6>';
        $html.='<p>'.$bot_response["physique"]["split_response"].'</p>';
        $html.='</div>';
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Health</h6>';
        $html.='<p>'.$bot_response["health"]["split_response"].'</p>';
        $html.='</div>';
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Career</h6>';
        $html.='<p>'.$bot_response["career"]["split_response"].'</p>';
        $html.='</div>';
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Relationship</h6>';
        $html.='<p>'.$bot_response["relationship"]["split_response"].'</p>';
        $html.='</div>';
        $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
        $html.='<h6 class="fw-bold">Family</h6>';
        $html.='<p>'.$bot_response["family"]["split_response"].'</p>';
        $html.='</div>';
      }
      return response()->json(["html"=>$html]);
    }
  }

  public function kundaliReportPlanetary(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $planet=Planet::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      if(!$planet){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('horoscope/personal-characteristics', 'planets', $params);
          if($apiLog){
            $planet=new Planet();
            $planet->date=date("Y-m-d",$dateSeconds);
            $planet->time=$time;
            $planet->lat=$lat;
            $planet->lon=$lon;
            $planet->tz=$tz;
            $planet->lang=$lang;
            $planet->response=$apiLog->response;
            $planet->save();
            $apiLog->table_id=$planet->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
        $jsonResponse=json_decode($planet->response,true);
        $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $planetaryReportJson=[];
        foreach($jsonResponse as $key => $value){
          $planetaryReportJson[]=[
            "planet"=>$value["lord_of_zodiac"],
            "prediction"=>$value["personalised_prediction"]
          ];
        }
        return $planetaryReportJson;
      }else{
        $html="";
        foreach ($jsonResponse as $key => $value) {
          $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
          $html.='<h6 class="fw-bold">'.$value["lord_of_zodiac"].' Consideration</h6>';
          $html.='<p>'.$value["personalised_prediction"].'</p>';
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }

  public function kundaliReportYoga(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){
      $apiKey=fromSetting('astro_api_key');
      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $yoga=Yoga::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      if(!$yoga){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('extended-horoscope/yoga-list', 'yogas', $params);
          if($apiLog){
            $yoga=new Yoga();
            $yoga->date=date("Y-m-d",$dateSeconds);
            $yoga->time=$time;
            $yoga->lat=$lat;
            $yoga->lon=$lon;
            $yoga->tz=$tz;
            $yoga->lang=$lang;
            $yoga->response=$apiLog->response;
            $yoga->save();
            $apiLog->table_id=$yoga->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($yoga->response,true);
          $jsonResponse=$jsonResponse["response"];

      }
      if($request->return_type && $request->return_type="json"){
        $yogaReportJson=[];
        foreach($jsonResponse["yogas_list"] as $key => $value){
          $yogaReportJson[]=[
            "name"=>$value["yoga"],
            "meaning"=>$value["meaning"]
          ];
        }
        return $yogaReportJson;
      }else{
        $html="";
        foreach ($jsonResponse["yogas_list"] as $key => $value) {
          $html.='<div class="p-3 border rounded bg-danger-subtle mb-3">';
          $html.='<h6 class="fw-bold">'.$value["yoga"].'</h6>';
          $html.='<p>'.$value["meaning"].'</p>';
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }



  public function kundaliReportPitra(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
      if(!$dosh){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dosha/pitra-dosh', 'doshes', $params);
          if($apiLog){
            $dosh=new Dosh();
            $dosh->date=date("Y-m-d",$dateSeconds);
            $dosh->time=$time;
            $dosh->lat=$lat;
            $dosh->lon=$lon;
            $dosh->tz=$tz;
            $dosh->lang=$lang;
            $dosh->response=$apiLog->response;
            $dosh->dosh=$request->id;
            $dosh->save();
            $apiLog->table_id=$dosh->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dosh->response,true);
          $jsonResponse=$jsonResponse["response"];

      }
      preg_match('/([\d.]+)%/',$jsonResponse["bot_response"],$matches);
      $percentage=0;
      if(isset($matches[1])){
        $percentage=(float) $matches[1];
      }
      $html='<h6 class="fw-bold">Pitra Analysis</h6>';
      $html.='<div class="p-3 border rounded bg-danger-subtle" style="border: 1px solid #dc3545;">';
      $html.='<div class="d-flex align-items-center mb-2">';
      $html.='<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">'.($jsonResponse["is_dosha_present"]?"Yes":"No").'</div>';
      $html.='<div><strong>Pitra Dosha</strong></div>';
      $html.='</div>';
      foreach ($jsonResponse["remedies"] as $key => $value){
        $html.='<p class="mb-1">'.$value.'</p>';
      }
      $html.='</div>';
      return response()->json(["html"=>$html]);
  }

  public function kundalimatching(Request $request){
      $breads[] = ['name' => 'kundalimatching','is_last' => true, 'url'=>'#'];
      $nav = "kundalimatching";

      $today = date('Y-m-d');
      $banners = Banner::where('status', 1)
      ->where('type', "kundali_matching")
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();


      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_free_kundali_matching");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "free_kundali_matching_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_free_kundali_matching", $user_type);
      }
      return view(themeView().'pages.kundalimatching.index', compact("nav", 'breads', "banners"));
  }

  public function kundaliMatchingEnquiry(Request $request){
    $request->validate([
      "boy_name"=>"required",
      "boy_birth_date"=>"required|date_format:Y-m-d|before:today",
      "boy_birth_time"=>"required",
      "boy_birth_place"=>"required",
      "girl_name"=>"required",
      "girl_birth_date"=>"required|date_format:Y-m-d|before:today",
      "girl_birth_time"=>"required",
      "girl_birth_place"=>"required",
    ]);
    $boy_name = $request->boy_name;
    $boy_birth_date = $request->boy_birth_date;
    $boy_birth_time = $request->boy_birth_time;
    $boy_birth_place = $request->boy_birth_place;
    $girl_name = $request->girl_name;
    $girl_birth_date = $request->girl_birth_date;
    $girl_birth_time = $request->girl_birth_time;
    $girl_birth_place = $request->girl_birth_place;
    $lang = $request->lang?:'en';

    $kundaliMatchingEnquiry = KundaliMatchingEnquiry::where("boy_name", $boy_name)
    ->where("boy_birth_date", $boy_birth_date)
    ->where("boy_birth_time", $boy_birth_time)
    ->where("boy_birth_place", $boy_birth_place)
    ->where("girl_name", $girl_name)
    ->where("girl_birth_date", $girl_birth_date)
    ->where("girl_birth_time", $girl_birth_time)
    ->where("girl_birth_place", $girl_birth_place)
    ->where("lang", $lang)
    ->first();

    if(!$kundaliMatchingEnquiry){
      $kundaliMatchingEnquiry = new KundaliMatchingEnquiry();
      $kundaliMatchingEnquiry->boy_name = $boy_name;
      $kundaliMatchingEnquiry->boy_birth_date = $boy_birth_date;
      $kundaliMatchingEnquiry->boy_birth_time = $boy_birth_time;
      $kundaliMatchingEnquiry->boy_birth_place = $boy_birth_place;

      $lH = LocationHistory::where('state', $boy_birth_place)->first();

      $kundaliMatchingEnquiry->boy_lat = $lH?$lH->latDeg:config('vrdcms.delhi_lat');
      $kundaliMatchingEnquiry->boy_lon = $lH?$lH->longDeg:config('vrdcms.delhi_lon');
      $kundaliMatchingEnquiry->girl_name = $girl_name;
      $kundaliMatchingEnquiry->girl_birth_date = $girl_birth_date;
      $kundaliMatchingEnquiry->girl_birth_time = $girl_birth_time;
      $kundaliMatchingEnquiry->girl_birth_place = $girl_birth_place;

      $lH = LocationHistory::where('state', $girl_birth_place)->first();

      $kundaliMatchingEnquiry->girl_lat = $lH?$lH->latDeg:config('vrdcms.delhi_lat');
      $kundaliMatchingEnquiry->girl_lon = $lH?$lH->latDeg:config('vrdcms.delhi_lon');
      $kundaliMatchingEnquiry->lang = $lang;
      $kundaliMatchingEnquiry->save();
    }
    return response()->json(["suceess"=>true,"message"=>"Please wait. Kundali is generating.","redirectTo"=>route("kundalimatchingdetail", [$kundaliMatchingEnquiry->id])]);
  }

  public function kundaliMatchingDetail(Request $request){
      $breads[] = ['name' => 'Kundali Matching','is_last' => false, 'url'=> route('kundalimatching')];
      $breads[] = ['name' => 'detail','is_last' => true, 'url'=>'#'];
      $nav="kundalimatching";
      $kundaliMatchingEnquiry = KundaliMatchingEnquiry::find($request->kundali_matching_enquiries_id);
      $data=[];
      $jsonResponse=null;

      if($kundaliMatchingEnquiry){

        $boy_birth_date_time_seconds = strtotime($kundaliMatchingEnquiry->boy_birth_date." ".$kundaliMatchingEnquiry->boy_birth_time);
        $boy_birth_date = date("d/m/Y", $boy_birth_date_time_seconds);
        $boy_birth_time = date("H:i", $boy_birth_date_time_seconds);
        $boy_lat = $kundaliMatchingEnquiry->boy_lat;
        $boy_lon = $kundaliMatchingEnquiry->boy_lon;
        $girl_birth_date_time_seconds = strtotime($kundaliMatchingEnquiry->girl_birth_date." ".$kundaliMatchingEnquiry->girl_birth_time);
        $girl_birth_date = date("d/m/Y", $girl_birth_date_time_seconds);
        $girl_birth_time = date("H:i", $girl_birth_date_time_seconds);
        $girl_lat = $kundaliMatchingEnquiry->girl_lat;
        $girl_lon = $kundaliMatchingEnquiry->girl_lon;
        $tz = "5.5";
        $lang=$kundaliMatchingEnquiry->lang;
        $kundaliMatching = KundaliMatching::where("kundali_matching_enquiries_id", $kundaliMatchingEnquiry->id)->where("lang", $lang)->first();

        if(!$kundaliMatching){
          $params = [
            'boy_dob'=>$boy_birth_date,
            'boy_tob'=>$boy_birth_time,
            'boy_tz'=>$tz,
            'boy_lat'=>$boy_lat,
            'boy_lon'=>$boy_lon,
            'girl_dob'=>$girl_birth_date,
            'girl_tob'=>$girl_birth_time,
            'girl_tz'=>$tz,
            'girl_lat'=>$girl_lat,
            'girl_lon'=>$girl_lon,
            'lang'=>$lang,
          ];
          $apiLog = $this->apiResp('matching/ashtakoot', 'kundali_matchings', $params);
          if($apiLog){
            $kundaliMatching = new KundaliMatching();
            $kundaliMatching->kundali_matching_enquiries_id = $kundaliMatchingEnquiry->id;
            $kundaliMatching->lang = $lang;
            $kundaliMatching->response = $apiLog->response;
            $kundaliMatching->save();

            $apiLog->table_id = $kundaliMatching->id;
            $apiLog->api_status = 1;
            $apiLog->save();

            if(isset($jsonResponseMain["response"])){
              $jsonResponse = $jsonResponseMain["response"];
            }
          }
        }else{
          $jsonResponse = json_decode($kundaliMatching->response, true);
          $jsonResponse = $jsonResponse["response"];
        }
      }else{
        return abort(404);
      }
      if(!is_array($jsonResponse)){
        Session::flash("error-toastr", "Some technical error occured. Please try again later.");
        return redirect()->route("kundalimatching");
      }

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_free_kundali_matching_detail");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "free_kundali_matching_detail_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $kundaliMatchingEnquiry->id;
        $body["custom_data"]["content_name"] = $kundaliMatchingEnquiry->boy_name." - ".$kundaliMatchingEnquiry->girl_name;
        $body["custom_data"]["content_type"] = "Kundali";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_free_kundali_matching_detail", $user_type);
      }
      return view(themeView().'pages.kundalimatching.detail', compact('nav', 'breads', 'jsonResponse', 'kundaliMatchingEnquiry'));
  }

  public function kunpanrep(){
    return view(themeView().'pages.kundali-panchang-report');
  }

  public function horoscopedetail(Request $request){
    $zodiac=$request->zodiac;

    $zodiac=zodiacSignNameHindiEnglish(strtolower($zodiac));
    $nav="horoscope";
    $zodiacSign=ZodiacSign::where("name",$zodiac)->first();
    if(!$zodiacSign) abort(404);
    $zodiac=$zodiacSign->id;
    $timeFrame=$request->timeFrame;
    $date=date("d/m/Y");

    $date=date("d/m/Y", strtotime($date));
    $split=$request->split?:true;
    $type=$request->type?:"big";
    $lang=$request->lang?:"en";
    $horoscopeDate=date("d F Y");
    $url="";
    $params = [];
    if($timeFrame=="today"){
      $date=date("d/m/Y");
      $horoscopeDate=date("d F Y");
      $params = [
        'date'=>$date,
        'zodiac'=>$zodiac,
        'split'=>$split,
        'type'=>$type,
        'lang'=>$lang,
      ];
      $url="prediction/daily-moon";
      $date=date("Y-m-d");
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="tomorrow"){
      $date=date("d/m/Y", strtotime('now+24hours'));
      $horoscopeDate=date("d F Y", strtotime('now+24hours'));
      $params = [
        'date'=>$date,
        'zodiac'=>$zodiac,
        'split'=>$split,
        'type'=>$type,
        'lang'=>$lang,
      ];
      $url="prediction/daily-moon";

      $date=date("Y-m-d", strtotime('now+24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="yesterday"){
      $date=date("d/m/Y", strtotime('now-24hours'));
      $horoscopeDate=date("d F Y", strtotime('now-24hours'));
      $params = [
        'date'=>$date,
        'zodiac'=>$zodiac,
        'split'=>$split,
        'type'=>$type,
        'lang'=>$lang,
      ];
      $url="prediction/daily-moon";

      $date=date("Y-m-d", strtotime('now-24hours'));
      $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where("lang",$lang)->first();
    }elseif($timeFrame=="weekly"){
      $week="thisweek";
      $horoscopeDate=date("d F Y", strtotime('monday this week'))." - ".date("d F Y", strtotime('sunday this week'));
      $fromDate=date("Y-m-d", strtotime('monday this week'));
      $toDate=date("Y-m-d", strtotime('sunday this week'));
      $params = [
        'week'=>$week,
        'zodiac'=>$zodiac,
        'split'=>$split,
        'type'=>$type,
        'lang'=>$lang,
      ];
      $url="prediction/weekly-moon";

      $horoscope=HoroscopeWeekly::where("zodiac_sign_id",$zodiacSign->id)->where("from_date",$fromDate)->where("to_date",$toDate)->where("lang",$lang)->first();
    }elseif($timeFrame=="yearly"){
      $timeFrameUrl="yearly";
      $horoscopeDate=$year=date("Y");
      $params = [
        'year'=>$year,
        'zodiac'=>$zodiac,
        'lang'=>$lang,
      ];
      $url="prediction/yearly";
      $horoscope=HoroscopeYearly::where("zodiac_sign_id",$zodiacSign->id)->where("year",$year)->where("lang",$lang)->first();
    }else{
      abort(404);
    }
    $data=[];

    if(!$horoscope){
      $apiLog = $this->apiResp($url, 'horoscope_yearlies', $params);
      if($apiLog){
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
            $apiLog->table_name="horoscope_dailies";
            $apiLog->name="horoscope_dailies";
          }
        }
        $horoscope->lang=$lang;
        $horoscope->api_response=$apiLog->response;
        $horoscope->save();
        $apiLog->table_id=$horoscope->id;
        $apiLog->api_status=1;
        $apiLog->save();
      }
    }

      $jsonResponse=json_decode($horoscope->api_response,true);
      if(isset($jsonResponse["response"])){
        $data=$jsonResponse["response"];
      }

    $viewFile=themeView().'pages.horoscopedetail';
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

    $user = auth()->guard("web")->user();
    $user_type = "guest";
    $event = Session::get("view_content_horoscope_detail");
    if($event == null || ($event == "guest" && $user != null)){
      $timestamp = time();
      $body["event_name"] = "ViewContent";
      $body["event_time"] = $timestamp;
      $body["event_id"] = "horoscope_detail_{$timestamp}";
      $body["action_source"] = "website";
      $body["event_source_url"] = $request->fullUrl();
      $body["user_data"]["client_ip_address"] = $request->ip();
      $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
      $body["user_data"]["fn"] = sha256("guest");
      if($user){
        $body["user_data"]["em"] = sha256($user->email);
        $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
        $body["user_data"]["fn"] = sha256($user->name);
        $user_type = "user";
      }
      $body["custom_data"]["content_id"] = $horoscope ? $horoscope->id : 0;
      $body["custom_data"]["content_name"] = $zodiacSign ? $zodiacSign->name : "0";
      $body["custom_data"]["content_type"] = "Panchang";
      $metaService = new Meta();
      $metaService->sendEvent($body);
      Session::put("view_content_horoscope_detail", $user_type);
    }
    $breads[]=["name"=>"Horoscope","url"=>route("horoscopes")];
    $breads[]=["name"=>"$zodiac Detail","url"=>route("horoscopedetail",[$zodiac,$timeFrame])];
    return view($viewFile, compact("nav","breads","zodiacSign","timeFrame","horoscopeDate","data"));
  }





  public function charDashaCurrent(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/char-dasha-current', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $antardashaJson=[];
        if(isset($jsonResponse["antardashas"])){
          $antardashas=$jsonResponse["antardashas"][0];
          $antardasha_order=$jsonResponse["antardasha_order"][0];
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($antardashas);$i++){
            $year=explode(" ",$antardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $antardashaJson[]=[
                "planet"=>$antardashas[$i],
                "start"=>$birth?$antardasha_order[($i-1)]:"Birth",
                "end"=>$antardasha_order[$i],
              ];
              $birth=true;
            }
          }
        }
        return $antardashaJson;
      }else{
        $html="";
        if(isset($jsonResponse["main_dasha"])){
          $main_dasha=$jsonResponse["main_dasha"];
          $main_dasha_lord=$jsonResponse["main_dasha_lord"];
          $sub_dasha_list=$jsonResponse["sub_dasha_list"];
          $sub_dasha_start_date=$jsonResponse["sub_dasha_start_date"];
          $sub_dasha_end_dates=$jsonResponse["sub_dasha_end_dates"];

          $html='<div class="table-responsive">';
          $html.="Main Dasha: $main_dasha";
          $html.="Main Dasha Lord: $main_dasha_lord";
          $html.="Sub Dasha List: ".implode(', ', $sub_dasha_list);
          $html.="Sub Dasha Start: $sub_dasha_start_date";
          $html.="Sub Dasha Ends: ".implode('<br> ', $sub_dasha_end_dates);
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }
  public function charDashaMain(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/char-dasha-main', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $antardashaJson=[];
        if(isset($jsonResponse["antardashas"])){
          $antardashas=$jsonResponse["antardashas"][0];
          $antardasha_order=$jsonResponse["antardasha_order"][0];
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($antardashas);$i++){
            $year=explode(" ",$antardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $antardashaJson[]=[
                "planet"=>$antardashas[$i],
                "start"=>$birth?$antardasha_order[($i-1)]:"Birth",
                "end"=>$antardasha_order[$i],
              ];
              $birth=true;
            }
          }
        }
        return $antardashaJson;
      }else{
        $html="";
        if(isset($jsonResponse["dasha_list"])){
          $dasha_list=$jsonResponse["dasha_list"];
          $start_date=$jsonResponse["start_date"];
          $dasha_end_dates=$jsonResponse["dasha_end_dates"];

          $html='<div class="table-responsive">';
          $html.="Dasha List: ".implode(', ', $dasha_list);
          $html.="Dasha Start: $start_date";
          $html.="Dasha Ends: ".implode('<br> ', $dasha_end_dates);
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }

  public function charDashaSub(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/char-dasha-sub', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $antardashaJson=[];
        if(isset($jsonResponse["antardashas"])){
          $antardashas=$jsonResponse["antardashas"][0];
          $antardasha_order=$jsonResponse["antardasha_order"][0];
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($antardashas);$i++){
            $year=explode(" ",$antardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $antardashaJson[]=[
                "planet"=>$antardashas[$i],
                "start"=>$birth?$antardasha_order[($i-1)]:"Birth",
                "end"=>$antardasha_order[$i],
              ];
              $birth=true;
            }
          }
        }
        return $antardashaJson;
      }else{
        $html="";
        if(count($jsonResponse)>0){
          $html = '';
          foreach($jsonResponse as $jrs){
              $main_dasha=$jrs["main_dasha"];
              $main_dasha_lord=$jrs["main_dasha_lord"];
              $sub_dasha_list=$jrs["sub_dasha_list"];
              $sub_dasha_start_date=$jrs["sub_dasha_start_date"];
              $sub_dasha_end_dates=$jrs["sub_dasha_end_dates"];

              $html='<div class="table-responsive">';
              $html.="Main Dasha: $main_dasha";
              $html.="Main Dasha Lord: $main_dasha_lord";
              $html.="Sub Dasha List: ".implode(', ', $sub_dasha_list);
              $html.="Sub Dasha Start: $sub_dasha_start_date";
              $html.="Sub Dasha Ends: ".implode('<br> ', $sub_dasha_end_dates);
              $html.='</div>';
            }
        }
        return response()->json(["html"=>$html]);
      }
  }

  public function currentMahadashaFull(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dasha=$request->dasha;
      $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('dashas/current-mahadasha-full', 'dashas', $params);
          if($apiLog){
            $dashaModel=new Dasha();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->dasha=$dasha;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"];
      }
      if($request->return_type && $request->return_type="json"){
        $antardashaJson=[];
        if(isset($jsonResponse["antardashas"])){
          $antardashas=$jsonResponse["antardashas"][0];
          $antardasha_order=$jsonResponse["antardasha_order"][0];
          $birth=false;
          $birthYear=date("Y",$dateSeconds);
          for($i=0;$i<count($antardashas);$i++){
            $year=explode(" ",$antardasha_order[$i]);
            $year=end($year);
            if($year>$birthYear){
              $antardashaJson[]=[
                "planet"=>$antardashas[$i],
                "start"=>$birth?$antardasha_order[($i-1)]:"Birth",
                "end"=>$antardasha_order[$i],
              ];
              $birth=true;
            }
          }
        }
        return $antardashaJson;
      }else{
        $html="";
        if(isset($jsonResponse["dasha_list"])){
          $dasha_list=$jsonResponse["dasha_list"];
          $start_date=$jsonResponse["start_date"];
          $dasha_end_dates=$jsonResponse["dasha_end_dates"];

          $html='<div class="table-responsive">';
          $html.="Dasha List: ".implode(', ', $dasha_list);
          $html.="Dasha Start: $start_date";
          $html.="Dasha Ends: ".implode('<br> ', $dasha_end_dates);
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
  }



  public function numeroTable(Request $request){
    $planetDetail=PlanetDetail::find($request->planet_detail_id);
    $jsonResponse=[];
    if($planetDetail){

      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $dashaModel=NumeroSuggestion::where("date",$date)->where("time",$time)->where("lat",$lat)
      ->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
      if(!$dashaModel){
          $date=date("d/m/Y",$dateSeconds);
          $params = [
            'dob' =>  $date,
            'tob' =>  $time,
            'lat' =>  $lat,
            'lon' =>  $lon,
            'tz'  =>  $tz,
            'lang'=>  $lang,
          ];
          $apiLog = $this->apiResp('extended-horoscope/numero-table', 'numero_suggestions', $params);
          if($apiLog){
            $dashaModel=new NumeroSuggestion();
            $dashaModel->date=date("Y-m-d",$dateSeconds);
            $dashaModel->time=$time;
            $dashaModel->lat=$lat;
            $dashaModel->lon=$lon;
            $dashaModel->tz=$tz;
            $dashaModel->lang=$lang;
            $dashaModel->response=$apiLog->response;
            $dashaModel->save();
            $apiLog->table_id=$dashaModel->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }
          $jsonResponse=json_decode($dashaModel->response,true);
          $jsonResponse=$jsonResponse["response"]??[];
      }
      if($request->return_type && $request->return_type="json"){
        return $jsonResponse;
      }else{
        $html="";
        if(isset($jsonResponse["name"])){
          $radical_number=$jsonResponse["radical_number"];
          $radical_ruler=$jsonResponse["radical_ruler"];
          $characteristics=$jsonResponse["characteristics"];
          $fav_color=$jsonResponse["fav_color"];
          $fav_day=$jsonResponse["fav_day"];
          $fav_god=$jsonResponse["fav_god"];
          $fav_mantra=$jsonResponse["fav_mantra"];
          $fav_metal=$jsonResponse["fav_metal"];
          $fav_stone=$jsonResponse["fav_stone"];
          $fav_substone=$jsonResponse["fav_substone"];
          $friendly_num=$jsonResponse["friendly_num"];
          $evil_num=$jsonResponse["evil_num"];
          $neutral_num=$jsonResponse["neutral_num"];
          $destiny=$jsonResponse["destiny"];
          $name_number=$jsonResponse["name_number"];
          $date_considered=$jsonResponse["date_considered"];


          $html='<div class="table-responsive">';
          $html.="Radical Number: ".$radical_number;
          $html.="<br>Radical Ruler: ".$radical_ruler;
          $html.="<br>Characteristics: ".implode('<br> ', $characteristics);
          $html.="<br>Fav Color: ".$fav_color;
          $html.="<br>Dav Day: ".$fav_day;
          $html.="<br>Fav God: ".$fav_god;
          $html.="<br>Fav Mantra: ".$fav_mantra;
          $html.="<br>Fav Metal: ".$fav_metal;
          $html.="<br>Fav Stone: ".$fav_stone;
          $html.="<br>Fav Substone: ".$fav_substone;
          $html.="<br>Friendly Num: ".$friendly_num;
          $html.="<br>Evil Num: ".$evil_num;
          $html.="<br>Neutral Num: ".$neutral_num;
          $html.="<br>Destiny: ".$destiny;
          $html.="<br>Name Number: ".$name_number;
          $html.="<br>Date Considered: ".$date_considered;
          $html.='</div>';
          return response()->json(["html"=>$html]);
        }
          return response()->json(["html"=>"No data found"]);
      }
  }


  public function locations(Request $request){
    $request->validate([
      "searchPlace"=>"required"
    ]);
    $searchPlace=$request->searchPlace;
    $search_result = LocationHistory::select('id','state','place')->where('place','LIKE',"$searchPlace%")->orderBy('place', 'ASC')->get();
    if($search_result->count()==0){
      $url=optionByKey('astro_api_ip') ?: "";
      $apiKey=optionByKey('astro_api_token') ?: "";
      $url="$url?api_key=$apiKey&city=$searchPlace";
      $response=Http::withoutVerifying()->get($url);
      $statusCode=$response->status();
      $jsonResponse=$response->json();
      $apiLog=new ApiLog();
      $apiLog->table_name="location_histories";
      $apiLog->table_id=0;
      $apiLog->name="location_histories";
      $apiLog->request=$url;
      $apiLog->response=$response;
      $apiLog->api_status=0;
      $apiLog->save();
      if($jsonResponse["status"]===200){
        $data=$jsonResponse["response"];
        foreach($data as $key=>$item){
          $place=$item["name"];
          $country=$item["country_name"];
          $lh = LocationHistory::where('place',$place)->where('country',$country)->first();
          if(!$lh){
            $coordinates=$item["coordinates"];
            $lh = new LocationHistory();
            $lh->place = $place;
            $lh->state = $item["full_name"];
            $lh->country = $country;
            $lh->latDeg = $coordinates[0];
            $lh->longDeg = $coordinates[1];
            $lh->save();
          }
        }
        $apiLog->table_id=$lh?$lh->id:0;
        $apiLog->api_status=1;
        $apiLog->save();
        if(isset($jsonResponse["response"])){
          $data=$jsonResponse["response"];
        }
        $search_result = LocationHistory::select('id','state','place')->where('place','LIKE',"$searchPlace%")->orderBy('place', 'ASC')->get();
      }
    }
    $data=$search_result->map(function($item){
      return [
        "name"=>$item->place,
        "full_name"=>$item->state,
      ];
    });
    return response()->json(["success"=>true,"data"=>$data]);
  }


}
