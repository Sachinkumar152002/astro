@foreach($courses as $course)
@if($course->type == 1)
<!-- live -->
  <div class="item">
      <div class="card border-0">
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
                <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="but_width">
                   <button class="live-courses-active">Enroll Now</button>
                </a>
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

  @else

 <div class="col-lg-4 ">
  <div class="item online-course">
        <div class="card border-0 radius">
          <a href="{{ route('course.handle', [$course->slug]) }}"> <!-- Assuming you have a route for course details -->
            <img src="{{ $course->image ? $course->image->image(1200,675) : '' }}" class="card-img-top card-img-h" alt="{{ $course->name }}">
          </a>
              <div class="card-body min-hh">
              @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                  <div class="tag">{{ round(100 - (($course->special_price / $course->price) * 100)) }}% Off</div>
                @endif
                <div class="name">
                    <img src="{{$course && $course->image ? $course->image->image(360, 209) : ''}}" class="ins-img" alt="Instructor Image"> <!-- Placeholder image for instructor -->
                    <h6 class="fw-bold">{{ $course->instructor ? $course->instructor->display_name : '' }}</h6>
                </div>
                <div class="category">
                    <div class="card-icon">
                      <i class="bi bi-list-check text-white"></i>
                    </div>
                    <h6>{{ $course->short_name }}</h6>
                </div>
                <div class="spacalization">
                    <div class="card-icon">
                      <i class="bi bi-journal-text text-white"></i>
                    </div>
                    <h6>{{ $course->category->name }}</h6>
                </div>
                <div class="footer">
                @if($course->special_from_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) )
                    <a href="javascript:void(0)" class="actual_price fs-7 text fw-semibold"><del>₹{{ number_format($course->price) }}</del></a>
                        <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold">₹{{ number_format($course->special_price) }}</a>
                    @else
                        <a href="javascript:void(0)" class="sale_price fs-7 text text-left fw-semibold">₹{{ number_format($course->price) }}</a>
                    @endif
                    @if($course->publish)
                      <form id="cartfom-{{ $course->id }}" class="cartfom" action="" method="post">
                           @csrf
                           <input type="hidden" name="item_id" value="{{ $course->id }}">
                           <button type="submit" class="float-shadow live-courses" >Enroll Now</button>
                       </form>
                     @endif
                     {{--
                    <a href="{{ $course->status ? route('course.handle', [$course->slug]) : 'javascript:void(0)' }}">
                      <button class="float-shadow btn btn-primary btn-sm" >Enroll Now</button>
                    </a>
                    --}}
                </div>
            </div>
        </div>
     </div>
    </div>
  @endif
  @endforeach
