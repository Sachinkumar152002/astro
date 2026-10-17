<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Wishlist;
use App\Product;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
  public function add(Request $request){
    $this->validate($request, [
      'item_id'=>'required|gt:0',
      'action'=>'required|in:add,remove'
    ]);
    $user = $request->user();
    $userType = "user";
    $itemId = $request->item_id;
    $itemType = "shop";
    $action = $request->action;

    if($action == 'remove'){
      return $this->remove($request);
    }

    $item = Product::find($itemId);
    if($item){
      $userId = $user->id;
      $wishlist = Wishlist::where('item_id', $itemId)->where('type', $itemType)->where('user_id', $userId)->where('user_type', $userType)->first();
      if(!$wishlist){
        $wishlist = new Wishlist();
        $wishlist->user_id = $userId;
        $wishlist->user_type = $userType;
        $wishlist->item_id = $itemId;
        $wishlist->type = $itemType;
        $wishlist->save();
      }
      $totalItem = Wishlist::where('user_id', $userId)->where('user_type', $userType)->count();
      $data['wishlist_id'] = $wishlist->id;
      return response()->json(['success'=>true, 'message'=>'Item added successfully in wishlist.', 'data'=>$data, 'totalItem'=>$totalItem], 200);
    }
    return response()->json(['success'=>false, 'message'=>'Item not found.'], 402);
  }

  public function remove(Request $request){
    $this->validate($request, [
      'item_id'=>'required|gt:0',
      'action'=>'required|in:remove'
    ]);
    $user = $request->user();
    $userType = "user";
    $itemId = $request->item_id;
    $itemType = "shop";
    $action = $request->action;

    $item = Product::find($itemId);
    if($item){
      $userId = $user->id;
      $wishlist = Wishlist::where('item_id', $itemId)->where('type', $itemType)->where('user_id', $userId)->where('user_type', $userType)->first();
      if($wishlist){
        $data['wishlist_id'] = $wishlist->id;
        $wishlist->delete();
        $totalItem = Wishlist::where('user_id', $userId)->where('user_type', $userType)->count();
        return response()->json(['success'=>true, 'message'=>'Item removed from wishlist successfully.', 'data'=>$data, 'totalItem'=>$totalItem], 200);
      }
    }
    return response()->json(['success'=>false, 'message'=>'Item not found.'], 402);
  }

  public function list(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $items = Wishlist::where('user_id', $user->id)->where('user_type', $user_type)->get();
    $whislists = [];
    $products = [];
    $services = [];
    $current_date = date('Y-m-d');
    foreach($items as $key => $value){
      $item = [];
      if($product=$value->product){
        $productImage = $product->image;
        $item['wishlist_id'] = $value->id;
        $item['item_id'] = $value->item_id;
        $item['image'] = $productImage ? $productImage->image(400,400) : no_image();
        $item['name'] = $product->title;
        $item['slug'] = $product->slug;
        $item['base_price'] = $product->base_price;
        $item['sale_price'] = $product->base_price;
        if($product->sale_price_start_date && $product->sale_price_start_date <= $current_date && $product->sale_price_end_date >= $current_date){
          $item['sale_price'] = $product->sale_price;
        }
        $item['category'] =  $product->category ? $product->category->name : 'category';
        if($product->type==1){
          array_push($products, $item);
        }else{
          array_push($services, $item);
        }
      }
    }
    $data['total_count'] = count($products)+count($services);
    $data['products'] = [];
    $data['products']['count'] = count($products);
    $data['products']['list'] = $products;
    $data['services'] = [];
    $data['services']['count'] = count($services);
    $data['services']['list'] = $services;
    return response()->json(['success'=>true, 'message'=>'list', 'data'=>$data], 200);
  }
}
