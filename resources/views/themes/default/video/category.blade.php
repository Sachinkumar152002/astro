@extends(themeView().'layout')

@section('title', $pageName)
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

<div class="container mt-5">

    <div class="row mb-5 mt-4">
        <div class="col-md-12">
            <h2 class="float-left">{{$pageName}}</h2>
        </div>
        @if($firstVideo)
        <div class="col-md-9 col-md-offset-3">
            <iframe id="video-embed" class="w-100" style="height:300px;" src="https://www.youtube.com/embed/{{$firstVideo->video_id}}?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <h2 class="h2" id="video-heading">{{$firstVideo->title}}</h2>
        </div>
        @endif

        <div class="clearfix"></div>

        @forelse ($videos as $video)
        <div class="col-sm-4 play-video" data-video="{{$video->video_id}}" data-title="{{$video->title}}" >
            <div class="card">
                <img src="{{$video->thumbnail}}" class="card-img-top" alt="{{$video->title}}">
                <div class="card-body">
                    <h5 class="card-title">{{$video->title}}</h5>
                </div>
            </div>
        </div>
        @empty
        <div class="col-sm-4">
            <div class="card text-center p-5">
                    No Daily Videos
            </div>
        </div>
        @endforelse
        <div class="col-md-12">
            {!! $videos->links() !!}
        </div>
        <div class="clearfix"></div>
    </div>
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