<section id="slider">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Learn Online Courses and <br/>
                    Connect with the World</h1>
                <p>Enrolled and likes to be changed <br/>over <span>20,000+</span> students</p>
            </div>
            <form action="{{route('subscribe')}}" method="post" id="subscriber-form" class="form-inline">
                <div class="form-group mx-sm-6 mb-2">
                    <input type="email" class="form-control input-area" name="email" required="" placeholder="Subscribe Now">
                </div>
                <button type="submit" class="btn btn-primary mb-2 btn-fil" id="subscriber-btn"><img alt="Learn" src="{{asset('rewamp/images/arrow.png')}}"></button>
            </form>
        </div>
    </div>
</section>

<section id="cartifid">
    <div class="container">
        <div class="row certifid">
            <div class="col-sm-4">
                <div class="car1">
                    <div class="car-left">
                        <img src="{{asset('rewamp/images/3.png')}}">
                    </div>
                    <div class="car-right">
                        <h4>Understand your Job Responsibilities</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="car1">
                    <div class="car-left">
                        <img src="{{asset('rewamp/images/4.png')}}">
                    </div>
                    <div class="car-right">
                        <h4>Internationally Recognized Certificates</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="car1 last">
                    <div class="car-left">
                        <img src="{{'/rewamp/images/2.png'}}">
                    </div>
                    <div class="car-right">
                        <h4>100% Online Training</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{--
@if($banners)
<section class="slider-section">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            @foreach($banners as $i => $banner)
            <div class="item {{$i==0?'active':''}}">
                --}}
{{--  @if($link = $banner->link)
                <a href="{{$link}}" title=" {{$banner->title}}" target=" _blank">
                @endif  --}}{{--

                <img class="opct" src="{{asset($banner->image->image('1400','500'))}}" alt="{{$banner->alt}}" title="{{$banner->title}}">
                <div class="caption"> {!! $banner->caption !!} </div>
                --}}
{{--  @if($banner->link)
                </a>
                @endif  --}}{{--

            </div>
            @endforeach
        </div>
        @if($banners->count()>1)
        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        @endif
    </div>
</section>
@endif--}}
