<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\HoroscopeWeekly;
use App\Model\ZodiacSign;

class HoroscopeWeeklyController extends Controller
{

    private $module = 'horoscope-weekly';
    private $moduleName = 'Horoscope weekly';
    private $moduleNameSingle = 'Horoscope weekly';

    public function index(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $zodiacSigns = ZodiacSign::pluck('name', 'id')->toArray();
         $zodiacSigns[0] = 'select';
         ksort($zodiacSigns);
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName', 'zodiacSigns'));
     }

     public function all(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');

          $results = HoroscopeWeekly::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')
            ->where('week_number', date('w'));

          if($zodiac_sign_id = $request->zodiac_sign_id) {
              $results->where('zodiac_sign_id', $zodiac_sign_id);
          }
          if($from_date = $request->from_date) {
            $results->where('from_date', $from_date);
          }
            if($to_date = $request->to_date) {
                $results->where('to_date', $to_date);
            }

          if($updated_at = $request->updated_at) {
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(18);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
        $item = HoroscopeWeekly::findOrNew($id);
        return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
        $item = HoroscopeWeekly::findOrNew($id);
        if($item->zodiac_sign_id){
            $validate = [
                'main_content' => 'required',
                'love_content' => 'required',
                'career_content' => 'required',
                'finance_content' => 'required',
                'health_content' => 'required',
            ];
        }else {
            $validate = [
                'main_content' => 'required',
            ];
        }
        $this->validate($request, );
        $id = $request->id;
        $item->main_content 		= $request->main_content;
        if($item->zodiac_sign_id){
            $item->love_content 		= $request->love_content;
            $item->career_content 		= $request->career_content;
            $item->finance_content 		= $request->finance_content;
            $item->health_content 		= $request->health_content;
        }
        $item->save();
        $redirectTo = $item->zodiac_sign_id==0 ? route('admin.'.$this->module.'.edit', $item->id) : route('admin.'.$this->module.'.index');
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
 }
