<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\HoroscopeMonthly;
use App\Model\ZodiacSign;

class HoroscopeMonthlyController extends Controller
{

    private $module = 'horoscope-monthly';
    private $moduleName = 'Horoscope monthly';
    private $moduleNameSingle = 'Horoscope monthly';

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

          $results = HoroscopeMonthly::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')
            ->where('month_number', date('m'));

          if($zodiac_sign_id = $request->zodiac_sign_id) {
              $results->where('zodiac_sign_id', $zodiac_sign_id);
          }
          if($month_number = $request->month_number) {
            $results->where('month_number', $month_number);
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
        $item = HoroscopeMonthly::findOrNew($id);
        return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
        $item = HoroscopeMonthly::findOrNew($id);
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
