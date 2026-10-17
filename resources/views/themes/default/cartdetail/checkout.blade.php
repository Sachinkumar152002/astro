@extends(themeView() . 'layout')

@push("style")
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
<style>

</style>
@endpush

@section('content')
@php
  $country_id = 0;
  $state_id = 0;
  $city_id = 0;
  $zipcode = 0;
@endphp
<section class="pt-5 pad-ap3">
  <div class="container">
    @if(isset($cartItems) && count($cartItems))
    <div class="row">
        <div class="col-md-6 col-12 px-3">
            <!-- deliver addresses start: show only when cart has physical product (puja/service show only billing) -->
            @if((!isset($showDeliveryAddress) || $showDeliveryAddress) && isset($addresses) && count($addresses) > 0)
                <h2 class="fs-20 f5 mt-md-0 mt-3 p-0 cart_del">Delivery Address</h2>
                {{-- Debug: Total addresses: {{ count($addresses) }} --}}
                <div class="row mx-0 add bg-light p-0 ps-2 ps-md-3 py-3 radius5 border-n" style="max-height: none; overflow: visible;">
                  @foreach($addresses as $key => $value)
                    <div class="col-12 m-0 p-0 mb-2" style="border-radius:10px">
                      <div class="card check-card1 pb-2">
                        <div class="d-flex align-items-start w-100">
                          <div class="me-3 mt-2">
                            @php
                              $urlWithAddressId = url()->current()."?address_id=".$value->id;
                              if($user_temp_cart_id){
                                $urlWithAddressId .= "&user_temp_cart_id=".$user_temp_cart_id;
                              }
                              if($cartitemid > 0){
                                $urlWithAddressId .= "&cartitemid=".$cartitemid;
                              }
                            @endphp
                            <input onclick="selectAddress('{{ $urlWithAddressId }}')" type="radio" {{ $value->id == $selecteAddressId ? "checked" : "" }} name="selected_address" class="c2radio pointer" value="{{ $value->id }}" />
                          </div>
                          <div class="cart_text courses_width p-0 pointer flex-grow-1" onclick="selectAddress('{{ $urlWithAddressId }}')">
                            <div class="card-title mb-0">
                              <h6 class="m-0">{{ $value->name }}</h6>
                            </div>
                            <div class="quantity">
                              {{$value->address}}, {{$value->city}}, {{$value->state}} - {{$value->zipcode}}
                            </div>
                          </div>
                          <div class="cross-icon-card1 pointer ms-2 mt-2">
                            <i class="bi bi-pencil text-purple fs-5" onclick='addEditAddressModal("edit", @json($value))'></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                  <div class="col-12  m-0 p-0" style="border-radius:10px">
                    <div class="">
                      <div class="cart_text courses_width p-0">
                        <div class="card-title mb-0">
                          <h6 class="add-address m-0 pointer text-purple" onclick='addEditAddressModal("edit", null)'>
                         <i class="bi bi-plus-circle me-2"></i> Add a new delivery address
                          </h6>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @elseif(!isset($showDeliveryAddress) || $showDeliveryAddress)
                <div class="alert alert-info">
                  <p class="mb-0">No delivery addresses found. Please add a delivery address.</p>
                  <button type="button" class="btn btn-sm btn-primary mt-2" onclick='addEditAddressModal("edit", null)'>
                    <i class="bi bi-plus-circle me-1"></i> Add Delivery Address
                  </button>
                </div>
              @endif
              @if(!isset($showDeliveryAddress) || $showDeliveryAddress)
              <div id="add_edit_delivery_address_div" class="d-none ">
                <h2 class="fs-20 f5 m-md-0 mt-md-2 mt-5">New delivery address</h2>
                <form onsubmit="return handleAddresses(this)" class="check-form p-3" style="border: 1px solid #ddd">
                  <input type="hidden" id="url" name="url" value="{{ url()->current() }}">
                  <input type="hidden" id="id" name="id" value="0">
                  <div class="col checkout_form">
                    <div class="row">
                      <div class="col-md-4 col-12">
                        <div class="mb-3">
                          <input type="text" class="form-control" id="new_full_name" name="new_full_name" placeholder="Full name">
                        </div>
                      </div>
                      <div class="col-md-4 col-12">
                        <div class="mb-3">
                          <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-md-4 col-12">
                        <div class="mb-3">
                          <input type="tel" class="form-control" id="new_phone" name="new_phone" placeholder="Phone" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                        </div>
                      </div>
                      <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="new_address" name="new_address" rows="2" placeholder="Address">
                      </div>
                      <div class="col-lg-6 col-12 d-none">
                        <div class="mb-3">
                          <select id="new_country_id" name="new_country_id" onchange="fetchStates('states', this, 'new_state_id')" class="form-select form-select-lg mb-0">
                            <option value="">Select Country</option>
                            @if($countries)
                            @foreach($countries as $key => $item)
                            <option value="{{$item->id}}" {{$item->id == 101 ? 'selected' : ''}} >{{$item->name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class=" col-lg-4 col-12 mb-3">
                        <div class="mb">
                          <select id="new_state_id" name="new_state_id" onchange="fetchStates('cities', this, 'new_city_id')" class="form-select form-select-lg mb-0">
                            <option value="">Select State</option>
                            @if($states)
                              @foreach($states as $key => $item)
                                <option value="{{$item->id}}" >{{$item->name}}</option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class=" col-lg-4 col-12 mb-2">
                        <div class="mb-0">
                          <select id="new_city_id" class="form-select form-select-lg" name="new_city_id">
                            <option value="">Select City</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-12 mb-3">
                        <div class="mb-2">
                          <input type="number" class="form-control" placeholder="Pin Code" name="new_zipcode" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);" id="new_zipcode">
                        </div>
                      </div>
                      <div class="col-12 text-center">
                        <button class="main_button" type="submit" id="add_edit_address_btn" >Submit</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              @endif
            <!-- deliver addresses end -->
            <h2 class="fs-20 f5 m-md-0 mt-md-3 mb-md-2 mt-3 p-0">Billing Address</h2>
            <div class="address-box bg-light billing-box border-0 radius5 ps-2 border-n">
              @if($item_type == 0)
                <div class="mt-2">
                  <label class="address-option">
                    <input type="radio" name="billing_address" value="shipping" checked>
                    Same as shipping address
                  </label>
                </div>
              @else
                <div class="">
                  <label class="address-option d-flex">
                    <input type="radio" name="billing_address" value="profile" checked>
                    <div class="cart_text courses_width ps-1 pointer">
                      <div class="card-title mb-0">
                        <h6 class="m-0">{{ $user->name }}</h6>
                      </div>
                      <div class="quantity">
                        {{$user->address}}, {{$user->city}}, {{$user->state}} - {{$user->pincode}}
                      </div>
                    </div>
                  </label>
                </div>
              @endif
               <div class="mt-2">
                 <label class="address-option">
                   <input type="radio" name="billing_address" value="different">
                   Use a different billing address
                 </label>
                 <form class="col checkout_form billing-form-box d-none p-0 pt-2 px-3">
                   <div class="row">
                     <div class="col-md-4 col-12">
                       <div class="mb-3">
                         <input type="text" class="form-control" id="billing_address_full_name" name="billing_address_full_name" placeholder="Full name">
                       </div>
                     </div>
                     <div class="col-md-4 col-12">
                     <div class="mb-3">
                         <input type="email" class="form-control" id="billing_address_email" name="billing_address_email" placeholder="Email">
                     </div>
                     </div>
                     <div class="col-md-4 col-12">
                       <div class="mb-3">
                         <input type="tel" class="form-control" id="billing_address_phone" name="billing_address_phone" placeholder="Phone" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                       </div>
                     </div>
                     <div class="col-12 mb-3">
                       <input type="text" class="form-control" id="billing_address_address" name="billing_address_address" rows="2" placeholder="Address">
                     </div>

                     <div class="col-lg-6 col-12 d-none">
                       <div class="mb-3">
                         <select id="billing_address_country_id" name="billing_address_country_id" onchange="fetchStates('states', this, 'billing_address_state_id')" class="form-select form-select-lg mb-0">
                           <option value="0">Select Country</option>
                           @if($countries)
                             @foreach($countries as $key => $item)
                               <option value="{{$item->id}}" {{$item->id == 101 ? 'selected' : ''}} >{{$item->name}}</option>
                             @endforeach
                           @endif
                         </select>
                       </div>
                     </div>
                     <div class=" col-lg-4 col-12 mb-3">
                       <div class="mb">
                         <select id="billing_address_state_id" name="billing_address_state_id" onchange="fetchStates('cities', this, 'billing_address_city_id')" class="form-select form-select-lg mb-0">
                           <option value="">Select State</option>
                           @if($states)
                             @foreach($states as $key => $item)
                             <option value="{{$item->id}}" >{{$item->name}}</option>
                             @endforeach
                           @endif
                         </select>
                       </div>
                     </div>
                     <div class=" col-lg-4 col-12 mb-2">
                       <div class="mb-0">
                         <select id="billing_address_city_id" class="form-select form-select-lg" name="billing_address_city_id">
                           <option value="">Select City</option>
                         </select>
                       </div>
                     </div>
                     <div class="col-lg-4 col-12 mb-3">
                       <div class="mb-2">
                         <input type="number" class="form-control" placeholder="Pin Code" name="billing_address_pincode" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);" id="billing_address_pincode">
                       </div>
                     </div>
                   </div>
                 </form>
               </div>
             </div>
             <h2 class="fs-20 f5 m-md-0 mt-md-2 mt-3 p-0">Payment Methods</h2>
             <div class="address-box  billing-box border-0 radius5 ps-0">
               @if($user_temp_cart==false && $item_type == 0 && fromSetting("cod") == 1)
                 <div class="cod-bb border-n bg-light">
                   <label class="address-option">
                     <input type="radio" name="payment_option" value="cod">
                     Cash on Delivery
                   </label>
                   <img src="{{asset('assets/img/cod_icon_v2.gif')}}" alt="" class="ms-2">
                 </div>
               @endif
                 <div class="cod-bb border-n mt-3 bg-light">
                   <label class="address-option">
                     <input type="radio" name="payment_option" value="now" checked>
                    Pay Online
                   </label>
                   <img src="{{asset('assets/img/prepaid_icon.gif')}}" alt="" class="ms-2">
                  </div>
              </div>
          </div>

          <div class="col-md-6 col-12 mt-md-0 my-3 check-sec px-3">
            <div class="bg-white radius5 border-n py-1 mt-3 check_side checkout_box">
            <!-- coupon code start -->
            <!-- review cart start -->
            <div class=" mx-3">
              <div class="">
                <div class="row mx-0 my-2">
                  @php
                    $productTypeNames = ["", "Product", "Pooja", "Service"];
                    $productTypeValue = 1;
                    $formDataArray = [];
                    $cartItemFormData = [];
                    $newCartItemFormData = [];
                    $product = null;
                    $firstCartItem = null;
                    $gotraValue = "";
                    $gotraUnknownChecked = false;
                  @endphp
                  @foreach($cartItems as $item)
                    @php
                      $itemId = $item->id;
                      $itemPrice = $item->unit_price;
                      $itemSalePrice = $item->price;
                      $itemQuantity = $item->quantity;
                      if($firstCartItem == null){
                        $firstCartItem = $item;
                        $cartItemFormData = json_decode($item->formdata, true);
                        // Ensure cartItemFormData is always an array
                        if(!is_array($cartItemFormData)){
                          $cartItemFormData = [];
                        }
                      }
                    @endphp
                    @if($item->type == "shop")
                      @php
                        $product = $item->product;
                        $productSlug = "";
                        $productImage = null;
                        $productTypeValue = 1;
                        $productTitle = $item->title;
                        $detaiPagelUrl = "";
                        if($product){
                          $productSlug = $product->slug;
                          $productImage = $product->image;
                          $productTypeValue = $product->type;
                          $productTitle = $product->title;
                          $productAttributes = $product->attributes;
                          $productAttributeArray=[];
                          foreach($productAttributes as $key => $valueTwo){
                            if($attribute=$valueTwo->attribute){
                              $productAttributeArray[]="$attribute->name: $valueTwo->attribute_value";
                            }
                          }
                          if(count($productAttributeArray)){
                            $title=implode(', ', $productAttributeArray);
                            $productTitle="$productTitle ($title)";
                          }
                          if($productTypeValue == 1){
                            $detaiPagelUrl = route("product.detail", [$productSlug, "id" => $product->id]);
                          }elseif($productTypeValue == 2){
                            $detaiPagelUrl = route("product.detailpuja", [$productSlug, "id" => $product->id]);
                          }elseif($productTypeValue == 3){
                            $detaiPagelUrl = route("product.detailservice", [$productSlug, "id" => $product->id]);
                          }
                        }
                        if($user_temp_cart==true){
                          $detaiPagelUrl = "";
                        }
                      @endphp
                      <div class="col-12 my-1 px-0">
                        <div class="card check-card1">
                          <div class="cart_img checkout-img2">
                            <img src="{{ $productImage ? $productImage->image(250, 250) : no_image() }}" class="radius5 rounded">
                            @php
                              // Get ashirwad_box value from cart item formdata
                              $ashirwadBoxValue = 0;
                              if(isset($cartItemFormData) && is_array($cartItemFormData)) {
                                foreach($cartItemFormData as $formField) {
                                  if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
                                    $ashirwadBoxValue = isset($formField['value']) ? (int)$formField['value'] : 0;
                                    break;
                                  }
                                }
                              }
                              $ashirwadBoxText = $ashirwadBoxValue == 1 ? 'True' : 'False';
                            @endphp

                          </div>
                          <div class="cart_text courses_width checkout_product" style="width:60% !important;">
                            <div class="card-title mb-0">
                              @if($productTypeValue == 2)
                                <div class="">
                                  <small class="text-muted d-block">Ashirwad box</small>
                                  <strong class="d-block">{{ $ashirwadBoxText }}</strong>
                                </div>
                                {{-- Hidden field to store ashirwad_box value for order submission --}}
                                <input type="hidden" name="ashirwad_box_value" id="ashirwad_box_value_{{ $itemId }}" value="{{ $ashirwadBoxValue }}">
                              @endif
                              @if($detaiPagelUrl)
                                <a href="{{ $detaiPagelUrl }}">
                                  <h6 class="">{{ $productTitle }}</h6>
                                </a>
                              @else
                                <a class="pointer"><h6 class="">{{ $productTitle }}</h6></a>
                              @endif
                            </div>
                            @if($itemPrice != $itemSalePrice)
                              <div class="quantity">
                                <strike class="text-black ">₹{{ $itemPrice }}</strike>
                                <span class="text-danger">₹{{ $itemSalePrice }} x {{ $itemQuantity }}</span>
                              </div>
                            @else
                              <div class="quantity">
                                <span class="text-danger">₹{{ $itemSalePrice }} x {{ $itemQuantity }}</span>
                              </div>
                            @endif
                          </div>
                          <div class="">
                            @if(count($cartItemFormData) > 0)
                              <button class="btn-checked" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#shareDetailsModal">Form Detail</button>
                              <!-- <i class="bi bi-pencil-fill" data-bs-toggle="modal" data-bs-target="#shareDetailsModal"></i> -->
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
            <!-- review cart end -->
            {{--<div class="p-0">
              <div class="card bg-light p-3 w-100 d-flex border-0 align-items-center justify-content-center pb-0">

                <div class="input-group w-100 mb-3">
                  <input type="text" id="coupon_code" name="coupon_code" class="form-control me-0  font-normal" placeholder="Discount code">
                  <button class="main_button rediuss" type="button" onclick="applyCouponCode('order')" id="coupon-apply-btn">Apply</button>
                  <button class="btn btn-danger d-none" type="button" onclick="removeCouponCode()" id="coupon-remove-btn">Remove</button>
                </div>
              </div>
            </div>--}}
            <!-- coupon code end -->
            <!-- amount summary start -->
            <div class="card bg-White mt-0 p-3 w-100 border-0 pt-0">
              <table class="table total_box bg-transparent table-borderless">
                <tbody>
                  @php
                    $subTotalWithDiscount = $subTotal-$discount;
                  @endphp
                  <tr class="table-light" style="border-style:1px solid red;">
                    <th class="f-14 f4">Subtotal</th>
                    <th class="text-end fs-14 fw-4" scope="row">
                      <i class="bi text-dark bi-currency-rupee">
                      </i><span class="subtotal_amount fs-14 fw-4">{{sprintf('%.2f', $subTotalWithDiscount)}}</span>
                    </th>
                  </tr>
                  <tr class="table-light {{ $coupon_amount>0?'':'d-none' }}">
                    <th class="f-14 f4">Discount</th>
                    <th class="text-end fs-16 f5 forntfont14" scope="row">-<i class="bi text-dark bi-currency-rupee"></i><span class="coupon_amount">{{sprintf('%.2f', $coupon_amount)}}</span></th>
                  </tr>
                  @if(isset($remediesReferralDiscount) && $remediesReferralDiscount > 0)
                  <tr class="table-light">
                    <th class="f-14 f4">Referral Discount ({{ $remediesReferralCode ?? '' }})</th>
                    <th class="text-end fs-16 f5 forntfont14" scope="row">-<i class="bi text-dark bi-currency-rupee"></i><span class="remedies_referral_discount">{{sprintf('%.2f', $remediesReferralDiscount)}}</span></th>
                  </tr>
                  @endif
                  <tr class="table-light" style="">
                    <th class="f-14 f4">GST & Taxes</th>
                    <th class="text-end fs-14 fw-4" scope="row">+<i class="bi text-dark bi-currency-rupee"></i><span class="taxes_amount fs-14 fw-4">{{sprintf('%.2f', $taxes)}}</span></th>
                  </tr>
                  <tr class="table-light {{$shippingPrice > 0 ? '' : 'd-none'}}">
                    <th class="f-14 f4">Shipping Charges</th>
                    @if($shippingPrice>0)
                      <th class="text-end fs-14 fw-4" scope="row">
                        +<i class="bi text-dark bi-currency-rupee"></i>
                        <span class="shipping_amount fs-14 fw-4">{{sprintf('%.2f', $shippingPrice)}}</span>
                      </th>
                    @endif
                  </tr>
                  @if($user_temp_cart==false)
                    <tr class="table-light ">
                      <td colspan="3" class="px-1">
                        <div class="coupon-box d-flex align-items-center p-2 rounded shadow-sm">
                          <div class="coupon-icon d-flex justify-content-center align-items-center me-3">
                          <i class="bi bi-tag text-success fs-5"></i>
                          </div>
                          <div class="coupon-input d-flex w-100">
                            <input type="text" id="coupon_code" name="coupon_code" class="form-control me-2  font-normal" placeholder="Discount code">
                            <button class="main_button " type="button" onclick="applyCouponCode('order')" id="coupon-apply-btn">Apply</button>
                            <button class="btn btn-danger d-none" type="button" onclick="removeCouponCode()" id="coupon-remove-btn">Remove</button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endif
                  <tr class="table-light">
                    <th class="fs-18 f5">Total Amount</th>
                    <th class="text-end fs-16 f5" scope="row">
                      <i class="bi text-dark bi-currency-rupee"></i>
                      <span class="total_amount">{{sprintf('%.2f', $total)}}</span>
                    </th>
                  </tr>
                </tbody>
              </table>
              {{--<div class="custom-control custom-switch my-2">
                <div class="d-flex">
                  <input type="checkbox" name="terms_conditionn" class="form-check-input" id="terms_conditionn">
                  <label for="terms_conditionn" class="custom-control-label ms-2 d-flex align-items-center">
                    <a target="_blank" href="{{url('terms-conditions')}}">Terms & Conditions</a>
                  </label>
                </div>
              </div>--}}
              <a class="main_button" onclick="validateAndSubmitOrderForm(this)" type="button">Place Order</a>
            </div>
            <!-- amount summary end -->
          </div>
        </div>
      @else
        <div class="text-danger">Your Cart Is Empty. Please Add Product To Cart.</div>
      @endif
    </div>
  </div>
