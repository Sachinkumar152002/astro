@extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@section('content')
<section class="page-header about-page" style="background-image:url('{{$page->bannerImage && $page->bannerImage->originalPath  ? $page->bannerImage->originalPath : noImage()}}'); background-repeat: no-repeat; width: 100%; background-size: cover;" >
<div class="container">
    <h1 class="text-center">{{ucfirst($page->page_type)}}s</h1>
    {{--<div class="text-center">
    @include(themeView().'common.breadcrumb')
  </div>--}}
</div>
</section>
  <div class="container mt-5 mb-3">

    <div class="row mb-5 mt-4">
      @foreach($planets as $planet)
        <div class="col-lg-3 mb-2 vastus">
          <a href="{{route('planet.page', $planet->slug)}}">
            <div class="card">
              <img src="{{$planet->image ? $planet->image->image(400,400):no_image()}}"  alt="{{$planet->name}}" class="img-fluid" alt="{{$planet->name}}">
              <div class="card-body">
                <h5 class="fw-semibold card-title text-truncate">{{$planet->name}}</h5>
                <p class="card-text">{{mySubStr(strip_tags($planet->description), 30)}}</p>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>

<a href="{{route('planet.page', 'transit')}}" class="btn btn-primary d-none btn-lg">Transit</a>
<a href="{{route('planet.page', 'retrograde')}}" class="btn btn-primary d-none btn-lg">Retrograde</a>
        <!-- text testimonial -->
          @include(themeView().'common.textTestimonial')


        <!-- video testimonial -->
        @include(themeView().'common.videoTestimonial')
  </div>
@endsection
