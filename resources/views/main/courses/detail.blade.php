@extends(themeView() . 'layout')

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
     "@id": "{{url()->current()}}",
     "@type": "Course",
     "name": "{{$course->name}}",
     "description": "{{strip_tags($course->description)}}",
     "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": ["online"],
        "courseWorkload": "PT22H",
        "endDate": "{{date('Y-m-d', strtotime('+6 months'))}}",
        "startDate": "{{date('Y-m-d', strtotime($course->created_at))}}",
        "instructor": {
          "@type": "Person",
          "name": "Dr. Yagya Dutt Sharma"
        }
      },
     "publisher": {
       "@type": "Organization",
       "name": "IIAG Jyotish Sansthan",
       "url": "iiag.co.in"
     },
     "provider": {
       "@type": "Organization",
       "name": "IIAG Jyotish Sansthan",
       "url": "iiag.co.in"
     },
     "image": [
       "{{$course->previewVideo?$course->previewVideo->image:asset('assets/img/logo.png')}}"
     ],
     "aggregateRating": {
       "@type": "AggregateRating",
       "ratingValue": 5,
       "ratingCount": 1234,
       "reviewCount": 450
     },
  {{--   @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) ) --}}
     "offers": [{
       "@type": "Offer",
       "category": "Paid",
       "priceCurrency": "INR",
       "price": {{$course->special_price}}
     }],
{{--   @endif --}}
     "totalHistoricalEnrollment": "{{$course->id+10500}}",
     "datePublished": "{{date('Y-m-d', strtotime($course->created_at))}}",
     @if($course->purchase_type==1)
      "educationalLevel": "Advance",
     @elseif($course->purchase_type==2)
      "educationalLevel": "Intermidiate",
     @elseif($course->purchase_type==3)
      "educationalLevel": "Basic",
     @else
      "educationalLevel": "Free",
     @endif
     @if($course->category)
      "about": ["{{$course->category->name}}"]
     @endif

   }
</script>
 @endpush



@section('title')
{{$course->meta_title}}
@endsection

@section('description')
{{$course->meta_description}}
@endsection

@section('keywords')
{{$course->meta_keywords}}
@endsection

@if($course->previewVideo && $course->previewVideo->image)
  @section('image')
  {{$course->previewVideo->image}}
  @endsection
@endif

@section('content')

