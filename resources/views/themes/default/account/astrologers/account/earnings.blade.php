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
                    <p class="text-uppercase text-white text-center mt-3">{{$totalCalls}} Calls Received</p>
                    {{-- <p class="text-center text-white"><i class="fa fa-rupee"></i> 00</p> --}}
                    <p class="text-center text-white">Avg. <i class="fa fa-inr"></i> {{$totalCalls ? round($totalCharge/$totalCalls, 2):0}} per call</p>
                </div>

            </div>
            <div class="col-sm-4 d-none">
                <div class="card bg-orange text-center text-white pt-3 mb-1">
                    <i class="fa fa-comment-dots fa-2x"></i>
                    <p class="text-uppercase text-white text-center  mt-3">00 Chat Received</p>
                    {{-- <p class="text-center text-white"><i class="fa fa-rupee"></i> 00</p> --}}
                    <p class="text-center text-white">Avg. <i class="fa fa-rupee"></i> 0 per chat</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card bg-success text-center text-white pt-3 mb-1">
                    <i class="fa fa-rupee fa-2x"></i>
                    <p class="text-uppercase text-white text-center  mt-3">{{$totalCharge}} Revenue</p>
                </div>
            </div>
        </div>


    </div>
    </div>
  </div>
@endsection
