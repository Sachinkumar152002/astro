@extends(themeView().'layout')

@section('title', 'Video')
@section('keywords', '')
@section('description','')

@section('content')
<section class="page-header">
    <div class="container">
    <h1 class="text-center">{{$pageName}}</h1>
        <div class="text-center">
            @include(themeView().'common.breadcrumb')
        </div>
    </div>
</section>

<div class="container">
        @if($firstVideo)
        <div class="card">
        <div class="card-body bg-dark pt-4 text-center">
        <div class="row">
        <div class="col-md-10 offset-md-1">
            <iframe id="video-embed" class="w-100" style="height:450px;" src="https://www.youtube.com/embed/{{$firstVideo->video_id}}?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <h5 class="text-white" id="video-heading">{{$firstVideo->title}}</h5>
        </div>
        </div>
        </div>
        </div>
        @endif

         <div class="row mt-5">
        <div class="col-md-9">
        <h3>Daily</h3>
        </div>
        <div class="col-sm-3">
        <a class="btn btn-warning pl-3 pr-3 btn-sm float-right btn-rounded" href="{{route('video.category', 'daily')}}" >View All</a>
        </div>
        @forelse ($dailyVideos as $video)
        <div class="col-sm-3 play-video" data-video="{{$video->video_id}}" data-title="{{$video->title}}" >
            <div class="card">
                <img src="{{$video->thumbnail}}" class="card-img-top" alt="{{$video->title}}">
                <div class="card-body">
                    <h6 class="card-title">{{$video->title}}</h6>
                </div>
            </div>
        </div>
        @empty
        <div class="col-sm-12">
            <div class="card text-center p-5">
                    No Daily Videos
            </div>
        </div>

        @endforelse


        <div class="col-md-9 mt-4">
        <h3>Weekly</h3>
        </div>
        <div class="col-sm-3 mt-4">
        <a class="btn btn-warning pl-3 pr-3 btn-sm float-right btn-rounded" href="{{route('video.category', 'weekly')}}" >View All</a>
        </div>

        @forelse ($weeklyVideos as $video)
        <div class="col-sm-3 play-video" data-video="{{$video->video_id}}" data-title="{{$video->title}}" >
            <div class="card">
                <img src="{{$video->thumbnail}}" class="card-img-top" alt="{{$video->title}}">
                <div class="card-body">
                    <h6 class="card-title">{{$video->title}}</h6>
                </div>
            </div>
        </div>
        @empty
        <div class="col-sm-12">
            <div class="card text-center p-5">
                    No Daily Videos
            </div>
        </div>
        @endforelse

        <div class="col-md-9 mt-4">
        <h3>Yearly</h3>
        </div>
        <div class="col-sm-3 mt-4">
        <a class="btn btn-warning pl-3 pr-3 btn-sm float-right btn-rounded" href="{{route('video.category', 'yearly')}}" >View All</a>
        </div>
        @forelse ($yearlyVideos as $video)
        <div class="col-sm-3 play-video" data-video="{{$video->video_id}}" data-title="{{$video->title}}" >
            <div class="card">
                <img src="{{$video->thumbnail}}" class="card-img-top" alt="{{$video->title}}">
                <div class="card-body">
                    <h6 class="card-title">{{$video->title}}</h6>
                </div>
            </div>
        </div>
        @empty
        <div class="col-sm-12">
            <div class="card text-center p-5">
                    No Daily Videos
            </div>
        </div>
        </div>
        @endforelse
        @include(themeView().'astrologers.top')
        @include(themeView().'blog.recent')
</div>

@endsection

@push('scripts')
<script>
    $('.play-video').on('click', function(){
        var videoId = $(this).data('video');
        $('#video-embed').attr('src', 'https://www.youtube.com/embed/'+videoId+'?autoplay=1&rel=0');
        $('#video-heading').html($(this).data('title'));
        $('#video-embed').click();
    });
</script>
@endpush