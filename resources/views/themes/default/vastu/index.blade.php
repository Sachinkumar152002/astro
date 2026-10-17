@extends(themeView().'layout')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Vastu Consultation",
    "item": "{{route('pages', vastuIndex())}}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Talk to Astrologer",
    "item": "{{route('astrologers.index')}}"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "Contact Us",
    "item": "{{route('pages','contact-us')}}"
  }]
}
</script>

@endpush

@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@if($page->bannerImage && $page->bannerImage->originalPath)
  @section('image')
  {{$page->bannerImage->originalPath}}
  @endsection
@endif

@section('content')
<section class="page-header about-page" style="background-image:url('{{$page->bannerImage && $page->bannerImage->originalPath  ? $page->bannerImage->originalPath : noImage()}}'); background-repeat: no-repeat; width: 100%; background-size: cover;" >
  <div class="container">
    <div class="title_breadcrumb">
      <h1 class="text-center">{{$page->name}}</h1>
      <div class="text-center">
        @include(themeView().'common.breadcrumb')
      </div>
    </div>
  </div>
</section>
  <div class="container">
    <div class="row">
      <div class="col-md-3 mt-5 sticky_top sticky-sidebar1 order-2 order-md-1 mb-5 d-md-block d-none">
        <div class="main-filter-box">
          <div class="btn-bg p-3 radius">
            <h5 class="h5 ms-2 mb-0"> Related Blogs</h5>
          </div>
          <div class="media border-bottom p-2 blog-list">
            @if(count($page->blogs)>0)
              <div class="side-scrollbar max-scroll">
              <ul class="category-list sidebar_ul">
                @foreach($page->blogs as $key=>$blog)
                <li class="category-item link link__0 border-bottom pb-2">
                  <a href="{{route('blog.post', [$blog->category->slug, $blog->slug])}}" class="text-dark">{{$blog->title}}</a>
                </li>
                @endforeach
                <div class="category-item pb-0 text-center">
                  <a href="{{route('blog.category', [$page->blogCategory->slug])}}" class="book_nowbut">View All</a>
                </div>
            </ul>
          </div>
          @endif
          </div>
        </div>
        @foreach(ads('vastu') as $ad)
          @if($ad->image)
           <a href="{{$ad->link}}" target="_blank">
            <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
          </a>
          @else
            {!! $ad->content !!}
          @endif
        @endforeach
      </div>

        <!-- phone-blog-section-start -->
      <div class="col-md-3 d-md-none d-block pt-3 mt-md-0 mb-0 pb-0">

        <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

          <div class="offcanvas-body off-body-m">
            <div class="p-2">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="main-filter-box">
              <div class="btn-bg p-3 radius">
                <h5 class="h5 ms-2 mb-0"> Related Blogs </h5>
              </div>
              <div class="media border-bottom p-2 blog-list">
                @if(count($page->blogs)>0)
                  <div class="side-scrollbar max-scroll">
                  <ul class="category-list">
                    @foreach($page->blogs as $key=>$blog)
                    <li class="category-item border-bottom pb-2">
                      <a href="{{route('blog.post', [$blog->category->slug, $blog->slug])}}" class="text-dark">{{$blog->title}}</a>
                    </li>
                    @endforeach
                    <li class="category-item pb-0 text-center">
                      <a href="{{route('blog.category', [$page->blogCategory->slug])}}" class=" btn btn-bg text-dark">View All</a>
                    </li>
                </ul>
              </div>
              @endif
              </div>
            </div>
            @foreach(ads('vastu') as $ad)
              @if($ad->image)
               <a href="{{$ad->link}}" target="_blank">
                <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
              </a>
              @else
                {!! $ad->content !!}
              @endif
            @endforeach
        </div>
      </div>
   </div>
   <!-- phone-blog-section-end -->
  <div class="col-md-9 order-1 order-md-2">


