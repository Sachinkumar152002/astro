<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{LocationHistory};
use App\Model\{ApiLog,KundaliEnquiry,PlanetDetail,DivisionChart,AkvargaChart, KundaliMatchingEnquiry, KundaliMatching};
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\{PagesController};

class KundaliController extends Controller
{
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
      $response=Http::get($url);
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

  public function detail(Request $request){
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
    $kundaliEnquiry=KundaliEnquiry::where("name",$name)->where("gender",$gender)->where("date",$date)->where("time",$time)->where("city",$city)->first();
    if(!$kundaliEnquiry){
      $lH=LocationHistory::where('state',$city)->first();
      $kundaliEnquiry=new KundaliEnquiry();
      $kundaliEnquiry->name=$name;
      $kundaliEnquiry->gender=$gender;
      $kundaliEnquiry->date=$date;
      $kundaliEnquiry->time=$time;
      $kundaliEnquiry->city=$city;
      $kundaliEnquiry->lat=$lH?$lH->latDeg:config('vrdcms.delhi_lat');
      $kundaliEnquiry->lon=$lH?$lH->longDeg:config('vrdcms.delhi_lon');
      $kundaliEnquiry->save();
    }
    $apiKey=fromSetting('astro_api_key');
    $dateSeconds=strtotime($date." ".$time);
    $date=date("Y-m-d",$dateSeconds);
    $time=date("H:i",$dateSeconds);
    $lat=$kundaliEnquiry->lat ?: "";
    $lon=$kundaliEnquiry->lon ?: "";
    $tz="5.5";
    $lang=$request->lang?:"en";
    $planetDetail=PlanetDetail::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
    $date=date("d/m/Y",$dateSeconds);
    if(!$planetDetail){
      $url="https://api.vedicastroapi.com/v3-json/horoscope/planet-details?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
      $response=Http::get($url);
      $statusCode=$response->status();
      $jsonResponseMain=$response->json();
      $apiLog=new ApiLog();
      $apiLog->table_name="planet_details";
      $apiLog->table_id=0;
      $apiLog->name="planet_details";
      $apiLog->request=$url;
      $apiLog->response=$response;
      $apiLog->api_status=0;
      $apiLog->save();
      if($jsonResponseMain["status"]===200){
        $planetDetail=new PlanetDetail();
        $planetDetail->date=date("Y-m-d",$dateSeconds);
        $planetDetail->time=$time;
        $planetDetail->lat=$lat;
        $planetDetail->lon=$lon;
        $planetDetail->tz=$tz;
        $planetDetail->lang=$lang;
        $planetDetail->response=$response;
        $planetDetail->save();
        $apiLog->table_id=$planetDetail->id;
        $apiLog->api_status=1;
        $apiLog->save();
        if(isset($jsonResponseMain["response"])){
          $jsonResponse=$jsonResponseMain["response"];
        }
      }
      //extended detail
      $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/extended-kundli-details?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
      $response=Http::get($url);
      $statusCode=$response->status();
      $jsonResponseExtended=$response->json();
      $apiLog=new ApiLog();
      $apiLog->table_name="planet_details";
      $apiLog->table_id=0;
      $apiLog->name="planet_details";
      $apiLog->request=$url;
      $apiLog->response=$response;
      $apiLog->api_status=0;
      $apiLog->save();
      if($jsonResponseExtended["status"]===200){
        if(isset($jsonResponseExtended["response"])){
          $jsonResponseMain["response"]["extended"]=$jsonResponseExtended["response"];
        }
        $planetDetail->response=$jsonResponseMain;
        $planetDetail->save();
        $apiLog->table_id=$planetDetail->id;
        $apiLog->api_status=1;
        $apiLog->save();
      }
      $jsonResponse=$jsonResponseMain["response"];
    }else{
      $jsonResponse=json_decode($planetDetail->response,true);
      $jsonResponse=$jsonResponse["response"];
    }
    $panchang = is_array($jsonResponse) && isset($jsonResponse["panchang"]) ? $jsonResponse["panchang"] : [];
    $basicDetail=[
      "name"=>$name,
      "date"=>$date,
      "time"=>date("h:i A",$dateSeconds),
      "place"=>$city,
      "latitude"=>$lat,
      "longitude"=>$lon,
      "timezone"=>"GMT+5.5",
      "sun_rise"=>isset($panchang["sunrise_at_birth"]) ? $panchang["sunrise_at_birth"] : "",
      "sun_set"=>isset($panchang["sunset_at_birth"]) ? $panchang["sunset_at_birth"] : "",
      "ayanamsha"=>isset($panchang["ayanamsa_name"]) ? $panchang["ayanamsa_name"] : ""
    ];
    $extended=isset($jsonResponse["extended"]) ? $jsonResponse["extended"] : [];
    $avakhadaDetail=[
      "varna"=>isset($extended["varna"]) ? $extended["varna"] : "",
      "vasya"=>isset($extended["vasya"]) ? $extended["vasya"] : "",
      "yoni"=>isset($extended["yoni"]) ? $extended["yoni"] : "",
      "gana"=>isset($extended["gana"]) ? $extended["gana"] : "",
      "nadi"=>isset($extended["nadi"]) ? $extended["nadi"] : "",
      "zodiac"=>isset($extended["rasi"]) ? $extended["rasi"] : "",
      "zodiac_lord"=>isset($extended["rasi_lord"]) ? $extended["rasi_lord"] : "",
      "nakshatra"=>isset($extended["nakshatra"]) ? $extended["nakshatra"] : "",
      "yoga"=>isset($extended["yoga"]) ? $extended["yoga"] : "",
      "karana"=>isset($extended["karana"]) ? $extended["karana"] : "",
      "tithi"=>isset($extended["tithi"]) ? $extended["tithi"] : "",
      "tatva"=>isset($extended["tatva"]) ? $extended["tatva"] : "",
      "name_alphabet"=>isset($extended["name_start"]) ? $extended["name_start"] : "",
      "paya"=>isset($extended["paya"]) ? $extended["paya"] : "",
    ];
    $panchangDetail=[
      "tithi"=>isset($panchang["tithi"]) ? $panchang["tithi"] : "",
      "karana"=>isset($panchang["karana"]) ? $panchang["karana"] : "",
      "yoga"=>isset($panchang["yoga"]) ? $panchang["yoga"] : "",
    ];
    $data=[
      "planet_id"=>$planetDetail ? $planetDetail->id : 0,
      "basic_detail"=>$basicDetail,
      "avakhada_detail"=>$avakhadaDetail,
      "panchang_detail"=>$panchangDetail,
    ];
    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function chart(Request $request, PlanetDetail $planetDetail){ //division chart
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $response="";
    if($planetDetail){
      $apiKey=fromSetting('astro_api_key');
      $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
      $date=date("Y-m-d",$dateSeconds);
      $time=date("H:i",$dateSeconds);
      $lat=$planetDetail->lat;
      $lon=$planetDetail->lon;
      $tz=$planetDetail->tz;
      $lang=$planetDetail->lang;
      $division=$request->div;
      $style="north";
      $divisionChart=DivisionChart::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("division",$division)->first();
      if(!$divisionChart){
        $date=date("d/m/Y",$dateSeconds);
        $url="https://api.vedicastroapi.com/v3-json/horoscope/chart-image?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang&div=$division&style=$style";
        $response=Http::get($url);
        $apiLog=new ApiLog();
        $apiLog->table_name="division_charts";
        $apiLog->table_id=0;
        $apiLog->name="division_charts";
        $apiLog->request=$url;
        $apiLog->response=$response;
        $apiLog->api_status=0;
        $apiLog->save();
        if(!str_contains($response, 'status')){
          $divisionChart=new DivisionChart();
          $divisionChart->date=date("Y-m-d",$dateSeconds);
          $divisionChart->time=$time;
          $divisionChart->lat=$lat;
          $divisionChart->lon=$lon;
          $divisionChart->tz=$tz;
          $divisionChart->lang=$lang;
          $divisionChart->division=$division;
          $divisionChart->style="north";
          $divisionChart->response=$response;
          $divisionChart->save();
          $apiLog->table_id=$divisionChart->id;
          $apiLog->api_status=1;
          $apiLog->save();
        }
      }else{
        $response=$divisionChart->response;
      }
    }
    return $response;
  }

  public function kundali(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $planetDetail=PlanetDetail::find($request->planet_id);
    $request->division="D1";
    $d1Chart=$this->chart($request, $planetDetail);
    $request->division="D9";
    $d9Chart=$this->chart($request, $planetDetail);

    $jsonResponse=json_decode($planetDetail->response,true);
    $jsonResponse=$jsonResponse["response"];
    $planets=[];
    for($i=0;$i<10;$i++){
      if(isset($jsonResponse["$i"])){
        $item=$jsonResponse["$i"];
        $planets[]=[
          "full_name"=>$item["full_name"],
          "zodiac"=>$item["zodiac"],
          "zodiac_lord"=>$item["zodiac_lord"],
          "nakshatra"=>$item["nakshatra"],
          "nakshatra_lord"=>$item["nakshatra_lord"],
          "local_degree"=>$item["local_degree"],
          "local_degree"=>$item["local_degree"],
          "retro"=>isset($item["retro"])?"Direct":"Retro",
          "is_combust"=>isset($item["is_combust"]) && $item["is_combust"]?"Yes":"No",
          "basic_avastha"=>$item["basic_avastha"],
          "house"=>$item["house"],
          "lord_status"=>$item["lord_status"]
        ];
      }
    }

    $request->planet_detail_id=$planetDetail->id;
    $request->return_type="json";
    $request->dasha="mahadasha";
    $pagesController=new PagesController();
    $mahadasha=$pagesController->kundaliMahadasha($request);
    $request->dasha="antardasha";
    $antradasha=$pagesController->kundaliAntardasha($request);
    $request->dasha="pratyantardasha";
    $pratyantardasha=$pagesController->kundaliPratyantardasha($request);
    $data["d1"]=$d1Chart;
    $data["d9"]=$d9Chart;
    $data["planets"]=$planets;
    $data["mahadasha"]=$mahadasha;
    $data["antradasha"]=$antradasha;
    $data["pratyantardasha"]=$pratyantardasha;
    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function kp(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $planetDetail=PlanetDetail::find($request->planet_id);
    $request->division="D1";
    $d1Chart=$this->chart($request, $planetDetail);
    $request->division="chalit";
    $chalitChart=$this->chart($request, $planetDetail);

    $jsonResponse=json_decode($planetDetail->response,true);
    $jsonResponse=$jsonResponse["response"];
    $rullingPlanets=[];
    for($i=0;$i<10;$i++){
      if(isset($jsonResponse["$i"])){
        $item=$jsonResponse["$i"];
        if($item["house"]==1){
          $rullingPlanets[]=[
            "full_name"=>$item["full_name"],
            "zodiac"=>$item["zodiac"],
            "zodiac_lord"=>$item["zodiac_lord"],
            "nakshatra"=>$item["nakshatra"],
            "nakshatra_lord"=>$item["nakshatra_lord"],
            "local_degree"=>$item["local_degree"],
            "local_degree"=>$item["local_degree"],
            "retro"=>isset($item["retro"])?"Direct":"Retro",
            "is_combust"=>isset($item["is_combust"]) && $item["is_combust"]?"Yes":"No",
            "basic_avastha"=>$item["basic_avastha"],
            "house"=>$item["house"],
            "lord_status"=>$item["lord_status"]
          ];
        }
      }
    }

    $panchang=$jsonResponse["panchang"];
    $dayLord=$panchang["day_lord"];

    $request->planet_detail_id=$planetDetail->id;
    $request->return_type="json";
    $pagesController=new PagesController();
    $planets=$pagesController->kundaliKpPlanet($request);

    $data["d1"]=$d1Chart;
    $data["chalit"]=$chalitChart;
    $data["rullingPlanets"]=$rullingPlanets;
    $data["dayLord"]=$dayLord;
    $data["planets"]=$planets;

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function ashtakvargaChart(Request $request, PlanetDetail $planetDetail){
    $apiKey=fromSetting('astro_api_key');
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
      $url="https://api.vedicastroapi.com/v3-json/horoscope/ashtakvarga-chart-image?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang&planet=$planet&style=$style";
      $response=Http::get($url);
      $apiLog=new ApiLog();
      $apiLog->table_name="akvarga_charts";
      $apiLog->table_id=0;
      $apiLog->name="akvarga_charts";
      $apiLog->request=$url;
      $apiLog->response=$response;
      $apiLog->api_status=0;
      $apiLog->save();
      if(!str_contains($response, 'status')){
        $akvargaChart=new AkvargaChart();
        $akvargaChart->date=date("Y-m-d",$dateSeconds);
        $akvargaChart->time=$time;
        $akvargaChart->lat=$lat;
        $akvargaChart->lon=$lon;
        $akvargaChart->tz=$tz;
        $akvargaChart->lang=$lang;
        $akvargaChart->planet=$planet;
        $akvargaChart->style="north";
        $akvargaChart->response=$response;
        $akvargaChart->save();
        $apiLog->table_id=$akvargaChart->id;
        $apiLog->api_status=1;
        $apiLog->save();
      }
    }else{
      $response=$akvargaChart->response;
    }
    if(!str_contains($response, 'status')){
      return $response;
    }else{
      return "";
    }
  }

  public function ashtakvarga(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $planetDetail=PlanetDetail::find($request->planet_id);
    $request->planet="Sun";
    $sunChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Moon";
    $moonChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Mercury";
    $mercuryChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Venus";
    $venusChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Mars";
    $marsChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Jupiter";
    $jupiterChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Saturn";
    $saturnChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Rahu";
    $rahuChart=$this->ashtakvargaChart($request, $planetDetail);
    $request->planet="Ketu";
    $ketuChart=$this->ashtakvargaChart($request, $planetDetail);

    $data["sun"]=$sunChart;
    $data["moon"]=$moonChart;
    $data["mercury"]=$mercuryChart;
    $data["venus"]=$venusChart;
    $data["mars"]=$marsChart;
    $data["jupiter"]=$jupiterChart;
    $data["saturn"]=$saturnChart;
    $data["rahu"]=$rahuChart;
    $data["ketu"]=$ketuChart;

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function divisionCharts(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $planetDetail=PlanetDetail::find($request->planet_id);
    $request->division="D1";
    $d1Chart=$this->chart($request, $planetDetail);
    $request->division="D3";
    $d3Chart=$this->chart($request, $planetDetail);
    $request->division="D3-s";
    $d3sChart=$this->chart($request, $planetDetail);
    $request->division="D7";
    $d7Chart=$this->chart($request, $planetDetail);
    $request->division="D9";
    $d9Chart=$this->chart($request, $planetDetail);
    $request->division="D10";
    $d10Chart=$this->chart($request, $planetDetail);
    $request->division="D10-R";
    $d10RChart=$this->chart($request, $planetDetail);
    $request->division="D12";
    $d12Chart=$this->chart($request, $planetDetail);
    $request->division="D16";
    $d16Chart=$this->chart($request, $planetDetail);
    $request->division="D20";
    $d20Chart=$this->chart($request, $planetDetail);
    $request->division="D24";
    $d24Chart=$this->chart($request, $planetDetail);
    $request->division="D24-R";
    $d24rChart=$this->chart($request, $planetDetail);
    $request->division="D30";
    $d30Chart=$this->chart($request, $planetDetail);
    $request->division="D40";
    $d40Chart=$this->chart($request, $planetDetail);
    $request->division="D45";
    $d45Chart=$this->chart($request, $planetDetail);
    $request->division="D60";
    $d60Chart=$this->chart($request, $planetDetail);
    $request->division="chalit";
    $chalitChart=$this->chart($request, $planetDetail);
    $request->division="moon";
    $moonChart=$this->chart($request, $planetDetail);
    $request->division="sun";
    $sunChart=$this->chart($request, $planetDetail);

    $data["description"]="Ashtakvarga is used to assess the strength and patterns that are present in a birth chart. The Ashtakavarga or Ashtakavarga is a numerical quantification or score of each planet placed in the chart with reference to the other 7 planets and the Lagna. In Sarva Ashtaka Varga the total scores of all the BAVs are overlaid and then totalled. This makes the SAV of the chart. The total of all the scores should be 337.";
    $data["d1"]=$d1Chart;
    $data["d3"]=$d3Chart;
    $data["d3s"]=$d3sChart;
    $data["d7"]=$d7Chart;
    $data["d9"]=$d9Chart;
    $data["d10"]=$d10Chart;
    $data["d10R"]=$d10RChart;
    $data["d12"]=$d12Chart;
    $data["d16"]=$d16Chart;
    $data["d20"]=$d20Chart;
    $data["d24"]=$d24Chart;
    $data["d24r"]=$d24rChart;
    $data["d30"]=$d30Chart;
    $data["d40"]=$d40Chart;
    $data["d45"]=$d45Chart;
    $data["d60"]=$d60Chart;
    $data["chalit"]=$chalitChart;
    $data["moon"]=$moonChart;
    $data["sun"]=$sunChart;

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function dasha(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);

    $request->planet_detail_id=$request->planet_id;
    $request->return_type="json";
    $request->dasha="vimshottari";
    $pagesController=new PagesController();
    $vimshottariDasha=$pagesController->kundaliDashaVimshottari($request);
    $request->dasha="yogini";
    $yoginiDasha=$pagesController->kundaliDashaYogini($request);

    $data["vimshottari"]=$vimshottariDasha;
    $data["yogini"]=[
      "description"=>"The Dhanya Yogini period brings prosperity, growth in finances, and successful communication. It's often associated with gaining respect, recognition, and a more fulfilled domestic life.",
      "planets"=>$yoginiDasha
    ];

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function reportGeneral(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);
    $planetDetail=PlanetDetail::find($request->planet_id);
    $jsonResponse=json_decode($planetDetail->response,true);
    $jsonResponse=$jsonResponse["response"];

    $request->return_type="json";
    $request->zodiac=$jsonResponse["rasi"];
    $request->timeFrame="yearly";

    $pagesController=new PagesController();
    $reportGeneral=$pagesController->kundaliReportGeneral($request);
    $request->planet_detail_id=$request->planet_id;
    $reportPlanetary=$pagesController->kundaliReportPlanetary($request);
    $reportYoga=$pagesController->kundaliReportYoga($request);

    $data["reportGeneral"]=$reportGeneral;
    $data["reportPlanetary"]=$reportPlanetary;
    $data["reportYoga"]=$reportYoga;

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function reportDosha(Request $request){
    $request->validate([
      "planet_id"=>"required|integer"
    ]);

    $request->return_type="json";
    $pagesController=new PagesController();
    $request->planet_detail_id=$request->planet_id;
    $request->id="manglik";
    $reportManglik=$pagesController->kundaliReportManglik($request);
    $request->id="kalsarpa";
    $reportKalsarpa=$pagesController->kundaliReportKalsarpa($request);
    $request->id="sadesati";
    $reportSadesati=$pagesController->kundaliReportSadesati($request);

    $data["reportManglik"]=$reportManglik;
    $data["reportKalsarpa"]=$reportKalsarpa;
    $data["reportSadesati"]=$reportSadesati;

    return response()->json(["suceess"=>true,"data"=>$data]);
  }

  public function kundaliMatchingDetail(Request $request){
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

    $kundaliMatchingEnquiry = KundaliMatchingEnquiry::where("boy_name", $boy_name)
    ->where("boy_birth_date", $boy_birth_date)
    ->where("boy_birth_time", $boy_birth_time)
    ->where("boy_birth_place", $boy_birth_place)
    ->where("girl_name", $girl_name)
    ->where("girl_birth_date", $girl_birth_date)
    ->where("girl_birth_time", $girl_birth_time)
    ->where("girl_birth_place", $girl_birth_place)
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
      $kundaliMatchingEnquiry->girl_lon = $lH?$lH->longDeg:config('vrdcms.delhi_lon');
      $kundaliMatchingEnquiry->save();
    }
    $data=[];
    $jsonResponse=null;

    $apiKey=fromSetting('astro_api_key');
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
    $lang = $request->lang ?: "en";
    $kundaliMatching = KundaliMatching::where("kundali_matching_enquiries_id", $kundaliMatchingEnquiry->id)->where("lang", $lang)->first();

    if(!$kundaliMatching){
      $url = "https://api.vedicastroapi.com/v3-json/matching/ashtakoot?api_key=$apiKey&boy_dob=$boy_birth_date&boy_tob=$boy_birth_time&boy_tz=$tz&boy_lat=$boy_lat&boy_lon=$boy_lon&girl_dob=$girl_birth_date&girl_tob=$girl_birth_time&girl_tz=$tz&girl_lat=$girl_lat&girl_lon=$girl_lon&lang=$lang";

      $response=Http::get($url);
      $statusCode=$response->status();
      $jsonResponseMain=$response->json();

      $apiLog = new ApiLog();
      $apiLog->table_name = "kundali_matchings";
      $apiLog->table_id = 0;
      $apiLog->name = "kundali_matchings";
      $apiLog->request = $url;
      $apiLog->response = $response;
      $apiLog->api_status = 0;
      $apiLog->save();

      if($jsonResponseMain["status"] === 200){
        $kundaliMatching = new KundaliMatching();
        $kundaliMatching->kundali_matching_enquiries_id = $kundaliMatchingEnquiry->id;
        $kundaliMatching->lang = $lang;
        $kundaliMatching->response = $response;
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

    $boy_data["name"] = $kundaliMatchingEnquiry->boy_name;
    $boy_data["datetime"] = date("d.m/Y h:i a", strtotime($kundaliMatchingEnquiry->boy_birth_date." ".$kundaliMatchingEnquiry->boy_birth_time));
    $boy_data["birth_place"] = $kundaliMatchingEnquiry->boy_birth_place;
    $boy_data["latitude"] = $kundaliMatchingEnquiry->boy_lat;
    $boy_data["longitude"] = $kundaliMatchingEnquiry->boy_lon;
    $boy_data["timezone"] = "GMT+5.5";

    $girl_data["name"] = $kundaliMatchingEnquiry->girl_name;
    $girl_data["datetime"] = date("d.m/Y h:i a", strtotime($kundaliMatchingEnquiry->girl_birth_date." ".$kundaliMatchingEnquiry->girl_birth_time));
    $girl_data["birth_place"] = $kundaliMatchingEnquiry->girl_birth_place;
    $girl_data["latitude"] = $kundaliMatchingEnquiry->girl_lat;
    $girl_data["longitude"] = $kundaliMatchingEnquiry->girl_lon;
    $girl_data["timezone"] = "GMT+5.5";

    $tara_data["guna"] = "";
    $tara_data["boy"] = "";
    $tara_data["girl"] = "";
    $tara_data["maximum_obtained"] = "";
    $tara_data["obtained_points"] = "";
    $tara_data["description"] = "";

    if(isset($jsonResponse["tara"])){
      $tara = $jsonResponse["tara"];
      $tara_data["guna"] = isset($tara["tara"]) ? $tara["tara"] : "";
      $tara_data["boy"] = isset($tara["boy_tara"]) ? $tara["boy_tara"] : "";
      $tara_data["girl"] = isset($tara["girl_tara"]) ? $tara["girl_tara"] : "";
      $tara_data["maximum_obtained"] = isset($tara["full_score"]) ? $tara["full_score"] : "";
      $tara_data["obtained_points"] = isset($tara["tara"]) ? $tara["tara"] : "";
      $tara_data["description"] = isset($tara["description"]) ? $tara["description"] : "";
    }

    $gana_data["guna"] = "";
    $gana_data["boy"] = "";
    $gana_data["girl"] = "";
    $gana_data["maximum_obtained"] = "";
    $gana_data["obtained_points"] = "";
    $gana_data["description"] = "";

    if(isset($jsonResponse["gana"])){
      $gana = $jsonResponse["gana"];
      $gana_data["guna"] = isset($gana["name"]) ? $gana["name"] : "";
      $gana_data["boy"] = isset($gana["boy_gana"]) ? $gana["boy_gana"] : "";
      $gana_data["girl"] = isset($gana["girl_gana"]) ? $gana["girl_gana"] : "";
      $gana_data["maximum_obtained"] = isset($gana["full_score"]) ? $gana["full_score"] : "";
      $gana_data["obtained_points"] = isset($gana["gana"]) ? $gana["gana"] : "";
      $gana_data["description"] = isset($gana["description"]) ? $gana["description"] : "";
    }

    $yoni_data["guna"] = "";
    $yoni_data["boy"] = "";
    $yoni_data["girl"] = "";
    $yoni_data["maximum_obtained"] = "";
    $yoni_data["obtained_points"] = "";
    $yoni_data["description"] = "";

    if(isset($jsonResponse["yoni"])){
      $yoni = $jsonResponse["yoni"];
      $yoni_data["guna"] = isset($yoni["name"]) ? $yoni["name"] : "";
      $yoni_data["boy"] = isset($yoni["boy_yoni"]) ? $yoni["boy_yoni"] : "";
      $yoni_data["girl"] = isset($yoni["girl_yoni"]) ? $yoni["girl_yoni"] : "";
      $yoni_data["maximum_obtained"] = isset($yoni["full_score"]) ? $yoni["full_score"] : "";
      $yoni_data["obtained_points"] = isset($yoni["yoni"]) ? $yoni["yoni"] : "";
      $yoni_data["description"] = isset($yoni["description"]) ? $yoni["description"] : "";
    }

    $bhakoot_data["guna"] = "";
    $bhakoot_data["boy"] = "";
    $bhakoot_data["girl"] = "";
    $bhakoot_data["maximum_obtained"] = "";
    $bhakoot_data["obtained_points"] = "";
    $bhakoot_data["description"] = "";

    if(isset($jsonResponse["bhakoot"])){
      $bhakoot = $jsonResponse["bhakoot"];
      $bhakoot_data["guna"] = isset($bhakoot["name"]) ? $bhakoot["name"] : "";
      $bhakoot_data["boy"] = isset($bhakoot["boy_rasi_name"]) ? $bhakoot["boy_rasi_name"] : "";
      $bhakoot_data["girl"] = isset($bhakoot["girl_rasi_name"]) ? $bhakoot["girl_rasi_name"] : "";
      $bhakoot_data["maximum_obtained"] = isset($bhakoot["full_score"]) ? $bhakoot["full_score"] : "";
      $bhakoot_data["obtained_points"] = isset($bhakoot["bhakoot"]) ? $bhakoot["bhakoot"] : "";
      $bhakoot_data["description"] = isset($bhakoot["description"]) ? $bhakoot["description"] : "";
    }

    $grahamaitri_data["guna"] = "";
    $grahamaitri_data["boy"] = "";
    $grahamaitri_data["girl"] = "";
    $grahamaitri_data["maximum_obtained"] = "";
    $grahamaitri_data["obtained_points"] = "";
    $grahamaitri_data["description"] = "";

    if(isset($jsonResponse["grahamaitri"])){
      $grahamaitri = $jsonResponse["grahamaitri"];
      $grahamaitri_data["guna"] = isset($grahamaitri["name"]) ? $grahamaitri["name"] : "";
      $grahamaitri_data["boy"] = isset($grahamaitri["boy_lord"]) ? $grahamaitri["boy_lord"] : "";
      $grahamaitri_data["girl"] = isset($grahamaitri["girl_lord"]) ? $grahamaitri["girl_lord"] : "";
      $grahamaitri_data["maximum_obtained"] = isset($grahamaitri["full_score"]) ? $grahamaitri["full_score"] : "";
      $grahamaitri_data["obtained_points"] = isset($grahamaitri["grahamaitri"]) ? $grahamaitri["grahamaitri"] : "";
      $grahamaitri_data["description"] = isset($grahamaitri["description"]) ? $grahamaitri["description"] : "";
    }

    $vasya_data["guna"] = "";
    $vasya_data["boy"] = "";
    $vasya_data["girl"] = "";
    $vasya_data["maximum_obtained"] = "";
    $vasya_data["obtained_points"] = "";
    $vasya_data["description"] = "";

    if(isset($jsonResponse["vasya"])){
      $vasya = $jsonResponse["vasya"];
      $vasya_data["guna"] = isset($vasya["name"]) ? $vasya["name"] : "";
      $vasya_data["boy"] = isset($vasya["boy_vasya"]) ? $vasya["boy_vasya"] : "";
      $vasya_data["girl"] = isset($vasya["girl_vasya"]) ? $vasya["girl_vasya"] : "";
      $vasya_data["maximum_obtained"] = isset($vasya["full_score"]) ? $vasya["full_score"] : "";
      $vasya_data["obtained_points"] = isset($vasya["vasya"]) ? $vasya["vasya"] : "";
      $vasya_data["description"] = isset($vasya["description"]) ? $vasya["description"] : "";
    }

    $nadi_data["guna"] = "";
    $nadi_data["boy"] = "";
    $nadi_data["girl"] = "";
    $nadi_data["maximum_obtained"] = "";
    $nadi_data["obtained_points"] = "";
    $nadi_data["description"] = "";

    if(isset($jsonResponse["nadi"])){
      $nadi = $jsonResponse["nadi"];
      $nadi_data["guna"] = isset($nadi["name"]) ? $nadi["name"] : "";
      $nadi_data["boy"] = isset($nadi["boy_nadi"]) ? $nadi["boy_nadi"] : "";
      $nadi_data["girl"] = isset($nadi["girl_nadi"]) ? $nadi["girl_nadi"] : "";
      $nadi_data["maximum_obtained"] = isset($nadi["full_score"]) ? $nadi["full_score"] : "";
      $nadi_data["obtained_points"] = isset($nadi["nadi"]) ? $nadi["nadi"] : "";
      $nadi_data["description"] = isset($nadi["description"]) ? $nadi["description"] : "";
    }

    $varna_data["guna"] = "";
    $varna_data["boy"] = "";
    $varna_data["girl"] = "";
    $varna_data["maximum_obtained"] = "";
    $varna_data["obtained_points"] = "";
    $varna_data["description"] = "";

    if(isset($jsonResponse["varna"])){
      $varna = $jsonResponse["varna"];
      $varna_data["guna"] = isset($varna["name"]) ? $varna["name"] : "";
      $varna_data["boy"] = isset($varna["boy_varna"]) ? $varna["boy_varna"] : "";
      $varna_data["girl"] = isset($varna["girl_varna"]) ? $varna["girl_varna"] : "";
      $varna_data["maximum_obtained"] = isset($varna["full_score"]) ? $varna["full_score"] : "";
      $varna_data["obtained_points"] = isset($varna["varna"]) ? $varna["varna"] : "";
      $varna_data["description"] = isset($varna["description"]) ? $varna["description"] : "";
    }

    $bot_response_data["bot_response"] = isset($jsonResponse["bot_response"]) ? $jsonResponse["bot_response"] : "";
    $score_data["score"] = [
      "obtained" => "0",
      "total" => "36"
    ];
    if(isset($jsonResponse["score"])){
      $score = $jsonResponse["score"];
      $score_data["score"]["obtained"] = $score;
    }

    $data["boy"] = $boy_data;
    $data["girl"] = $girl_data;
    $data["gana"] = $gana_data;
    $data["yoni"] = $yoni_data;
    $data["bhakoot"] = $bhakoot_data;
    $data["grahamaitri"] = $grahamaitri_data;
    $data["vasya"] = $vasya_data;
    $data["nadi"] = $nadi_data;
    $data["varna"] = $varna_data;
    $data["bot_response"] = $bot_response_data;
    $data["score"] = $score_data;
    return response()->json(["suceess"=>true,"data"=>$data]);
  }


}
