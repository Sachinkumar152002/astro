<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\Model\Role;
use App\Model\Module;

class PermissionController extends Controller
{

    private $module = 'permission';
    private $moduleName = 'Permission';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan('permission','view')) return dd('No Permission');
        $nav = 'permission';
        $breads = [
            ['name'=>'Permission']
        ];
        $moduleName = $this->moduleName;
        $roles	= Role::where('id', '!=', 1)->pluck('name', 'id')->toArray();
        $roles[0]='Select';
        ksort($roles);
        return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName', 'roles'));
    }

    public function all(Request $request){
      $results = Permission::where('role_id', '!=', 1)->groupBy('parent_id')->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
      if($role_id = $request->role_id){
        $results->where('role_id', $role_id);
      }
      if($updated_at = $request->updated_at){
        $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
      }
      $results = $results->paginate(16);
      $data = [
        'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
      ];
      return response()->json($data, 200);
    }

    public function form(Request $request){
         $nav = $this->module;
       $moduleName = $this->moduleName;
       $id = $request->id;
       $breads = [
         ['name'=>'Permission', 'url'=> route('admin.'.$this->module.'.index')],
         ['name'=>($id ? 'Edit': 'Add')]
       ];
       $role_id = 0;
       $all = [];
       $permission = Permission::find($id);
       if($permission){
         $role_id=$permission->role_id;
         $all=Permission::where('parent_id', $id)->orderBy('module_id', 'asc')->pluck('module_id')->toArray();
       }
       $roles	= Role::where('id', '!=', 1)->pluck('name', 'id')->toArray();
       $roles[0]='Select';
       ksort($roles);
       $modules	= Module::whereNotIn('id', [0])->orderBy('id', 'asc')->get();
       return view('admin.'.$this->module.'.form', compact('nav', 'moduleName', 'breads', 'permission', 'role_id', 'roles', 'modules', 'all'));
    }

    public function submit(Request $request){
      // dd($request->permissions);
      $id = $request->id;
      if(!$id && !adminCan($this->module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($this->module, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $this->validate($request, [
        'role_id' => 'required|gt:0',
        'permissions' => 'required|array',
      ], [
        'role_id.gt'=>'Role field is required'
      ]);
      if($id){
        $exist=Permission::where('parent_id', '!=', $id)->where('role_id', $request->role_id)->first();
        if($exist){
          return response()->json(['errors'=>['role_id'=>['Role already taken']]], 402);
        }
        // Permission::where('id', $id)->delete();
        Permission::where('parent_id', $id)->delete();
      }else{
        $exist=Permission::where('role_id', $request->role_id)->first();
        if($exist){
          return response()->json(['errors'=>['role_id'=>['Role already taken']]], 402);
        }
      }

      $parent_id = 0;
      foreach ($request->permissions["module_id"] as $key => $value){
        $permission = new Permission();
        $permission->parent_id = $parent_id;
        $permission->role_id = $request->role_id;
        $permission->module_id = $value;
        // $permission->can_view = isset($request->permissions["can_view"][$value])?1:0;
        $permission->can_view = 1;
        $permission->can_add = isset($request->permissions["can_add"][$value])?1:0;
        $permission->can_update = isset($request->permissions["can_update"][$value])?1:0;
        $permission->can_delete = isset($request->permissions["can_delete"][$value])?1:0;
        $permission->save();
        if($parent_id==0){
          $parent_id=$permission->id;
          $permission->parent_id = $parent_id;
          $permission->save();
        }
      }
      return response()->json(['message'=>'Saved succcessfully', 'redirectto'=>route('admin.'.$this->module.'.edit', [$parent_id])], 200);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan('permission','delete')) return dd('No Permission');
      if($request->id) {
        $permission = Permission::where('parent_id', $request->id);
        $permission->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }

}
