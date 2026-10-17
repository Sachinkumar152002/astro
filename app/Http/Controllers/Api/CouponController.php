<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{RechargePlan, UserRechargeHistory, Coupon, ShippingAddress};
use App\{Product, Order, OrderItem, CartItem, User};

class CouponController extends Controller
{
  public function apply(Request $request){
    $this->validate($request, [
      'page'=>'required|in:order,recharge',
      'coupon_code'=>'required',
    ],[
      'coupon_code.required'=>'Coupon code is required.',
    ]);
    $type = $request->page;
    if($type=='recharge'){
      $this->validate($request, [
        'plan_id'=>'required|gt:0',
      ]);
    }
    $item_type = $request->item_type?:0;
    $plan_id = $request->plan_id?:0;
    $code = trim($request->coupon_code);
    $api_token = $request->api_token?:'';
    $amount = 0;
    $discountAmount = 0;
    $taxAmount = 0;
    $totalShippingAmount = 0;
    $carItems = [];
    $plan = null;
    $user = $request->all();
    $user_type = 'user';
    $address_id = $request->address_id ?: 0;
    $pincode = 0;

    $shippingAddressData = ShippingAddress::find($address_id);

    if($shippingAddressData){
      $pincode = $shippingAddressData->zipcode;
    }

    $currentDate = date('Y-m-d');
    $coupon = Coupon::where('code', $code)
    ->where('status', 1)
    ->where(function($query) use ($currentDate){
      $query->whereNull('from_date')
      ->orWhere(function($q) use ($currentDate){
        $q->whereDate('from_date', '<=', $currentDate)
        ->whereDate('to_date', '>=', $currentDate);
      });
    })
    ->first();

    if(!$coupon){
      return response()->json([
        'success' => false,
        'message' => 'Invalid coupon.',
      ], 402);
    }

    if($coupon->only_one_time){
      if($type == 'order'){
        $order = Order::whereNotIn('order_status', ['pending', 'cancel'])
        ->where('coupon_id', $coupon->id)
        ->where('coupon', $code)
        ->first();
        if($order){
          return response()->json([
            'success' => false,
            'message' => 'Invalid coupon.'
          ], 402);
          // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
        }
      }elseif($type == 'recharge'){
        $userRecharge = UserRechargeHistory::where('payment_status', 'success')
        ->where('coupon_id', $coupon->id)
        ->where('coupon_code', $code)
        ->first();
        if($userRecharge){
          return response()->json([
            'success' => false,
            'message' => 'Invalid coupon.'
          ], 402);
          // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
        }
      }
    }
    if($coupon->one_time){
      if($type == 'order'){
        $order = Order::where('status', 'Success')
        ->where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('coupon_id', $coupon->id)
        ->where('coupon', $code)
        ->first();
        if($order){
          return response()->json([
            'success' => false,
            'message' => 'This coupon is valid only one time and you already used this coupon once.'
          ], 402);
        }
      }elseif($type == 'recharge'){
        $userRecharge = UserRechargeHistory::where('payment_status', 'success')
        ->where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('coupon_id', $coupon->id)
        ->where('coupon_code', $code)
        ->first();
        if($userRecharge){
          return response()->json([
            'success' => false,
            'message' => 'This coupon is valid only one time and you already used this coupon once.'
          ], 402);
        }
      }
    }
    if($type == 'order'){
      if($coupon->apply_on == 'recharge'){
        return response()->json([
          'success' => false,
          'message' => 'Not valid for shopping.'
        ], 402);
      }
      if($coupon->apply_on == 'any'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Please Add item in cart first.'
          ], 402);
        }
      }elseif($coupon->apply_on == 'product'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', 'shop')
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Coupon valid for product, please add product in your cart first.'
          ], 402);
        }
      }elseif($coupon->apply_on == 'course'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', 'course')
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Coupon valid for course, please add course in your cart first.'
          ], 402);
        }
      }
    }elseif($type == 'recharge'){
      if($coupon->apply_on == 'product' || $coupon->apply_on == 'course'){
        return response()->json([
          'success' => false,
          'message' => 'Not valid for recharge.'
        ], 402);
      }
      $plan = RechargePlan::find($plan_id);
      if(!$plan){
        return response()->json([
          'success' => false,
          'message' => 'Item not found'], 402);
      }
      $amount = $plan->amount;
    }

    if($amount < $coupon->minimum_amount){
      return response()->json([
        'success' => false,
        'message' => 'Minimum amount to use this coupon is ₹'.$coupon->minimum_amount
      ], 402);
    }
    if($coupon->fixed_perc){
      $percentage = $coupon->amount;
      $discountAmount = ($amount/100)*$percentage;
    }else{
      $discountAmount = $coupon->amount;
    }

    $totalBaseAmount = 0;
    $totalSaleAmountt = 0;
    $totalDiscountAmount = 0;
    $totalAmount = 0;
    $discountAmount = (double) $discountAmount;

    $data = [];

    if($type == 'order'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('item_type', $item_type)
      ->get();
      if($coupon->apply_on == 'product'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', "shop")
        ->where('item_type', $item_type)
        ->get();
      }elseif($coupon->apply_on == 'course'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', "course")
        ->where('item_type', $item_type)
        ->get();
      }
      $totalQuantity = $cartItems->sum('quantity');
      foreach($cartItems as $key => $cartItem){
        if($cartItem->type == 'shop'){
          $product = $cartItem->product;
          if($product){
            $itemTotalBaseAmount = $cartItem->unit_price*$cartItem->quantity;
            $totalBaseAmount += $itemTotalBaseAmount;
            $itemTotalSaleAmount = $cartItem->price*$cartItem->quantity;
            $totalSaleAmountt += $itemTotalSaleAmount;
            $itemDiscountAmount = $itemTotalBaseAmount-$itemTotalSaleAmount;
            $totalDiscountAmount += $itemDiscountAmount;
            $itemCouponAmount = ($itemTotalSaleAmount/$amount)*$discountAmount;
            $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;

            $totalAmount += $itemTotalSaleAmount;
            if($product->hnsCode){
              $gst_percentage = $product->hnsCode->gst_percentage;
              $taxAmount += $itemTotalSaleAmount*($gst_percentage/100);
            }
            if($product->apply_shipping){
              $itemShippingAmount = calculateShippingAmount($product, $pincode)*$cartItem->quantity;
              $totalShippingAmount += $itemShippingAmount;
            }
          }
        }elseif($cartItem->type == 'course'){
          $course = $cartItem->course;
          if($course){
            $itemTotalAmount = $cartItem->price*$cartItem->quantity;
            $itemTotalBaseAmount = $cartItem->unit_price*$cartItem->quantity;
            $totalBaseAmount += $itemTotalBaseAmount;
            $itemTotalSaleAmount = $cartItem->price*$cartItem->quantity;
            $totalSaleAmountt += $itemTotalSaleAmount;
            $itemDiscountAmount = $itemTotalBaseAmount-$itemTotalSaleAmount;
            $totalDiscountAmount += $itemDiscountAmount;
            if($course->apply_gst){
              if($course->hnsCode){
                $gst_percentage = $course->hnsCode->gst_percentage;
              }
              if($course->price_type){
                // tax included
                $itemCouponAmount = ($itemTotalSaleAmount/$amount)*$discountAmount;
                $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
                $taxAmountt = $itemTotalSaleAmount*($gst_percentage/100);
                $itemTotalSaleAmount = $itemTotalSaleAmount - $taxAmountt;
                $taxAmount += $taxAmountt;
                $totalAmount += $itemTotalSaleAmount;
              }else{
                $itemCouponAmount = ($itemTotalSaleAmount/$itemTotalAmount)*$discountAmount;
                $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
                $taxAmount += $itemTotalSaleAmount*($gst_percentage/100);
                $totalAmount += $itemTotalSaleAmount;
              }
            }else{
              $itemCouponAmount = ($itemTotalSaleAmount/$itemTotalSaleAmount)*$discountAmount;
              $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
              $totalAmount += $itemTotalSaleAmount;
            }
          }
        }
      }
      $data['shippingPrice'] = $totalShippingAmount;
    }else{
      $totalBaseAmount = $plan->amount;
      $taxAmount = ($plan->amount-$discountAmount)*0.18;
    }

    $data['subTotal'] = number_format(($totalBaseAmount-$totalDiscountAmount), 2);
    $data['discount'] = sprintf('%.2f', 0);
    $data['coupon_amount'] = sprintf('%.2f', $discountAmount);
    $data['taxes'] = number_format($taxAmount, 2);
    $data['shippingPrice'] = sprintf('%.2f', $totalShippingAmount);
    $data['total'] = number_format((($totalBaseAmount-$totalDiscountAmount-$discountAmount)+$taxAmount+$totalShippingAmount), 2);

    return response()->json([
      'success' => true,
      'message' => 'Coupon code applied successfully.',
      'discountAmount' => $discountAmount,
      'coupon' => $coupon,
      'data' => $data
    ], 200);
  }

  public function apply2(Request $request){
    $this->validate($request, [
      'user_id'=>'required|gt:0',
      'page'=>'required|in:order,recharge',
      'coupon_code'=>'required',
    ],[
      'coupon_code.required'=>'Coupon code is required.',
    ]);
    $type = $request->page;
    if($type=='recharge'){
      $this->validate($request, [
        'plan_id'=>'required|gt:0',
      ]);
    }
    $item_type = $request->item_type?:0;
    $plan_id = $request->plan_id?:0;
    $code = trim($request->coupon_code);
    $api_token = $request->api_token?:'';
    $amount = 0;
    $discountAmount = 0;
    $taxAmount = 0;
    $totalShippingAmount = 0;
    $carItems = [];
    $plan = null;
    $user_id = $request->user_id;
    $user = User::find($user_id);
    $user_type = 'user';
    $address_id = $request->address_id ?: 0;
    $pincode = 0;

    $shippingAddressData = ShippingAddress::find($address_id);

    if($shippingAddressData){
      $pincode = $shippingAddressData->zipcode;
    }

    $currentDate = date('Y-m-d');
    $coupon = Coupon::where('code', $code)
    ->where('status', 1)
    ->where(function($query) use ($currentDate){
      $query->whereNull('from_date')
      ->orWhere(function($q) use ($currentDate){
        $q->whereDate('from_date', '<=', $currentDate)
        ->whereDate('to_date', '>=', $currentDate);
      });
    })
    ->first();

    if(!$coupon){
      return response()->json([
        'success' => false,
        'message' => 'Invalid coupon.',
      ], 402);
    }

    if($coupon->only_one_time){
      if($type == 'order'){
        $order = Order::whereNotIn('order_status', ['pending', 'cancel'])
        ->where('coupon_id', $coupon->id)
        ->where('coupon', $code)
        ->first();
        if($order){
          return response()->json([
            'success' => false,
            'message' => 'Invalid coupon.'
          ], 402);
          // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
        }
      }elseif($type == 'recharge'){
        $userRecharge = UserRechargeHistory::where('payment_status', 'success')
        ->where('coupon_id', $coupon->id)
        ->where('coupon_code', $code)
        ->first();
        if($userRecharge){
          return response()->json([
            'success' => false,
            'message' => 'Invalid coupon.'
          ], 402);
          // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
        }
      }
    }
    if($coupon->one_time){
      if($type == 'order'){
        $order = Order::where('status', 'Success')
        ->where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('coupon_id', $coupon->id)
        ->where('coupon', $code)
        ->first();
        if($order){
          return response()->json([
            'success' => false,
            'message' => 'This coupon is valid only one time and you already used this coupon once.'
          ], 402);
        }
      }elseif($type == 'recharge'){
        $userRecharge = UserRechargeHistory::where('payment_status', 'success')
        ->where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('coupon_id', $coupon->id)
        ->where('coupon_code', $code)
        ->first();
        if($userRecharge){
          return response()->json([
            'success' => false,
            'message' => 'This coupon is valid only one time and you already used this coupon once.'
          ], 402);
        }
      }
    }
    if($type == 'order'){
      if($coupon->apply_on == 'recharge'){
        return response()->json([
          'success' => false,
          'message' => 'Not valid for shopping.'
        ], 402);
      }
      if($coupon->apply_on == 'any'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Please Add item in cart first.'
          ], 402);
        }
      }elseif($coupon->apply_on == 'product'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', 'shop')
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Coupon valid for product, please add product in your cart first.'
          ], 402);
        }
      }elseif($coupon->apply_on == 'course'){
        $cartItems = CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where('type', 'course')
        ->where('item_type', $item_type)
        ->get();
        $amount = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });
        if($cartItems->count() == 0){
          return response()->json([
            'success' => false,
            'message' => 'Coupon valid for course, please add course in your cart first.'
          ], 402);
        }
      }
    }elseif($type == 'recharge'){
      if($coupon->apply_on == 'product' || $coupon->apply_on == 'course'){
        return response()->json([
          'success' => false,
          'message' => 'Not valid for recharge.'
        ], 402);
      }
      $plan = RechargePlan::find($plan_id);
      if(!$plan){
        return response()->json([
          'success' => false,
          'message' => 'Item not found'], 402);
      }
      $amount = $plan->amount;
    }

    if($amount < $coupon->minimum_amount){
      return response()->json([
        'success' => false,
        'message' => 'Minimum amount to use this coupon is ₹'.$coupon->minimum_amount
      ], 402);
    }
    if($coupon->fixed_perc){
      $percentage = $coupon->amount;
      $discountAmount = ($amount/100)*$percentage;
    }else{
      $discountAmount = $coupon->amount;
    }

    return response()->json([
      'apply_on' => $coupon->apply_on,
      'discount' => $discountAmount,
      'code' => $coupon->code,
      'id' => $coupon->id,
    ], 200);
  }
}
