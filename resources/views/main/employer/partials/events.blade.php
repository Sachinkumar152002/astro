<div class="col-md-6 wow slideInRight">
    <div class="events-section">
      <div class="box">
        <h3>Events at <span>smart skill tech</span></h3>
        <div class="row">
          @if(count($events)>0)
          @foreach($events as $event)
          <div class="col-md-12">
            <div class="box1 row">
              <div class="mentor-image col-md-3"><img src="{{$event->image ? $event->image->image(100,100) : asset('images/payu.jpg')}}"></div>
              <div class="candidate-info col-md-9">
                <div class="row">
                  <div class="title-box">
                    <div class="col-md-9"><h3>{{$event->title}}</h3></div>
                    <div class="col-md-3">
                      <ul class="date-city">
                        <li class="date">{{date('M d Y', strtotime($event->event_date))}}</li>
                        <li class="city">{{$event->cityobj ? $event->cityobj->name : ''}}</li>
                      </ul>
                    </div>
                  </div>
                  <p>{{$event->short_description}}</p>
                  <!-- <div class="viewall-btn-small">
                    <button>View All</button>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
          @endforeach
          <div class="ask-ques-btn text-center"><a href="{{route('employer.events')}}" class="button">View All</a></div>
          @else
          <p class="text-center">No Event</p>
          @endif
        </div>
      </div>
    </div>
  </div>