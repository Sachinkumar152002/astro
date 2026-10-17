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
              <h4>
              Recent Courses
              </h4>
            </div>
            <div class="col-lg-12">
              @if(isset($orders) && count($orders))
                @foreach($orders as $orderKey => $order)
                  @foreach($order->orderItems as $itemKey => $orderItem)
                    @if(($orderItem->type == 'course') && $orderItem->course && $orderItem->course->type != 1)
                      <div class="card mb-2 ">
                        <div class="row align-items-center">
                          <div class="col-lg-3 col-12">
                            <img src="{{ $orderItem->course && $orderItem->course->image ? $orderItem->course->image->image(150,150) : no_image() }}" alt="{{$orderItem->course->name}}" class="w-100 h-100 img-fluid">
                          </div>
                          <div class="col-lg-6 col-12">
                            <h5>{{ $orderItem->course->name }}</h5>
                            <div class="">
                              Price: <del class="actual_price fs-7 text fw-bold" tabindex="0">₹{{inrConvert($orderItem->quantity*$orderItem->unit_price)}}</del>
                              <span class="ms-2 sale_price text-left fs-7 text fw-bold text-ac-price" tabindex="0">₹{{inrConvert($orderItem->quantity*$orderItem->price)}}</span>
                           </div>
                            <ul class="p-0 d-flex flex-wrap mt-2 gap-2">
                              @if($orderItem->course->status)
                                <li class="list-group-item">
                                  <i class="bi bi-eye text-blue"></i> <a href="{{route('course.handle', [$orderItem->course->slug])}}" class="text-blue fw-bold underline">View Details</a>
                                </li>
                              @endif
                            </ul>
                          </div>
                          <div class="col-lg-3 col-12 d-flex justify-content-center align-items-center flex-column">
                            @if($orderItem->course->type==3)
                              <a href="{{route('astrologer.course.explore', [$orderItem->course->slug])}}">
                                <button class="btn btn-secondary">Explore Course</button></a>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                @endforeach
              @endif
            </div>
          </div>
        </div>
    </div>
  </div>

@endsection
