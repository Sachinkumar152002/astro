<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Model\Permission;
use App\Admin;

class RoleController extends Controller
{
    private $module = 'role';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan('role','view')) return dd('No Permission');
      $nav = 'role';
       $breads = [
           ['name'=>'Role']
       ];
      return view('admin.role.list', compact('nav','breads'));
    }

    /**/
    public function submit(Request $request)
    {
        $id = $request->role_id;
        if(!$id && !adminCan('role', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('role', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $this->validate($request, [
            'name' => 'required|max:150|unique:roles,name'.($id?','.$id.',id':''),
        ]);

        if($id) {
            $role = Role::find($id);
        } else {
            $role = new Role();
        }
        $role->name = $request->name;
        $role->save();
        if($role->save()){
            return response()->json(['message'=>'Succcessfully saved!'], 200);
        }
        return response()->json(['message'=>'Error in saving'], 401);

    }

    /**/
    public function all(Request $request)
    {
        $role = Role::where('id', '!=', 1)->orderBy($request->sort_by,$request->sort_dir);
        if($name = $request->name){
          $role->where('name','like', "%$name%");
        }
        $results = $role->paginate(10);
        $data = [
            'rows' => view('admin.role.listrows',['results'=>$results])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
        ];
        return response()->json($data, 200);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan('role','delete')) return dd('No Permission');
      if($role_id = $request->id) {
        $role = Role::find($role_id);
        if(Admin::where('role_id', $role->id)->first()){
          return response()->json(['message'=>"Can't delete. Admin of this role is exists."], 401);
        }
        $role->delete();
        Permission::where('role_id', $role_id)->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }

}
