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
<div class="container mt-3">
    <div class="row">
        <div class="col-sm-2">
            @if($page->image)
                <img src="{{$page->image->image(300)}}" alt="zodiac-signs" class="img-fluid">
            @else
                <img src="{{asset('images/zodiac-signs.png')}}" alt="zodiac-signs" class="img-fluid">
            @endif
        </div>
        <div class="col-sm-10">
            <h3>{{$page->name}}</h3>
            <p class="text-justify">{!! $page->short_description !!}</p>
        </div>
    </div>
</div>
<div class="container rashi-light">
    <h2 class="pl-3 pr-3 mb-3">Select your sign</h2>
    <div class="row">
        <div class="col-12">
            <div class="rashi-carousel owl-carousel owl-theme pl-3 pr-3">
                @foreach($signs as $sign)
                <div class="item">
                    <a href="{{route('zodiac-signs.sign', $sign->slug)}}">
                        @if($sign->image)
                        <img src="{{$sign->image ? $sign->image->image(100) : no_image()}}" alt="{{$sign->name}}" title="{{$sign->name}}">
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
</div>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-sm-12">
            {{-- @if($page->image)
                <img src="{{$page->image->image(1150, 400)}}" alt="img-fluid">
            @endif --}}
            {!! $page->content !!}
        </div>
        <div class="col-sm-4 d-none">
            <div class="card mb-3">
                <div class="card-body bg-light pt-1">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="{{route('zodiac-signs.sign', 'sunsigns')}}">Know your sign</a></li>
                        <li class="list-group-item"><a href="{{route('zodiac-signs.sign', 'moonsigns')}}">Know your moon sign</a></li>
                        <li class="list-group-item"><a href="{{route('zodiac-signs.sign', 'compatibility')}}">Zodiac Compatibility</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-orange">
                    <div class="media">
                        <div class="media-body ml-2 mt-2">
                            <h4>Zodiac Horoscope </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <ul class="astro-list-group">
                        <li class="list-group-item"><a href="horoscopes/daily">Daily horoscope </a></li>
                        <li class="list-group-item"><a href="horoscopes/weekly">Weekly Horoscope </a></li>
                        <li class="list-group-item"><a href="horoscopes/monthly">Monthly Horoscope </a></li>
                        <li class="list-group-item"><a href="horoscopes/2021">Yearly horoscope 2021 </a></li>
                    </ul>
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
