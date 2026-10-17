<div class="col-md-6 wow slideInLeft">
    <div class="box">
      <h3><span>Trained Candidates</span></h3>
      <div class="row">
        @if($trained)
        @foreach($trained as $student)
        <div class="col-md-6">
          <div class="box1">
            <div class="mentor-image text-center"><img src="{{$student->details && $student->details->avatar ? $student->details->avatar->image(100,100)  : $student->no_image}}"></div>
            <div class="clearfix"></div>
            <div class="candidate-info ">
              <h3 class="text-center"><span>{{ucfirst($student->name)}}</span></h3>
              {{--<p>Trained in : $student->id</p>--}}
              {{--<p>Certtification Type : $student->id</p>--}}
            </div>
          </div>
        </div>
        @endforeach
        @endif
        <!-- <div class="ask-ques-btn text-center"><button>View All</button></div> -->
      </div>
    </div>
  </div>