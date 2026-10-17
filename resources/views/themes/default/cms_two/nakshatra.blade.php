@extends(themeView() . 'layout')

@push('schema')
  <script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "BreadcrumbList",
    "itemListElement": [{
      "@type": "ListItem",
      "position": 1,
      "name": "NG Software",
      "item": "{{route('pages', ngSoftwarePage('software'))}}"
    },{
      "@type": "ListItem",
      "position": 2,
      "name": "Contact Us",
      "item": "{{route('pages','contact-us')}}"
    }]
  }
  </script>
@endpush

@if($data->bannerImage && $data->bannerImage->originalPath)
  @section('image')
  {{$data->bannerImage->originalPath}}
  @endsection
@endif

@section('content')
<!-- Banner Section Start -->
<section class="page-header about-page mt-5 mt-md-0" style="background-image:url('{{$data->bannerImage && $data->bannerImage->originalPath  ? $data->bannerImage->originalPath : noImage()}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
  <div class="container">
    <div class="title_breadcrumb">
    <h1 class="text-center">{{$data->title}}</h1>
    <div class="text-center">
      @include(themeView().'common.breadcrumb')
    </div>
</div>
  </div>

</section>
<!-- Banner Section End -->


<!-- about us section -->
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-3 mt-5 sticky_top sticky-sidebar1  order-2 order-md-1 d-md-block d-none">
        <div class="main-filter-box">
          <div class="btn-bg p-3 radius">
            <h5 class="h5 ms-2 mb-0">Related Blogs</h5>
          </div>
          <div class="media border-bottom p-2 blog-list">
            <div class="side-scrollbar max-scroll">
            <ul class="category-list sidebar_ul">
              @foreach($data->blogs as $key=>$blog)
                <li class="category-item border-bottom link link__0 pb-2">
                  <a href="{{route('blog.post', [$blog->category->slug, $blog->slug])}}" class="text-dark">{{$blog->title}}</a>
                </li>
              @endforeach
              @if($data->blogCategory)
               <div class="category-item pb-0 text-center">
                 <a href="{{route('blog.category', [$data->blogCategory->slug])}}" class="book_nowbut">View All</a>
               </div>
             @endif
            </ul>
          </div>
          </div>
        </div>
        @foreach(ads('software') as $ad)
          @if($ad->image)
           <a href="{{$ad->link}}" target="_blank">
            <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
          </a>
          @else
            {!! $ad->content !!}
          @endif
        @endforeach
      </div>

      <!-- mobile-mobile-sidebar -->
      <div class="col-md-3 d-md-none d-block pt-3 mt-md-0 mb-0 pb-0">
        <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
          <div class="offcanvas-body off-body-m">
            <div class="p-2">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="main-filter-box">
              <div class="btn-bg p-3 radius">
                <h5 class="h5 ms-2 mb-0">Related Blogs</h5>
              </div>
              <div class="media border-bottom p-2 blog-list">
                <div class="side-scrollbar max-scroll">
                <ul class="category-list"> @foreach($data->blogs as $key=>$blog)
                  <li class="category-item link link__0 border-bottom pb-2">
                    <a href="{{route('blog.post', [$blog->category->slug, $blog->slug])}}" class="text-dark">{{$blog->title}}</a>
                  </li>
                   @endforeach
                   @if($data->blogCategory)
                     <li class="category-item pb-0 text-center">
                       <a href="{{route('blog.category', [$data->blogCategory->slug])}}" class=" btn btn-bg text-dark">View All</a>
                     </li>
                   @endif
                </ul>
              </div>
              </div>
            </div>
            @foreach(ads('software') as $ad)
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

      <!-- mobile-sidebar-end -->

      <div class="col-md-9 order-1 order-md-2">
        <div class="container py-4 pb-0 pb-md-0">
          <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <div class="d-md-none d-block v-btn-box">
          <button type="button" name="button text-white" class="arrow-b btn verticalButton" id="scrollButton">Related Blogs</button>
      </div>
        </a>
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
          <h2 class="py-3 mb-0 pb-0">{{$data->title}}</h2>
        </div>
          <div class="row content_inner"> {!! $data->short_description !!} </div>
          <button class="main_button pulse d-md-block d-none" data-bs-toggle="modal" data-bs-target="#Bookdemo" name="button"> Book Demo </button>
          <div class="row pt-5 content_inner"> {!! $data->description !!} </div>

        </div>

     <!-- book-demo-moadl start-->
      <div class="modal fade modal-lg" id="Bookdemo" tabindex="-1" aria-labelledby="BookdemoLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              @include(themeView().'cms_two.form')
            </div>
          </div>
        </div>
      </div>







 <div class="modal fade modal-lg" id="Packages" tabindex="-1" aria-labelledby="insufficientBalanceLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         @include(themeView().'cms_two.form') </div>
     </div>
   </div>
 </div>

  </div>
  </div>

  <div class="row">
