@if($banners)
<section class="slider-section">
    <div id="carousel-fade" class="carousel fade">
        <div class="carousel-inner">
            @foreach($banners as $i => $banner)
            <div class="item {{$i==0?'active':''}}">
                @if($link = $banner->link)
                <a href="{{$link}}" title="{{$banner->title}}" target="_blank">
                @endif
                <img src="{{asset($banner->image->image('1400','500'))}}" alt="{{$banner->alt}}" title="{{$banner->title}}"  width="100%">
                <div class="carousel-caption banner_text">
                    {!! $banner->caption !!}
                </div>
                @if($banner->link)
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @if($banners->count()>1)
        <a class="left carousel-control" href="#carousel-fade" data-slide="prev">&lsaquo;</a>
        <a class="right carousel-control" href="#carousel-fade" data-slide="next">&rsaquo;</a>
        @endif
    </div>
</section>
@endif