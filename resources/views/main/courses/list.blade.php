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
