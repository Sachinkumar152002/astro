
@extends(themeView().'layout')

@php
$user = null;
if(auth()->guard('web')->check()){
  $user = auth()->guard('web')->user();
}
@endphp

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($courses as $key => $c)
      {
        "@type": "ListItem",
        "position": 1,
        "name": "{{$c->name}}",
        "item": "{{route('course.handle', [$c->slug])}}"
      }
      @if($key+1!=count($courses))
      ,
      @endif
    @endforeach
  ]
}
</script>
{{--
<script type="application/ld+json">
  @foreach($courses as $key => $c)
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "{{$c->name}}",
      "description": "{{strip_tags($c->short_description)}}",
      "provider": {
        "@type": "Organization",
        "name": "IIAG Jyotish Sansthan",
        "sameAs": "iiag.co.in"
      }
    }
    @if($key+1!=count($courses))
    ,
    @endif
  @endforeach
</script>
--}}

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
      @foreach($courses as $key => $c)
    {
      "@type": "ListItem",
      "position": 1,
      "item": {
        "@type": "Course",
        "url": "{{route('course.handle', ['slug'=>$c->slug])}}",
        "name": "{{$c->name}}",
        "description": "{{strip_tags($c->short_description)}}",
        "hasCourseInstance": {
           "@type": "CourseInstance",
           "courseMode": ["online"],
           "courseWorkload": "PT22H",
           "endDate": "{{date('Y-m-d', strtotime('+6 months'))}}",
           "startDate": "{{date('Y-m-d', strtotime($c->created_at))}}",
           "instructor": {
             "@type": "Person",
             "name": "Dr. Yagya Dutt Sharma"
           }
         },
         "offers": [{
           "@type": "Offer",
           "category": "Paid",
           "priceCurrency": "INR",
           "price": {{$c->special_price}}
         }],
        "provider": {
          "@type": "Organization",
          "name": "IIAG Jyotish Sansthan",
          "sameAs": "{{route('home')}}"
        }
      }
    }
    @if($key+1!=count($courses))
    ,
    @endif
  @endforeach

  ]
}
</script>

@endpush

