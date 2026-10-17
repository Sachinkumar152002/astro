@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
<section class="dashboad_section">
  <div class="container">
  <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
      <div class="row my-3 recent">
        <div class="col-lg-12">
          <h4>
          Recent Courses
          </h4>
        </div>

          @if(isset($lms) && count($lms))
            @foreach($lms as $keyLms => $lmsCourse)
              @if($lmsCourse->course)
                <div class="col-lg-4 live_courses live_courses_dashboard  dc-c-card">
                   <div class="card border-0">
                    <div class="course_bg">
                        <img src="{{ $lmsCourse->course->image ? $lmsCourse->course->image->image(350,350) : no_image() }}" alt="{{$lmsCourse->course->name}}" class="w-100 h-100 img-fluid">
                     </div>
                        <h5 class="course_bg_h5 text-truncate fw-bold">{{ $lmsCourse->course->name }}</h5>
                        <div class="pb-2 fw-bold">
                          Price:
                          <span class="ms-0 ms-md-1 sale_price text-left fs-7 text text-ac-price" tabindex="0">₹ {{inrConvert($lmsCourse->amount)}}</span>
                       </div>
                       <div class="d-flex" style="align-items: baseline;gap: 0.4em">
                          {{-- @if($lmsCourse->course->status)
                            <a href="{{route('course.handle', [$lmsCourse->course->slug])}}" class="live-courses-active w-50 d-block text-center mb-3 mt-3">View Details</a>
                          @endif --}}
                          @if(count($lmsCourse->course->modules))
                            <a href="{{route('user.course.explore', [$lmsCourse->course->slug])}}" class="w-100">
                              <button class="live-courses">Explore Page</button>
                            </a>
                          @endif
                      </div>
                  </div>
                </div>
              @endif
            @endforeach
          @else
            <div class="no-course my-3 text-center">
              <img src="{{asset('/assets/images/product.webp')}}" alt="No Item" class="nodata">
               <h5 class="mt-3 text-dark"> No  Course Found!</h5>
               <p class="text-secondary"> Courses will be displayed here.</p>
            </div>
          @endif
      </div>
    </div>
  </div>
  </div>
</section>
@endsection
