@extends(themeView().'layout')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-4">
            <div class="card bg-primary text-center text-white pt-3 mb-1">
              <i class="fa fa-phone-volume fa-2x"></i>
              <p class="text-uppercase mt-3 text-center text-white"> {{$totalCalls}} Call Received</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item active">
                <h5 class="float-left"><i class="fa fa-phone-volume"></i> Recent Calls</h5>
                <a href="{{route('astrologer.calls')}}" class="btn btn-light float-right btn-sm">View All</a>
              </li>
            </ul>
          </div>
          <div class="col-sm-4 d-none">
            <div class="card bg-orange text-center text-white pt-3 mb-1">
              <i class="fa fa-comment-dots fa-2x"></i>
              <p class="text-uppercase mt-3 text-center text-white"> 0 Chat Received</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item bg-orange text-white">
                <h5 class="float-left"><i class="fa fa-comment-dots"></i> Recent Chats</h5>
                <a href="{{route('astrologer.chat')}}" class="btn btn-light float-right btn-sm">View All</a>
              </li>
            </ul>

          </div>
          <div class="col-sm-4">
            <div class="card bg-success text-center text-white pt-3 mb-1">
              <i class="fa fa-inr fa-2x"></i>
              <p class="text-uppercase mt-3 text-center text-white"> {{$totalCharge}} Revenue</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item bg-success text-white">
                <h5 class="float-left"><i class="fa fa-inr"></i> Recent Earnings</h5>
                <a href="{{route('astrologer.earnings')}}"class="btn btn-light float-right btn-sm">View All</a>
              </li>
            </ul>
          </div>
        </div>




      </div>
    </div>
  </div>
@endsection
