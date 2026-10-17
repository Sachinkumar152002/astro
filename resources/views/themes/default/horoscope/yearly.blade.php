@extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)
@push('styles')
<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush

@section('content')
<section class="page-header horoscope">
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
                <a href="{{route('horoscopes.yearly', [$type, $sign->slug, $field, $year])}}">
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
<div class="container mt-4">
    <div class="row">
    <div class="col-md-12">
        @if($page->image)
            <img src="{{$page->image->image(100)}}" alt="{{$page->name}}" class="bg-darkblue mb-3" title="{{$page->name}}">
        @endif
        <h3>{{$page->name}} {{$field}} Horoscope {{$year}}</h3>

        @if($horoscrope)
            {!! $horoscrope->{$selectField.'_content'} !!}
        @endif
    </div>
</div>
    <div class="row d-none">
    <div class="card mt-2 mb-3">
        <div class="card-body bg-light">
            <h5>Know More about your {{$page->slug}}</h5>
            @foreach(typeYear() as $sl=>$sp)
                @if($sl!=$field)
                    <a href="{{route('horoscopes.yearly', [$type, $page->slug, $sl, $year])}}" class="btn btn-outline-danger m-1">{{$page->name}} {{$sp}} {{$year}}</a>
                @endif
            @endforeach
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