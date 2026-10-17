  @extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@if($page->bannerImage && $page->bannerImage->originalPath)
  @section('image')
  {{$page->bannerImage->originalPath}}
  @endsection
@endif

@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush
@section('content')
<section class="page-header about-page mt-5 mt-md-0"  style="background-image:url('{{$page->bannerImage && $page->bannerImage->originalPath ? $page->bannerImage->originalPath : noImage()}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
<div class="container">
    <h1 class="text-center">{{$page->name}}</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
  </div>
</div>
</section>
@if(isset($page) && $page)
<div class="container mt-0 mt-md-5">
  <div class="row">
    <div class="col-sm-3 d-md-block d-none">
      @if(isset($services) && count($services))

          <div class="f-heading-main p-3 float-shadow-2">
          <h4 class="fs-5 text fw-semibold p-0 m-0 ms-2">Vastu Services</h4>
          </div>
            <div class="side-scrollbar max-scroll">
            @foreach($services as $service)

              <div class="media blog-list">

                <ul class="category-list mb-0">
                  <li class="category-item border-bottom pb-2 mx-2">
                    <a href="{{$service->link ? : route('vastu.page', $service->slug)}}" class="text-dark">{{$service->name}}</a>
                  </li>
                </ul>
              </div>

            @endforeach
              </div>
        </div>
      @endif


    <div class="col-md-3 d-md-none d-block pt-3 mt-md-0 mb-0 pb-0">

    <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

        <div class="offcanvas-body off-body-m">
          <div class="p-2">
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>

          @if(isset($services) && count($services))
            <div class="main-filter-box">
              <div class="f-heading-main p-3 float-shadow-2">
              <h4 class="fs-5 text fw-semibold p-0 m-0 ms-2">Vastu Services</h4>
              </div>
                <div class="side-scrollbar max-scroll">
                @foreach($services as $service)
                  <div class="media mb-2 border-bottom p-2 px-3 pb-2 pt-3">
                      <h6 class="ml-2"><a href="{{$service->link ? : route('vastu.page', $service->slug)}}" class="text-dark">{{$service->name}}</a></h6>
                  </div>

                @endforeach
            </div>
          </div>
          @endif

    </div>
      </div>
        </div>

    <div class="col-sm-9">
      <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  <div class="d-md-none d-block v-btn-box">
      <button type="button" name="button text-white" class="arrow-b btn verticalButton">Services</button>
  </div>
    </a>
      <div class="">
        <div class="text-center">
          <img src="{{$page->image ? $page->image->image(500, 300) : ''}}" class="img-fluid v-service-img" style="max-width:100%;">
        </div>
        <div class="blog-desc">
        <div class="btn-bg radius">
              <h2 class="text-start p-2 px-4 text-white">{{$page->name}}</h2>
          </div>
          <div class="p-4 pt-0">
          <div class="blog-box-p">
         <div class="clearfix pe-3">
             {!! $page->description !!}
         </div>

        </div>
          </div>
        </div>

      </div>
      @endif
      @if(isset($procedures) && count($procedures))
        <section class="mt-4">

          <div class="container">
            <div class="">
              <h2 class="pb-3">Procedures</h2>
            </div>
          <div class="row">
            @foreach($procedures as $key => $procedure)
              <div class="col-sm-4 mb-md-0 mb-3 pb-3">
              <div class="card radius-7 pro-card">
               <img src="{{$procedure->image ? $procedure->image->image(150, 150) : no_image() }}" class="img-fluid mx-auto d-block pt-4 pb-2 a-c-img" alt="Astrology Proposal">
               <div class="card-body text-white">
                 <h5 class="card-title text-center text-dark">{{$key+1}}. {{$procedure->title}}</h5>
                 <div class="card-text text-justify text-dark procedure-p">
                  {!! $procedure->description !!}
                 </div>
               </div>
              </div>
              </div>
            @endforeach
          </div>
          </div>
        </section>
      @endif
      @if($page && count($page->packageServices))
      <section class="mt-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <h2 class="pb-3">Packages</h2>
            </div>
        @foreach($page->packageServices as $key => $service)
            <div class="col-md-3 mb-md-0 mb-3">
              <div class="main-filter-box">

                <div class="f-heading-main pkg-title p-3 px-2">
                  <h6>{{$service->title}}</h6>
                </div>
                <div class="">
                  <ul class="pkg-list side-scrollbar">
                    @foreach($service->services as $nameKey => $name)
                    <li><a href="#vastu_form_section">{{$name->name}}</a></li>
                    @endforeach
                  </ul>
                <div class="text-center pb-3">
                  <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Packages">Get Quote</button>
                </div>
                </div>
              </div>
            </div>
              @endforeach
          </div>
        </div>
      </section>
      @endif
      <!-- Modal -->
      <div class="modal fade modal-lg mt-5 mt-md-0" id="Packages" tabindex="-1" aria-labelledby="insufficientBalanceLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              @include(themeView().'vastu.form')
            </div>
            <!-- <div class="modal-footer d-flex justify-content-between">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-secondary" id="rechargeWalletButton">Save</button>
            </div> -->
          </div>
        </div>
      </div>

    </div>
  </div>
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
