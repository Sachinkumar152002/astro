@extends(themeView().'layout')

@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush
@section('content')
@if($banners)
<!-- Slider Start -->
 <div id="planet-vedic-home-slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach($banners as $n=>$banner)
            <li data-target="#planet-vedic-home-slider" data-slide-to="{{$n}}" class="{{$n==0?'active':''}}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach($banners as $n=>$banner)
        <div class="carousel-item {{$n==0?'active':''}}">
  <a href="talk-to-astrologers"><img src="{{$banner->image->originalPath}}" alt="Planetvedic"></a>
            {{-- <div class="container">
                <div class="caption text-center">
                    {!! $banner->caption !!}
                </div>
            </div> --}}
        </div>
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#planet-vedic-home-slider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#planet-vedic-home-slider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- Slider End -->
@endif
<section class="bg-light border pt-3 mb-3 d-none">
    <form class="needs-validation" novalidate>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h3>Sign Up</h3>
                    <p class="lead">Consult with Indias best Astrologers Free Online</p>
                </div>
                <div class="col-sm-5 mt-2">
                    <div class="form-row no-gutters">
                        <div class="col-3">
                            <select class="custom-select">
                                <option value="91">+91</option>
                                <option value="1">+1</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Mobile Number" maxlength="13"
                                required id="phone">
                            <div class="invalid-feedback">
                                Please enter valid Mobile number.
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted text-center">
                        enter your valid mobile number along with your country code.
                    </small>
                </div>
                <div class="col-sm-4">
                    <div class="form-group text-center mt-2">
                        <button type="submit" id="phonelogin"
                            class="btn btn-warning btn-rounded w-100">Submit</button>

                        <p class="mt-2">Already Registered <a href="#">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<!-- How it works -->
<div class="howitworks d-none d-sm-block">
    <div class="container">
        <h2 class="text-center section-title text-white text-uppercase" data-aos="fade-down">How it works</h2>
        <div class="row mt-4">
            @if(auth()->guard('web')->check())
            <div class="col-sm-3 mb-2">
                <div class="howit-steps">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange mt-1"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p>Hi {{auth()->user()->name}},</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-sm-3 mb-2">
                <div class="howit-steps">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange mt-1"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p>Sign Up with Planet Vedic</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-3  mb-2">
                <div class="howit-steps">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange mt-1"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p>Add money to<br>
                                wallet</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3  mb-2">
                <div class="howit-steps">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16"
                            class="bi bi-person-bounding-box text-orange mt-1" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z" />
                            <path fill-rule="evenodd"
                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p>Select Astrolger and click </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3  mb-2">
                <div class="howit-steps">
                    <div class="media">
                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16"
                            class="bi bi-star-fill text-orange mt-2" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                        </svg>
                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16"
                            class="bi bi-star-fill text-orange mt-2" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                        </svg>
                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16"
                            class="bi bi-star-fill text-orange mt-2" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p>How you feel Share feedback</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- How it works END-->


<!-- Top Astrologers -->
<div class="container pt-4 pb-5 top-astro">
    <h2 class="text-uppercase mb-4 text-center">Top Astrologers</h2>
    <div class="row">
        @foreach ($astrologers as $astrologer)
            @include(themeView().'astrologers._item', array('col'=>'3'))
        @endforeach


    </div>
<p class="text-center mt-4"><a href="{{route('astrologers.index')}}" class="btn btn-rounded btn-orange pl-5 pr-5 btn-lg">View All</a></p>

</div>
<!-- Top Astrologers END-->

<!-- Rashi Start -->
<section class="rashi">
    <div class="container">
        <h2 class="text-orange text-center  pl-3 pr-3 mb-3"><strong>Daily Horoscopes</strong></h2>
        <div class="row">

                    @foreach($signs as $sign)
                    <div class="col-sm-2 col-4 text-center mb-3">
                        <a href="{{route('horoscopes.detail', ['daily',$sign->slug,'free'])}}">
                            @if($sign->image)
                            <img src="{{$sign->image ? $sign->image->image(60) : no_image()}}" alt="{{$sign->name}}" title="{{$sign->name}}">
                            @endif
                            <p class="text-dark text-center"><strong>{{$sign->name}}</strong><br>
                                    {{$sign->sign_date_sd}}/{{$sign->sign_date_sm}} -  {{$sign->sign_date_ed}}/{{$sign->sign_date_em}}
                            </p>
                        </a>
                    </div>
                    @endforeach

        </div>
    </div>