@if(isset($page) && $page)
<section class="py-0 py-md-5">
    <div class="container-fluid container-xl">
      <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  <div class="d-md-none d-block v-btn-box">
      <button type="button" name="button text-white" class="arrow-b btn verticalButton" id="scrollButton">Related Blogs</button>
  </div>
    </a>
    <!-- <h2 class="fs-3 heading">Best Vastu Shastra Consultant in India</h2> -->


        <div class="row">
          <div class="heading_main">
            <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
            <h2 class="mb-1 mb-md-4">{{$page->name}}</h2>
          </div>
            <div class="col-12 content_inner page-desc">
              {!! $page->short_description !!}
            </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-12 d-md-block d-none mb-3 mb-md-0">
            <button class="main_button pulse" data-bs-toggle="modal" data-bs-target="#Packages">Book Appointment</button>
          </div>

          <!-- <div class="col-md-6 col-12 d-md-none d-block text-center mb-3 mb-md-0">
            <button class="btn btn-secondary w-70 w-md-100" data-bs-toggle="modal" data-bs-target="#Packages">Book an Appointment</button>
          </div> -->
        </div>
    </div>
</section>


<section class="py-0">
    <div class="container-fluid container-xl">


            <!-- <h2 class="fs-3 ms-0 heading">What is Vastu Shastra?</h2> -->


        <div class="row py-3">
            <div class="col-12 full-desc">
              {!! $page->description !!}
            </div>
        </div>
    </div>
</section>
@endif



@if($page->why_choose_us)
<section class="">
    <div class="container-fluid container-xl">
        <div class="row py-3">
            <div class="col-12 full-desc content_inner">
              {!! $page->why_choose_us !!}
            </div>
        </div>
    </div>
</section>
@endif

@if($page->benifits)
<section class="py-0 py-md-0">
    <div class="container-fluid container-xl">
        <div class="row pb-3">
            <div class="col-12 content_inner full-desc">
              {!! $page->benifits !!}
            </div>
        </div>
    </div>
</section>
@endif

</div>
</div>

</div>

<!-- card-section-start -->
@if(isset($procedures) && count($procedures))
<div class="container mt-4">
  <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6 pt-5">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Procedures</h2>
      </div>
      <p class="fs-6 text-center">Get detailed insights into Vastu Procedures, guiding you through the step-by-step process to enhance the energy and harmony in your home or workspace.</p>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
  <div class="row">
    @foreach($procedures as $key => $procedure)
      <div class="col-sm-3">
        <div class="card procedures radius-7 mt-3" style="border: 2px #fa6113 solid; padding: 3px 0px 0px 3px;">
          <div class="icons">
          <img src="{{$procedure->image ? $procedure->image->image(1000, 1000) : no_image() }}" class="" alt="Astrology Proposal" style="width: 100%; padding-right: 4px; border-radius: 4px;">
          </div>
          <div class="card-body text-white">
            <h5 class="card-title text-center text-dark min-hh">{{$procedure->title}}</h5>
            <div class="card-text text-justify text-dark procedure-p">
              {!! $procedure->description !!}
            </div>
          </div>
        </div>
      </div>
    @endforeach
    </div>
  </div>
@endif

@if(isset($services) && $services)
<section class="py-0 py-md-3 mt-5">
    <div class="container-fluid container-xl">

<div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-2">
      </div>
      <div class="col-lg-8 col-sm-8 pt-5">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Vastu Services Offered by Us</h2>
      </div>
      <p class="fs-6 text-center">Our Vastu Services cater to residential, commercial & industrial spaces and increases their energy flow, improve productivity, and create a positive, prosperous environment.</p>
      </div>
      <div class="col-lg-2 col-sm-2 mb-2 mb-md-0">

      </div>
    </div>
      <div class="row">
          @foreach($services as $key => $service)
          <div class="col-lg-3 mb-2 vastus vas">
            <a href="{{$service->link ? : route('vastu.page', $service->slug)}}">
              <div class="card">
                <img src="{{$service && $service->image ? $service->image->image(400, 400) : ''}}" class="img-fluid vsatu-i" alt="{{$service->name}}">
                <div class="card-body v-text-d">
                  <h5 class="fw-semibold card-title text-truncate">{{$service->name}}</h5>
                {!! $service->short_description !!}
                </div>
              </div>
            </a>
          </div>
        @endforeach
          </div>
    </div>
