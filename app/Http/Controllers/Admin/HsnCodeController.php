<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\HsnCode;

class HsnCodeController extends Controller
{
  public function index(Request $request)
  {
    $module = 'hsn';
    $moduleKey = 'hsn';
    $moduleName = 'HSN';
    $nav = 'hsn';
    $breads = [
      ['name' => $moduleName]
    ];
    return view("admin.$module.list", compact('nav', 'breads', "moduleName", "moduleKey"));
  }

  public function all(Request $request){
    $hsnCodes = HsnCode::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
     if($hsn_code = $request->hsn_code){
         $hsnCodes->where('hsn_code', 'like', "%$hsn_code%");
     }
    if($name = $request->name){
      $hsnCodes->where('name', 'like', "%$name%");
    }
    if($gst_percentage = $request->gst_percentage){
      $hsnCodes->where('gst_percentage', 'like', "%$gst_percentage%");
    }
    if($updated_at_from = $request->updated_at_from) {
        $hsnCodes->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
    }
    if($updated_at_to = $request->updated_at_to) {
        $hsnCodes->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
    }
    $hsnCodes = $hsnCodes->paginate(10);

    $data = [
      'rows' => view('admin.hsn.listrows',['results'=>$hsnCodes])->render(),
      'pagination' => view('admin._partials.pagination',['results'=>$hsnCodes])->render(),
    ];
    return response()->json($data, 200);
  }

  public function form(Request $request){
    $module = 'hsn';
    $moduleKey = 'hsn';
    $moduleName = 'HSN';
    $nav = 'hsnadd';
    $id = $request->id;
    $breads = [
      ['name' => $moduleName, 'url' => route('admin.hsn.index')],
      ['name' => ($id ? 'Edit' : 'Add')]
    ];
    $data = $id ? HsnCode::find($id) : false;
    return view('admin.hsn.form', compact('nav', 'breads', "moduleKey", "moduleName", 'data'));
  }

  public function submit(Request $request) {
    $id = $request->id;
    $this->validate($request, [
        'name' => 'required',
    'hsn_code' => 'required|gt:0|min:4|max:8|unique:hsn_codes,hsn_code,'.$id.',id',
        'gst_percentage' => 'required|gt:0',
        'short_description' => 'required',
        'description' => 'required',
        ],[
    'name.required' => 'Hsn Code is required',
    'hsn_code.required' => 'Hsn Code is required',
    'gst_percentage.required' => 'Gst Percentage is required',
        'short_description.required' => 'Short Description is required',
    'description.required' => 'Description is required',
    ]);

    if($id) {
    $hsnCode = HsnCode::find($id);
    }else {
    $hsnCode = new HsnCode();
    }
    $hsnCode->name = $request->name;
    $hsnCode->hsn_code = $request->hsn_code;
    $hsnCode->gst_percentage = $request->gst_percentage;
    $hsnCode->short_description = $request->short_description;
    $hsnCode->description = $request->description;
    $hsnCode->status = 1;
    $hsnCode->save();
    return response()->json(['message' => 'Saved Successfully', 'redirectto' => route('admin.hsn.edit', [$hsnCode->id])], 200);
    }

  public function delete(Request $request){
    if($id = $request->id){
      $hsnCode = HsnCode::find($id);
      $hsnCode->delete();
      return response()->json(['message'=>'Deleted successfully!'], 200);
    }
    return response()->json(['message'=>'Error in deleting data'], 401);
  }

  public function duplicate(Request $request){
    if($id = $request->id){
      $hsnCode = HsnCode::find($id);
      $newHsnCode =  $hsnCode->replicate();
      $newHsnCode->name = $hsnCode->name.'-copied';
      $newHsnCode->hsn_code = $hsnCode->hsn_code.$hsnCode->id;
      $newHsnCode->status = 0;
      $newHsnCode->save();
      return response()->json(['message'=>'Copied Successfully!'], 200);
    }
    return response()->json(['message'=>'Error in coping data'], 401);
  }
}
