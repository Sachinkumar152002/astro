<div class="banner-section">
    <div class="container">
    <div class="row">
    <div class="col-md-10 wow slideInLeft lt-section col-md-offset-1">
    <h1><strong>Hire Skilled People,</strong> Best of Them</h1>
    <div class="search-section">
    <form action="{{route('employer.search')}}" method="get">
    <div class="row">
    <div class="col-md-12">
    <div class="col-md-6">
    {!! Form::select('industry', $industries, '', ['class'=>"form-control", 'placeholder'=>'Select Job Role' ]) !!}
    </div>
    <div class="col-md-6 location-box">
    <div class="col-md-9 pd-0">
    {!! Form::select('location', $locations, '', ['class'=>"form-control", 'placeholder'=>'Select Location' ]) !!}
    </div>
    <div class="col-md-3 pd-0"><div class="home-btns">
    <button type="submit" class="btn btn-danger">Search</button></div></div>
    </div>
    </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
</div>