</section>
@endif





  <!-- card-section-end -->
  @if($page && count($page->packageServices))
  <section class="mt-0 pb-5">
    <div class="container">
      <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6 pt-5">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Packages</h2>
        <p class="fs-6 text-center">Our Vastu Packages offer comprehensive solutions for residential, commercial & industrial spaces providing expert consultations, and effective remedies to enhance positivity and success.</p>
      </div>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
      <div class="row">
    @foreach($page->packageServices as $key => $service)
        <div class="col-md-3 col-12 mb-3 mb-md-0 ">


        <div class="price_card card-1">
    <h2>{{$service->heading}}</h2>
    <h3><span><i class="bi bi-currency-rupee fs-1"></i></span>{{$service->title}}</h3>
    <ul>
      @foreach($service->services as $nameKey => $name)
        <li class="aval">{{$name->name}}</li>
      @endforeach
    </ul>
    <button class="main_button pulse" data-bs-toggle="modal" data-bs-target="#Packages">Book Now</button>
  </div>

        </div>
    @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- Modal -->
  <div class="modal fade modal-lg" id="Packages" tabindex="-1" aria-labelledby="insufficientBalanceLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-0">

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="
    top: 15px;
"></button>
        </div>
        <div class="modal-body pt-0">
          @include(themeView().'vastu.form')
        </div>
        <!-- <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-secondary" id="rechargeWalletButton">Save</button>
        </div> -->
      </div>
    </div>
  </div>

@include(themeView().'common.textTestimonial')
@include(themeView().'common.videoTestimonial')

@if(isset($faqs) && count($faqs))
  <section class="py-0">
      <div class="container pt-5">
        <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-2">
      </div>
      <div class="col-lg-8 col-sm-8">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Frequently Asked Questions</h2>
        <p class="fs-6 text-center">Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
      </div>
      </div>
      <div class="col-lg-2 col-sm-2 mb-2 mb-md-0">

      </div>
    </div>

          <div class="row justify-content-center py-3">
              <div class="col-lg-8">
                  <div class="accordion" id="accordionPanelsStayOpenExample">
                    @foreach($faqs as $key => $faq)
                      <div class="accordion-item">
                          <h2 class="accordion-header">
                              <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                  data-bs-target="#panelsStayOpen-collapse{{$key}}" aria-expanded="false"
                                  aria-controls="panelsStayOpen-collapse{{$key}}">
                                {{$faq->question}}
                              </button>
                          </h2>
                          <div id="panelsStayOpen-collapse{{$key}}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                              <div class="accordion-body">
                            {!! $faq->answer !!}
                              </div>
                          </div>
                      </div>
                    @endforeach()
                  </div>
              </div>
          </div>
      </div>
  </section>
  <div class="position-relative text-center d-md-none d-block">
        <div class="touch-mobile">
          <button class="main_button pulse py-2"  data-bs-toggle="modal" data-bs-target="#Packages" name="button">Book Appointment</button>

        </div>
      </div>
  @endif
  @include(themeView().'common.adpopup', ['page'=>'vastu'])
@endsection

@push('scripts')
  <script>
    let scrollTimeout;

    window.addEventListener('scroll', () => {
      let button = document.getElementById('scrollButton');

      // Show the button when scrolling
      button.style.display = 'block';

      // Clear the timeout if it's already set
      if (scrollTimeout) clearTimeout(scrollTimeout);

      // Set a timeout to hide the button after a delay
      scrollTimeout = setTimeout(() => {
        button.style.display = 'none';
      }, 2000); // Adjust time (ms) to suit your preference
    });
  </script>
@endpush
