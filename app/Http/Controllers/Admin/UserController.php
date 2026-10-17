<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Model\Role;
use Validator;

class UserController extends Controller
{
    private $module = 'user';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan('admin','view')) return dd('No Permission');
      $nav = 'user';
      $breads = [
          ['name'=>'Admin']
      ];
      return view('admin.user.list', compact('nav', 'breads'));
    }

    public function all(Request $request)
    {
        $users = Admin::where('role_id', '!=',1)->orderBy($request->sort_by,$request->sort_dir);
        if(isset($request->status) && is_numeric($request->status)) {$status = $request->status;
            $users->where('status', $status);
        }
        if($name = $request->name) {
            $users->where('name', 'like', "%$name%");
        }
        if($email = $request->email) {
            $users->where('email', 'like', "%$email%");
        }
        if($role_id = $request->role_id) {
            $users->where('role_id', 'like', "%$role_id%");
        }
        if($created_at = $request->created_at) {
            $users->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
        }
        if($updated_at = $request->updated_at) {
            $users->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        $users = $users->paginate(10);
         // $location = Location::orderBy('created_at','desc')->paginate(10);
        $data = [
            'rows' => view('admin.user.listrows',['results'=>$users])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$users])->render(),
        ];
        return response()->json($data, 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request)
    {
        $id = $request->user_id;
        $nav = 'user';
         $breads = [
             ['name'=>'Admin', 'url'=> route('admin.user.index')],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
    $user = false;
    if($id) {
    $user = Admin::find($id);
    }
        $roles = Role::where('id','!=', 1)->get()->pluck('name','id')->toArray();
        $roles[0] = 'Select';
        ksort($roles);
        return view('admin.user.form', compact('user', 'nav', 'breads', 'roles') );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
      if(!adminCan('admin','delete')) return dd('No Permission');
    if($user = Admin::find($request->id)){
    $user->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
    }
      return response()->json(['message'=>'Error in delete'], 401);
    }

    public function submit(Request $request)
    {
    $id = $request->user_id;
    if(!$id && !adminCan('admin', 'add')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
    if($id && !adminCan('admin', 'update')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
    $validate = [
    'name' => 'required',
    'email' => 'required|email|unique:admins,email,'.$id.',id',
      // 'password' => 'required|min:8',
      'role_id' => 'required',
    ];
    if(($id && $request->password!='') || !$id) {
          $validate['password'] = 'required|min:8';
    }
    Validator::make($request->all(), $validate)->validate();
    if($id) {
    $user = Admin::find($id);
    }else {
    $user = new Admin();
    }
    if($password = $request->password) {
        $user->password = bcrypt($password);
    }
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role_id = $request->role_id;
    $user->status = $request->status;
    // $user->wallet = $request->wallet;
    $user->save();
    return response()->json(['message'=>'Saved Successfully', 'redirectto'=>route('admin.user.edit', [$user->id])], 200);
    }
}
