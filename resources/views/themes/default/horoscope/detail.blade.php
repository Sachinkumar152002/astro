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
                <a href="{{route('horoscopes.detail', [$type, $sign->slug, $field])}}">
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
    <div class="col-md-3 text-center">
        <div class="rashi bg-white">
        @if($page->image)
            <img src="{{$page->image->image(100)}}" alt="{{$page->name}}" title="{{$page->name}}">
        @endif
    </div>
    </div>
    <div class="col-md-9">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            @if($type=='daily')
              <a class="nav-item nav-link " id="nav-yesterday-tab" data-toggle="tab" href="#nav-yesterday" role="tab" aria-controls="nav-yesterday" aria-selected="true">Yesterday</a>
              <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="false">Today</a>
              <a class="nav-item nav-link" id="nav-tomorrow-tab" data-toggle="tab" href="#nav-tomorrow" role="tab" aria-controls="nav-tomorrow" aria-selected="false">Tomorrow</a>
              {{-- <a class="nav-link" href="{{route('horoscopes.detail', ['weekly', $page->slug, $field])}}">This Week</a> --}}
              {{-- <a class="nav-link" href="{{route('horoscopes.detail', ['monthly', $page->slug, $field])}}">This Month</a> --}}
            @elseif($type=='weekly')
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-yesterday">Yesterday</a>
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-today">Today</a>
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-tomorrow">Tomorrow</a>
                {{-- <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="false">This Week</a> --}}
                {{-- <a class="nav-link" href="{{route('horoscopes.detail', ['monthly', $page->slug, $field])}}">This Month</a> --}}
            @elseif($type=='monthly')
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-yesterday">Yesterday</a>
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-today">Today</a>
                <a class="nav-link" href="{{route('horoscopes.detail', ['daily', $page->slug, $field])}}#nav-tomorrow">Tomorrow</a>
                {{-- <a class="nav-link" href="{{route('horoscopes.detail', ['weekly', $page->slug, $field])}}">This Week</a> --}}
                {{-- <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="false">This Month</a> --}}
            @endif
                <a class="nav-link" href="{{route('horoscopes.yearly', [date('Y'), $page->slug, 'free', date('Y')])}}">{{date('Y')}}</a>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            @if($type=='daily')
                <div class="tab-pane fade" id="nav-yesterday" role="tabpanel" aria-labelledby="nav-yesterday-tab">
                 <p>@php
                        $yesterday = date('Y-m-d', strtotime('-1 day'));
                    @endphp
                    @if(isset($horoscrope[$yesterday]))
                        {!! $horoscrope[$yesterday]->first()->{$selectField.'_content'} !!}
                    @endif</p>
                </div>
                <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                    <p> @php
                        $today = date('Y-m-d');
                    @endphp
                    @if(isset($horoscrope[$today]))
                        {!! $horoscrope[$today]->first()->{$selectField.'_content'} !!}
                    @endif</p>
                </div>
                <div class="tab-pane fade" id="nav-tomorrow" role="tabpanel" aria-labelledby="nav-tomorrow-tab">
                    <p> @php
                        $tomorrow = date('Y-m-d', strtotime('+1 day'));
                    @endphp
                    @if(isset($horoscrope[$tomorrow]))
                        {!! $horoscrope[$tomorrow]->first()->{$selectField.'_content'} !!}
                    @endif</p>
                </div>
            @elseif($type=='weekly' || $type=='monthly')
                <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                    <p>  @if($horoscrope)
                        {!! $horoscrope->{$selectField.'_content'} !!}
                    @endif</p>
                </div>
            @endif
          </div>
    </div>
</div>

    <div class="card mt-2 mb-3 d-none">
        <div class="card-body bg-light">
            <h5>Know More about your {{$page->slug}}</h5>
            @foreach(typeDWM() as $sl=>$sp)
                @if($sl!=$field)
                    <a href="{{route('horoscopes.detail', [$type, $page->slug, $sl])}}" class="btn btn-outline-danger m-1">{{$page->name}} {{ucfirst($type)}} {{$sp}}</a>
                @endif
            @endforeach
            <a href="{{route('zodiac-signs.sign', [$page->slug])}}" class="btn btn-outline-danger m-1">{{$page->name}}</a>
            @foreach(zodiacSignsMorePagesTypes() as $sp)
                <a href="{{route('zodiac-signs.sign.page', [$page->slug, str_slug($sp)])}}" class="btn btn-outline-danger m-1">{{$page->name}} {{$sp}}</a>
            @endforeach
            <a href="#" class="btn btn-outline-danger m-1">{{$page->name}} Celebrity</a>
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
    });
</script>
@endpush