</section>




  <!-- By now form Modal start -->
  <div class="modal fade " id="shareDetailsModal" tabindex="-1" aria-labelledby="shareDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-md mt-5 pt-5">
      <div class="modal-content report_popup">
        @if(count($cartItemFormData) > 0)
          <div class="sticky-sidebar p-0">
            <div class="card bg-white border-0 puja-form-card">
              <!-- Form Header -->
              <div class="puja-form-header" id="headingOne">
                <button type="button" class="btn-close puja-form-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="puja-form-header-content">
                    @php
                      $todayDate = date("Y-m-d");
                      $productPrice = $product ? $product->base_price : 0;
                      $productSpecialPrice = $product ? $product->sale_price : 0;
                      $productSpeicalPriceFromDate = $product ? $product->sale_price_start_date : null;
                      $productSpeicalPriceToDate = $product ? $product->sale_price_end_date : null;
                      $finalPrice = ($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate)) ? $productSpecialPrice : $productPrice;
                      $maxOrderQty = $product ? $product->max_order_qty : 1;
                      $personsText = $maxOrderQty > 1 ? $maxOrderQty . ' persons' : '1 person';
                    @endphp
                    <h4 class="puja-form-title text-truncate" style="max-width:160px;">{{ $productTitle }}</h4>
                    <p class="puja-form-persons mb-0">{{ $personsText }}</p>
                    <p class="puja-form-price">₹{{ number_format($finalPrice, 2) }}</p>
                  </div>
                  <div class="puja-form-header-image">
                    <img src="{{ $productImage ? $productImage->image(80,80) : no_image() }}" alt="{{ $productTitle }}" class="puja-header-img">
                  </div>
                </div>
              </div>
              <!-- Form Body -->
              <div class="puja-form-body">
                <form id="productField" onsubmit="return addToCartForm(this)" method="post">
                  <input type="hidden" name="item_id" value="{{ $product->id }}">
                  <div class="row">
                    @php
                      $index = -1;
                      $productFormFields = $product->fields;
                      $isOneToOnePuja = $product && $product->type == 2 && $product->is_one_to_one_puja == 1;
                      $genderNames = ["gender", "p1_gender", "p2_gender"];
                      $placesNames = ["birth_place", "p1_birth_place", "p2_birth_place"];

                      // For one-to-one puja, get name value from form data
                      $oneNameValue = "";
                      if($isOneToOnePuja && is_array($cartItemFormData) && !empty($cartItemFormData)) {
                        foreach($cartItemFormData as $formField) {
                          if(is_array($formField) && isset($formField["name"]) && strtolower(trim($formField["name"])) == "name") {
                            $oneNameValue = isset($formField["value"]) ? $formField["value"] : "";
                            break;
                          }
                        }
                      }

                      // Count fields in "other all form" section (excluding P1 and P2)
                      $otherFieldsCount = 0;
                      foreach($productFormFields as $field) {
                        if($field->field) {
                          $fieldLabel = $field->field->form_label;
                          if(!str_contains($fieldLabel, "P1") && !str_contains($fieldLabel, "P2")) {
                            $otherFieldsCount++;
                          }
                        }
                      }

                      // Get gotra value from form data
                      $gotraValue = "";
                      $gotraUnknownChecked = false;
                      // Check both array format and object format
                      if(is_array($cartItemFormData) && !empty($cartItemFormData)) {
                        // Method 1: Check if gotra is stored with string key
                        if(isset($cartItemFormData["gotra"])) {
                          $gotraData = $cartItemFormData["gotra"];
                          if(is_array($gotraData) && isset($gotraData["value"])) {
                            $gotraValue = $gotraData["value"];
                            if($gotraValue == "I do not know my gotra") {
                              $gotraUnknownChecked = true;
                              $gotraValue = "";
                            }
                          } elseif(is_string($gotraData)) {
                            $gotraValue = $gotraData;
                            if($gotraValue == "I do not know my gotra") {
                              $gotraUnknownChecked = true;
                              $gotraValue = "";
                            }
                          }
                        }

                        // Method 2: Loop through entire array to find gotra by name (if not found above)
                        if(empty($gotraValue) && !$gotraUnknownChecked) {
                          foreach($cartItemFormData as $key => $formDataItem) {
                            // Check if this item has name "gotra"
                            if(is_array($formDataItem)) {
                              if(isset($formDataItem["name"]) && strtolower(trim($formDataItem["name"])) == "gotra") {
                                $gotraValue = isset($formDataItem["value"]) ? $formDataItem["value"] : "";
                                if($gotraValue == "I do not know my gotra") {
                                  $gotraUnknownChecked = true;
                                  $gotraValue = "";
                                }
                                break;
                              }
                            }
                            // Also check if key itself is "gotra" (case-insensitive)
                            if(is_string($key) && strtolower(trim($key)) == "gotra") {
                              if(is_array($formDataItem) && isset($formDataItem["value"])) {
                                $gotraValue = $formDataItem["value"];
                                if($gotraValue == "I do not know my gotra") {
                                  $gotraUnknownChecked = true;
                                  $gotraValue = "";
                                }
                                break;
                              } elseif(is_string($formDataItem)) {
                                $gotraValue = $formDataItem;
                                if($gotraValue == "I do not know my gotra") {
                                  $gotraUnknownChecked = true;
                                  $gotraValue = "";
                                }
                                break;
                              }
                            }
                          }
                        }
                      }
                    @endphp
                    @if($isOneToOnePuja)
                      <!-- One-to-One Puja Form Fields -->
                      <div class="col-12 mb-2">
                        <div class="form-group">
                          <label for="one_name" class="form-label fw-semibold mb-1">Name</label>
                          <input type="text" name="one_name" id="one_name" class="form-control form-control-lg" required value="{{ htmlspecialchars($oneNameValue, ENT_QUOTES, 'UTF-8') }}">
                        </div>
                      </div>
                      <div class="col-12 mb-2">
                        <div class="form-group">
                          <label for="one_gotra" class="form-label fw-semibold mb-1">Gotra</label>
                          <div class="row">
                            <div class="col-6">
                              <input type="text" name="one_gotra" id="one_gotra" class="form-control form-control-lg" placeholder="Enter Gotra" value="{{ htmlspecialchars($gotraValue, ENT_QUOTES, 'UTF-8') }}" {{ $gotraUnknownChecked ? 'disabled' : '' }}>
                            </div>
                            <div class="col-6">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="one_gotra_unknown" name="one_gotra_unknown" value="I do not know my gotra" {{ $gotraUnknownChecked ? 'checked' : '' }} onchange="if(this.checked){$('#one_gotra').val('I do not know my gotra').prop('disabled',true);}else{$('#one_gotra').val('').prop('disabled',false);}">
                                <label class="form-check-label" for="one_gotra_unknown" style="font-size: 14px;">
                                  I do not know my gotra
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 mb-2">
                        <div class="form-group">
                          <div class="form-check">
                            @php
                              $ashirwadChecked = false;
                              if(is_array($cartItemFormData) && !empty($cartItemFormData)) {
                                foreach($cartItemFormData as $formField) {
                                  if(is_array($formField) && isset($formField["name"]) && strtolower(trim($formField["name"])) == "ashirwad_box" && isset($formField["value"]) && $formField["value"] == 1) {
                                    $ashirwadChecked = true;
                                    break;
                                  }
                                }
                              }
                            @endphp
                            <input class="form-check-input" type="checkbox" id="one_ashirwad" name="one_ashirwad" value="1" {{ $ashirwadChecked ? 'checked' : '' }}>
                            <label class="form-check-label" for="one_ashirwad" style="font-size: 14px;">
                              Ashirwad Box
                            </label>
                          </div>
                        </div>
                      </div>
                    @else
                      <!-- Normal Puja Form Fields -->
                    @php
                    $gotraIndex = count($productFormFields) * $product->max_order_qty;
                    @endphp
                    @for($i = 1; $i <= $product->max_order_qty; $i++)
                      @if($product->max_order_qty > 1)
                        <!-- <div class="col-12"><h6 class="fw m-0 mt-2">{{ $i }}. Details</h6></div> -->
                      @endif
                      @php
                        $p1Heading = false;
                        $p2Heading = false;
                      @endphp

                      @foreach($productFormFields as $indexx => $field)
                        @if($formField = $field->field)
                          @php
                            $index++;
                            $type = $formField->form_type;
                            $label = $formField->form_label;
                            $name = strtolower(str_replace(' ', '_', $label));
                            $required = $formField->isrequired;
                            if($i > $product->min_order_qty){
                              $required = 0;
                            }
                            $formFieldArray = [
                              "type" => $type,
                              "label" => $label,
                              "name" => $name,
                            ];
                            $formDataArray[] = $formFieldArray;

                            $value = "";
                            if(isset($cartItemFormData[$index])){
                              $cartItemFormField = $cartItemFormData[$index];
                              if(isset($cartItemFormField["value"])){
                                $value = $cartItemFormField["value"];
                                $newCartItemFormData[] = $cartItemFormField;
                              }
                            }
                          @endphp

                          @if(str_contains($formField->form_label, "P1"))
                            <!-- boy form -->
                            @if(!$p1Heading)
                              @php
                                $p1Heading = true;
                              @endphp
                              <div class="col-6">
                                <div class="row">
                                  <div class="col-12 d-flex align-items-center mb-2">
                                    <img src="{{ asset('assets/images/icons/man.png') }}" width="30px" height="30px" alt="man">
                                    <h6 class="fw m-0 mt-2 ps-1">Boy Details</h6>
                                  </div>
                            @endif

                            @if($type == "select" && $name == "p1_gender")
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                  <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                    @if(in_array($name, $genderNames))
                                      <option value="">Select</option>
                                      <option value="male" {{ $value == "male" ? "selected" : "" }}>Male</option>
                                      <option value="female" {{ $value == "female" ? "selected" : "" }}>Female</option>
                                      <option value="other" {{ $value == "other" ? "selected" : "" }}>Other</option>
                                    @endif
                                </select>
                                </div>
                              </div>
                            @elseif($type == "search")
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                  @if(in_array($name, $placesNames))
                                    <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}_search" onkeyup='locationPlaces("#{{ $name }}_search", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} value="{{ $value }}">
                                  @endif
                                  <div class="suggestion_birth">
                                    <ul id="{{ $name }}_suggestions"></ul>
                                  </div>
                                  <input type="hidden" name="{{ $name }}" id="{{ $name }}" {{ $required ? "required" : "" }} value="{{ $value }}">
                                </div>
                              </div>
                            @else
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                  @if($type == 'time')
                                    <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                  @endif
                                  <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1" value="{{ $value }}">
                                </div>
                              </div>
                            @endif
                          @elseif(str_contains($formField->form_label, "P2"))
                            <!-- girl form -->
                            @if(!$p2Heading)
                              @php
                                $p2Heading = true;
                                $p2FormEnd = false;
                              @endphp
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="row">
                                  <div class="col-12 d-flex align-items-center mb-2">
                                    <img src="{{ asset('assets/images/icons/woman.png') }}" width="30px" height="30px" alt="woman">
                                    <h6 class="fw m-0 mt-2 ps-1">Girl Details</h6>
                                  </div>
                            @endif

                            @if($type == "select" && $name == "p2_gender")
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                  <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                    @if(in_array($name, $genderNames))
                                      <option value="">Select</option>
                                      <option value="male" {{ $value == "male" ? "selected" : "" }}>Male</option>
                                      <option value="female" {{ $value == "female" ? "selected" : "" }}>Female</option>
                                      <option value="other" {{ $value == "other" ? "selected" : "" }}>Other</option>
                                    @endif
                                </select>
                                </div>
                              </div>
                            @elseif($type == "search")
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                  @if(in_array($name, $placesNames))
                                    <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}_search" onkeyup='locationPlaces("#{{ $name }}_search", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} value="{{ $value }}">
                                  @endif
                                  <div class="suggestion_birth">
                                    <ul id="{{ $name }}_suggestions"></ul>
                                  </div>
                                  <input type="hidden" name="{{ $name }}" id="{{ $name }}" {{ $required ? "required" : "" }} value="{{ $value }}">
                                </div>
                              </div>
                            @else
                              <div class="col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                  @if($type == 'time')
                                    <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                  @endif
                                  <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1" value="{{ $value }}">
                                </div>
                              </div>
                            @endif
                            @if($productFormFields->count()-1 == $indexx && isset($p2FormEnd) && $p2FormEnd == false)
                              </div>
                            </div>
                              @php
                                $p2FormEnd = true;
                              @endphp
                            @endif
                          @else
                            <!-- other all form -->
                            @if(isset($p2FormEnd) && $p2FormEnd == false)
                              </div>
                            </div>
                              @php
                                $p2FormEnd = true;
                              @endphp
                            @endif

                            @if($type == "select" && $name == "language")
                              <div class="col-6 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], [" ", "Girl"], $label) }}</label>
                                  <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                    <option value="">Select</option>
                                    <option value="english" {{ $value == "english" ? "selected" : "" }}>English</option>
                                    <option value="hindi" {{ $value == "hindi" ? "selected" : "" }}>Hindi</option>
                                  </select>
                                </div>
                              </div>
                            @elseif($type == "select" && $name == "gender")
                              <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                  <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                    @if(in_array($name, $genderNames))
                                      <option value="">Select</option>
                                      <option value="male" {{ $value == "male" ? "selected" : "" }}>Male</option>
                                      <option value="female" {{ $value == "female" ? "selected" : "" }}>Female</option>
                                      <option value="other" {{ $value == "other" ? "selected" : "" }}>Other</option>
                                    @endif
                                </select>
                                </div>
                              </div>
                            @elseif($type == "search")
                              <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                  @if(in_array($name, $placesNames))
                                    <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}_search" onkeyup='locationPlaces("#{{ $name }}_search", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} value="{{ $value }}">
                                  @endif
                                  <div class="suggestion_birth">
                                    <ul id="{{ $name }}_suggestions"></ul>
                                  </div>
                                  <input type="hidden" name="{{ $name }}" id="{{ $name }}" {{ $required ? "required" : "" }} value="{{ $value }}">
                                </div>
                              </div>
                            @else
                              @php
                                // Check if this is name field and if it's the only field
                                $isNameField = (stripos($label, 'name') !== false || $name == 'name' || $name == 'full_name' || $name == 'first_name');
                                $colClass = 'col-md-6 col-6';
                                if($isNameField && $otherFieldsCount == 1) {
                                  $colClass = 'col-6';
                                }
                              @endphp
                              <div class="{{ $colClass }} mb-2">
                                <div class="form-group">
                                  <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                  @if($type == 'time')
                                    <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                  @endif
                                  <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1" value="{{ $value }}">
                                </div>
                              </div>
                            @endif
                          @endif

                        @endif
                      @endforeach
                    @endfor

                    <!-- Gotra Field -->
                    <div class="col-12 mb-3">
                      <div class="form-group">
                        <label for="gotra" class="form-label fw-semibold mb-1">Gotra</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="text" name="gotra" id="gotra" class="form-control form-control-lg" placeholder="Enter Gotra" value="{{ htmlspecialchars($gotraValue, ENT_QUOTES, 'UTF-8') }}" {{ $gotraUnknownChecked ? 'disabled' : '' }} oninput='addFormData(this, {"type":"text","label":"Gotra","name":"gotra"}, "{{ $gotraIndex }}")'>
                          </div>
                          <div class="col-6">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gotra_unknown" name="gotra_unknown" value="I do not know my gotra" {{ $gotraUnknownChecked ? 'checked' : '' }} onchange="handleGotraCheckbox(this)">
                              <label class="form-check-label" for="gotra_unknown" style="font-size: 14px;">
                                I do not know my gotra
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @if(!$isOneToOnePuja)
                      @php
                        // Ensure gotra is included in newCartItemFormData before saving
                        $gotraFoundInNewArray = false;
                        foreach($newCartItemFormData as $field) {
                          if(is_array($field) && isset($field['name']) && strtolower(trim($field['name'])) === 'gotra') {
                            $gotraFoundInNewArray = true;
                            break;
                          }
                        }

                        // If gotra is not in newCartItemFormData but exists in cartItemFormData, add it
                        if(!$gotraFoundInNewArray && !empty($gotraValue)) {
                          $newCartItemFormData[] = [
                            'type' => 'text',
                            'label' => 'Gotra',
                            'name' => 'gotra',
                            'value' => $gotraValue
                          ];
                        } elseif(!$gotraFoundInNewArray && $gotraUnknownChecked) {
                          // Even if gotra is unknown, we should save it
                          $newCartItemFormData[] = [
                            'type' => 'text',
                            'label' => 'Gotra',
                            'name' => 'gotra',
                            'value' => 'I do not know my gotra'
                          ];
                        } elseif(!$gotraFoundInNewArray && is_array($cartItemFormData)) {
                          // Try to find gotra in original cartItemFormData and add it
                          foreach($cartItemFormData as $field) {
                            if(is_array($field) && isset($field['name']) && strtolower(trim($field['name'])) === 'gotra') {
                              $newCartItemFormData[] = $field;
                              break;
                            }
                          }
                        }

                        $firstCartItem->formdata = json_encode($newCartItemFormData);
                        $firstCartItem->save();
                      @endphp
                    @endif
                    @endif
                  </div>
                  <div class="puja-form-submit-wrapper">
                    <button type="submit" class="btn puja-book-btn" id="service-submit-btn">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endif
    </div>
  </div>
