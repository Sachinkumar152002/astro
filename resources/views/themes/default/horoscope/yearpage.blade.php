@extends(themeView().'layout')
@section('title', $pagename)
@section('keywords', $pagename)
@section('description', $pagename)

@section('content')
<section class="page-header horoscope">
    <div class="container">
        <h1 class="text-center">{{$pagename}}</h1>
        <div class="text-center">
            @include(themeView().'common.breadcrumb')
        </div>
    </div>
</section>
<div class="container mt-4">
    <div class="row">
    <div class="col-md-12">
        <h3>Yearly Horoscope {{$year}}</h3>
        @if($horoscrope)
            {!! $horoscrope->{'main_content'} !!}
        @endif
    </div>
    </div>

    <div class="row">
        @foreach($signs as $sign)
        <div class="col-md-4 mb-4">
            <div class="card ">
            <div class="card-body text-center">

                @if($sign->image)
                 <img src="{{$sign->image ? $sign->image->image(100) : no_image()}}" alt="{{$sign->name}}" title="{{$sign->name}}">
                @endif
                <h6>{{$sign->name}} Horoscope {{$year}}</h6>
                <a href="{{route('horoscopes.yearly', [$year, $sign->slug, 'free', $year])}}"> Read more ..</a>
            </div>
            </div>
        </div>
        @endforeach
    </div>

    @include(themeView().'astrologers.top')
    @include(themeView().'blog.recent')
</div>

@endsection
