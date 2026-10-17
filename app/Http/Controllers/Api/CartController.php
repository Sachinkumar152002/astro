<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Validation\ValidationException;
use App\Model\{Course, ShippingAddress};
use App\{User, Product, CartItem};

class CartController extends Controller
{
  public function add(Request $request){
    $request->validate([
      'api_token'=>'required',
      'type'=>'required',
      'item_id'=>'required',
    ]);

    $user = null;
    $user_id = $request->api_token;
    $user_type = 'guest';

    if($user=User::where('api_token', $user_id)->first()){
      $user_id = $user->id;
      $user_type = 'user';
    }

    $type = $request->type ?: 'shop';
    $item_id = $request->item_id ?: 0;
    $quantity = $request->quantity ?: 1;
    $form_data = $request->form_data && is_array($request->form_data)? $request->form_data:[];
    $redirectTo = 'checkout';
    $item = null;
    $cartItem = null;
    $message = 'Item added to cart successfully!';
    $item = Product::where('id', $item_id)->where('avaibility', '>', 0)->first();
    $itemm = ["id" => 0];
    if($item){
      if($item->type != 1){
        CartItem::where('user_id', $user_id)->where('user_type', $user_type)->where('item_type', 1)->delete();
      }
      $cartItem = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->where('item_id', $item->id)->where("type", $type)->first();
      if($cartItem){
        $message = 'Item already added in cart.';
      }else{
        $cartItem = new CartItem();
        $cartItem->user_id = $user_id;
        $cartItem->user_type = $user_type;
        $cartItem->item_id = $item->id;
        $cartItem->type = $type;
        $cartItem->quantity = 1;
        $cartItem->price = $item->base_price;
        $cartItem->unit_price = $item->base_price;
        if($item->sale_price > 0 && (date('Y-m-d') >= $item->sale_price_start_date ) && (date('Y-m-d') <= $item->sale_price_end_date) ){
          $cartItem->price = $item->sale_price;
        }
        $cartItem->formdata=json_encode($form_data);
        if($item->type == 1){
          $cartItem->item_type = 0;
          $redirectTo = 'cart';
        }elseif($item->type == 2){
          $cartItem->item_type = 1;
          $redirectTo = 'checkout';
        }elseif($item->type == 3){
          $cartItem->item_type = 1;
          $redirectTo = 'checkout';
        }
        $cartItem->save();
      }
      $productAttributes=$item->attributes;
      $productAttributeArray=[];
      foreach($productAttributes as $key => $value){
        if($attribute=$value->attribute){
          $productAttributeArray[]="$attribute->name: $value->attribute_value";
        }
      }
      $title=$item->title;
      if(count($productAttributeArray)){
        $title=implode(', ', $productAttributeArray);
        $title="$item->title ($title)";
      }
      $itemm['id'] = $cartItem->id;
      $itemm['item_id'] = $item->id;
      $itemm['name'] = $title;
      $itemm['slug'] = $item->slug;
      $itemm['image'] = $item->image?$item->image->image(100,100):no_image();
      $itemm['base_price'] = $cartItem->unit_price;
      $itemm['sale_price'] = $cartItem->price;
      $itemm['discount'] = ceil((abs($cartItem->unit_price-$cartItem->price)/$cartItem->unit_price)*100);
      $itemm['quantity'] = $cartItem->quantity;
      $itemm['can_increase'] = $cartItem->item_type ? 'no' : "yes";
    }else{
      $message='Out of stock!';
    }
    $totalQuantity = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->sum('quantity');
    return response()->json(['success'=>true, 'message'=>$message, 'data'=>$itemm, 'totalQuantity'=>$totalQuantity, 'item_id'=>$itemm['id'], 'redirect_to'=>$redirectTo], 200);
  }

  public function remove(Request $request){
    $this->validate($request, [
      'item_id'=>'required|gt:0',
    ]);
    $item_id=$request->item_id?:0;
    $cartItem=CartItem::where('id', $item_id)->first();
    if(!$cartItem){
      return response()->json(['success'=>true, 'message'=>'Item not found'], 404);
    }
    $cartItem->delete();
    $totalQuantity = CartItem::where('user_id', $cartItem->user_id)->where('user_type', $cartItem->user_type)->sum('quantity');
    return response()->json(['success'=>true, 'message'=>'Item remove from cart successfully', 'totalQuantity'=>$totalQuantity], 200);
  }

