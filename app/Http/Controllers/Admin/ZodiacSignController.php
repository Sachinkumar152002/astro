<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Media;
use App\Model\ZodiacSign;
use App\Model\SubPage;

class ZodiacSignController extends Controller
{

    private $module = 'zodiacsigns';
    private $moduleName = 'Zodiac Signs';
    private $moduleNameSingle = 'Zodiac Sign';

    public function index(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.zodiacsigns.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');

          $results = ZodiacSign::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
          if(is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', "$status");
    }
          if($updated_at = $request->updated_at) {
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(12);

           $data = [
               'rows' => view('admin.zodiacsigns.listrows',['results'=>$results, 'subPagesName' => zodiacSignsMorePagesTypes()])->render(),
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
             ['name'=>$this->moduleName, 'url'=> route('admin.zodiacsigns.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = ZodiacSign::findOrNew($id);
         $medias  = Media::where('type','zodiacsign')->get();
         $allSigns = ZodiacSign::pluck('name', 'id')->toArray();

         return view('admin.zodiacsigns.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'allSigns'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');


         $this->validate($request, [
             'name' => 'required|max:255|unique:zodiac_signs,name,'.$id.',id',
             // 'description' => 'required',
             'sign_date_sd' => 'required',
             'sign_date_sm' => 'required',
             'sign_date_ed' => 'required',
             'sign_date_em' => 'required',
         ]);

         $id = $request->id;

        $item = ZodiacSign::findOrNew($id);
        $item->image_id 		= $request->image_id?:0;
        $item->banner_image_id 		= $request->banner_image_id?:0;
        $item->name          = $request->name;
        $item->description          = $request->description;
        $item->sign_date_sd          = $request->sign_date_sd;
        $item->sign_date_sm          = $request->sign_date_sm;
        $item->sign_date_ed          = $request->sign_date_ed;
        $item->sign_date_em          = $request->sign_date_em;
        $item->meta_keywords          = $request->meta_keywords;
        $item->meta_title          = $request->meta_title;
        $item->meta_description          = $request->meta_description;
        $item->lucky_colors          = $request->lucky_colors;
        $item->lucky_gemstones          = $request->lucky_gemstones;
        $item->lucky_days          = $request->lucky_days ? json_encode($request->lucky_days) : '[]';
        $item->symbol          = $request->symbol;
        $item->lucky_number          = $request->lucky_number;
        $item->ruiling_planet          = $request->ruiling_planet ? json_encode($request->ruiling_planet) : '[]';
        $item->compatible_zodiac_signs          = $request->compatible_zodiac_signs ? json_encode($request->compatible_zodiac_signs) : '[]';
        $item->slug 	        = str_slug($request->name);
        $item->status          = $request->status;
        $item->save();
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>route('admin.zodiacsigns.index')], 200);
     }

    public function page(Request $request){

        $nav = $this->module;
        $item = SubPage::where([
            'module_key' => 'zodiac-signs',
            'parent_slug'=> $request->parent_slug,
            'slug' => $request->slug
        ])->first();
            $id = $item->id;
        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
        $breads = [
            ['name'=>$this->moduleName, 'url'=> route('admin.zodiacsigns.index')],
            ['name'=> $item->name]
        ];
        $medias  = Media::where('type','zodiacsign')->get();
        return view('admin.zodiacsigns.subpageform', compact('nav', 'breads', 'item', 'medias'));
    }

    public function pageSave(Request $request){
        $id = $request->id;
        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
        $this->validate($request, [
            'description' => 'required',
        ]);

        $page = SubPage::findOrNew($id);
        $page->name          = $request->name;
        $page->description   = $request->description;
        $page->meta_title    = $request->meta_title;
        $page->meta_keywords  = $request->meta_keywords;
        $page->meta_description          = $request->meta_description;
        $page->banner_image_id 		= $request->banner_image_id?:0;
        $page->status 		= $request->status?:0;
        $page->save();
       return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>route('admin.zodiacsigns.index')], 200);
    }

    public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = SubPage::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

 }
