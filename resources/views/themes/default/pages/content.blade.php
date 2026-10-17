@extends(themeView().'layout')

@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description',$page->meta_description)

@if($page->bannerImage && $page->bannerImage->image())
  @section('image')
  {{$page->bannerImage->image()}}
  @endsection
@endif

@section('content')
<section class="page-header about-page" style="background-image: url('<?php echo $page->bannerImage ? $page->bannerImage->image() : ''; ?>'); background-repeat: no-repeat; width: 100%; background-size: cover;">

    <div class="container">

<div class="title_breadcrumb">
      <h1 class="text-center">{{$page->name}}</h1>
      <div class="text-center">
      @include(themeView().'common.breadcrumb')
    </div>
    </div>

</section>
<section>
        <div class="container mt-5 common_pages">
            <div class="row">
            @if ($page->image)
            <div class="col l4 m12 s12">
              <div class="box" itemscope itemtype="http://schema.org/Thing">
                  <figure>
                      <img itemprop="image" src="{{$page->image ? $page->image->image(200) : 'N/A'}}"
                          alt="{{$page->name}}">
                  </figure>
              </div>
            </div>
            @endif
          </div>

            <div class="row mt-5 pt-4 about-list content_inner common_pages">
              {!! $page->content !!}
            </div>


        </div>

</section>

@include(themeView().'common.textTestimonial')
@include(themeView().'common.videoTestimonial')

@if(count($faqs))
<section>
    <div class="container rounded py-md-5 py-2 mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-sm-3">
                </div>
                <div class="col-lg-8 col-sm-6">
                <div class="heading_main">
                    <img src="/assets/img/icons/heading_icon.svg">
                    <h2 class="fw-bold">Frequently Asked Questions</h2>
                    <p class="fs-6">Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
                    </div>
            </div>
            <div class="col-lg-2 col-sm-3">
            </div>
        </div>
        <div class="row justify-content-center py-3">
            <div class="col-lg-8">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                  @foreach($faqs as $key => $f)
                  <div class="accordion-item">
                      <h2 class="accordion-header">
                          <button class="accordion-button text-black aco-btn collapsed pe-0" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapse{{ $key }}"
                              aria-controls="collapse{{ $key }}">
                              {!! $f->question !!}
                          </button>
                      </h2>
                      <div id="collapse{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                          <div class="accordion-body text14 aco-btn">
                              {!! $f->answer !!}
                          </div>
                      </div>
                  </div>
              @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- faq section End -->

@endsection

@push('scripts')
@endpush
