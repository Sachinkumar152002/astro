<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wishlist;

class WishlistController extends Controller
{
  public function addToWishlist(Request $request){
    $user = null;
    $user_type = '';
    if(auth()->guard('web')->check()){
      $user = auth()->guard('web')->user();
      $user_type = 'user';
    }elseif(auth()->guard('astrologer')->check()){
      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';
    }
    if(!$user  && !$user_type){
      return response()->json([
          'status' => 'fail',
          'message' => 'Please Login First.'
      ], 404);
    }
    $request->validate([
      'item_id'=>'required',
      'type'=>'required'
    ]);
    $actionType = $request->actionType ?: '';
    if($actionType == 'remove'){
      return $this->removeFromWishlist($request);
    }

    $wishlist = Wishlist::where('item_id', $request->item_id)->where('type', $request->type)->where('user_id', $user->id)->where('user_type', $user_type)->first();
    if(!$wishlist){
      $wishlist = new Wishlist();
      $wishlist->user_id = $user->id;
      $wishlist->user_type = $user_type;
      $wishlist->item_id = $request->item_id;
      $wishlist->type = $request->type;
    }
    $wishlist->save();
    return response()->json([
        'status' => 'success',
        'message' => 'Item Added In Wishlist Successfully.',
        'wishlist_id'=>$wishlist->id
    ], 200);
  }

  public function removeFromWishlist(Request $request){
    $user = null;
    $user_type = '';
    if(auth()->guard('web')->check()){
      $user = auth()->guard('web')->user();
      $user_type = 'user';
    }elseif(auth()->guard('astrologer')->check()){
      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';
    }
    if(!$user  && !$user_type){
      return response()->json([
          'status' => 'fail',
          'message' => 'Please Login First.'
      ], 404);
    }
    $request->validate([
      'wishlist_id'=>'required',
    ]);

    $wishlist = Wishlist::find($request->wishlist_id)->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'Item Remove From Wishlist Successfully.'
    ]);
  }

}
