<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{User, CartItem, Product};
use App\Model\{Astrologer, Course};

class CartItemController extends Controller
{
    private $module = 'cartitem';
    private $moduleName = "Abondoned Cart";
    private $moduleNameSingle = 'CartItem';

    public function index(Request $request){
      $nav = "cartitems";
      $moduleName = $this->moduleName;
      $user_id = $request->user_id;
      $type = $request->type ?: "shop";
      $breads = [
        ['name'=>'Customer', 'url'=> route('admin.customer.index')],
        ['name'=>$this->moduleName]
      ];
      if($user_id == 0){
        $breads = [
          ['name'=>'Sales'],
          ['name'=>'CartItems']
        ];
      }
      return view('admin.customer.cartitem.list', compact('nav','breads', 'moduleName', 'user_id', 'type'));
    }

     public function all(Request $request){
       $user_id = $request->user_id;
       $query = CartItem::with(['user', 'product.image', 'course.image'])
         ->orderBy('created_at', 'desc');
       if($user_id){
         $query->where('user_id', $user_id)->where('user_type', 'user');
       }else{
         $query->where('user_type', "!=", 'guest');
       }
       if($type = $request->type){
         $query->where('type', $type);
       }
       if($customer_name = trim((string) $request->customer_name)) {
         $like = '%'.$customer_name.'%';
         $query->where(function ($q) use ($like) {
           $q->where(function ($q2) use ($like) {
             $q2->where('user_type', 'user')
               ->whereIn('user_id', User::where('name', 'like', $like)->pluck('id'));
           })->orWhere(function ($q2) use ($like) {
             $q2->where('user_type', 'astrologer')
               ->whereIn('user_id', Astrologer::where('name', 'like', $like)->pluck('id'));
           });
         });
       }
       if($item_name = trim((string) $request->item_name)) {
         $like = '%'.$item_name.'%';
         $productIds = Product::where('title', 'like', $like)->pluck('id');
         $courseIds = Course::where('name', 'like', $like)->pluck('id');
         $query->where(function ($q) use ($productIds, $courseIds) {
           $q->where(function ($q2) use ($productIds) {
             $q2->where('type', 'shop')->whereIn('item_id', $productIds);
           })->orWhere(function ($q2) use ($courseIds) {
             $q2->where('type', 'course')->whereIn('item_id', $courseIds);
           });
         });
       }
       if($updated_at_from = $request->updated_at_from) {
         $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
         $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $results = $query->paginate(13);
       $data = [
         'rows' => view('admin.customer.cartitem.listrows',['results'=>$results, 'user_id'=>$user_id])->render(),
         'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
       ];
       return response()->json($data, 200);
     }

     public function form(Request $request){
      $id = $request->id;
      $user_id = $request->user_id;
        $nav = "cartitems";
      $moduleName = $this->moduleNameSingle;
      $breads = [
           ['name'=>'Customer', 'url'=> route('admin.customer.index')],
           ['name'=>$this->moduleName, 'url'=> route('admin.customer.cartitem', [$request->user_id])],
           ['name'=>'Detail']
      ];
      $user=User::find($user_id);
      $cartItems = CartItem::where("user_id", $user_id)->get();
       return view('admin.customer.cartitem.form', compact('nav', 'breads', 'moduleName', 'cartItems', 'user'));
     }

     public function delete(Request $request){
        if($id = $request->id){
          $item = CartItem::find($id);
          $item->delete();
          return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
}