@if(isset($course) && $course)
<section class="banner-bg-c hh py-5 mt-md-0 mt-5">
    <div class="container">
        <div class="row" style="align-items: center;">
            <div class="col-lg-6 col-12 banner-description cousresbg_text b-text order-md-first order-last">
                <h2 class="fs-2 mt-2 m-0">Learn {{$course->name}}</h2>
                {!! $course->short_description !!}
                    <div class="row justify-content-between">
                        <div class="col-lg-12 col-12 d-flex flex-column">
                          @if(isset($textTestimonials) && count($textTestimonials))
                            <ul class="clients_list">
                              @foreach($textTestimonials as $key => $txtTes)
                                <li>
                                  <img src="{{$txtTes->image ? $txtTes->image->image(50, 50) : '' }}" alt="user" style="border-radius:50%">
                                </li>
                              @endforeach
                            </ul>
                          @endif
                            <span class="fs-6 text text-black mb-3" style="font-weight: 600;">
                              {{$course->id+10500}}+ people have already joined
                            </span>
                        </div>


                    </div>
                    <div class="row mb-3">
                    <div class="col-md-4">
                    <div class="inline_card_courses">
                    <div class="icon">
                    <i class="bi bi-google" style="font-size:28px;line-height: 0 !important;"></i>
                    </div>
                    <div class="texts">
                    <ul class="ratings black d-flex gap-2 p-0 m-0">
                                @for($i=0;$i<$rating;$i++)
                                  <li><i class="bi text-black bi-star-fill"></i></li>
                                @endfor
                              </ul>
                              <span class="fs-8 text white text-black">
                                {{$rating}}/5 on Google
                              </span>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="inline_card_courses">
                    <div class="icon">
                    <i class="bi bi-youtube" style="color:#FF0000;font-size:24px;line-height: 0 !important;"></i>
                    </div>
                    <div class="texts">
                    <h4 class="fs-7 p-0 m-0 text">
                                    50,000+
                    </h4>
                    <p class="fs-7 m-0 text">Subscribers</p>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="inline_card_courses">
                    <div class="icon">
                    <i class="bi fs-1 bi-facebook" style="color:#316FF6;line-height: 0 !important;    font-size: 30px !important;"></i>
                    </div>
                    <div class="texts">
                    <h4 class="fs-7 p-0 m-0 text">
                                    30,000+
                    </h4>
                    <p class="fs-7 m-0 text">Followers</p>
                    </div>
                    </div>
                    </div>
                    </div>

                    <div class="p-2">
                      @if(!str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile'))
                        @if($course->publish)
                          @if($user)
                            @if($course->purchased)
                              <a class="but_width" onclick="alreadyPurchased()">
                                 <button class="live-courses-active2 main_button pulse">Enroll Now</button>
                              </a>
                            @else
                              <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                                 <button class="live-courses-active2 main_button pulse">Enroll Now</button>
                              </a>
                            @endif
                          @else
                            <button  type="button" onclick="checkAuth('{{ route('checkout', ['buynow', 'course', $course->slug]) }}')" class="main_button pulse mb-3 mb-md-0">Enroll Now</button>
                          @endif
                        @endif
                      @endif

                      @if(auth()->guard('web')->check() || auth()->guard('astrologer')->check())
                        @if($course->wishlist)
                        <button data-item_id="{{$course->id}}" data-type="course" data-wishlist_id="{{$course->wishlist->id}}" data-action-type="remove" class="toggle_wishlist button_wishlist ms-2">
                          <i class="fa fa-heart text-danger pointer"></i>
                          Wishlist
                        </button>
                        @else
                        <button data-item_id="{{$course->id}}" data-type="course" data-wishlist_id="0" data-action-type="add" class="toggle_wishlist button_wishlist">
                          <i class="fa fa-heart pointer"></i>
                          Wishlist
                        </button>
                        @endif
                      @else
                        <button data-item_id="{{$course->id}}" data-type="course" data-wishlist_id="0" data-action-type="login" class="auth_login button_wishlist">
                          <i class="fa fa-heart pointer"></i>
                          Wishlist
                        </button>
                      @endif
                      @if($course->brochure)
                        <a href="{{asset($course->brochure)}}" target="_blank" class="but_width">
                           <button class="button_wishlist">
                             <i class="fa fa-download pointer"></i>
                             Brochure</button>
                        </a>
                      @endif
                    </div>
            </div>
            @if($course->previewVideo)
              <div class="col-lg-6 order-md-last order-first">
                <div class="banner-video">
                <a href="javascript:void(0)" onclick="previewVideoPlay(this, '{{$course->previewVideo->video_id}}')" class="position-relative">
                    <img src="{{$course->previewVideo->image}}" class="w-100 img-fluid" alt="preview video">
                    <span class="position-absolute top-50 start-50 translate-middle">
                        <i class="bi bi-youtube" style="font-size: 3rem; color: #ED3237;"></i>
                    </span>
                </a>
                <div class="modal modal-lg" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-preview-1Label" id="modal-preview-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header course-de">
                          <button type="button" class="btn-close position-absolute v_btn" id="modal-preview-1-close-btn" ></button>
                      </div>
                      <div class="modal-body">
                        <div class="" id="preview-video-div"></div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>
            @endif
        </div>
    </div>
</section>
@endif
<!-- Banner Section End -->
<!-- specialties section start -->
<section class="specialties testimonials_bg sec_section">
    <div class="container">
        <div class="row i-box">
            @foreach($course->specifications as $item => $spec)
              <div class="col-md-6 mb-2 card course_box">
                  <div class="card-image">
                      <img src="{{asset($spec->icon_id)}}" class="{$spec->text}}" alt="{{$spec->heading}}">
                  </div>
                  <div class="card-body">
                      <h4 class="fs-6 text">
                        {{$spec->heading}}
                      </h4>
                      <p class="fs-7 text mb-0 text-center text-md-start">{{$spec->text}}</p>
              </div>
              </div>
            @endforeach
        </div>
    </div>
</section>
<!-- specialties section end -->
<!-- discription start -->
<section class="discription discription_start">
    <div class="container">
        <div class="row content_inner">
            <div class="col-lg-8 ">
              <div class="heading_main">
                <img src="/assets/img/icons/heading_icon.svg">
                <h2 class="fs-2 text fw-bold mb-3">
                    {{ $course->name }}
                </h2>
              </div>
                <!-- <h5 class="fs-4 text fw-semibold">
                    Description
                </h5> -->
                {!! $course->description !!}
                @php
                $modules = $course->modules;
                @endphp
                @if(count($modules))
                  <ul class="group-list d-flex flex-wrap gap-md-4 gap-2 p-0">
                    <li class="list-group-item fw-semibold" id="totalmodules">{{count($modules)}} Chapters</li>
                    <li class="list-group-item fw-semibold" id="totallectures">{{$course->topics->count()}} Lectures</li>
                  </ul>
                @endif
                @php
                $totalmodules=0;
                $totallectures=0;
                @endphp
                <div class="accordion" id="accordionPanelsStayOpenExample">
                  @foreach ($modules as $key => $module) <!-- Add index to ensure unique IDs -->
                    @php
                      $contents=$module->content;
                    @endphp
                    @if($contents->count())
                    @php
                    $totalmodules++;
                    @endphp
                      <div class="accordion-item topics">
                        <h2 class="accordion-header">
                          <button class="d-flex justify-content-between accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{ $key }}" aria-expanded="false" aria-controls="panelsStayOpen-collapse{{ $key }}">
                            <div class="">
                              <span class="svg-img d-flex align-items-center justify-content-center">
                                <i class="bi bi-folder2-open text-white fw-bold fs-5"></i>
                              </span>
                            </div>
                            <div class="ms-2">
                              <span class="fw-semibold  course-titles">{{ $module->name }}</span>
                              <spam class="d-none  d-md-block fs-9 topic-ttt text-gold" id="contentcounts{{$key}}">Topic: {{$contents->count()}}
                              </div>
                            </button>
                          </h2>
                          @php
                          $contentcounts=0;
                          @endphp
                          <div id="panelsStayOpen-collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading{{ $key }}"
                          data-bs-parent="#accordionPanelsStayOpenExample">
                            <div class="accordion-body course-acco">
                              <ul class="list-group course-list-g">
                                @foreach ($contents as $contentKey => $content)
                                  @if($previewVideo = $content->preview)
                                  @php
                                  $totallectures++;
                                  $contentcounts++;
                                  @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      <div class="d-flex align-items-center">
                                        <div class="content-icon d-flex align-items-center justify-content-center">
                                          <div class="">
                                            <i class="bi bi-chevron-right"></i>
                                          </div>
                                        </div>
                                        <span>{{ $content->title }}</span>
                                      </div>
                                      <div>
                                        @if($content->files)
                                        <a href="{{asset($content->files->file)}}" class="pointer">
                                          <i class="fs-4 bi bi bi-file-earmark-fill text-gold pointer"></i>
                                        </a>
                                        @endif
                                        @if($previewVideo->type == 'youtube')
                                          @php
                                            $url = 'https://www.youtube.com/embed/'.$previewVideo->video_id;
                                          @endphp
                                          <i onclick="youtubeVideoPlay('{{$url}}','{{$previewVideo->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')" class=" pointer fs-4 bi bi-play-circle-fill"></i>
                                        @elseif($previewVideo->type == 'vimeo')
                                          @php
                                            $url = $previewVideo->player_embed_url;
                                          @endphp
                                          <i onclick="vimeoVideoPlay('{{$url}}','{{$previewVideo->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}', '{{$content->title}}')" class=" pointer fs-4 bi bi-play-circle-fill"></i>
                                        @endif
                                      </div>
                                    </li>
                                  @endif
                                @endforeach
                              </ul>
                              <script type="text/javascript">
                                document.getElementById('contentcounts{{$key}}').innerText = 'Topic: {{$contentcounts}}';
                              </script>
                            </div>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </div>
                @if($course->ilearn)
                <section class="benifits py-3">
                    <div class="container ms-0 ps-0">
                        <div class="row ">
                            <div class="col-lg-12">
                              <div class="heading_main">
                                <img src="/assets/img/icons/heading_icon.svg">
                                <h2 class="heading mb-3 ms-0">What you will learn?</h2>
                            </div>
                          </div>
                        </div>
                        <div class="row content_inner">
                          {!! $course->ilearn !!}
                        </div>
                    </div>
                </section>
                @endif
                <!-- benifits section start -->
                <section class="benifits py-3">
                    <div class="container ms-0 ps-0">
                        <div class="row">
                            <div class="col-lg-12">
                              <div class="heading_main">
                                <img src="/assets/img/icons/heading_icon.svg">
                                <h2 class="heading mb-3 ms-0">Who can benefit from enrolling?</h2>
                            </div>
                          </div>
                        </div>
                        @foreach($course->benifits as $key => $benifit)
                          <div class="card">
                            <div class="d-md-flex align-items-center">
                              <div class="col-md-3 col-12">
                                <div class="benifit-icon-box">
                                  <img src="{{$benifit->image ? $benifit->image->image(300,300) : ''}}" height="300" width="300" class="card-img-top img-fluid" alt="{{$benifit->title}}">
                                </div>
                              </div>
                              <div class="col-md-9 col-12">
                                <div class="card-body">
                                  <h5 class="card-title text-md-left fw-semibold fs-6 d-none d-md-block">{{$benifit->title}}</h5>
                                  <h5 class="card-title text-center fw-semibold fs-6 d-md-none d-block">{{$benifit->title}}</h5>
                                  <p class="card-text beni-p d-none d-md-block" style="text-align:left;">{{ strip_tags($benifit->description) }}</p>
                                  <p class="card-text beni-p text-center  d-md-none d-block" style="text-align:left;">{{ strip_tags($benifit->description) }}</p>
                                </div>
                              </div>
                            </div>

                          </div>
                        @endforeach
                    </div>
                </section>
                <!-- benifits section end -->
                <!-- like section start -->

                @if(($course->type == 1) && isset($liveCourses) && count($liveCourses))
                <section class="py-3">
                  <div class="container ps-0">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="heading_main">
                      <img src="/assets/img/icons/heading_icon.svg">
                        <h2 class="fs-5 fw-semibold">Related Live Courses</h2>
                      </div>
                    </div>
                        </div>
                    <div class="row">
                      <div class="col-lg-12 ps-md-0">
                        <div class="live_courses-2">
                  @foreach($liveCourses as $item)
                  <div class="item">

                      <div class="card border-0">
                                <div class="course_bg">
                                    <img class="course_logo pt-2 img-fluid" src="/assets/images/iiag-logo10.png" alt="{{ $item->name }}">
                                    <div class="row align-items-center">
                                        <div class="col-7 ps-4">
                                             <h5 class="fw-bold fs-6 cours_name text-left f-name">{{ $item->name }}</h5>
                                             {{--
                                               <h5 class="fw-bold text-left l-name">{{ $item && $item->service ? $item->service->name : '' }}</h5>
                                            --}}
                                            <span class="text-center">By</span><br>
                                            <span class="d-inline-block fs-6 text fw-bold text-truncate"style="max-width:150px;">{{ $item->instructor ? $item->instructor->display_name : '' }}</span>
                                        </div>
                                        <div class="user col-5 p-0">
                                            <img src="{{ $item->image ? $item->image->image(360, 209) : '/assets/images/image 218.png' }}" alt="{{ $item->name }}">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="fs-7 text" scope="row">Classes</th>
                                        <td class="fs-7 text text-truncate" style="max-width:150px;">
                                          {{$item->total_class}}
                                          (@foreach($item->courseDays as $dayKey => $day)
                                          {{$day->day}},
                                          @endforeach)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fs-7 text" scope="row">Started</th>
                                        <td class="fs-7 text">on {{date('d-m-Y', strtotime($item->start_date))}}</td>
                                    </tr>
                                    @if($item->start_time && $item->end_time)
                                    <tr>
                                        <th class="fs-7 text" scope="row">Time</th>
                                        <td class="fs-7 text">{{\DateTime::createFromFormat('H:i', $item->start_time)->format('h:i A')}} - {{\DateTime::createFromFormat('H:i', $item->end_time)->format('h:i A')}}</td>
                                    </tr>
                                    @endif
                                    @if($item->special_from_date && (date('Y-m-d') >= $item->special_from_date) && (date('Y-m-d') <= $item->special_to_date) )
                                    <tr>
                                        <th class="fs-7 text" scope="row">Price</th>
                                        <td class="fs-6 text fw-bold">
                                            <del>₹ {{$item->price}}</del> <span class="text-ac-price"> ₹ {{$item->special_price}}</span>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <th class="fs-7 text" scope="row">Price</th>
                                        <td class="fs-6 text fw-bold">
                                            <span class="text-ac-price"> ₹ {{$item->price}}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                <div class="card-body pt-0 mt-0">
                                    <div class="footer d-flex gap-2">
                                      @if($item->publish)
                                        <form id="cartfom-{{ $item->id }}" class="cartfom" action="" method="post">
                                             @csrf
                                             <input type="hidden" name="item_id" value="{{ $item->id }}">
                                             <input class="main_button pulse" type="submit" value="Enroll Now ">
                                         </form>
                                      @endif
                                        <a href="{{ route('course.handle', [$course->slug]) }}">
                                        <button class="float-shadow btn btn-primary btn-sm">Know More</button></a>
                                    </div>
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


                @if(($course->type == 3) && isset($onlineCourses) && count($onlineCourses))
                <section class="py-md-0 related_courses py-2">
                  <div class="container ps-0">
                    <div class="row align-items-center gx-0 mx-2">
                      <div class="col-lg-12 col-sm-12 col-12">
                        <div class="heading_main">
                      <img src="/assets/img/icons/heading_icon.svg">
                        <h2 class="fw-bold heading">Related Online Courses</h2>
                      </div>
                    </div>
                    </div>
                        <div class="live_courses-2">
                          @foreach($onlineCourses as $item)
                                <div class="item">
                                    <a href="{{ route('course.handle', [$item->slug]) }}">
                                    <div class="card border-0 p-card">
                                      <img src="{{$item->image ? $item->image->image(360, 209) : asset('assets/images/image 218.png') }}" class="card-img-top" alt="{{ $item->name }}">
                                    <div class="card-body">
                                    @if($item->special_from_date && (date('Y-m-d') >= $item->special_from_date) && (date('Y-m-d') <= $item->special_to_date) )
                                      <div class="tag">{{ round(100 - (($item->special_price / $item->price) * 100)) }}% Off</div>
                                    @endif
                                  <!-- <div class="name">
                                      <img src="{{$item && $item->image ? $item->image->image() : ''}}" class="ins-img" alt="Instructor Image">
                                      <h6 class="fw-bold c-h  {{$item->course_type}}">{{ $item->instructor ? $item->instructor->display_name : '' }}</h6>
                                  </div> -->
                                  <div class="spacalization">
                      <span class="c-h-f">{{ $course->category->name }}</span>
                  </div>
                                  <div class="category">
                                      <span class="text-truncate" style="max-width:150px;">{{ $item->name }}</span>
                                  </div>
                                  <div class="footer  justify-content-between align-items-center">
                                    <div class="d-flex">
                                    @if($item->special_from_date && (date('Y-m-d') >= $item->special_from_date) && (date('Y-m-d') <= $item->special_to_date) )
                                      <a href="javascript:void(0)" class="actual_price fs-7 text fw-semibold">₹{{ number_format($item->price) }}</a>
                                      <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold ms-2 text-orange">₹{{ number_format($item->special_price) }}</a>
                                    @else
                                      <a href="javascript:void(0)" class="sale_price fs-7 text text-left fw-semibold">₹{{ number_format($item->price) }}</a>
                                    @endif
                                    </div>
                                    <div class="mt-2">
                                      <a href="{{ route('course.handle', [$item->slug]) }}">
                                        <button class="live-courses" >
                                          Know More
                                        </button>
                                      </a>
                                    </div>
                                  </div>

                              </div>
                              </a>
                          </div>
                  </div>

                  @endforeach

                        </div>
                  </div>
                </section>
                @endif
                <!-- like section End -->


            </div>
            <div class="col-lg-4 side desktop-r d-none d-md-block">
              <div class="sidebar-course-enroll" >
                <div class="fixed-enroll">
                <div class="card p-2 d-card-m">
                    <div class="card-body">
                    @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                      <h5>
                          <b>Price : </b>₹<strike>{{$course->price}}</strike> ₹{{$course->special_price}}
                      </h5>
                      @else
                      <h5>
                          <b>Price : </b>₹{{$course->price}}
                      </h5>
                      @endif
                      @if($course->publish)
                        @if($user)
                          @if($course->purchased)
                            <a class="but_width" onclick="alreadyPurchased()">
                               <button class="live-courses-active2 main_button pulse">Enroll Now</button>
                            </a>
                          @else
                            <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                               <button class="live-courses-active2 main_button pulse">Enroll Now</button>
                            </a>
                          @endif
                        @else
                        <button  type="button" onclick="checkAuth('{{ route('checkout', ['buynow', 'course', $course->slug]) }}')" class="main_button pulse mb-3 mb-md-0">Enroll Now</button>
                        @endif
                      @endif
                    </div>

                </div>
                <div class="gth-form">
                  <div id="message" class="text-center">
                  </div>
                  <div class="form-title">
                    <h2 class="fs-2 text">Get Course Brochure</h2>
                    <p class="fs-7 text">To get more information fill the form below, our team will contact you shortly</p>
                    <form action="" id="getIn" class="formValidateAndSubmit">
                      <input type="hidden" name="type" value="Course Inquiry">
                      <input type="hidden" name="page_type" value="courses">
                      @if($course->type==1)
                      <input type="hidden" name="category" value="Live (Online & Offline)">
                      @elseif($course->type==2)
                      <input type="hidden" name="category" value="Recoreded">
                      @elseif($course->type==3)
                      <input type="hidden" name="category" value="E-Learning">
                      @endif
                      <input type="hidden" name="course" value="{{$course->name}}">
                        <div class=" mb-2">
                            <input class="form-control form-control-lg" name="name" type="text" placeholder="Full Name">
                        </div>
                        <div class=" mb-2">
                            <input class="form-control form-control-lg" name="phone" type="number" placeholder="Mobile Number">
                        </div>
                        <div class=" mb-2">
                            <input class="form-control form-control-lg" type="email" name="email" placeholder="Email Id">
                        </div>
                        <div class=" mb-2">
                            <input class="form-control form-control-lg" name="message" type="text" placeholder="Message" rows="3">
                        </div>
                        <div class="mb-2">
                            <div class=" text-left">
                                <button class="main_button" id="enquirynow">Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <div class="col-lg-4 mobile" style="position:relative">
          <div  class="touch-mobile">
            <div class="card p-2 d-card-m">
              <div class="card-body enroll-noww">
                <div class="d-none d-md-block">
                  @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                  <h5>
                    <b>Price : </b>₹ <strike>{{$course->price}}</strike> ₹ {{$course->special_price}}
                  </h5>
                  @else
                  <h5>
                    <b>Price : </b>₹ {{$course->price}}
                  </h5>
                  @endif
                </div>
                @if($course->publish)
                  @if($user)
                  <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                    <button type="button" class="main_button pulse mb-3 mb-md-0">Enroll Now</button>
                  </a>
                  @else
                  <button  type="button" onclick="checkAuth('{{ route('checkout', ['buynow', 'course', $course->slug]) }}')" class="main_button pulse mb-3 mb-md-0">Enroll Now</button>
                  @endif
                @endif
                </div>
            </div>
        </div>
    </div>
      </div>
    </div>

</section>
<!-- discription End -->
<!-- faq section start -->

<!-- testimonials shop section start -->
        @include(themeView().'common.textTestimonial')

        <!-- testimonials service section start -->
        <!-- video shop section start -->
        @include(themeView().'common.videoTestimonial')
        <!-- video service section start -->
<section class="Rating py-3 py-md-5 ">
    <div class="container px-4">
        <div class="row d-flex">
            <div class="col-lg-6 ">
              <h4 class="fs-5 text">Rating and Reviews  (<i class="bi text-dark bi-person-fill"></i> {{count($course->reviews)}})</h4>
              <p>Only ratings from verified purchases are included in the final evalution.</p>
                <div class="card p-3 star_box">
                    <div class="row ">
                        <div class="col-lg-4 col-12 my-rating-box">
                            <div class="rounded-circle rating-count text-center bg-light ">
                              @if(isset($rating))
                              <h2>{{$rating}}</h2>
                                <ul class="p-0">
                                  <li class="list-type-group d-flex justify-content-center align-col-lg-4s-center">
                                  @for($i=0;$i<$rating;$i++)
                                    <i class="bi text-rating bi-star-fill"></i>
                                  @endfor
                                    </li>
                                </ul>
                              @else
                              <h2>4.9</h2>
                                <ul class="p-0">
                                    <li class="list-type-group d-flex justify-content-center align-col-lg-4s-center">
                                    <i class="bi text-rating bi-star-fill"></i>
                                    <i class="bi text-rating bi-star-fill"></i>
                                    <i class="bi text-rating bi-star-fill"></i>
                                    <i class="bi text-rating bi-star-fill"></i>
                                    <i class="bi text-rating bi-star-half"></i>
                                    </li>
                                </ul>
                              @endif
                            </div>
                        </div>
                        <div class="rating-bar col-lg-6 col-12 p-2">
                          @if(isset($stars) && $stars)
                            @for($i=(count($stars)-1);$i>=0;$i--)
                              @if($i)
                                <div class=" d-flex my-pro">{{$i}}<i class="bi bi-star-fill"></i>
                                    <div class="progress col-8 m-2">
                                        <div class="progress-barbg-warning  bg-warning" role="progressbar" style="width: {{count($course->reviews) ? ($stars[$i]/count($course->reviews))*100 : 0}}%" aria-valuenow="80" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span>{{$stars[$i]}}</span>
                                </div>
                              @endif
                            @endfor
                          @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 sumbmit-frm" id="review-form">
              @if(( auth()->guard('web')->check()  || auth()->guard('astrologer')->check() ) && count($course->orderItem))
                <h4 class="fs-5 text">
                    Submit a Review
                </h4>
                <p>Share Your Experience with Our Courses</p>
                  <div class="card p-3 ">
                      <form action="" method="post" id="courseR">
                          @csrf
                          <div class="row">
                          <div class="col-lg-12 col-12">
                              <div class="mb-2">
                                  <textarea class="form-control" placeholder="Write a Review" name="comments" id="comments" rows="2" cols="100" required></textarea>
                              </div>

                              <!-- Radio buttons for star rating -->
                              <div class="btn-group mb-2 ratings" role="group" aria-label="Star Rating">
                                <input type="hidden" id="rating" name="rating" value="0">
                                    <i class="ratings_stars bi fs-2 text bi-star" data-rating="1"></i>
                                    <i class="ratings_stars bi fs-2 text bi-star" data-rating="2"></i>
                                    <i class="ratings_stars bi fs-2 text bi-star" data-rating="3"></i>
                                    <i class="ratings_stars bi fs-2 text bi-star" data-rating="4"></i>
                                    <i class="ratings_stars bi fs-2 text bi-star" data-rating="5"></i>
                              </div>
                          </div>
                          </div>

                          <!-- Submit button -->
                          <input type="submit" class="btn btn-secondary" value="Submit Review">
                      </form>

                  </div>
              @endif
            </div>
        </div>
</section>

@if(isset($faqs) && count($faqs))
  <section class="py-0 py-md-5 mt-3">
      <div class="container bg-light container-xl pt-5 ">
          <div class="row justify-content-center">
              <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center">
                <div class="heading_main">
                  <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
                  <h2 class="fs-3 heading">Frequently Asked Questions</h2>
                  <p class="fs-6 text-center">These FAQ helps potential students quickly find relevant information and make informed decisions about enrolling in the course.</p>
              </div>
            </div>
          </div>
          <div class="row justify-content-center py-3">
              <div class="col-lg-8">
                  <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $key => $faq)
                      <div class="accordion-item">
                          <h2 class="accordion-header">
                              <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                  data-bs-target="#faq-coll-{{$key}}" aria-expanded="false"
                                  aria-controls="faq-coll-{{$key}}">
                                {{$faq->question}}
                              </button>
                          </h2>
                          <div id="faq-coll-{{$key}}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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

