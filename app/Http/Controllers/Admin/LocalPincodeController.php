<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LocalPincode;

class LocalPincodeController extends Controller
{
    private $module = 'localpincode';
    private $moduleName = 'Local Pincode';
    private $moduleNameSingle = 'localpincode';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan($this->module,'view')) return dd('No Permission');
      $nav = $this->module;
      $modulename = $this->moduleName;
      $modulenamesingle = $this->moduleName;
      $breads = [
          ['name'=>$this->moduleName]
      ];
      return view('admin.'.$this->module.'.list', compact('nav','breads', 'modulename', 'modulenamesingle'));
    }

    /**/
    public function submit(Request $request)
    {
      $id = $request->item_id;
      if(!$id && !adminCan($this->module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($this->module, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }

      $this->validate($request, [
        'city' => 'required',
        'post_office' => 'required',
        'pincode' => 'required|numeric',
      ]);

      $item = LocalPincode::findOrNew($id);
      $item->city = $request->city;
      $item->post_office = $request->post_office;
      $item->pincode = $request->pincode?:0;

      if($item->save()){
          return response()->json(['message'=>'Succcessfully saved!'], 200);
      }
      return response()->json(['message'=>'Error in saving'], 401);
    }

    /**/
    public function all(Request $request)
    {
       $items = LocalPincode::orderBy($request->sort_by,$request->sort_dir);

       if($city = $request->city){
         $items->where('city', $city);
       }
       if($post_office = $request->post_office){
         $items->where('post_office', $post_office);
       }
       if($pincode = $request->pincode){
         $items->where('pincode', $pincode);
       }

      $results = $items->paginate(100);
      $nav = $this->module;
      $data = [
          'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

      ];
      return response()->json($data, 200);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan($this->module,'delete')) return dd('No Permission');
      if($item_id = $request->item_id) {
        $item = LocalPincode::find($item_id);
        $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }
    /*
     public function form(Request $request)
     {
       if(!adminCan('localpincode','view')) return dd('No Permission');
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName],
        ];
       $pincodes = LocalPincode::get();
       return view('admin.'.$nav.'.form', compact('nav', 'breads', 'pincodes', 'moduleName'));
     }

     public function submit(Request $request) {
         \DB::select('truncate table local_pincodes');
         if(is_array($request->pincode) && count($request->pincode)){
           foreach($request->pincode as $index => $value){
             if($value && isset($request->city[$index]) && isset($request->post_office[$index])){
               $pincode = new LocalPincode();
               $pincode->city	= $request->city[$index];
               $pincode->post_office	= $request->post_office[$index];
               $pincode->pincode	= $value;
               $pincode->save();
             }
           }
         }
        $redirectTo =route('admin.'.$this->module.'.add');
        return response()->json(['message'=>'Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
     */
}
