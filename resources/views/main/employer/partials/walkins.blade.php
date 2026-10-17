<div class="col-md-6 wow slideInRight">
  <div class="box">
    <h3><span>walk in opportunities</span></h3>
    <div class="row b1">
      <div class="b-con">
         @if($is_walkins)
      @foreach($is_walkins as $job)
      <div class="recent-jobs walkin">
        <div class="col-md-12">
        <h4>{{$job->title}}  </h4>
        </div>
        <div class="col-md-9">
        <div class="job-data">

          <h5>{{$job->industry->name}}</h5>
          <div class="company-details">

            <p>{{$job->company->name}}</p>
            <p>Location : {{$job->location_text}}</p>
            <p>Skill : {{$job->skill_text}}</p>
            <p>Salary : <i class="fa fa-inr"></i> {{number_format($job->salary_min,2)}} - <i class="fa fa-inr"></i> {{number_format($job->salary_max,2)}} p.a.</p>
          </div>

        </div>
      </div>
      <div class="col-md-3"><div class="company-logo"><img src="{{$job->company->logoimage->image(150,150)}}" class=""></div></div>
    </div>

      @endforeach
      @endif

      </div>
      <!-- <div class="ask-ques-btn text-center"><button>View All</button></div> -->
    </div>
  </div>
</div>