<section class="review  pt-5">
    <div class="container ">

      <div class="row p-3 astrologerReview">
        @if(isset($course) && count($course->reviews))
        @foreach($course->reviews as $key => $review)

                <div class="text-center text-md-start">
                  @if($review->user)
                  <div class="d-flex justify-content-center justify-content-md-start">
                    <img src="{{ $review->user->image ? $review->user->image->image(88,88) : '' }}" alt="{{$review->user->name}}" height="88px" width="88px" class="radius">

                  </div>
                    <div class="text ps-0">
                        <h6 class="m-0 ps-0 mt-2">{{$review->user->name}}</h6>
                        <p class="m-0 py-1 date_time">{{date('d-m-Y', strtotime($review->created_at))}}</p>
                        <p class="comment lh-1 m-0">{{$review->review}}</p>
                    </div>
                    @elseif($review->astrologer)
                    <img src="{{ $review->astrologer->image ? $review->astrologer->image->image(88,88) : '' }}" alt="{{$review->astrologer->firstname}}" height="88px" width="88px" class="radius">
                      <div class="text ps-2">
                          <h6 class="m-0">{{$review->astrologer->firstname." ".$review->astrologer->lastname}}</h6>
                          <p class="m-0 py-1 date_time">{{$review->created_at}}</p>
                          <p class="comment lh-1 m-0">{{$review->review}}</p>
                      </div>

                    @endif
                    <div class="d-flex justify-content-center justify-content-md-start">
                      <ul class="ratings list-unstyled d-flex  justify-content-left mt-2 text-left">
                          @for($i=0;$i<$review->rating;$i++)
                          <li><i class="bi bi-star-fill"></i></li>
                          @endfor
                      </ul>
                    </div>
                </div>
            @endforeach
            @endif
          </div>

    </div>
