@php
$services = $serviceNames = [];
if(isset($astrologer) && $astrologer){
  $services = $astrologer->services->pluck('service_id')->toArray();
  $_services = \App\Model\AstrologerService::whereHas('astrologer', function($asttrolgr){
    $asttrolgr->where('status', 1)->where('is_verified', 1);
  })->whereNotIn('service_id',$services)->groupBy('service_id')->take(2)->get();
  $services = $_services->pluck('service_id')->toArray();
  foreach($_services as $serv){
    $serviceNames[] = $serv->service->name;
  }
}

    $astrologers = topAstrologers(8, $services);
@endphp
@if($astrologers)
@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush
<div class="container  pb-5 top-astro">
    @if(count($serviceNames)>0)
      <h2 class="text-orange text-center mb-4 text-38">You might be interested Connecting with <br>{{implode(' And ',$serviceNames)}} Experts!</h2>
    @else
      <h2 class="text-orange text-center mb-4 text-38">You might be interested connecting with our top rated astrologers</h2>
    @endif
    <div class="carousel owl-carousel owl-theme">
        @foreach ($astrologers as $astrologer)
            <div class="item">
                @include(themeView().'astrologers._item', array('col'=>'-1'))
            </div>
        @endforeach
    </div>
<p class="text-center mt-4"><a href="{{route('astrologers.index')}}" class="btn btn-rounded btn-orange pl-5 pr-5 btn-lg">View All</a></p>
</div>

@push('scripts')
<script src="{{asset('js/owl.carousel.min.js')}}"></script>
<script>
     $('.carousel').owlCarousel({
      loop: true,
      margin: 10,
      responsiveClass: true,
      autoplay: true,
      responsive: {
        0: {
          items: 1,
          nav: true,
          autoplay: true

        },
        600: {
          items: 2,
          autoplay: true,
          nav: false
        },
        1000: {
          items: 4,
          margin: 15,
          nav: false,
          loop: false
        }
      }
    })
</script>
@endpush
@endif