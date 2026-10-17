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
<div class="container ">
        @if(!$type)
            {!! $page->content !!}
        @endif
        <div class="rashi-light mb-3">
            <div class="rashi-carousel owl-carousel owl-theme pl-3 pr-3">
                @foreach($signs as $sign)
                <div class="item">
                    <a href="{{route('horoscopes.detail', [($type?:'daily'), $sign->slug, 'free'])}}">
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

        @if(!$type)
        {{-- <div class="col-12">
            <h3>Daily planetary movements</h3>
            <p class="text-justify">
                Things are likely to get a little heated up as Moon makes its presence felt in Virgo. Any hurdles that you may come across now are bound to create tensions and worries. Truth be told, the transit can be a bit frustrating, especially for those who do not like to wait. This is definitely not the right time to take any major decisions. Even if you are the go-getter kind, you ought to wait. Being patient and exercising restraint will be important and you must remember it if you wish to navigate the hurdles brought about by the transit. But with the transit of Moon in Virgo, things will be balanced and this transit will bail you out of any difficulties. astroYogi astrologers urge us to rectify the mistakes of the past and to tune into our higher selves to identify the right path for us. For those in love, this period may turn out to be the best time of their life as there is an increased urge to partner up due to the influence of this transit. Even if it is not so apparent now, love is definitely going to grow stronger.
            </p>
        </div> --}}
        @endif
        <div class="row">
        @foreach($signs as $sign)
        <div class="col-md-3 mb-4">
            <div class="card">
            <div class="card-body">
            <div class="text-center rashi pt-0 pb-0">
                @if($sign->image)
                 <img src="{{$sign->image ? $sign->image->image(70) : no_image()}}" alt="{{$sign->name}}" title="{{$sign->name}}">
                 <div class="text-center lead"><strong>{{$sign->name}}</strong></div>
                @endif
            </div>
            <ul class="astro-list-group mt-0 pt-0">
        @if($type)
                @foreach (typeDWM() as $field=>$label)
                <li class="list-group-item">
                    <a href="{{route('horoscopes.detail', [$type, $sign->slug, $field])}}">
                    {{$sign->name}} {{ucfirst($type)}} {{$label}} Horoscope </a>
                </li>
                @endforeach
        @else
                <li class="list-group-item">
                    <a href="{{route('horoscopes.detail', ['daily', $sign->slug, 'free'])}}">
                    {{$sign->name}} Daily Horoscope </a>
                </li>
                <li class="list-group-item">
                    <a href="{{route('horoscopes.detail', ['weekly', $sign->slug, 'free'])}}">
                    {{$sign->name}} Weekly Horoscope </a>
                </li>
                <li class="list-group-item">
                    <a href="{{route('horoscopes.detail', ['monthly', $sign->slug, 'free'])}}">
                    {{$sign->name}} Monthly Horoscope </a>
                </li>


        @endif
    </ul>
</div>
    </div>
    </div>
        @endforeach
        <div class="row mb-4 d-none">
            @if($type!='daily')
            <div class="col-sm-4 col-12 col-md-4">
                <div class="card">
                    <div class="card-header h4">Daily Horoscope </div>
                    <div class="card-body">
                    <p>
                    </p>
                    <a type="button" href="{{route('horoscopes.index', 'daily')}}" class="btn btn-warning btn-sm">Read More</a>
                </div>
            </div>
            </div>
            @endif
            @if($type!='weekly')
            <div class="col-sm-4 col-12 col-md-4">
                <div class="card">
                    <div class="card-header h4">Weekly Horoscope</div>
                    <div class="card-body">
                    <p>
                      </p>
                    <a type="button" href="{{route('horoscopes.index', 'weekly')}}" class="btn btn-warning btn-sm">Read More</a>
                </div>
            </div>
            </div>
            @endif
            @if($type!='monthly')
            <div class="col-sm-4 col-12 col-md-4">
                <div class="card">
                    <div class="card-header h4">Monthly Horoscope</div>
                    <div class="card-body">
                    <p>
                     </p>
                    <a type="button" href="{{route('horoscopes.index', 'monthly')}}" class="btn btn-warning btn-sm">Read More</a>
                </div>
            </div>
            </div>
            @endif
            @if($type)
            <div class="col-sm-4 col-12 col-md-4">
                <div class="card">
                    <div class="card-header h4">Yearly Horoscope</div>
                    <div class="card-body">
                    <p>
                     </p>
                    <a type="button" href="{{route('horoscopes.index', date('Y'))}}" class="btn btn-warning btn-sm">Read More</a>
                </div>
            </div>
            </div>
                @endif
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
