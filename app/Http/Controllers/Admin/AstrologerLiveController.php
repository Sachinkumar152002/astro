<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\{AstrologerLive,Astrologer};
use DB;

class AstrologerLiveController extends Controller
{
  private $module = 'astrologerlive';
  private $moduleName = 'Astrologer Live';

  public function index(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $nav = $module;
    if(!adminCan($module, 'view')) return dd('No Permission');
    $breads = [
      ['name' => $moduleName]
    ];
    return view("admin.$module.list", compact('nav','breads', 'module', 'moduleName'));
  }

   public function all(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = $module;
     $results = AstrologerLive::orderBy("end_at", "DESC");
     if($astrologer_name = $request->astrologer_name){
       $astrologerIds=Astrologer::where("display_name","like","%$astrologer_name%")->pluck("id")->toArray();
       $results->whereIn('astrologer_id',$astrologerIds);
     }
     if($status = $request->status){
       $results->where('status',$status);
     }
     if($start_at_from = $request->start_at_from) {
       $results->where("start_at",">=",$start_at_from);
     }
     if($start_at_to = $request->start_at_to) {
       $results->where("start_at","<=",$start_at_to);
     }
     if($end_at_from = $request->end_at_from) {
       $results->where("end_at",">=",$end_at_from);
     }
     if($end_at_to = $request->end_at_to) {
       $results->where("end_at","<=",$end_at_to);
     }
     $results = $results->paginate(10);
     $data = [
       'rows' => view("admin.$module.listrows", ['results' => $results, "module" => $module])->render(),
       'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
     ];
     return response()->json($data, 200);
   }

   public function form(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = $module."add";
     $id = $request->id?:0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> ($id ? 'Edit': 'Add')]
     ];
     $item = AstrologerLive::find($id);
     $astrologers = Astrologer::where("status",1)->where("is_verified",1)->where("can_go_live",1)->pluck("display_name","id")->toArray();
     return view("admin.$module.form", compact('nav', 'breads', 'module', "moduleName", 'item', 'astrologers'));
   }

   public function submit(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $id = $request->id ?: 0;
      if(!$id && !adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($module, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $request->validate([
        "astrologer_id"=>"required|integer|gt:0",
        "start_date"=>"required|date_format:Y-m-d",
        "start_time"=>"required",
        "end_date"=>"required|date_format:Y-m-d",
        "end_time"=>"required",
      ]);
      $astrologer_id=$request->astrologer_id;
      $start_date=$request->start_date;
      $start_time=$request->start_time;
      $start_at=date("Y-m-d H:i:00",strtotime("{$start_date} {$start_time}"));
      $end_date=$request->end_date;
      $end_time=$request->end_time;
      $end_at=date("Y-m-d H:i:00",strtotime("{$end_date} {$end_time}"));
      $astrologerLive=AstrologerLive::where("id","!=",$id)
      ->where("astrologer_id",$astrologer_id)
      ->where("status", "!=", "cancelled")
      ->where(function($query) use($start_at,$end_at){
        $query->whereBetween("start_at",[$start_at,$end_at])
        ->orWhereBetween("end_at",[$start_at,$end_at])
        ->orWhere(function($querySecond) use($start_at,$end_at){
          $querySecond->where("start_at","<",$start_at)
          ->where("end_at",">",$start_at);
        })
        ->orWhere(function($querySecond) use($start_at,$end_at){
          $querySecond->where("start_at","<",$end_at)
          ->where("end_at",">",$end_at);
        });
      })->first();
      if($astrologerLive){
        return response()->json(["errors"=>["astrologer_id"=>["This Slot is already booked."]]],422);
      }
      $astrologer=Astrologer::find($astrologer_id);

      $astrologerLive=AstrologerLive::find($id);
      if($astrologerLive==null){
        $astrologerLive=new AstrologerLive();
      }
      $astrologerLive->astrologer_id=$astrologer->id;
      $astrologerLive->astrologer_name=$astrologer->display_name;
      $astrologerLive->logo=asset('assets/img/logo.png');
      $astrologerLive->app_id="";
      $astrologerLive->app_secret="";
      $astrologerLive->app_sign="";
      $astrologerLive->room_id=Str::random(10);
      $astrologerLive->request=json_encode([]);
      $astrologerLive->status=$request->status;
      $astrologerLive->start_at=$start_at;
      $astrologerLive->end_at=$end_at;
      $astrologerLive->save();
      return response()->json(['message'=>'Scheduled Successfully', 'redirectto'=>route("admin.$module.edit", [$astrologerLive->id])], 200);
   }

   public function viewData(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = $module;
     if(!adminCan($module, 'updated')) return dd('No Permission');
     $id = $request->id?:0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> 'View']
     ];
     $item = AstrologerLive::find($id);
     return view("admin.$module.view", compact('nav','breads', 'module', 'moduleName',"item"));
   }

  public function delete(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $id = $request->id;
    $item = AstrologerLive::find($id);
     if($item){
       if(!adminCan($module, 'delete')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       $item->delete();
       return response()->json(['message'=>'Deleted successfully!'], 200);
     }
     return response()->json(['message'=>'Error in deleting data'], 401);
   }
}
