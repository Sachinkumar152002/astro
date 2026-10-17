<?php

namespace App\Http\Controllers;
use App\{Order, OrderItem, CartItem,UserTempCart};

use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function redirectToCheckout(Request $request){
    $accountController = new AccountController();
    $user = $accountController->login($request);
    $user_id = $user->id;
    $user_type = "user";
    $order_id = $request->order_id ? decrypt($request->order_id) : 0;
    $address_id=$request->address_id?$request->address_id:0;
    $order = Order::where("id", $order_id)->where("user_id", $user_id)->where("user_type", $user_type)->first();
    if($order == null){
      return redirect()->back();
    }
    $url = route("checkout", ["shop", "cart", "Product", "items", "order_id"=>$order_id]);
    if($address_id>0){
      $url = route("checkout", ["shop", "cart", "Product", "items", "order_id"=>$order_id,"address_id"=>$address_id]);
    }
    $orderItems = OrderItem::where("order_id", $order_id)->get();
    $cartItemIds = [];
    foreach ($orderItems as $index => $item) {
      $cartItem = CartItem::where("user_id", $user_id)
      ->where("user_type", $user_type)
      ->where("item_id", $item->product_id)
      ->where("type", "shop")
      ->first();
      if(!$cartItem){
        $cartItem = new CartItem();
        $cartItem->user_id = $user_id;
        $cartItem->user_type = $user_type;
        $cartItem->item_id = $item->product_id;
        $cartItem->type = "shop";
      }
      $cartItem->quantity = $item->quantity;
      $cartItem->price = $item->quantity;
      $cartItem->unit_price = $item->unit_price;
      $cartItem->item_type = $order->item_type;
      $options = $item->options  ? json_decode($item->options, true) : [];
      $formdata = isset($options["formdata"]) ? $options["formdata"] : [];

      // Ensure formdata is an array
      if(!is_array($formdata)) {
        $formdata = [];
      }

      // If gotra exists in order_items.gotra column, add it to formdata if not already present
      if(!empty($item->gotra)) {
        $gotraExists = false;
        foreach($formdata as $key => $field) {
          if(is_array($field) && isset($field['name']) && strtolower(trim($field['name'])) === 'gotra') {
            $gotraExists = true;
            // Update the value if it exists
            $formdata[$key]['value'] = $item->gotra;
            break;
          }
        }
        if(!$gotraExists) {
          $formdata[] = [
            'type' => 'text',
            'label' => 'Gotra',
            'name' => 'gotra',
            'value' => $item->gotra
          ];
        }
      }

      $cartItem->formdata = json_encode($formdata);
      $cartItem->save();
      $product = $cartItem->product;
      if($product){
        $cartItemIds[] = $cartItem->id;
        if($product->type == 1){
          $url = route("checkout", ["shop", "cart", "Product", "items", "order_id"=>$order_id]);
          if($address_id>0){
            $url = route("checkout", ["shop", "cart", "Product", "items", "order_id"=>$order_id,"address_id"=>$address_id]);
          }
        }elseif($product->type == 2){
          $url = route("checkout", ["shop", "cart", "Pooja", $product->slug, "order_id"=>$order_id]);
          if($address_id>0){
            $url = route("checkout", ["shop", "cart", "Pooja", $product->slug, "order_id"=>$order_id,"address_id"=>$address_id]);
          }
        }elseif($product->type == 1){
          $url = route("checkout", ["shop", "cart", "Service", $product->slug, "order_id"=>$order_id]);
          if($address_id>0){
            $url = route("checkout", ["shop", "cart", "Service", $product->slug, "order_id"=>$order_id,"address_id"=>$address_id]);
          }
        }
      }
    }
    CartItem::where("user_id", $user_id)
    ->where("user_type", $user_type)
    ->whereNotIn("id", $cartItemIds)
    ->where("type", "shop")
    ->delete();
    return redirect($url);
  }
  public function redirectToBuynow(Request $request){
    $accountController = new AccountController();
    $user = $accountController->login($request);
    $user_id = $user->id;
    $user_type = "user";
    $cartitemid = $request->cartitemid ? decrypt($request->cartitemid) : 0;
    $cartItem = CartItem::where("id", $cartitemid)->where("user_id", $user_id)->where("user_type", $user_type)->first();
    if($cartItem == null){
      return redirect()->route("home");
    }
    $url=route("checkout",["shop","cart","Product","items","cartitemid"=>$cartitemid]);
    return redirect($url);
  }
  public function redirectToDirectCheckout(Request $request){
    $accountController = new AccountController();
    $user = $accountController->login($request);
    // $user_temp_cart_id=$request->user_temp_cart_id?decrypt($request->user_temp_cart_id):0;
    $user_temp_cart_id=$request->user_temp_cart_id?:"";
    $address_id=$request->address_id?:0;
    $request_parameters=["product"];
    if($user_temp_cart_id){
      $request_parameters["user_temp_cart_id"]=$user_temp_cart_id;
    }
    if($address_id){
      $request_parameters["address_id"]=$address_id;
    }
    $url=route("checkout",$request_parameters);
    return redirect($url);
  }
}