</div>
<!-- By now form Modal end -->

@endsection
@push('scripts')
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  $(document).ready(function(){
    $('.timepicker').mdtimepicker({
      timeFormat:'hh:mm:ss',
      format:'h:mm tt',
      theme:'pink',
      clearBtn:true
    }).on("timechanged", function(e){
      (this.oninput)()
    });
  });
  function handleTimePickerIcon(that){
    $(that).click();
  }
</script>
<script>
  var itemType = "{{ $item_type }}";
  var selecteAddressId = "{{ $selecteAddressId }}";
  var subTotalWithDiscount = "{{ $subTotalWithDiscount }}";
  subTotalWithDiscount = Number(subTotalWithDiscount);
  var couponCode = '';
  var couponAmount = 0;
  var taxesAmount = "{{ $taxes }}";
  taxesAmount = Number(taxesAmount);
  var shippingAmount = "{{ $shippingPrice }}";
  shippingAmount = Number(shippingAmount);
  var remediesReferralDiscount = Number("{{ $remediesReferralDiscount ?? 0 }}");
  var totalAmount = Number("{{ $total }}");
  var rzp1;
  let orderId = Number("{{ $orderId }}");

  function applyCouponCode(type, error=1){
    $('#coupon-apply-btn').prop('disabled', true);
    couponCode = '';
    let couponCodeValue = $('#coupon_code').val();
    $('.coupon_amount').text('').parent().parent().addClass('d-none');
    $('.taxes_amount').text(Number(taxesAmount).toFixed(2));
    $('.total_amount').text(Number(totalAmount).toFixed(2));
    $('#coupon_id').val('');
    $.ajax({
        type: 'POST',
        url: "{{ route('apply.coupon') }}",
        data: {
          type:type,
          coupon_code:couponCodeValue,
          item_type:itemType,
          cartitemid:"{{ $cartitemid }}",
        },
        success: function(response){
          toastr.success(response.message);
          couponAmount = Number(response.discount) || 0;
          $('.coupon_amount').text(couponAmount.toFixed(2)).parent().parent().removeClass('d-none');
          var taxAmountAfterCoupon = Number(response.taxes) || 0;
          $('.taxes_amount').text(taxAmountAfterCoupon.toFixed(2));
          var totalAmountAfterCoupon = (subTotalWithDiscount - couponAmount - remediesReferralDiscount) + shippingAmount + taxAmountAfterCoupon;
          totalAmountAfterCoupon = Number(totalAmountAfterCoupon.toFixed(2));
          $('.total_amount').text(totalAmountAfterCoupon.toFixed(2));
          totalAmount = totalAmountAfterCoupon;
          couponCode = response.coupon_code;
          $('#coupon_id').val(couponCode);
          $('#coupon-remove-btn').removeClass('d-none');
          $('#coupon-apply-btn').addClass('d-none');
          $('#coupon_code').prop('readonly', true);
      },
      error: function(error){
        $('#coupon-apply-btn').prop('disabled', false);
        let errors = error.responseJSON.errors;
        if(errors){
          for(let key in errors){
            toastr.error(errors[key][0]);
          }
        }else{
          let message = error.responseJSON.message;
          if(message){
            toastr.error(message);
          }
        }
      }
    });
  }

  function removeCouponCode(){
    $('#coupon_code').val('');
    couponCode = '';
    couponAmount = 0;
    $('#coupon_id').val('');
    $('.coupon_amount').text('').parent().parent().addClass('d-none');
    $('.taxes_amount').text(Number(taxesAmount).toFixed(2));
    totalAmount = (subTotalWithDiscount - remediesReferralDiscount) + shippingAmount + taxesAmount;
    $('.total_amount').text(totalAmount.toFixed(2));
    $('#coupon_code').prop('readonly', false);
    $('#coupon-remove-btn').addClass('d-none');
    $('#coupon-apply-btn').prop('disabled', false).removeClass('d-none');
  }

  function validateAndSubmitOrderForm(that){
    let button = $(that);
    let billing_address_full_name = $("#billing_address_full_name").val();
    let billing_address = $("input[name=billing_address]:checked").val();
    let billing_address_email = $("#billing_address_email").val();
    let billing_address_phone = $("#billing_address_phone").val();
    let billing_address_address = $("#billing_address_address").val();
    let billing_address_country = $("#billing_address_country_id").val();
    let billing_address_state = $("#billing_address_state_id").val();
    let billing_address_city = $("#billing_address_city_id").val();
    let billing_address_pincode = $("#billing_address_pincode").val();
    let payment_option = $("input[name=payment_option]:checked").val();

    // Get ashirwad_box value from hidden field(s)
    let ashirwadBoxValue = 0;
    $('input[id^="ashirwad_box_value_"]').each(function() {
      let value = parseInt($(this).val()) || 0;
      if(value == 1) {
        ashirwadBoxValue = 1;
        return false; // Break loop
      }
    });

    button.prop("disabled", true).text("Placing...");
    // Get CSRF token from meta tag (more reliable after login)
    let csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
    $.ajax({
        type: 'POST',
        url: "{{ route('placeorder') }}",
        data: {
          order_id:orderId,
          address_id:selecteAddressId,
          item_type:itemType,
          coupon_code:couponCode,
          billing_address_full_name:billing_address_full_name,
          billing_address:billing_address,
          billing_address_email:billing_address_email,
          billing_address_phone:billing_address_phone,
          billing_address_address:billing_address_address,
          billing_address_country:billing_address_country,
          billing_address_state:billing_address_state,
          billing_address_city:billing_address_city,
          billing_address_pincode:billing_address_pincode,
          payment_option:payment_option,
          ashirwad_box:ashirwadBoxValue,
          cartitemid:"{{ $cartitemid }}",
          user_temp_cart_id:"{{ $user_temp_cart_id }}",
          _token: csrfToken
        },
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response){
          orderId = response.order_id ? response.order_id : 0;
          button.prop("disabled", false).text("Order Placed");
          let next = response.next;
          if(next == 'payment'){
            rzp1 = new Razorpay(response.paymentRequestData);
            makePayment();
          }else if(next == 'ordered'){
            window.location.href = "{{route('thank.you')}}";
          }
        },
        error: function(error){
          button.prop("disabled", false).text("Place Order");
          error = error.responseJSON.errors;
          for(var key in error){
            toastr.error(error[key][0]);
          }
        }
    });
    return false;
  }

  function makePayment(){
    rzp1.open();
  }

  function fetchStates (options, that, id, value=0){
    let data = '';
    let url = '{{route('locations')}}';
    if(options == 'states'){
      data += `<option value="" selected>Select State</option>`
      url += '/master_states/?country_id='+ $(that).val();
    }else if(options == 'cities'){
      data += `<option value="">Select City</option>`;
      url += '/master_cities/?state_id='+ $(that).val();
    }
    $.ajax({
        type: "GET",
        url: url, // Laravel route for verifying Razorpay payment
        success: function(response) {
          let i = 0;
          for(let item in response){
            if(i){
              data += `<option value="${item}">${response[item]}</option>`
            }
            i++;
          }
          $('#'+id).html('');
          $('#'+id).append(data);
          if(value){
            $('#'+id).find('option[value='+value+']').prop('selected', true);
          }

        },
        error: function(error) {
            console.log("fetch states by country:", error);
        }
    });
  }