  public function list(Request $request){
    $this->validate($request, [
      'api_token'=>'required',
    ]);

    $user_id = $request->api_token;
    $user_type = 'guest';
    if($user=User::where('api_token', $user_id)->first()){
      $user_id = $user->id;
      $user_type = 'user';
    }
    $item_type = $request->item_type ? 1 : 0;
    $cartItems = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->where('item_type',$item_type)->get();
    $cart = [];
    $totalQuantity = $cartItems->sum('quantity');
    $total = 0;
    $discount = 0;
    $tax = 0;
    $shipping = 0;
    $subTotal = 0;
    foreach($cartItems as $key => $value){
      $item = [];
      if($value->type == 'shop'){
        $product = $value->product;
        if($product && $product->status){
          $value->unit_price = $product->base_price;
          $value->price = $product->base_price;
          if($product->sale_price_start_date && (date('Y-m-d') >= $product->sale_price_start_date) && (date('Y-m-d') <= $product->sale_price_end_date)){
            $value->price = $product->sale_price;
          }
          $value->save();

          $productAttributes=$product->attributes;
          $productAttributeArray=[];
          foreach($productAttributes as $key => $valueTwo){
            if($attribute=$valueTwo->attribute){
              $productAttributeArray[]="$attribute->name: $valueTwo->attribute_value";
            }
          }
          $title=$product->title;
          if(count($productAttributeArray)){
            $title=implode(', ', $productAttributeArray);
            $title="$product->title ($title)";
          }

          $item['id'] = $value->id;
          $item['item_id'] = $product->id;
          $item['name'] = $title;
          $item['slug'] = $product->slug;
          $item['image'] = $product->image?$product->image->image(100,100):no_image();
          $item['base_price'] = $value->unit_price;
          $item['sale_price'] = $value->price;
          if($value->unit_price == $value->price){
            $item['sale_price'] = 0;
          }
          $item['discount'] = ceil((($value->unit_price-$value->price)/$value->unit_price)*100);
          $item['quantity'] = $value->quantity;
          $item['can_increase'] = 'yes';

          // $formFields = $product->fields->sortBy('order');
          // $newCartItemFormData = [];
          // $cartItemFormData = $value->formdata ? json_decode($value->formdata, true) : [];
          // $index = -1;
          //
          // for($i = 0; $i < $product->max_order_qty; $i++){
          //   foreach($formFields as $key => $value){
          //     if($field=$value->field){
          //       $index++;
          //       if(isset($cartItemFormData[$index])){
          //         $cartItemFormField = $cartItemFormData[$index];
          //         if(isset($cartItemFormField["value"])){
          //           $newCartItemFormData[] = $cartItemFormField;
          //         }
          //       }
          //     }
          //   }
          // }
          //
          // $value->formdata = json_encode($newCartItemFormData);
          // $value->save();

          $item['form_data'] = $value->formdata?json_decode($value->formdata):[];
        }else{
          $value->delete();
        }
      }
      $total += $value->price*$item['quantity'];
      $subTotal += $value->unit_price*$item['quantity'];
      if($item){
        array_push($cart, $item);
      }
    }
    $discount = abs($total - $subTotal);
    return response()->json([
      'success'=>true,
      'message'=>'Cart',
      'data'=>$cart,
      'total'=>$total,
      // 'discount'=>$discount,
      'discount' => 0,
      'tax'=>$tax,
      // 'subTotal'=>$subTotal,
      'subTotal'=>$total,
      'shipping'=>$shipping,
      'totalQuantity'=>(string)$totalQuantity
    ], 200);
  }

  public function updateCartItemFormData(Request $request){
    $request->validate([
      'item_id'=>'required',
      'form_data'=>'required',
    ]);
    $item_id=$request->item_id;
    $form_data = $request->form_data && is_array($request->form_data)? $request->form_data:[];
    $cartItem = CartItem::find($request->item_id);
    $cartItem->formdata=json_encode($form_data);
    $cartItem->save();
    return response()->json(['success'=>false, 'message'=>'Update successfully.'], 200);
  }

  public function increase(Request $request){
    $this->validate($request,[
      'item_id'=>'required'
    ]);
    $item_id=$request->item_id;
    $cartItem = CartItem::find($request->item_id);
    if(!$cartItem){
      return response()->json(['success'=>false, 'message'=>'Cart Item has not added in your cart'], 402);
    }
    $message = "Reached maximum limit";
    $increased = false;
    if($cartItem->type == 'shop'){
      $product = $cartItem->product;
      if($product && ($cartItem->quantity<$product->avaibility) && ($cartItem->quantity<$product->max_order_qty)){
        $cartItem->quantity = $cartItem->quantity + 1;
        $cartItem->save();
        $increased = true;
        $message = "Quantity increased by one!";
      }
    }
    return response()->json(['success' => true, 'message' => $message, 'qunatity'=>$cartItem->quantity], 200);
  }

