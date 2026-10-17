@extends(themeView() . 'layout')

@push('style')
@if(request()->is('collections', 'collections/*', 'reports-collections', 'reports-collections/*', 'pujas-collections', 'pujas-collections/*', 'one-to-one-puja', 'one-to-one-puja/*'))
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/collections-ui.css') }}">
@endif
@endpush

@section('title', ' Astro Mall | '.config("app.name"))

@php
  $user = auth()->guard('web')->user();
@endphp

@section('content')
@php
    $mobileView = str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
  @endphp
<style>
/* Professional Astro Mall Category Cards */
.mall-cat-card {
    transition: all 0.3s ease;
    border: 1px solid #eaeaea !important;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
}
.mall-cat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    border-color: #f59e0b !important;
}
.mall-cat-img-wrap {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
    background: #f9fafb;
}
.mall-cat-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.mall-cat-card:hover .mall-cat-img-wrap img {
    transform: scale(1.08);
}
.mall-cat-body {
    padding: 16px;
    text-align: center;
    background: #ffffff;
    border-top: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mall-cat-title {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    transition: color 0.3s ease;
}
.mall-cat-card:hover .mall-cat-title {
    color: #d97706;
}
.mall-action-icon {
    display: inline-block;
    background: #fef3c7;
    color: #d97706;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    line-height: 26px;
    text-align: center;
    font-size: 11px;
    margin-left: 10px;
    transition: all 0.3s;
}
.mall-cat-card:hover .mall-action-icon {
    background: #f59e0b;
    color: #fff;
    transform: translateX(4px);
}
</style>
<!-- Banner Section Start -->
<section class="container">
  @foreach($banners as $index => $banner)
    @if($banner)
      @php
        $bannerImage = $banner->image;
        $bannerMobileImage = $banner->mobileImage;
        $bannerImageUrl = "";
        if($mobileView && $bannerMobileImage){
          $bannerImageUrl = $bannerMobileImage->image();
        }elseif($bannerImage){
          $bannerImageUrl = $bannerImage->image(1250, 250);
        }
      @endphp
      <a href="" class="banner-n2">
        <img src="{{ $bannerImageUrl }}" alt="{{ $banner->alt }}" class="banner-second">
      </a>
    @endif
  @endforeach
</section>
<!-- Banner Section End -->

@php
  $count = $categories->count();
@endphp



<section class="shop_filter h  mt-0 mt-lg-3 pt-0 pt-lg-0">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 scroll-section-side">
        <div class="row justify-content-end mb-3">
          <div class="col-lg-4 mt-2 forshowingresultl d-none d-lg-block">
            <h6 class="fs-7 text fw-semibold">Showing {{$count}} results of {{ $productTypeName }} Categories</h6>
          </div>

          <div class="col-lg-8"></div>

          <div class="col-lg-4 mt-3 forshowingresultl d-lg-none">
            <h6 class="fs-7 text fw-semibold">Showing {{$count}} results of {{ $productTypeName }} Categories</h6>
          </div>
        </div>

        <!-- Categories List start -->
        <div class="row g-3" id="categories-list">
          @foreach($categories as $index => $item)
            @php
              $productCategoryImage = $item->image;
              $productCategoryName = $item->name;
              $url = route('product.handle', [$item->slug]);
              if($productTypeValue == 2){
                if(isset($is_one_to_one_puja) && $is_one_to_one_puja == 1){
                  $url = route('one.to.one.puja.slug', ['slug' => $item->slug]);
                }else{
                  $url = route('puja.handle', [$item->slug]);
                }
              }elseif($productTypeValue == 3){
                $url = route('service.handle', [$item->slug]);
              }
            @endphp
            <div class="col-6 col-md-3">
              <a href="{{ $url }}" class="text-decoration-none">
                <div class="card h-100 mall-cat-card">
                  <div class="mall-cat-img-wrap">
                    <img src="{{ $productCategoryImage ? $productCategoryImage->image() : no_image() }}" alt="{{ $productCategoryName }}">
                  </div>
                  <div class="mall-cat-body">
                    <h6 class="mall-cat-title">{{ $productCategoryName }}</h6>
                    <span class="mall-action-icon"><i class="fa fa-arrow-right"></i></span>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
        <!-- Categories List end -->
      </div>
    </div>
  </div>
</section>
{{--
@include(themeView().'common.textTestimonial')
@include(themeView().'common.videoTestimonial')
@include(themeView().'common.adpopup', ['page'=>'product'])
--}}
@endsection
