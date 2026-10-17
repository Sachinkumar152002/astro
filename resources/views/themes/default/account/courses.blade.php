@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
<section class="mt-3 mb-5">
  <div class="container">
  <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
      <div class="row my-3 recent">
        <div class="col-lg-12">
          <h4>
          Recent Courses
          </h4>
        </div>
        <div class="col-lg-12 col-6 dc-c-card">
          @if(isset($orders) && count($orders))
            @foreach($orders as $orderKey => $order)
              @foreach($order->orderItems as $itemKey => $orderItem)
                @if($orderItem->type == 'course')
                  <div class="card mb-2 d-c-card">
                    <div class="row align-items-center">
                      <div class="col-lg-3 col-12">
                        <img src="{{ $orderItem->course && $orderItem->course->image ? $orderItem->course->image->image(250, 250) : 'assets/image/default.png' }}" alt="{{$orderItem->course->name}}">
                      </div>
                      <div class="col-lg-6 col-12">
                        <h5 class="c-c-text-new">{{ $orderItem->course->name }}</h5>
                        <div class="grid d-flex flex-wrap ">
                          <div class="g-col-12">
                            <span><b>Classes:</b> {{$orderItem->course->total_class}} Classes (Monday, Tuesday, Wednesday, Friday)</span>
                          </div>
                          <div class="g-col-6">
                            <span><b>Date:</b> Started on 05-08-2024</span>
                          </div>
                          <div class="g-col-6">
                            <span><b>Time:</b>  11:00 AM - 12:30 PM</span>
                          </div>
                        </div>
                        <ul class="p-0 d-flex flex-wrap mt-2 gap-2">
                          <li class="list-group-item">
                          <i class="bi bi-calendar-date"></i> Order placed
                          {{Illuminate\Support\Carbon::parse($orderItem->created_at)->format('d-m-Y')}}
                          </li>
                          <li class="list-group-item">
                          <i class="bi bi-bookmark"></i> Order #{{$order->order_number}}
                          </li>
                          @if($orderItem->course->status)
                            <li class="list-group-item">
                              <i class="bi bi-eye"></i> <a href="{{route('course.handle', [$orderItem->course->slug])}}">View Details</a>
                            </li>
                          @endif
                        </ul>
                      </div>
                      <div class="col-lg-3 col-12 d-flex justify-content-center align-items-center flex-column">
                        <h5 class="text-center">Price: ₹{{ $orderItem->quantity*$orderItem->price }}</h5>
                        <button class="btn btn-secondary text-black">Explore Course</button>
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
