<section class="skill-categories">
  <div class="container wow fadeInDown">
    <h3><span>Skills</span> We Provide</h3>
    <div class="categories-container">
      @foreach($skills as $skill)
      <div class="cat-box">
        <div class="card">
          {{--<img src="{{asset('images/sales1.png')}}">--}}
          {{--<img class="img-top" src="{{asset('images/sales2.png')}}">--}}
        </div>
        <h4>{{$skill->name}}</h4>
        {{--<p><a href="#">Explore</a></p>--}}
      </div>
      @endforeach
    </div>
    {{--<div class="explore-btn"><button>Explore all Skills</button></div>--}}
  </div>
</section>