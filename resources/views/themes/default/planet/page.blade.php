@extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)
@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush
@section('content')
<section class="page-header about-page"  style="background-image:url('{{$page->bannerImage && $page->bannerImage->originalPath ? $page->bannerImage->originalPath : noImage()}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
<div class="container">
    <h1 class="text-center">{{$page->name}}</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
    </div>
</div>
</section>
<div class="container mt-5">
    <div class="row">
      <div class="col-sm-3">
          <div class="course-filter main-filter-box">
            <div class="f-heading-main p-2 float-shadow-2">
            <h5 class="fs-5 text fw-semibold p-0 m-0 ms-2">All Planets</h5>
            </div>
              <ul class="astro-list-group h-100 p-0">
                @foreach($planets as $planet)
                <div class="media mb-2 border-bottom p-2 px-3 pb-2">
                  <li class="list-group-item">
                  @if(in_array($page->slug, ['transit', 'retrograde']))
                  <a href="{{route('planet.subpage', [$page->slug, $planet->slug])}}">
                  @elseif(in_array($page->page_type, ['transit', 'retrograde']))
                  <a href="{{route('planet.subpage', [$page->page_type, $planet->slug])}}">
                  @else
                  <a href="{{route('planet.page', $planet->slug)}}">
                  @endif
                    <i class="fa fa-globe" aria-hidden="true"></i> {{$planet->name}}</a>
                  </li>
                </div>
                @endforeach
              </ul>
          </div>
      </div>
      <div class="col-sm-9">
        <div class="">
          <div class="">
            <img src="{{$page->image->image(1150, 400)}}" class="w-100">
          </div>
          <div class="blog-desc" >
            <div class="description" id="description">
              {{ substr(strip_tags($page->description), 0, 1000) }}...
            </div>
            @if(strlen($page->description) > 100)
            <button id="toggleButton" onclick="toggleReadMore()" class="read-more text-gold">Read More</button>
            @endif
          </div>
        </div>
      </div>
    </div>
    {{--@include(themeView().'astrologers.top')
    @include(themeView().'blog.recent')
    --}}
    @include(themeView().'common.textTestimonial')

    <!-- video testimonial -->
    @include(themeView().'common.videoTestimonial')
  </div>
@endsection

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
    });

    const fullDescription = `{{ strip_tags($page->description) }}`;
    let isExpanded = false;

    function toggleReadMore() {
        const descriptionDiv = document.getElementById('description');
        const button = document.getElementById('toggleButton');

        if (fullDescription.length > 1000) {
            if (!isExpanded) {
                descriptionDiv.innerHTML = fullDescription;
                button.innerHTML = "Read Less";
            } else {
                descriptionDiv.innerHTML = fullDescription.substring(0, 1000) + '...';
                button.innerHTML = "Read More";
            }
            isExpanded = !isExpanded;
        }
    }


</script>
@endpush
