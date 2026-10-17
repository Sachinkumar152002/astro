@extends(themeView().'layout')

@section("title", "Notifications | ASTROVYRA")

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-0 mt-md-0">
        <div class="order-box my-4 mt-md-0">

            <div class="px-4 py-3 border-n radius">
              <div class="row">

                {{--<div class="col-md-2" style="position:relative;">

                  @if($link)
                    @php
                      $redictToUrl=$link;
                      if($_token){
                        $redictToUrl=$link."_token=${$_token}";
                      }
                    @endphp
                    <a href="{{ $redictToUrl }}">
                      <img src="{{$imageUrl}}" class="radius img-fluid" height="100px" width="100px">
                    </a>
                  @else
                    <img src="{{$imageUrl}}" class="radius img-fluid" height="100px" width="100px">
                  @endif
                </div>--}}
                <div class="notif-heading d-flex align-items-center gap-2">
                <!-- Bootstrap Bell Icon -->
                <i class="bi bi-bell-fill text-purple fs-5"></i>
                <!-- Heading -->
                <h2 class="fs-16 mb-0">Notifications</h2>
              </div>
              @foreach($notifications as $key => $value)
              @php
                $imageUrl = $value->image ? $value->image->image(125, 125) : asset('assets/img/logo.png');
                $date = date('d F Y | h:i A', strtotime($value->created_at));
                $message = $value->message;
                $link = $value->link;
              @endphp
                <div class="bor-bottom">
                  <div class="mt-2">
                    <p class="testimonials-p noti_p pb-1 mb-2 dash_noti">{{$message}}</p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-2 mt-3 mt-md-0">{{$date}}</p>
                    @if($link && false)
                      @php
                        $redictToUrl=$link;
                        if($_token){
                          $redictToUrl=$link."_token=${$_token}";
                        }
                      @endphp
                      <br>
                      <a href="{{ $redictToUrl }}" class="main_button">View Detail</a>
                    @endif
                  </div>
                </div>

                @endforeach
              </div>
            </div>
      </div>
        <div class="text-center">
          {!! $notifications->links('vendor.pagination.bootstrap-5') !!}
        </div>
        @if($notifications->count() == 0)
          <div class="col-12 text-center">
            <img src="/assets/images/wish.webp" alt="No Item" class="nodata">
            <h5 class="mt-3 text-dark"> No Notifications Found!</h5>
          </div>
        @endif
    </div>
  </div>
</section>

@endsection
@push("scripts")
<script type="text/javascript">
  let _token='{{ $_token }}';
  if(_token!=""){
    $(".pagination a").on("click",function(e){
      e.preventDefault();
      let url = $(this).attr("href");
      url=`${url}&_token=${_token}`;
      window.location.href=url;
    });
  }
</script>
@endpush
