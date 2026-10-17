@extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)
@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush

@section('content')
<section class="page-header" @if($page->bannerImage)style="background-image:url('{{$page->bannerImage->originalPath}}')"@endif>
    <div class="container">
        <h1 class="text-center">{{$page->name}}</h1>
        <div class="text-center">
            @include(themeView().'common.breadcrumb')
        </div>
    </div>
</section>

<div class="container">
    <div class="rashi-light">
        <div class="rashi-carousel owl-carousel owl-theme pl-3 pr-3">
            @foreach($signs as $sign)
            <div class="item">
                <a href="{{route('zodiac-signs.sign', $sign->slug)}}">
                    @if($sign->image)
                    <img src="{{$sign->image->image(100)}}" alt="{{$sign->name}}" title="{{$sign->name}}">
                    @endif
                    <p class="text-warning small text-center">{{$sign->name}}<br>
                            {{$sign->sign_date_sd}}/{{$sign->sign_date_sm}} -  {{$sign->sign_date_ed}}/{{$sign->sign_date_em}}
                    </p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-8">
            <div class="">
                <h3 class="mt-0">{{$page->name}}</h3>
                <div class="media">
                    @if($page->image)
                    <img src="{{$page->image->image(200)}}" class="mr-3 mt-3 bg-darkblue w-110 p-3 border"
                        alt="{{$page->name}}">
                     @endif
                    <div class="media-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="astro-list-group text-left">
                                    <li class="list-group-item text-left">Page Date : <strong>{{$page->sign_date_sd}}/{{$page->sign_date_sm}} -  {{$page->sign_date_ed}}/{{$page->sign_date_em}}</strong></li>
                                    <li class="list-group-item text-left">Lucky Color : <strong>{{$page->lucky_colors}}</strong></li>
                                    <li class="list-group-item text-left">Lucky Gemstone : <strong>{{$page->lucky_gemstones}}</strong></li>
                                    @php
                                        $days = $page->lucky_days ? json_decode($page->lucky_days) : [];
                                        $daysName = [];
                                        $weekDays = weekDays();
                                        foreach($days as $d) {
                                            $daysName[] = $weekDays[$d];
                                        }
                                    @endphp
                                    <li class="list-group-item text-left">Lucky Day : <strong>{{implode(', ', $daysName)}}</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="astro-list-group text-left">
                                    <li class="list-group-item text-left">Symbol : <strong>{{$page->symbol}}</strong></li>
                                    <li class="list-group-item text-left">Lucky Number : <strong>{{$page->lucky_number}}</strong></li>
                                    @php
                                        $pPlanet = $page->ruiling_planet ? json_decode($page->ruiling_planet) : [];
                                        $planetNames = [];
                                        $planets = planets();
                                        foreach($pPlanet as $d) {
                                            $planetNames[] = $planets[$d];
                                        }
                                    @endphp
                                    <li class="list-group-item text-left">Ruling Planet : <strong>{{implode(', ', $planetNames)}}</strong></li>
                                    @php
                                        $czs = $page->compatible_zodiac_signs ? json_decode($page->compatible_zodiac_signs) : [];
                                        $czsNames = [];
                                        foreach($czs as $d) {
                                            $czsNames[] = $signsArray[$d];
                                        }
                                    @endphp
                                    <li class="list-group-item text-left">Compatible Zodiac Sign : <strong>{{implode(', ', $czsNames)}}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-4">
            <div class="card-header bg-orange mb-4 mt-4">
                <div class="media">
                    <div class="media-body ml-2 mt-2 text-center">
                        <h4>Connect with India's Top Astrology Experts!</h4>
                        <h4><a href="{{route('astrologers.index')}}" class="btn btn-outline-light">Click Here!</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-2 mb-3 d-none">
        <div class="card-body bg-light">
            <h5>Know More about your Sign</h5>
            @foreach(zodiacSignsMorePagesTypes() as $sp)
                <a href="{{route('zodiac-signs.sign.page', [$page->slug, str_slug($sp)])}}" class="btn btn-outline-danger m-1">{{$page->name}} {{$sp}}</a>
            @endforeach

            <a href="#" class="btn btn-outline-danger m-1">{{$page->name}} Celebrity</a>
            <a href="{{route('horoscopes.detail', ['daily', $page->slug, 'free'])}}" class="btn btn-outline-danger m-1">{{$page->name}} Daily Prediction</a>
            <a href="{{route('horoscopes.detail', ['weekly', $page->slug, 'free'])}}" class="btn btn-outline-danger m-1">{{$page->name}} Weekly Prediction</a>
            <a href="{{route('horoscopes.detail', ['monthly', $page->slug, 'free'])}}" class="btn btn-outline-danger m-1">{{$page->name}} Monthly Prediction</a>
            <a href="#" class="btn btn-outline-danger m-1">{{$page->name}} Yearly Prediction</a>
            <a href="#" class="btn btn-outline-danger m-1">Check Your {{$page->name}} Compatibility</a>

        </div>
    </div>
    <h2>Know About {{$page->name}} Sign</h2>
    <div class="text-justify">
        {!! $page->description !!}

        <div class="card-header bg-orange mt-4 mb-4">
            <div class="media">
                <div class="media-body ml-2 mt-2 text-center">
                    <h4>Connect with India's Top Astrology Experts!</h4>
                    <h4><a href="{{route('astrologers.index')}}" class="btn btn-outline-light">Click Here!</a></h4>
                </div>
            </div>
        </div>
    </div>

    @include(themeView().'astrologers.top')
    @include(themeView().'blog.recent')
</div>

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