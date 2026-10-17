@extends(themeView().'layout')

@push('style')
<link src="{{asset('assets/css/')}}"></link>

@endpush
@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-4">
            <div class="card bg-primary text-center text-white pt-3 mb-1">
            <i class="bi bi-telephone-fill fs-2 text"></i>
              <p class="text-uppercase mt-3 text-center text-white"> {{$totalCalls}} Call Received</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item active">
                <h5 class="float-left text-dark"><i class="bi bi-telephone-fill"></i> Recent Calls</h5>
                <a href="{{route('astrologer.calls')}}" class="float-shadow mt-3 d-flex align-items-center justify-content-center btn btn-secondary d-block mx-auto">View All</a>
              </li>
            </ul>
          </div>
          <div class="col-sm-4">
            <div class="card bg-orange text-center text-white pt-3 mb-1">
            <i class="bi bi-chat-fill text-dark fs-2 text"></i>
              <p class="text-uppercase mt-3 text-center text-dark"> 0 Chat Received</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item text-white">
                <h5 class="float-left text-dark"><i class="bi bi-chat-fill"></i> Recent Chats</h5>
                <a href="{{route('astrologer.chat')}}" class="float-shadow mt-3 d-flex align-items-center justify-content-center btn btn-secondary d-block mx-auto">View All</a>
              </li>
            </ul>

          </div>
          <div class="col-sm-4">
            <div class="card bg-success text-center text-white pt-3 mb-1">
            <i class="bi bi-credit-card-fill fs-2 text"></i>
              <p class="text-uppercase mt-3 text-center text-white"> {{$totalCharge}} Revenue</p>
            </div>
            <ul class="list-group">
              <li class="list-group-item bg-success text-white">
                <h5 class="float-left text-dark"><i class="bi bi-credit-card-fill"></i> Recent Earnings</h5>
                <a href="{{route('astrologer.earnings')}}"class="float-shadow mt-3  d-flex align-items-center justify-content-center btn btn-secondary d-block mx-auto">View All</a>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script src="{{asset('assets/js/chartdata.js')}}"></script>
@endpush