</section>


<!-- faq section End -->
<!-- sidebar-start -->
<div class="d-md-none d-block v-btn-box">
  <div class="d-md-none d-block v-btn-box">
    <button type="button" name="button text-white" class="arrow-b btn verticalButton" data-bs-toggle="modal" data-bs-target="#contact" id="scrollButton">
      Enquire Now
    </button>
  </div>
    </div>
<!--
contect-popup-start -->
<div class="modal fade mt-5 course-m" id="contact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
<div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
      <div class="modal-body p-0">

            <div class="">
            <div class="card d-card-m btn-bg p-2">
                <div class="card-body p-2">
                @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                  <h5 class="pb-0">
                      <b>Price : </b>₹<strike>{{$course->price}}</strike> ₹{{$course->special_price}}
                  </h5>
                  @else
                  <h5>
                      <b>Price : </b>₹{{$course->price}}
                  </h5>
                  @endif
                    @if($course->publish)
                  <form id="cartfom-{{ $course->id }}" class="cartfom" action="" method="post">
                       @csrf
                       <input type="hidden" name="item_id" value="{{ $course->id }}">

                   </form>
                   @endif
                </div>

            </div>
            <div class="gth-form p-5 pb-4 pt-3">
              <div id="message" class="text-center">
              </div>
                <div class="form-title p-2">
                    <h2 class="fs-2 text">Get Course Brochure</h2>
                    <p class="fs-6 text">
                        To get more information fill the form below, our team will contact you shortly
                    </p>
                    <form action="" id="getIn" class="formValidateAndSubmit">
                      <input type="hidden" name="type" value="Course Inquiry">
                      <input type="hidden" name="page_type" value="courses">
                      @if($course->type==1)
                      <input type="hidden" name="category" value="Live (Online & Offline)">
                      @elseif($course->type==2)
                      <input type="hidden" name="category" value="Recoreded">
                      @elseif($course->type==3)
                      <input type="hidden" name="category" value="E-Learning">
                      @endif
                      <input type="hidden" name="course" value="{{$course->name}}">
                        <div class="row mb-2">
                            <input class="form-control form-control-lg" name="name" type="text" placeholder="Name">
                        </div>
                        <div class="row mb-2">
                            <input class="form-control form-control-lg" type="email" name="email" placeholder="Email">
                        </div>
                        <div class="row mb-2">
                            <input class="form-control form-control-lg" name="phone" type="number" placeholder="Mobile Number">
                        </div>
                        <div class="row mb-2">
                            <input class="form-control form-control-lg" name="message" type="text" placeholder="Message" rows="3">
                        </div>
                        <div class="row mb-2">
                            <div class=" text-center">
                                <button class="btn btn-secondary w-50 w-md-100" id="enquirynow">Enquire Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal modal-lg" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-preview-1Label" id="content-preview-modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header course-de">
          <button type="button" class="btn-close position-absolute v_btn" id="content-preview-modal-close-btn" ></button>
      </div>
      <div class="modal-body">
        <h6 id="content-title">title</h6>
        <div class="main-f" id="main-frame"></div>
      </div>
    </div>
  </div>
