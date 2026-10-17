@extends(themeView().'layout')

@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description',$page->meta_description)

@if($page->bannerImage && $page->bannerImage->image())
  @section('image', $page->bannerImage->image())
@endif

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"AboutPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"About {{ config('app.name') }}",
    "description":"{{ strip_tags($page->short_description) }}",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "primaryImageOfPage":{
      "@type":"ImageObject",
      "url":"{{ logo() }}"
    },
    "breadcrumb":{
      "@id":"{{ url()->current() }}#breadcrumb"
    }
  }
  </script>
@endpush

@section('content')
<section class="page-header about-page" style="background-image: url('{{$page->bannerImage ? $page->bannerImage->image() : ''}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">

    <div class="container">

  <div class="head-sec">
    <h1 class="text-center text-white">About Us</h1>
    <!-- <p class="text-center text-white">Discover the secrets of the stars with our personalized astrology readings. Explore your zodiac sign, daily horoscopes, and celestial insights. Unlock the mysteries of the universe and find your true path.</p> -->

  </div>
</section>
      <section class="pb-0">
        <div class="container mt-5">
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
            <div class="container">
              <div class="row">


                <div class="col l8 m12 s12 content_inner short_desc mt-3 mt-md-0">
                  <h2>About Us</h2>

                  {!! $page->short_description !!}
                <a href="{{ route('contactForm') }}">  <button type="button" name="button" class=" btn ab-btn mt-md-4 mt-0 main_button">Contact Us <i class="bi bi-arrow-right ms-2"></i></button></a>

                </div>
            </div>
            </div>
          </div>

            <div class="row mt-3 mt-lg-5 about-list content_inner">
              {!! $page->content !!}
            </div>
            {{--

            @if(($page->questions && $page->questions->count()>0) || ($page->faqs && $page->faqs->count()>0))
            <div class="content" itemscope itemtype="https://schema.org/FAQPage">
              <div class="heading_main">
                <img src="/assets/img/icons/heading_icon.svg">
                <h4>FAQ's </h4>
                <p>Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
                @include(themeView().'pages.faqs')
              </div>
            </div>

            @endif
            --}}

        </div>


        <!-- service-section-start -->
      </section>












<!-- faq section End -->

@endsection

@push('scripts')
@endpush
