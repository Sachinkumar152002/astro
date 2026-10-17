@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
@include(themeView().'astrologers._partials.top')
<section class="mt-3 mb-5">
  <div class="container">
  <div class="row justify-content-between">
  @include(themeView().'astrologers._partials.sidebar')
    <div class="col-lg-9 col-12">


      <div class="row mb-3 recent">
        <div class="col-lg-12">
          <h4>
          Recent Courses
          </h4>
        </div>
          @if(isset($orders) && count($orders))
            @foreach($orders as $orderKey => $order)
              @foreach($order->orderItems as $itemKey => $orderItem)
                @if($orderItem->type == 'course')
<div class="card mb-2 ">
  <div class="row align-items-center">
    <div class="col-lg-3 col-12">
      <img src="{{ $orderItem->course && $orderItem->course->image ? $orderItem->course->image->image(250, 250) : 'assets/image/default.png' }}" class="img-fluid w-100 h-100" alt="{{$orderItem->course->name}}" alt="test1">
    </div>
    <div class="col-lg-6 col-12">
      <h5>{{ $orderItem->course->name }}</h5>
      <div class=""> Price: <del class="actual_price fs-7 text fw-bold" tabindex="0"> ₹{{ $orderItem->quantity*$orderItem->price }}</del>
        <span class="ms-2 sale_price text-left fs-7 text fw-bold text-ac-price" tabindex="0">₹1,200</span>
      </div>
      <ul class="p-0 d-flex flex-wrap mt-2 gap-2">
        <li class="list-group-item">
          <i class="bi bi-eye text-blue"></i>
          <a href="{{route('course.handle', [$orderItem->course->slug])}}" class="text-blue fw-bold underline">View Details</a>
        </li>
      </ul>
    </div>
    <div class="col-lg-3 col-12 d-flex justify-content-center align-items-center flex-column">
      <a href="/astrologer/courseexplore">
        <button class="btn btn-secondary">Explore Course</button>
      </a>
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
</section>
@endsection