</div>


    <!-- side-bar-end -->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://player.vimeo.com/api/player.js"></script>
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
<script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
let totalmodules = '{{$totalmodules}}';
let totallectures = '{{$totallectures}}';

$('#totalmodules').text(totalmodules+' Modules');
$('#totallectures').text(totallectures+' Lectures');
</script>

<script type="text/javascript">
var mainFrame = $('#main-frame');
function vimeoVideoPlay(url, video_id=0, course_id=0, module_id=0, content_id=0, title=""){
  $('#content-title').html(title);
  mainFrame.html('');
  let iframe = $("<iframe>", {src:url});
  iframe.attr('width', '100%');
  iframe.attr('height', '400');
  mainFrame.append(iframe);
  let player = new Vimeo.Player(iframe, { background: true });
  player.setQuality('240p').then(function() {
    console.log('Quality set to 240p');
  }).catch(function(error) {
      console.error('Error setting quality:', error);
  });
  $('#content-preview-modal').modal('show');
}
function youtubeVideoPlay(url, video_id = 0, course_id = 0, module_id = 0, content_id = 0, title='') {
    $('#content-title').html(title);
    mainFrame.html('');
    url = `https://www.youtube.com/embed/${video_id}?origin=https://plyr.io&iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1`;
    let iframe = $("<iframe>", {src:url});
    iframe.attr('width', '100%')
    iframe.attr('height', '400')
    mainFrame.append(iframe);
    const player = new Plyr('#main-frame');
    $('#content-preview-modal').modal('show');
}

