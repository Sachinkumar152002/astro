<div class="col-md-6 hot-hiring wow slideInLeft">
<h3>Hot Hiring <span>Companies</span></h3>
<div class="logo-container">
  <div class="hot-job ">
    <!--hot-border start-->
    @if($companies)
    <ul>
      @foreach($companies as $company)
      <li>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="img-box">
            <img src="{{$company->logoimage ? $company->logoimage->image(126,86) : $company->no_image}}">
            </div>
            <p> {{$company->name}} </p>
          </div>
          <div class="panel-footer">
            <div class="pot-emp-follow">
              <span class="follow-tip"></span>
              <div id="follow_company_widget_1818_887"><a title="Click to follow" class="followbtn">Hot Hiring</a></div>
            </div>
            <ul>
              <li>
                <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                <a href="javascript:void(0)"> {{$company->jobs->count()}} jobs</a>
              </li>
            </ul>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
    @endif
    </div>   <!--hot-border end-->
  </div>
</div>