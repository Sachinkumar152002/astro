<div class="col-md-6 wow slideInLeft">
    <div class="box">
      <h3>Video <span>Resume</span></h3>
      <div class="row">
        @if($videos)
        <div class="video-resume-box">
        @foreach($videos as $video)
        <div class="col-md-6">
          <div class="box1">
            <div class="video-resume-img text-center">
              @if($video && $video->youtube)
              <iframe width="100%" height="200" src="{{$video->youtube}}" frameborder="0" allowfullscreen></iframe>
              @else
              <img src="{{asset('images/no-video.png')}}" class="img-responsive" alt="No Video" width="100%" height="200">
              @endif
            </div>
            <div class="clearfix"></div>
            <div class="candidate-info text-center">
              <h3><span>{{ucfirst($video->user->name)}}</span></h3>
              {{--<p>Trained in : {{$video->user->id}}</p>
              <p>Certtification Type : {{$video->user->id}}</p>
              <p>Marks : 00%</p>--}}
            </div>
          </div>
        </div>
        @endforeach
        </div>
        @endif
        <!-- <div class="ask-ques-btn text-center"><button>View All</button></div> -->
      </div>
    </div>
  </div>