$('#content-preview-modal-close-btn').on('click', function(){
  $('#content-preview-modal').modal('hide');
  mainFrame.html('');
});
</script>
<script>
  let mainFramee = $('#preview-video-div');
  function previewVideoPlay(that, video_id){
    mainFramee.html('');
    url = `https://www.youtube.com/embed/${video_id}?origin=https://plyr.io&iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1`;
    let iframe = $("<iframe>", {src:url});
    iframe.attr('width', '100%')
    iframe.attr('height', '400')
    mainFramee.append(iframe);
    let playerr = new Plyr('#preview-video-div');
    $('#modal-preview-1').modal('show');
    playerr.on('ready', function(){
      playerr.play();
    });
  }

  $('#modal-preview-1-close-btn').on('click', function(){
    $('#modal-preview-1').modal('hide');
    mainFramee.html('');
  });


$(document).ready(function() {
    $('#enroll-btn').click(function() {
        var courseId = $(this).data('course-id');

        // Check if the user is logged in
        var isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

        if (isLoggedIn) {
            // Show SweetAlert confirmation dialog before proceeding with the AJAX call
            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
              },
              buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
              title: "Are you sure?",
              text: "You are about to enroll in this course.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "Yes, enroll!",
              cancelButtonText: "No, cancel!",
              reverseButtons: true
            }).then((result) => {
              if (result.isConfirmed) {
                // If confirmed, proceed with the AJAX request
                var userId = '{{ auth()->user() ? auth()->user()->id : '' }}';

                $.ajax({
                    url: '{{ route('courseenroll') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        course_id: courseId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        swalWithBootstrapButtons.fire({
                          title: "Enrolled!",
                          text: "You have been successfully enrolled in the course.",
                          icon: "success"
                        });
                    },
                    error: function(xhr) {
                        swalWithBootstrapButtons.fire({
                          title: "Error!",
                          text: "There was a problem enrolling in the course. Please try again.",
                          icon: "error"
                        });
                    }
                });
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                  title: "Cancelled",
                  text: "Your enrollment has been cancelled.",
                  icon: "error"
                });
              }
            });
        } else {
            // If the user is not logged in, open the login modal
            openLoginModal();
        }
    });

});