</script>

<script type="text/javascript">
  function addEditAddressModal(action, value){
    $('#id').val(value?value.id:0);
    $('#new_full_name').val(value?value.name:'');
    $('#new_email').val(value?value.email:'');
    $('#new_phone').val(value?value.phone:'');
    $('#new_country_id').val(value?value.country_id:101);
    $('#new_state_id').val(value?value.state_id:'');
    $('#new_city_id').val(value?value.city_id:'');
    $('#new_zipcode').val(value?value.zipcode:'');
    $('#new_address').val(value?value.address:'');
    $('#add_edit_delivery_address_div').removeClass('d-none').find("h2").text(value ? 'Edit deliver address' : 'Add delivery address');
    fetchStates('cities', $('#new_state_id'), 'new_city_id', (value ? value.city_id : 0));
  }

  function selectAddress(urlWithAddressId){
    if(orderId > 0){
      urlWithAddressId = `${urlWithAddressId}&order_id=${orderId}`;
      window.location.href = urlWithAddressId;
    }else{
      window.location.href = urlWithAddressId;
    }
  }

  function handleAddresses(that){
    $(that).find('button[type=submit]').prop('disabled', true);
    let formData = new FormData(that);
    $.ajax({
        url: "{{route('user.addresses')}}",
        method: "POST",
        data:formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
        },
        processData:false,
        contentType:false,
        dataType:false,
        success: function(response){
          toastr.success(response.message);
          setTimeout(function(){
            // Checkout page par address add/update ke baad isi page par hi rehne dena
            // Chahe controller redirectTo bheje, hum yahan sirf current checkout ko reload karenge
            window.location.reload();
          }, 1500);
        },
        error: function(error){
          $(that).find('button[type=submit]').prop('disabled', false);
          let errors = error.responseJSON.errors;
          if(errors){
            for(var key in errors){
              toastr.error(errors[key][0]);
            }
          }
          console.log(error);
        }
    });
    return false;
  }

  let formDataArray = @json($cartItemFormData ?? []);
  let productTypeValue = @json($productTypeValue);

  // Initialize gotra field on page load if value exists
  $(document).ready(function() {
    @if(!empty($gotraValue) || $gotraUnknownChecked)
      @if($gotraUnknownChecked)
        $('#gotra_unknown').prop('checked', true);
        $('#gotra').val('I do not know my gotra').prop('disabled', true);
        // Ensure gotra is in formDataArray
        updateGotraInFormData('I do not know my gotra');
      @elseif(!empty($gotraValue))
        $('#gotra').val(@json($gotraValue));
        // Ensure gotra is in formDataArray
        updateGotraInFormData(@json($gotraValue));
      @endif
    @endif

    // Debug: Log formDataArray to check gotra
    console.log('FormDataArray on load:', formDataArray);
    console.log('Gotra value from PHP:', @json($gotraValue));
    console.log('Gotra unknown checked:', @json($gotraUnknownChecked));
  });

  function addFormData (that, jsonObject, index){
    let value = $(that).val();
    if(value){
      jsonObject.value = value;
    }
    formDataArray[index] = jsonObject;
    console.log(formDataArray);
  }

  // Function to handle gotra input
  function handleGotraInput(input) {
    let gotraValue = $(input).val();
    updateGotraInFormData(gotraValue);
  }

  // Function to update gotra in formDataArray
  function updateGotraInFormData(gotraValue) {
    let gotraExists = false;
    for(let i = 0; i < formDataArray.length; i++) {
      if(formDataArray[i] && formDataArray[i].name === 'gotra') {
        formDataArray[i].value = gotraValue;
        gotraExists = true;
        break;
      }
    }
    if(!gotraExists && gotraValue) {
      formDataArray.push({
        type: "text",
        label: "Gotra",
        name: "gotra",
        value: gotraValue
      });
    }
  }

  // Function to handle gotra checkbox
  function handleGotraCheckbox(checkbox) {
    if($(checkbox).is(':checked')) {
      $('#gotra').val('I do not know my gotra').prop('disabled', true);
      // Add to formDataArray
      updateGotraInFormData('I do not know my gotra');
    } else {
      $('#gotra').val('').prop('disabled', false);
      // Clear value from formDataArray
      for(let i = 0; i < formDataArray.length; i++) {
        if(formDataArray[i] && formDataArray[i].name === 'gotra') {
          formDataArray[i].value = '';
          break;
        }
      }
    }
  }

  var typingTimer;
  function locationPlaces(that, suggestionId, inputId, jsonObject, index){
    $(inputId).val("");
    addFormData(inputId, jsonObject, index);
    clearTimeout(typingTimer);
    typingTimer=setTimeout(function(){
      var inputVal = $(that).val(); // Get the value of the input field
      if (inputVal.length > 2) { // Make sure input is at least 3 characters
        $(suggestionId).html('<li>Searching...</li>');
        $.ajax({
          url: '/search-location/' + encodeURIComponent(inputVal), // Make the GET request
          method: 'GET',
          success: function(response) {
            $(suggestionId).empty(); // Clear previous suggestions
            response.forEach(function(location) {
              var li = $('<li class="pointer"></li>'); // Create a new list item
              li.text(location.state);
              li.on('click', function(){
                  $(that).val($(this).text()); // Set clicked place in input
                  $(inputId).val($(this).text()); // Set clicked place in input
                  $(suggestionId).empty(); // Clear suggestions after selection
                  addFormData(inputId, jsonObject, index);
              });
              $(suggestionId).append(li); // Append the list item to the suggestion box
            });
          },
          error: function(xhr, status, error) {
            // console.error(error);
            $(suggestionId).html('');
          }
        });
      } else {
        $(suggestionId).empty(); // Clear suggestions if input is too short
      }
    }, 1000);
  }

  function addToCartForm(that){
    let _form = $(that);
    let item_id = _form.find("input[name=item_id]").val();

    // Handle gotra field
    let gotraValue = '';
    if($('#gotra_unknown').is(':checked')) {
      gotraValue = 'I do not know my gotra';
    } else {
      gotraValue = $('#gotra').val();
    }

    // Add gotra to formDataArray if not already present
    let gotraExists = false;
    for(let i = 0; i < formDataArray.length; i++) {
      if(formDataArray[i] && formDataArray[i].name === 'gotra') {
        formDataArray[i].value = gotraValue;
        gotraExists = true;
        break;
      }
    }
    if(!gotraExists && gotraValue) {
      formDataArray.push({
        type: "text",
        label: "Gotra",
        name: "gotra",
        value: gotraValue
      });
    }

    _form.find("button[type=submit]").prop("disabled", true);
    $.ajax({
        url: "{{ route('cart.addform') }}",
        method: "POST",
        data: {
          item_id:item_id,
          product_type_value:productTypeValue,
          type:"shop",
          form_data:formDataArray,
        },
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response){
          if(response.status){
            toastr.success(response.message);
          }
          if(response.redirectTo){
            window.location.href = response.redirectTo;
          }
        },
        error: function(error){
          console.log(error);
        }
    });
    return false;
  };
  document.addEventListener("DOMContentLoaded", function () {
    const billingRadios = document.querySelectorAll("input[name='billing_address']");
    const billingFormBox = document.querySelector(".billing-form-box");
    function toggleBillingForm(){
      const selected = document.querySelector("input[name='billing_address']:checked").value;
      if (selected === "different") {
        billingFormBox.classList.remove("d-none");
      } else {
        billingFormBox.classList.add("d-none");
      }
    }
    billingRadios.forEach(radio => {
      radio.addEventListener("change", toggleBillingForm);
    });
    toggleBillingForm();
  });

</script>


@endpush