@if(count($data->features)>0)
<section class="feature_wrap feature_wrap mt-1">
  <div class="container">
    <div class="row align-items-center gx-0 mx-2" style="margin-top: 25px;">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Features Of Nakshatra Gold</h2>
      </div>
      <p class="fs-6 text-center">Nakshatra Gold offers advanced astrology features, including personalized and detailed compatibility predictions & reports to guide you on your life’s journey.</p>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
    <div class="row">

          </div>


      <div class="row f-row">
          @foreach($data->features as $key => $item)
        <div class="col-md-3 col-6 p-0">
          <div class="card m-2 pb-3 feature-card">

              <img src="{{$item->image ? $item->image->image(250,250) : no_image()}}" class="feature-card-img" alt="{{$item->title}}">

            <div class="card-body text-center pb-0">
              <p class="fw-semibold card-title pb-0 mb-0 f11">{{$item->title}}</p>
            </div>
          </div>
        </div>
   @endforeach
      </div>


  </div>
</section>
  @endif
@if(count($data->procedures)>0)
<section class="mb-0 mb-md-0 pt-5">
  <div class="container">
    <div class="row align-items-center gx-0 mx-2 mb-3">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Procedures</h2>
      </div>
      <p class="fs-6 text-center">NGS Procedures involve step-by-step guidance for using Nakshatra Gold Software, ensuring accurate birth chart analysis, and personalized predictions with ease and precision.</p>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
      </div> @foreach($data->procedures as $key => $procedure) <div class="col-sm-3 mb-3">
        <div class="card procedures radius-7 mb-3 mb-md-0"  style="border: 2px #fa6113 solid; padding: 3px 0px 0px 3px;">
          <div class="icons">
          <img src="{{$procedure->image ? $procedure->image->image(1000, 1000) : no_image() }}" class="" alt="Astrology Proposal" style="width: 100%; padding-right: 4px; border-radius: 4px;">
        </div>
          <div class="card-body text-white">
            <h5 class="card-title text-center text-dark min-hh">{{$key+1}}. {{$procedure->title}}</h5>
            <div class="card-text text-justify text-dark procedure-p"> {!! $procedure->description !!} </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@include(themeView().'common.video')


  @endif @if(count($data->packages)>0)
  <section class="mt-0 mt-md-5">
   <div class="container">
    <div class="row align-items-center gx-0 mx-2 mb-3">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Subscription Packages</h2>
      </div>
      <p class="fs-6 text-center">Get our premium software Packages, designed to offer personalized solutions, in-depth insights, and expert guidance to navigate every area of your life.</p>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>


     <div class="row">
      @foreach($data->packages as $key => $item)
      <div class="col-md-4 mb-3 mb-md-0">

      <div class="price_card card-1">
    <h2>{{$item->heading}}</h2>
    <h3><span><i class="bi bi-currency-rupee fs-1"></i></span>{{$item->title}}</h3>
    <ul>
      @foreach($item->services as $serviceKey => $service)
              <li class="aval">{{$service->title}}</li>
              @endforeach
    </ul>
    <button class="main_button pulse" data-bs-toggle="modal" data-bs-target="#Bookdemo">Book Now</button>
  </div>

      </div>
      @endforeach
    </div>
   </div>
 </section>
 @endif
  </div>
  </div>

  @include(themeView().'common.textTestimonial')
  @include(themeView().'common.videoTestimonial')
  @if(isset($faqs) && count($faqs))
  <section class="bg-light py-0 py-md-5 mt-3">
    <div class="container container-xl">
      <div class="row justify-content-center">
        <div class="col-lg-8 d-flex flex-column justify-content-center align-items-center">
          <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
          <h2 class="fs-3 fw-semibold">Frequently Asked Questions</h2>
        </div>
          <p class="fs-6 text-center">Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
        </div>
      </div>
      <div class="row justify-content-center py-3">
        <div class="col-lg-8">
          <div class="accordion" id="accordionPanelsStayOpenExample">
            @foreach($faqs as $key => $faq) <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{$key}}" aria-expanded="false" aria-controls="panelsStayOpen-collapse{{$key}}">
                  {{$faq->question}}
                </button>
              </h2>
              <div id="panelsStayOpen-collapse{{$key}}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                <div class="accordion-body">
                  {!! $faq->answer !!} </div>
              </div>
            </div>
             @endforeach()
           </div>
        </div>
      </div>
      <!-- sidebar-btn -->
      <div class="position-relative text-center d-md-none d-block">
        <div class="touch-mobile">
          <button class="main_button pulse" data-bs-toggle="modal" data-bs-target="#Bookdemo" name="button">Book Demo</button>

        </div>
      </div>
      <!-- sidebar-btn-end -->
    </div>
  </section>
   @endif
   @include(themeView().'common.adpopup', ['page'=>'software'])
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