// Function to open the login modal
function openLoginModal() {
    $('#loginModal').modal('show');
}
</script>

<script>
  var isAlertShow = true;
</script>
    <script type='text/javascript' defer>
        $(document).ready(function () {
            var $sticky = $('.sticky');
            var $stickyrStopper = $('.sticky-stopper');
            if (!!$sticky.offset()) { // make sure ".sticky" element exists
                var generalSidebarHeight = $sticky.innerHeight();
                var stickyTop = $sticky.offset().top;
                var stickOffset = 0;
                var stickyStopperPosition = $stickyrStopper.offset().top;
                var stopPoint = stickyStopperPosition - generalSidebarHeight - stickOffset;
                var diff = stopPoint + stickOffset;
                $(window).scroll(function () { // scroll event
                    var windowTop = $(window).scrollTop(); // returns number
                    if (stopPoint < windowTop) {
                        $sticky.css({position: 'absolute', top: diff});
                    } else if (stickyTop < windowTop + stickOffset) {
                        $sticky.css({position: 'fixed', top: stickOffset});
                    } else {
                        $sticky.css({position: 'absolute', top: 'initial'});
                    }
                });
            }
        });
    </script>
    <script type='text/javascript' defer>
        $(function () {
            $("#addClass").click(function () {
                $('#qnimate').addClass('popup-box-on');
            });

            $("#removeClass").click(function () {
                $('#qnimate').removeClass('popup-box-on');
            });
        })
    </script>

    <script type="text/javascript">
    $(document).ready(function() {
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
                  toastr.success('Item added successfully');
                  sessionUserId = response.user_id;
                  showCart2();
                },
                error: function(xhr) {
                  toastr.error('Error adding item to cart. Please try again.');
                }
            });
        });
    });

    $('.ratings').on('click', '.ratings_stars', function () {
      var star = $(this);
      $('.ratings_stars').removeClass('bi-star').addClass('bi-star-fill');
      star.addClass('bi-star-fill');
      star.prevAll().addClass('bi-star-fill');
      star.nextAll().removeClass('bi-star-fill').addClass('bi-star');
      $('#rating').val(star.data('rating'));
    });

    $('#courseR').on('submit',function(e){
      e.preventDefault();
      let frmData = $(this).serialize();
      $.ajax({
        url : "{{route('course.review', ['slug' => $course->slug])}}",
        type : 'POST',
        data : frmData,
        success:function(){
          toastr.success('Review Submitted Successfully');
          $('#productR')[0].reset();
        }
      })
    })
    </script>
