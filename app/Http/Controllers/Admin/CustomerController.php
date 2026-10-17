<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\{Media,Course,LmsCourse, UserAdditionalInformation, WalletTransition};
use App\{Product,CartItem,Order,OrderItem,UserCourse};
use DB;
use App\Model\{Language, ShippingAddress,AdminSideCartItem};
use Auth;

class CustomerController extends Controller
{
    private $module = 'customer';
    private $moduleName = 'Customers';
    private $moduleNameSingle = 'Customer';

    public function index(Request $request)
     {
        if(!adminCan('customer','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        $results = User::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
        if($uuid = $request->uuid) {
            $results->where('uuid', 'like', "%$uuid%");
        }
        if($name = $request->name) {
            $results->where('name', 'like', "%$name%");
        }
        if($email = $request->email) {
            $results->where('email', 'like', "%$email%");
        }
        if($phone = $request->phone) {
            $results->where('phone', 'like', "%$phone%");
        }
        if($cart_items = $request->cart_items){
          $cartItems = CartItem::where('type', $cart_items)->where('user_type', 'user')->pluck('user_id')->toArray();
          $results->whereIn('id', $cartItems);
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', $status);
        }
        if($trashed = $request->trashed){
            $results->onlyTrashed();
          }

        if($updated_at = $request->updated_at) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'trashed' => $request->trashed])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
            'total'=>$results->total()
        ];
        return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;

        // $nav = $this->module;
        $nav = "customeradd";
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
        $medias  = Media::where('type','users')->get();
        $item = User::with(['image', 'passbookImage'])->findOrNew($id);
        $countries = DB::table('master_countries')->select('id', 'name')->orderBy('name','asc')->get();
        $languages = Language::orderBy('position', 'asc')->pluck('name', 'id')->toArray();
        $languages[0]= 'select';
        ksort($languages);
        $shipping =  ShippingAddress::where('user_id', $item->id)->where('user_type', 'user')->first();
        $shippingAddresses =  ShippingAddress::where('user_id', $item->id)->where('user_type', 'user')->get();
        // return $shippingAddresses;
        return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item',  'moduleName', 'countries', 'languages','medias', 'shipping',"shippingAddresses"));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('customer', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('customer', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

        $this->validate($request, [
          'name'          => 'required',
          'phone'         => 'required|max:255|unique:users,phone,'.$id.',id',
          'email'        => 'required|email|unique:users,email,'.$id.',id',
        ]);
        $country = DB::table('master_countries')->find($request->country_id);

        $id = $request->id;
        $bonus = $request->bonus?:0;
        $item = User::find($id);
        if(!$item){
          $item = new User();
          $item->register_from_ip  = request()->ip();
        }
        $item->name 		            = $request->name;
        $item->email 		            = $request->email;
        $item->temp_email 		            = $request->email;
        $item->phone 		            = $request->phone;
        $item->temp_phone 		            = $request->phone;
        $item->status 		            = $request->status?:0;
        $item->is_verified 		        = $request->is_verified?:0;
        $item->wallet 		            = $request->wallet+$bonus;
        $item->city_id 		            = $request->city_id?:0;
        $item->state_id 		        = $request->state_id?:0;
        $item->country_id 		        = $request->country_id?:101;
        $item->pincode 		            = $request->pincode;
        $item->address 		            = $request->address;
        $item->city 		            = $request->city_id ? DB::table('master_cities')->find($request->city_id)->name : '';
        $item->state 		            = $request->state_id ? DB::table('master_states')->find($request->state_id)->name : '';
        $item->country 		          = $country ? $country->name : 'India';
        $item->phone_code 		      = $country ? $country->phonecode : '+91';
        $item->country_code 		    = $country ? $country->sortname : 'IN';
        $item->birth_date               = date('Y-m-d', strtotime($request->birth_date));
        $item->dob_yyyy                 = date('Y', strtotime($request->birth_date));
        $item->dob_mm                   = date('m', strtotime($request->birth_date));
        $item->dob_dd                   = date('d', strtotime($request->birth_date));
        $item->birth_time 		        = $request->birth_time;
        $item->birth_place 		        = $request->birth_place;
        $item->gender 		            = $request->gender?:0;
        $item->language_id 		        = $request->language_id;
        $item->image_id 		        = $request->image_id;
        $item->is_influencer            = $request->is_influencer ? 1 : 0;
        $item->account_number            = $request->account_number;
        $item->ifsc_code                 = $request->ifsc_code;
        $item->upi_id                    = $request->upi_id;
        $item->passbook_image_id        = $request->passbook_image_id;
        $item->save();
        if($education = $request->education) {
           $educationItem = $item->education;
           if(!$educationItem) {
             $educationItem = new UserAdditionalInformation();
             $educationItem->user_id = $item->id;
           }
           $educationItem->qualification= $education['qualification'];
           $educationItem->institution= $education['institution'];
           $educationItem->passing_year= $education['passing_year'];
           $educationItem->division_grade= $education['division_grade'];
           $educationItem->marks= $education['marks'];
           $educationItem->experience= $education['experience'];
           $educationItem->save();
        }

