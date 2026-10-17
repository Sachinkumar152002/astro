 <!-- Banner Section Start -->
 <!-- Banner Section Start -->
 @php
 $user = null;
 if(auth()->guard('web')->check()){
   $user = auth()->guard('web')->user();
 }
 @endphp
 <div class="container-fluid p-0 m-0">
   <div class="bannerslider">
     @if(str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile'))
     @foreach($banners as $banner)
         <div class="slide d-flex align-items-center p-5" style="background-image: url('{{$banner->mobileImage ? $banner->mobileImage->image() : ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;height:400px;">
           <div class="container">
             <div class="row w-100">
               <div class="col-lg-9 col-md-8 col-12 text-white d-flex flex-column align-items-center align-items-md-start justify-content-center">
                 <h2 class="white fs-1 text-center text-md-start">{{$banner->title}}</h2>
                 {!! $banner->caption !!}
                 <a href="{{route('astrologers.index')}}">
                 <button class="roboto-bold btn btn-light btn-padding btn-radius ">Consult Now</button></a>
               </div>
             </div>
           </div>
         </div>
     @endforeach
     @else
 @foreach($banners as $banner)
     <div class="slide d-flex align-items-center p-5" style="background-image: url('{{$banner->image ? $banner->image->image(1200, 675) : ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;height:400px;">
       <div class="container">
         <div class="row w-100">
           <div class="col-lg-9 col-md-8 col-12 text-white d-flex flex-column align-items-center align-items-md-start justify-content-center">
             <h2 class="white fs-1 text-center text-md-start">{{$banner->title}}</h2>
             {!! $banner->caption !!}
             <a href="{{route('astrologers.index')}}">
             <button class="roboto-bold btn btn-light btn-padding btn-radius ">Consult Now</button></a>
           </div>
         </div>
       </div>
     </div>
 @endforeach
 @endif
     </div>
     </div>
<!-- Banner Section End -->
<!-- course categary section -->
@if(isset($categories) && count($categories))
  <section class="testimonials_bg sec_section  d-none d-md-block">
  <div class="container">

          <div class="row space_mobile d-flex justify-content-between align-items-center">
            @foreach($categories as $key => $category)


                <a class="card_courses_all" href="{{route('course.handle', ['slug'=>$category->slug])}}">
  <div class="corner top left"></div>
  <div class="corner top right"></div>
  <div class="corner bottom left"></div>
  <div class="corner bottom right"></div>
  <div class="card-icon">
            <img src="{{$category->image ? $category->image->image(1200,675) : ''}}" alt="{{$category->name}}">
          </div>
  <h1>{{$category->name}}</h1>
</a>

            @endforeach
          </div>
      </div>
  </section>
@endif


@if(isset($categories) && count($categories))
  <section class="testimonials_bg sec_section courses_innerone d-block d-md-none">
    <div class="container">
      <div class="row space_mobile d-flex justify-content-between align-items-center">
        <div class="coursesinner">
          @foreach($categories as $key => $category)
            <div class="item">
              <a class="courses_inner" href="{{ route('course.handle', ['slug' => $category->slug]) }}">
                <div class="courses_inner_icon">
                  @if($category->image)
                    <img src="{{ $category->image->image(1200,675) }}" alt="{{ $category->name }}">
                  @endif
                </div>
                <h1>{{ $category->name }}</h1>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endif





<button class="main_button pulse d-none" id="allcourselandingpageopenbtn" data-bs-toggle="modal" data-bs-target="#Bookdemo" name="button">Book Consultation</button>
<!-- course section end -->

@if(isset($onlineCourses) && count($onlineCourses))
<section class="py-md-3 py-2">
  <div class="container">
  <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-2">
      </div>
      <div class="col-lg-8 col-sm-8">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Our E-Learning Courses</h2>
      </div>
      </div>
      <div class="col-lg-2 col-sm-2 mb-2 mb-md-0">
        <a href="{{asset('courses/all?sortBy=price_low_to_high&type=online')}}">
           <button class="view_all d-block ms-auto">View All <i class="fa fa-angle-right"></i></button>
         </a>
      </div>
    </div>
    <div class="primium courses">
      @foreach($onlineCourses as $key => $course)
      <div class="item">
          <div class="card border-0">
            <a href="{{ route('course.handle', [$course->slug]) }}">
              <img src="{{ $course->image ? $course->image->image(1200, 675) : asset('assets/images/image 218.png') }}" class="card-img-top card-img-h" alt="{{ $course->name }}">
            </a>
            <div class="card-body">
              @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
              <div class="tag">{{ round(100 - (($course->special_price / $course->price) * 100)) }}% Off</div>
              @endif
              <div class="spacalization">
                <span class="c-h-f">{{ $course->category->name }}</span>
              </div>
              <a href="{{ route('course.handle', [$course->slug]) }}">
                <div class="category">
                  <span class="c-h-f text-truncate">{{ $course->name }}</span>
                </div>
              </a>
              <div class="footer ">
                <div class="pb-3">
                  @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                  <del>₹{{$course->price}}</del><span class="text-ac-price fw-semibold mx-2">₹{{$course->special_price}}</span>
                  @else
                  <span class="text-ac-price fw-semibold pb-3"> ₹{{$course->special_price}}</span>
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
        @endforeach
      </div>
  </div>
</section>
@endif

@if(isset($liveCourses) && count($liveCourses))
  <section class="pt-5 mb-5 live_section">
    <div class="container">
    <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-2">
      </div>
      <div class="col-lg-8 col-sm-8">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Our Live Courses</h2>
      </div>
      </div>
      <div class="col-lg-2 col-sm-2 mb-2 mb-md-0">
        <a href="{{asset('courses/all?sortBy=price_low_to_high&type=live')}}">
           <button class="view_all d-block ms-auto">View All <i class="fa fa-angle-right"></i></button>
         </a>
      </div>
    </div>
      <div class="row">

        <div class="col-lg-12">
          <div class="live_courses">
            @foreach($liveCourses as $key => $course)
              @include('main.courses.list')
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
<!-- testimonials shop section start -->
@include(themeView().'common.textTestimonial')
<!-- testimonials service section start -->
<!-- video shop section start -->
@include(themeView().'common.videoTestimonial')
<!-- video service section start -->
<!-- faq section start -->

@if(isset($faqs) && count($faqs))
  <section class="py-0 py-md-2 mt-3">
      <div class="container pt-5">

          <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-2">
      </div>
      <div class="col-lg-8 col-sm-8">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Frequently Asked Questions</h2>
        <p class="fs-6 text-center">Learn about the astrology and vastu courses we provide.</p>
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
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
  @endif
<!-- faq section End -->