<script>
  let scrollTimeout;

  window.addEventListener('scroll', () => {
    const button = document.getElementById('scrollButton');

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
<script type="text/javascript">

function alreadyPurchased(){
  toastr.error('Course already purchased.');
}

$('.formValidateAndSubmit').on('submit', function(e) {
    e.preventDefault();
    let _f = $(this);
    _f.find('button[type=submit]').attr('disabled', true);
    $(_f).find("input[required], select[required], textarea[required]").each(function() {
        if (!this.checkValidity()) {
            toastr.error($(this).attr('name') + ' is required.');
        }
    });
    let formData = _f.serialize();
    $.ajax({
        url: '{{url("/lp/form/submit")}}',
        type: 'POST',
        data: formData,
        success: function(response) {
            toastr.success(response.message);
            setTimeout(function() {
                toastr.success('Thank You & Visit Again.');
                if(response.redirectTo){
                  window.location.href = response.redirectTo;
                }
            }, 1500);
        },
        error: function(error) {
            _f.find('button[type=submit]').attr('disabled', false);
            if (error.responseJSON && error.responseJSON.errors) {
                error = error.responseJSON.errors;
                for (var key in error) {
                    toastr.error(error[key][0]);
                }
            }
            console.log(error);
        }
    });
});


</script>
@endpush