  public function decrease(Request $request){
    $this->validate($request,[
      'item_id'=>'required'
    ]);
    $cartItem = CartItem::find($request->item_id);
    if(!$cartItem){
      return response()->json(['success'=>false, 'message' => 'Cart Item has not added in your cart'], 402);
    }
    $message = "Item removed successfully.";
    $decreased = false;
    if($cartItem->quantity > 1){
      $decreased = true;
      $cartItem->quantity = $cartItem->quantity - 1;
      $cartItem->save();
      $message = "Quantity decreased by one!";
    }else{
      $cartItem->delete();
    }
    return response()->json(['success'=>true, 'message'=>$message, 'qunatity'=> $decreased?$cartItem->quantity:1], 200);
  }

  public function checkout(Request $request){
    $this->validate($request, [
      'item_type'=>'required|integer', // checkout, buynow
    ]);
    // $itemType = $request->itemType ?: 'shop';
    $itemType = "shop";
    $item_type = $request->item_type ? 1 : 0;
    $user = $request->user();
    $user_id = $user->id;
    $user_type = 'user';
    $subTotal = 0;
    $total = 0;
    $shippingPrice = 0;
    $discount = 0;
    $taxes = 0;
    $coupon_amount = 0;
    $course = null;
    $product = null;
    $pincode = $user->pincode;
    $addresses = ShippingAddress::where('user_id',$user->id)->where('user_type', $user_type)->get();
    if(count($addresses)==0){
      $shippingAddress = new ShippingAddress();
      $shippingAddress->user_id = $user->id;
      $shippingAddress->user_type = $user_type;
      $shippingAddress->name = $user->name;
      $shippingAddress->email = $user->email;
      $shippingAddress->phone = $user->phone;
      $shippingAddress->city_id = $user->city_id;
      $shippingAddress->city = $user->city;
      $shippingAddress->state_id = $user->state_id;
      $shippingAddress->state = $user->state;
      $shippingAddress->country_id = $user->country_id;
      $shippingAddress->country = $user->country;
      $shippingAddress->address = $user->address;
      $shippingAddress->zipcode = $user->pincode;
      $shippingAddress->save();
    }
    if($shippingAddress = $user->shippingAddress){
      $pincode = $shippingAddress->zipcode;
    }
    $pincode=$pincode ?: 000000;

    $cartItems = CartItem::where('user_id', $user->id)->where('user_type', $user_type)->where('type', $itemType)->where('item_type', $item_type)->get();
    if(count($cartItems)==0){
      return response()->json(['success'=>false, 'message'=>'Item not found'], 404);
    }
    foreach($cartItems as $key => $item){
      if($item->type == 'shop'){
        if(($product=$item->product) && $product->status){
          $item->unit_price = $product->base_price;
          $item->price = $product->base_price;
          if($product->sale_price_start_date && (date('Y-m-d') >= $product->sale_price_start_date) && (date('Y-m-d') <= $product->sale_price_end_date)){
            $item->price = $product->sale_price;
          }
          $item->save();
          $subTotal += $item->unit_price*$item->quantity;
          $total += $item->price*$item->quantity;
          $gst_percentage = 0;
          if($product->hnsCode){
            $gst_percentage = $product->hnsCode->gst_percentage;
          }
          $taxes += ($item->quantity*$item->price)*($gst_percentage/100);
          if($product->apply_shipping){
            $itemShippingAmount = calculateShippingAmount($product, $pincode)*$item->quantity;
            $shippingPrice += $itemShippingAmount;
          }
        }else{
          $item->delete();
        }
      }
    }
    $discount = $subTotal-$total;
    $total += $shippingPrice+$taxes;
    // $data['subTotal'] = number_format($subTotal, 2);
    $data['subTotal'] = number_format(($subTotal-$discount), 2);
    // $data['discount'] = sprintf('%.2f', $discount);
    $data['discount'] = sprintf('%.2f', 0);
    $data['coupon_amount'] = sprintf('%.2f', $coupon_amount);
    $data['taxes'] = number_format($taxes, 2);
    $data['shippingPrice'] = sprintf('%.2f', $shippingPrice);
    $data['total'] = number_format($total, 2);
    return response()->json(['success'=>true, 'message'=> 'order is ready to be placed', 'data'=>$data ], 200);
  }

}