        if($bonus > 0){
          // user wallet transition
          $walletTransition = new WalletTransition();
          $walletTransition->user_id = $item->id;
          $walletTransition->user_type = "user";
          $walletTransition->table_id = $item->id;
          $walletTransition->table_name = "users";
          $walletTransition->amount = $bonus;
          $walletTransition->action = 1; //credit
          $walletTransition->status = 1; //success
          $walletTransition->remark = "Bonus/Gift from ".config("app.name");
          $walletTransition->invoice = 0;
          $walletTransition->save();
        }

        $redirectTo = route('admin.'.$this->module.'.edit', [$item->id])  ;
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan('customer','delete')) return dd('No Permission');
       if($id = $request->id) {
         $item = User::withTrashed()->find($id);
         if(is_null($item->deleted_at)) $item->delete();
         else $item->forceDelete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function restore(Request $request)
     {
        if(!adminCan('customer','update') || !adminCan('customer','add')) return dd('No Permission');
        if($id = $request->id) {
          $item = User::withTrashed()->find($id);
          $item->restore();
        }
        return redirect()->route('admin.'.$this->module.'.index');
     }

     public function exportCsv(Request $request){
        $results = User::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
        if($uuid = $request->uuid) {
            $results->where('uuid', 'like', "%$uuid%");
        }
        if($name = $request->name) {
            $results->where('name', 'like', "%$name%");
        }
        if($email = $request->email) {
            $results->where('email', 'like', "%$email%");
        }
        if($phone = $request->phone) {
            $results->where('phone', 'like', "%$phone%");
        }
        if($cart_items = $request->cart_items){
          $cartItems = CartItem::where('type', $cart_items)->where('user_type', 'user')->pluck('user_id')->toArray();
          $results->whereIn('id', $cartItems);
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', $status);
        }

        if($trashed = $request->trashed){
          $results->onlyTrashed();
        }

        if($updated_at = $request->updated_at) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

       $results = $results->get();

       $genders = ["Select", "Male", "Female", "Other"];

       $filePath = public_path('csvs/customers/list.csv');

       $handle = fopen($filePath, 'w');

       fputcsv($handle, ["UUID", "Name", "Email", "Phone", "Gender", "Status", "Created at"]);

       foreach ($results as $index => $item){
         fputcsv($handle, [
           $item->uuid,
           $item->name,
           $item->email,
           $item->phone,
           $genders[$item->gender],
           $item->status ? "Active" : "Inactive",
           $item->created_at
         ]);
       }

       fclose($handle);

       $message = "Data Export Successfully";
       $redirectTo = url("csvs/customers/list.csv");

       return response()->json([
         'success'=>true,
         'message'=> $message,
         'redirectTo'=> $redirectTo,
       ], 200);
     }

     public function login(Request $request){
       $user_type = "user";
       $user_id = $request->user_id ?: 0;
       $user = User::find($user_id);
       if($user == null){
         return redirect()->back();
       }
       Auth::guard('web')->logout();
       Auth::guard('astrologer')->logout();
       Auth::guard('web')->login($user);
     }
     public function saveShippingAddress(Request $request){
       $request->validate([
         "user_id"=>"required|integer|gt:0",
         "shipping_name"=>"required",
         "shipping_email"=>"required",
         "shipping_phone"=>"required",
         "shipping_country_id"=>"required",
         "shipping_state_id"=>"required",
         "shipping_city_id"=>"required",
         "shipping_address"=>"required",
         "shipping_pincode"=>"required",
       ]);
       $id = $request->shipping_id?:0;
       $shippingAddress=ShippingAddress::find($id);
       $message="Shipping Address Edit Successfully.";
       if(!$shippingAddress){
         $shippingAddress=new ShippingAddress();
         $message="Shipping Address Add Successfully.";
       }
       $shippingAddress->user_id=$request->user_id;
       $shippingAddress->user_type="user";
       $shippingAddress->name=$request->shipping_name;
       $shippingAddress->email=$request->shipping_email;
       $shippingAddress->phone=$request->shipping_phone;
       $shippingAddress->country=DB::table('master_countries')->find($request->shipping_country_id)->name;
       $shippingAddress->country_id=$request->shipping_country_id;
       $shippingAddress->state=DB::table('master_states')->find($request->shipping_state_id)->name;
       $shippingAddress->state_id=$request->shipping_state_id;
       $shippingAddress->city=DB::table('master_cities')->find($request->shipping_city_id)->name;
       $shippingAddress->city_id=$request->shipping_city_id;
       $shippingAddress->address=$request->shipping_address;
       $shippingAddress->zipcode=$request->shipping_pincode;
       $shippingAddress->save();
       return response()->json(["success"=>true,"message"=>$message],200);
     }
     public function deleteShippingAddress(Request $request){
       $id=$request->id?:0;
       ShippingAddress::where("id",$id)->delete();
       return response()->json(["success"=>true,"message"=>"Shipping Address Delete Successfully."],200);
     }

 }