</section>
<!-- Rashi Start END-->
<div class="container mt-5">
    <h2 class="mb-3 text-center text-orange">Astrology Readings</h2>
    <div class="row d-none">
        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                        <img src="{{asset('images/rashi-icons/gift.svg')}}" width="45" alt="gift">
                        <div class="media-body ml-2 mt-2">
                            <h5>Love</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="#">Love Compatibility</a></li>
                        <li class="list-group-item"><a href="#">Love Sutra </a></li>
                        <li class="list-group-item"><a href="#">Soulmate</a></li>
                        <li class="list-group-item"><a href="#">Love Tarot</a></li>
                        <li class="list-group-item"><a href="#">Love Horoscope</a></li>
                        <li class="list-group-item"><a href="#">Love Profile </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                        <img src="{{asset('images/rashi-icons/tarot.svg')}}" width="45" alt="tarot">
                        <div class="media-body ml-2 mt-2">
                            <h5>Tarot</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="#">Love Compatibility</a></li>
                        <li class="list-group-item"><a href="#">Love Sutra </a></li>
                        <li class="list-group-item"><a href="#">Soulmate</a></li>
                        <li class="list-group-item"><a href="#">Love Tarot</a></li>
                        <li class="list-group-item"><a href="#">Love Horoscope</a></li>
                        <li class="list-group-item"><a href="#">Love Profile </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                        <img src="{{asset('images/rashi-icons/star.svg')}}" width="45" alt="star">
                        <div class="media-body ml-2 mt-2">
                            <h5>Numerology</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="#">Love Compatibility</a></li>
                        <li class="list-group-item"><a href="#">Love Sutra </a></li>
                        <li class="list-group-item"><a href="#">Soulmate</a></li>
                        <li class="list-group-item"><a href="#">Love Tarot</a></li>
                        <li class="list-group-item"><a href="#">Love Horoscope</a></li>
                        <li class="list-group-item"><a href="#">Love Profile </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                        <img src="{{asset('images/rashi-icons/swastika.svg')}}" width="45" alt="swastika">
                        <div class="media-body ml-2 mt-2">
                            <h5>Panchang</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="#">Love Compatibility</a></li>
                        <li class="list-group-item"><a href="#">Love Sutra </a></li>
                        <li class="list-group-item"><a href="#">Soulmate</a></li>
                        <li class="list-group-item"><a href="#">Love Tarot</a></li>
                        <li class="list-group-item"><a href="#">Love Horoscope</a></li>
                        <li class="list-group-item"><a href="#">Love Profile </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                         <img src="{{asset('images/rashi-icons/diwali.svg')}}" width="45" alt="diwali">
                        <div class="media-body ml-2 mt-2">
                            <h5>Festivals</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        @foreach($festivals as $item)
                        <li class="list-group-item">
                            <a href="{{route('festival.index', $item->slug)}}">{{$item->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-header bg-orange">
                    <div class="media">
                        <img src="{{asset('images/rashi-icons/swastika-2.svg')}}" width="45" alt="swastika-2">
                        <div class="media-body ml-2 mt-2">
                            <h5>Spirituality</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="#">Love Compatibility</a></li>
                        <li class="list-group-item"><a href="#">Love Sutra </a></li>
                        <li class="list-group-item"><a href="#">Soulmate</a></li>
                        <li class="list-group-item"><a href="#">Love Tarot</a></li>
                        <li class="list-group-item"><a href="#">Love Horoscope</a></li>
                        <li class="list-group-item"><a href="#">Love Profile </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-sm-4 mb-4">
            <h4 class="text-orange">Yearly Horoscope {{date('Y')}}</h4>
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        @foreach($signs as $sign)
                        <li class="list-group-item">
                            <a href="{{route('horoscopes.yearly', [date('Y'), $sign->slug, 'free', date('Y')])}}">
                                <img src="{{$sign->image ? $sign->image->image(35) : no_image()}}" alt="{{$sign->name}}" title="{{$sign->name}}"> {{$sign->name}} {{date('Y')}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <h4 class="text-orange">Planets</h4>
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        @foreach($planets as $planet)
                        <li class="list-group-item"><a href="{{route('planet.page', $planet->slug)}}"><i class="fa fa-globe"></i> {{$planet->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-4">
            <h4 class="text-orange">Vastu</h4>
            <div class="card" data-aos="zoom-in" data-aos-duration="600">
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        @foreach($vastus as $item)
                        <li class="list-group-item">
                            <a href="{{route('vastu.index', $item->slug)}}"><i class="fa fa-compress"></i>  Vastu for {{$item->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
  @include(themeView().'common.adpopup', ['page'=>'home'])
@endsection

@push('scripts')
<script src="{{asset('js/owl.carousel.min.js')}}"></script>
<script>
    $('.rashi-carousel').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        autoplay: false,
        responsive: {
            0: {
                items: 3,
                nav: true,
                autoplay: true

            },
            600: {
                items: 6,
                autoplay: true,
                nav: false
            },
            1000: {
                items: 12,
                margin: 15,
                nav: false,
                loop: false
            }
        }
    })
</script>
@endpush