@section('content')<!--banner start-->
<!-- banner section -->
   <section class="shop_list_banner">
       <div class="container">
           <div class="row">
               <div class="col-lg-12">
               </div>
           </div>
       </div>
   </section>
   <!-- banner section end -->
   <!-- shop  listing section start -->
    <section class="p-0 p-md-4 mt-5 mt-md-0  shop_filter">
       <div class="container">
           <div class="row">
             <div class="col-lg-3 sticky_top sticky-sidebar1 p-0 mb-1">
              <div class="course-filter main-filter-box">
              </div>
              @if(str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile'))
               <div class="offcanvas offcanvas-start mt-5 mt-md-0" tabindex="-1" id="offcanvasWithBothOptions1" aria-labelledby="offcanvasWithBothOptionsLabel" style="z-index:999999 ;width:70%;">
                <div class="offcanvas-header">
                  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-3">
                  @include('main.courses.filter')
                </div>
              </div>
               @else
               <div class="d-md-block d-none"> @include('main.courses.filter')</div>
               @endif
             </div>
             <div class="col-lg-9  ab">
                   <div class="row mobile_courses_issue" id="courses-list">
                    <div class="row justify-content-center align-items-center mb-0">
                       <div class="col-lg-4">
                           <h6 class="fs-7 text fw-semibold d-md-block d-none">Showing {{$count}} results</h6>
                       </div>
                       <div class="col-lg-8">

                        <div class="d-flex d-md-block filter-d">
                          <button class="btn btn-primary d-block d-md-none mt-5 mb-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions1" aria-controls="offcanvasWithBothOptions"><i class="bi bi-funnel"></i></button>
                         <div class="row justify-content-end align-items-center p-2">

                              <div class="col-lg-3 main-filter p-0">

                                  <h6 class="fs-7 text  fw-semibold text-md-end mb-0">Sort by</h6>
                              </div>
                              <div class="col-lg-4 me-0 pe-0 p-0 p-md-2">
                                  <div class="input-group">
                                      <select class="form-select p-12" id="sort_by" >
                                        <option value="order_wise">Select</option>
                                        @foreach(filterSortBy() as $key => $item)
                                          <option value="{{$key}}" {{$sortBy == $key ? 'selected' : ''}} >{{$item}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                              </div>
                              {{--
                              <div class="col-lg-5">
                                  <div class="input-group">
                                      <select class="form-select" id="inputGroupSelect02">
                                        <option selected>Price low to high</option>
                                      </select>
                                    </div>
                              </div>
                              --}}
                          </div>
                        </div>
                       </div>
                   </div>
                     <h6 class="fs-6 text fw-semibold d-md-none d-block mt-2 mb-3">Showing all {{$count}} results</h6>

                     @if(isset($courses) && count($courses))
                     @foreach($courses as $course)
                     @if($course->type == 1)
                     <!-- live -->
                       <div class="col-lg-4">
                         <div class="item">
                             <div class="card">
                               <div class="course_bg ">
                                 <a href="{{route('course.handle', ['slug'=>$course->slug])}}">
                                   <img src="{{ $course->image ? $course->image->image(360, 209) : '/assets/images/image 218.png' }}" alt="{{$course->slug}}" class="card-img-h">
                                 </a>
                               </div>
                               <table class="table table-borderless">
                                 @if($course->total_class)
                                 <tr>
                                   <th class="fs-7 c-h-f text" scope="row">Classes</th>
                                   <td class="fs-7 c-h-f text text-truncate max-width-text" style="max-height:50px;">
                                     {{$course->total_class}}
                                     (@foreach($course->courseDays as $dayKey => $day)
                                     {{substr($day->day,0,3)}},
                                     @endforeach)
                                   </td>
                                 </tr>
                                 @endif
                                 <tr>
                                   <th class="fs-7 c-h-f text" scope="row">Started</th>
                                   <td class="fs-7 c-h-f text">on {{date('d-m-Y', strtotime($course->start_date))}}</td>
                                 </tr>
                                 @if($course->start_time && $course->end_time)
                                 <tr>
                                   <th class="fs-7 c-h-f text" scope="row">Time</th>
                                   <td class="fs-7 c-h-f text">{{\DateTime::createFromFormat('H:i', $course->start_time)->format('h:i A')}} - {{\DateTime::createFromFormat('H:i', $course->end_time)->format('h:i A')}}</td>
                                 </tr>
                                 @endif
                                 @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                                 <tr>
                                   <th class="fs-7 c-h-f text" scope="row">Price</th>
                                   <td class="fs-6 c-h-f text fw-bold">
                                     <del>₹ {{$course->price}}</del> <span class="text-ac-price"> ₹{{$course->special_price}}</span>
                                   </td>
                                 </tr>
                                 @else
                                 <tr>
                                   <th class="fs-7 c-h-f text" scope="row">Price</th>
                                   <td class="fs-6 c-h-f text fw-bold">
                                     <span class="text-ac-price"> ₹{{$course->price}}</span>
                                   </td>
                                 </tr>
                                 @endif
                               </table>
                               <div class="card-body live-body  pt-0 mt-0">
                                 <div class="">
                                   <div class="footer foot_width live-footer">
                                     @if($course->publish)
                                       @if($user)
                                         @if($course->purchased)
                                           <a class="but_width" onclick="alreadyPurchased()">
                                              <button class="live-courses-active">Enroll Now</button>
                                           </a>
                                         @else
                                           <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                                              <button class="live-courses-active">Enroll Now</button>
                                           </a>
                                         @endif
                                       @else
                                       <button class="live-courses-active but_width" onclick="checkAuth('{{ route('checkout', ['buynow', 'course', $course->slug]) }}')"> Enroll Now</button>
                                       @endif
                                     @endif
                                     <a href="{{ route('course.handle', [$course->slug]) }}" class="but_width">
                                       <button class="live-courses">Know More</button>
                                     </a>
                                   </div>
                                 </div>
                               </div>
                             </div>
                         </div>
                       </div>
                       @else
                      <div class="col-lg-4 mb-4 ">
                        <div class="item">
                            <div class="card">
                              <a href="{{ route('course.handle', [$course->slug]) }}">
                                <img src="{{ $course->image ? $course->image->image(1200, 675) : asset('assets/images/image 218.png') }}" class="card-img-top card-img-h" alt="{{ $course->name }}">
                              </a>
                              <div class="card-body">
                                @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                                <div class="tag">{{ round(100 - (($course->special_price / $course->price) * 100)) }}% Off</div>
                                @endif
                                <div class="spacalization padding-b2">
                                  <span class="c-h-f2">{{ $course->category->name }}</span>
                                </div>
                                <a href="{{ route('course.handle', [$course->slug]) }}">
                                  <div class="category padding-b2">
                                    <span class="c-h-f text-truncate">{{ $course->name }}</span>
                                  </div>
                                </a>
                                <div class="footer padding-b c-h-f2">
                                  <div class="">
                                    @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                                    <del>₹{{$course->price}}</del><span class="text-ac-price fw-semibold mx-2">₹{{$course->special_price}}</span>
                                    @else
                                    <span class="text-ac-price fw-semibold"> ₹{{$course->price}}</span>
                                    @endif
                                  </div>
                                </div>
                                <div class="">
                                  <div class="footer foot_width live-footer">
                                    @if($course->publish)
                                      @if($user)
                                        @if($course->purchased)
                                          <a class="but_width" onclick="alreadyPurchased()">
                                             <button class="live-courses-active">Enroll Now</button>
                                          </a>
                                        @else
                                          <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                                             <button class="live-courses-active">Enroll Now</button>
                                          </a>
                                        @endif
                                      @else
                                      <button class="live-courses-active but_width" onclick="checkAuth('{{ route('checkout', ['buynow', 'course', $course->slug]) }}')"> Enroll Now</button>
                                      @endif
                                    @endif
                                    <a href="{{ route('course.handle', [$course->slug]) }}" class="but_width">
                                      <button class="live-courses">Know More</button>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                         </div>
                       @endif
                       @endforeach
                       @endif
                  </div>
                  @if($count > $take)
                    <div class="row mt-2 justify-content-center">
                        <div class="col-lg-2" id="load-more">
                          {!! $courses->links('vendor.pagination.bootstrap-5') !!}
                          {{--
                            <button id="load-more" class="btn btn-secondary float-shadow w-100 ">View More</button>
                            --}}
                        </div>
                    </div>
                  @endif
               </div>
           </div>
       </div>
    </section>

    <button class="main_button pulse d-none" id="allcourselandingpageopenbtn" data-bs-toggle="modal" data-bs-target="#Bookdemo" name="button">Book Consultation</button>
    @include('main.courses.form')
@endsection
@push('scripts')

<script>
var slug = '{{$slug}}';
var type = '';
var purchaseType = '';
var priceFrom = parseInt('{{$priceFrom}}');
// var priceFrom = 0;
var priceTo = parseInt('{{$priceTo}}');
// var priceTo = 0;
var maxPrice = parseInt('{{$maxPrice}}');
var minPrice = parseInt('{{$minPrice}}');
var sortBy = '{{$sortBy}}';
filterTypeOnLoad('{{$type}}');
filterPurchaseTypeOnLoad('{{$purchaseType}}');
  // document.getElementById('apply-btn').addEventListener('click',applyFilter)
  function applyFilter(urll = null){
    let url = '/'+slug;
    if(sortBy){
      url += '?sortBy='+sortBy;
    }
    if(type){
      url += '&type='+type;
    }
    if(purchaseType){
      url += '&purchaseType='+purchaseType;
    }
    if(priceFrom && priceFrom != minPrice){
      url += '&priceFrom='+priceFrom;
    }
    if(priceTo && priceTo != maxPrice){
      url += '&priceTo='+priceTo;
    }
    url = '{{asset("/courses")}}'+url;
    window.location.href = url
  }
  function filterCategory(s){
    slug = s
    applyFilter();
  }
  function filterTypeOnLoad(t){
    if(t==1){
      type = 'live';
    }else if(t==3){
      type = 'online';
    }else if(t==2){
      type = 'recorded';
    }
    // applyFilter()
  }
  function filterType(t){
    if(t==1){
      type = 'live';
    }else if(t==3){
      type = 'online';
    }else if(t==2){
      type = 'recorded';
    }
    applyFilter();
  }
  function filterPurchaseTypeOnLoad(pT){
    if(pT==0){
      purchaseType = 'all';
    } else if(pT==1){
      purchaseType = 'advance';
    } else if(pT==2){
      purchaseType = 'intermediate';
    } else if(pT == 3){
      purchaseType = 'basic';
    } else if(pT == 4) {
      purchaseType = 'introduction';
    }
    // applyFilter()
  }
  function filterPurchaseType(pT){
    if(pT==0){
      purchaseType = 'all';
    } else if(pT==1){
      purchaseType = 'advance';
    } else if(pT==2){
      purchaseType = 'intermediate';
    } else if(pT == 3){
      purchaseType = 'basic';
    } else if(pT == 4) {
      purchaseType = 'introduction';
    }
    // console.log(purchaseType)
    applyFilter();
  }
  $('#sort_by').change(function (){
    sortBy = $(this).val();
    applyFilter();
  })
</script>
<script type="text/javascript">
$(document).ready(function() {
  setTimeout(function(){
    document.getElementById('allcourselandingpageopenbtn').click();
  }, 15000);
    $('.cartfom').on('submit', function(event){
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "{{ route('addtocartcourse') }}",
            method: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
              toastr.success('Item added successfully.');
              sessionUserId = response.user_id;
              showCart2();
            },
            error: function(xhr){
              toastr.error('Error adding item to cart. Please try again.');
            }
        });
    });
});
</script>
<script>
$('#lower').change(function (){
  priceFrom = parseInt($(this).val())
  applyFilter();
})
$('#upper').change(function (){
  priceTo = parseInt($(this).val())
  applyFilter();
})
</script>
<script>
var page = '{{$page}}';
  $('#load-more a').on('click', function(e){
    e.preventDefault();
    filterResult($(this).attr('href'));
   });
  var filterResult = function(paginateUrl){
      if(sortBy){
        paginateUrl += '&sortBy='+sortBy;
      }
      if(type){
        paginateUrl += '&type='+type;
      }
      if(purchaseType){
        paginateUrl += '&purchaseType='+purchaseType;
      }
      if(priceFrom && priceFrom != minPrice){
        paginateUrl += '&priceFrom='+priceFrom;
      }
      if(priceTo && priceTo != maxPrice){
        paginateUrl += '&priceTo='+priceTo;
      }
      window.location.href = paginateUrl;
  }
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var offcanvas = document.getElementById("offcanvasWithBothOptions1");
    var coursesList = document.getElementById("courses-list");

    // When Offcanvas is shown
    offcanvas.addEventListener("shown.bs.offcanvas", function () {
        coursesList.classList.add("show");
    });

    // When Offcanvas is hidden
    offcanvas.addEventListener("hidden.bs.offcanvas", function () {
        coursesList.classList.remove("show");
    });
});
function alreadyPurchased(){
  toastr.error('Course already purchased.');
}
</script>
@endpush
