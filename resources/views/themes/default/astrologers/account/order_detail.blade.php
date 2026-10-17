@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}
<div class="container mt-3 mb-3">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12">
          <div class="row my-3 recent">
            <div class="col-lg-12">
              <h4>Order detail</h4>
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <h6 class="m-0">Ordered on {{date('d m Y', strtotime($order->created_at))}}</h6>
                  <h6 class="m-0 ms-2">#{{$order->order_number}}</h6>
                </div>
                <div class="">
                  <button type="button" name="button" class="btn btn-secondary mb-2">Invoice</button>
                  <button type="button" name="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal11">Check Status</button>
                </div>
              </div>
              <div class="o-details px-4 py-3">
                <div class="row">
                  <div class="col-md-4">
                    <h6>Shipping Address</h6>
                    <p>{{$order->address}}, {{$order->city_name?$order->city_name->name:''}}, {{$order->state_name?$order->state_name->name:''}} - {{$order->zipcode}} ({{$order->country_name?$order->country_name->name:''}})</p>
                  </div>
                  <div class="col-md-4">
                    <h6>Payment Methods</h6>
                    <p>{{ucfirst($order->payment_mode)}}</p>
                  </div>
                  <div class="col-md-4">
                    <h6>Order Summary</h6>
                    <table class="w-100">
                      <tr>
                        <td>item(s) subtotal</td>
                        <td>₹{{inrConvert($order->total_price)}}</td>
                      </tr>
                      <tr>
                        <td>Shipping</td>
                        <td>₹{{inrConvert($order->shipping_price)}}</td>
                      </tr>
                      <tr>
                        <td>Total</td>
                        <td>₹{{inrConvert($order->total_price + $order->shipping_price)}}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- <div class="col-md-7"> -->
                <div class="col-12 more-items">
                  <h4 class="mt-4">Order Items</h4>
                </div>
                <div class="card-box">
                  <div class="row">
                    @foreach($order->orderItems as $key => $item)
                    <div class="col-md-4 primium-n ps-0">
                      <div class="card border-0 p-card">
                        <img src="{{$item->image ? $item->image : no_image()}}" class="card-img-top" alt="Basic Astrology Course">
                        <div class="card-body">
                          <div class="name">
                            <img src="{{$item->image ? $item->image : no_image()}}" class="ins-img" alt="Instructor Image">
                            <h6 class="fw-bold c-h  4">{{$item->name}}</h6>
                          </div>
                          <div class="footer">
                            <div class="">
                              <del href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold" tabindex="0">{{inrConvert($item->unit_price)}}</del>
                              <span class="ms-2 sale_price text-left fs-7 text fw-bold text-ac-price" tabindex="0">₹{{inrConvert($item->price)}}</span>
                            </div>
                               <div class="d-flex align-items-center justify-content-between w-100 mt-2">
                                 <a href="{{$item->course?route('course.handle', [$item->course->slug]):($item->product?route('product.handle', [$item->product->slug]):'#')}}" tabindex="0">
                                    <button class="float-shadow btn btn-primary btn-sm me-2" tabindex="0">Write a Review</button>
                                  </a>
                                  <a href="{{$item->course?route('course.handle', [$item->course->slug]):($item->product?route('product.handle', [$item->product->slug]):'#')}}" tabindex="0">
                                  <button class="float-shadow btn btn-primary btn-sm me-2" tabindex="0">Reorder</button>
                                 </a>
                               </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
      </div>
    </div>
        <div class="modal fade modal-lg" id="exampleModal11" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="d-flex align-items-center justify-content-between w-100">
                      <div class="">
                        <h2 class="fs-6 m-0 p-0">Order Tracking</h2>
                      </div>
                      <div class="">
                        <button type="button" name="button" class="btn btn-secondary mb-2">Share</button>
                      </div>
                    </div>
                    <div class="col-12 col-md-12 hh-grayBox pt-3">
                      <h5 class="text-center mb-4">Status of your order</h5>
                      <div class="row justify-content-between">
                        <div class="order-tracking completed">
                          <span class="is-complete"></span>
                          <p>Ordered <br>
                            <span>Mon, June 24</span>
                          </p>
                        </div>
                        <div class="order-tracking">
                          <span class="is-complete"></span>
                          <p>Dispatch <br>
                            <span>Fri, June 28</span>
                          </p>
                        </div>
                        <div class="order-tracking">
                          <span class="is-complete"></span>
                          <p>Shipped <br>
                            <span>Tue, June 25</span>
                          </p>
                        </div>
                        <div class="order-tracking">
                          <span class="is-complete"></span>
                          <p>In-Transit <br>
                            <span>Fri, June 28</span>
                          </p>
                        </div>
                        <div class="order-tracking completed">
                          <span class="is-complete"></span>
                          <p>Delivered <br>
                            <span>Fri, June 28</span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
