<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Panchang;


class PanchangController extends Controller
{

    private $module = 'panchange';
    private $moduleName = 'Thought of the Day';
    private $moduleNameSingle = 'Punchang';

    public function index(Request $request)
     {
       $nav = $this->module;
       if(!adminCan($nav, 'view')) return dd('No Permission');
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request){
       $results = Panchang::orderBy('updated_at', 'desc');

       if($date_from = $request->date_from){
         $results->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') >= ? " , $date_from);
       }
       if($date_to = $request->date_to){
         $results->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') <= ? " , $date_to);
       }
       if($english = $request->english){
         $results->where('english', 'like', "%$english%");
       }
       if($hindi = $request->hindi){
         $results->where('hindi', 'like', "%$hindi%");
       }
       if(isset($request->status) && is_numeric($request->status)) {
         $results->where('status', $request->status);
       }
       if($updated_at_from = $request->updated_at_from){
         $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to){
         $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $results = $results->paginate(16);

       $data = [
         'rows' => view('admin.'.$this->module.'.listrows', ['results' => $results, 'module' => $this->module])->render(),
         'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
       ];
       return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Panchang::find($id);
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request){
       $nav = $this->module;
       $id = $request->id;

       if(!$id && !adminCan($nav, 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id && !adminCan($nav, 'update')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }

       $this->validate($request, [
         'date' => 'required|unique:panchangs,date,'.$id.',id',
       ]);

       $panchang = Panchang::findOrNew($id);
       $panchang->date = date('Y-m-d', strtotime($request->date));
       $panchang->english = $request->english;
       $panchang->hindi = $request->hindi;
       $panchang->status = $request->status ?: 0;
       $panchang->save();
       $redirectTo = route('admin.'.$this->module.'.edit', [$panchang->id]);
       return response()->json(['message'=> $id ? 'Edit Successfully' : 'Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request){
       $nav = $this->module;
       if(!adminCan($nav, 'delete')) return dd('No Permission');
       if($id = $request->id){
         $panchang = Panchang::find($id);
         $panchang->delete();
         return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

    // public function duplicate(Request $request){
    //   $nav = $this->module;
    //   if(!adminCan($nav, 'add')) return dd('No Permission');
    //   if($id = $request->id){
    //     $panchang = Panchang::find($id);
    //     $newPanchang = $panchang->replicate();
    //     $newPanchang->date = date('Y-m-d', strtotime('now+'.$newPanchang->id.'days'));
    //     $newPanchang->status = 0;
    //     $newPanchang->save();
    //     return redirect()->back()->with('success', 'Copied successfully!');
    //   }
    //   return redirect()->back()->with('error', 'Error in coping');
    // }
}
