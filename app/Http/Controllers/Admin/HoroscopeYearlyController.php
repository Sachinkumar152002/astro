<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\HoroscopeYearly;
use App\Model\ZodiacSign;

class HoroscopeYearlyController extends Controller
{

    private $module = 'horoscope-yearly';
    private $moduleName = 'Horoscope yearly';
    private $moduleNameSingle = 'Horoscope yearly';

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

          $results = HoroscopeYearly::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')
            ->where('year', date('Y'));

          if($zodiac_sign_id = $request->zodiac_sign_id) {
              $results->where('zodiac_sign_id', $zodiac_sign_id);
          }
          if($year = $request->year) {
            $results->where('year', $year);
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
        $item = HoroscopeYearly::findOrNew($id);
        return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
        $item = HoroscopeYearly::findOrNew($id);
        if($item->zodiac_sign_id){
            $validate = [
                'main_content' => 'required',
                // 'love_content' => 'required',
                // 'career_content' => 'required',
                // 'finance_content' => 'required',
                // 'family_content' => 'required',
            ];
        }else {
            $validate = [
                'main_content' => 'required',
            ];
        }

        $this->validate($request, $validate);

        $id = $request->id;
        $item->main_content 		= $request->main_content;
        if($item->zodiac_sign_id){
            $item->love_content 		= $request->love_content;
            $item->career_content 		= $request->career_content;
            $item->finance_content 		= $request->finance_content;
            $item->family_content 		= $request->family_content;
        }
        $item->save();
        $redirectTo = $item->zodiac_sign_id==0 ? route('admin.'.$this->module.'.edit', $item->id) : route('admin.'.$this->module.'.index');
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }


 }
