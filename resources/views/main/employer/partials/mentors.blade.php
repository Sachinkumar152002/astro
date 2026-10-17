@if($mentors)
<section class="mentor">
  <div class="container wow fadeInDown">
    <div class="row">
      <h3>Mentors at <span>Smart Skill Tech</span></h3>
      @foreach($mentors as $mentor)
      <div class="mentor-section">
        <div class="col-md-4">
          <div class="mentor-box">
            <div class="mentor-image"><img src="{{$mentor->image->image(100,100)}}"></div>
            <h4>{{$mentor->name}}</h4>
            <p>{{$mentor->description}}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif