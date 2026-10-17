<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Form;

class FormFieldsController extends Controller
{
  public function index()
  {
    if(!adminCan('form-field','view')) return dd('No Permission');
    $nav = 'form';
    $breads = [
        ['name'=>'form']
    ];
    return view('admin.form.list', compact('nav','breads'));
  }

  public function submit(Request $request)
  {
    $id = $request->item_id;
    if(!$id && !adminCan('form-field', 'add')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
    if($id && !adminCan('form-field', 'update')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
      $this->validate($request, [
          'form_label' => 'required|unique:forms,form_label,'.$id.',id',
          'form_type' => 'required',
      ]);

      $item = Form::findOrNew($id);
      // $item = new Form();
      $item->form_label = $request->form_label;
      $item->form_type = $request->form_type;
      $item->isrequired = $request->isrequired ?? 0;
      $item->filetype = $request->filetype;
      $item->save();
      return response()->json(['message'=>'Succcessfully saved!'], 200);
  }

  public function all(Request $request)
  {
     $items = Form::whereNotIn("form_type", ["select", "search"])->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

        if($form_label = $request->form_label){
          $items->where('form_label','like', "%$form_label%");
        }
         if($form_type = $request->form_type){
          $items->where('form_type','like', "%$form_type%");
        }
        $results = $items->paginate(10);
        $data = [
            'rows' => view('admin.form.listrows',['results'=>$results])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

        ];
        return response()->json($data, 200);
  }

  public function delete(Request $request)
  {
    if(!adminCan('form-field','delete')) return dd('No Permission');
    if($item_id = $request->item_id) {
      $item = Form::find($item_id);
      $item->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
    }
    return response()->json(['message'=>'Error in delete'], 401);
  }
}
