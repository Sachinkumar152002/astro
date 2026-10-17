<div class="left-box">
  <div class="nav-side-menu">
    <div class="brand">Filter</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
    <div class="menu-list">
      <ul id="menu-content" class="menu-content collapse out">
        <li  data-toggle="collapse" data-target="#industries" class="collapsed">
          <a href="#"><i class="fa fa-desktop"></i>Industries  <span class="arrow"></span></a>
        </li>
        <li>
          <ul id='industries' class="sub-menu collapse in">

            @if($industries)
            @foreach($industries as $industryid=>$industry_name)
            <li data-value="{{$industryid}}" class="dropdown-option {{$industryid==$industry_id?'active':''}}">{{$industry_name}}</li>
            @endforeach
            @endif
          </ul>
        </li>

     <li  data-toggle="collapse" data-target="#locations" class="collapsed">
          <a href="#"><i class="fa fa-desktop"></i>Location  <span class="arrow"></span></a>
        </li>
        <li>
          <ul id='locations' class="sub-menu collapse ">

            @if($locations)
            @foreach($locations as $locationid=>$location_name)
            <li data-value="{{$locationid}}" class="dropdown-option {{$locationid==$location_id?'active':''}}">{{$location_name}}</li>
            @endforeach
            @endif
          </ul>
        </li>

    <li  data-toggle="collapse" data-target="#skills" class="collapsed">
          <a href="#"><i class="fa fa-desktop"></i>Course/Skill  <span class="arrow"></span></a>
        </li>
        <li>
          <ul id='skills' class="sub-menu collapse ">

            @if($skills)
            @foreach($skills as $skill)
            <li data-value="{{$skill->id}}" class="dropdown-option">{{$skill->name}}</li>
            @endforeach
            @endif
          </ul>
        </li>
        <li data-toggle="collapse" data-target="#salary_range" class="collapsed">
          <a href="#"><i class="fa fa-inr fa-lg"></i> Salary Range (monthly)<span class="arrow"></span></a>
        </li>
        <li>
          <ul class="sub-menu collapse " id="salary_range">

            @foreach(range(15000,100000,15000) as $key=>$value)
            <li data-value="{{$value-15000}}-{{$value}}" class="dropdown-option">{{number_format($value-15000)}}-{{number_format($value)}}</li>
            @endforeach
            <li data-value="more" class="dropdown-option">Greater than {{number_format(90000)}}</li>
          </ul>
        </li>
        <li data-toggle="collapse" data-target="#timings" class="collapsed">
          <a href="#"><i class="glyphicon glyphicon-time"></i> Timings <span class="arrow"></span></a>
        </li>
        <li>
          <ul  class="sub-menu collapse " id="timings">
            @foreach($shifts as $k=>$shift)
            <li data-value="{{$k}}" class="dropdown-option {{$k==0?'active':''}}" >{{$shift}}</li>
            @endforeach
          </ul>
        </li>
        <li data-toggle="collapse" data-target="#experience" class="collapsed">
          <a href="#"><span class="exp"><img src="{{asset('images/exp_icon.jpg')}}" alt="Experience"></span> Experience (yrs)<span class="arrow"></span></a>
        </li>
        <li>
          <ul  class="sub-menu collapse " id="experience">

             @foreach(range(2,10,2) as $key=>$value)
            <li data-value="{{$value-2}}-{{$value}}" class="dropdown-option">{{number_format($value-2)}}-{{number_format($value)}}</li>
            @endforeach
            <li data-value="more" class="dropdown-option">More than {{number_format(10)}}</li>
          </ul>
        </li>
        <li data-toggle="collapse" data-target="#trained" class="collapsed">
          <a href="#"><span class="exp"><img src="{{asset('images/exp_icon.jpg')}}" alt="Trained"></span> Trained/Untrained<span class="arrow"></span></a>
        </li>
        <li>
          <ul  class="sub-menu collapse " id="trained">
            <li data-value="1" class="dropdown-option">Trained</li>
            <li data-value="0" class="dropdown-option">Un Trained</li>
          </ul>
        </li>

        <li data-toggle="collapse" data-target="#videoresume" class="collapsed">
          <a href="#"><i class="fa fa-desktop"></i> Filter By Video<span class="arrow"></span></a>
        </li>
        <li>
          <ul  class="sub-menu collapse " id="videoresume">
            <li data-value="1" class="dropdown-option">Video Resume</li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
