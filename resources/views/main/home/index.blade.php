@extends('layouts.app')

@section('content')
<!-- Slider Start -->
         @include('main.home.slider2')
        <!-- Slider End -->
        <!--Blue-section start-->
        {{--@include('main.home.bluebox')--}}
        <!--blue-section end-->
         <!--abour_portion start-->
        {{--@include('main.home.about')--}}
        <!--card_section start-->
        @include('main.home.course')
        <!--card_section end-->
        <!--about_portion end-->

        <!--partner-portion start-->
        @include('main.home.partners')
        <!--partner-portion end-->
        <!--last steps start-->
        {{--@include('main.home.steps')--}}
        <!--last steps end-->
          @include(themeView().'common.adpopup', ['page'=>'home'])
@endsection

@push('scripts')
<script src="{{asset('js/steps-home.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
<script>

$(".carousel").swipe({

  swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

    if (direction == 'left') $(this).carousel('next');
    if (direction == 'right') $(this).carousel('prev');

  },
  allowPageScroll:"vertical"

});
$("#partner-slide").swipe({

  swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

    if (direction == 'left') $(this).carousel('next');
    if (direction == 'right') $(this).carousel('prev');

  },
  allowPageScroll:"vertical"

});

(function(){
  // setup your carousels as you normally would using JS
  // or via data attributes according to the documentation
  // https://getbootstrap.com/javascript/#carousel
  $('#carousel123').carousel({ interval: 2000 });
  $('#carouselABC').carousel({ interval: 3600 });
}());

(function(){
  $('.carousel-showmanymoveone .item').each(function(){
    var itemToClone = $(this);

    for (var i=1;i<4;i++) {
      itemToClone = itemToClone.next();

      // wrap around if at end of item collection
      if (!itemToClone.length) {
        itemToClone = $(this).siblings(':first');
      }

      // grab item, clone, add marker class, add to collection
      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo($(this));
    }
  });
}());
</script>
@endpush
