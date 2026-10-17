@extends(themeView().'layout')

@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@section('content')
<section class="page-header" @if($page->bannerImage)style="background-image:url('{{$page->bannerImage->originalPath}}')"@endif>
    <div class="container">
    <h1 class="text-center">{{$sign->name}} {{$page->name}}</h1>
        <div class="text-center">
            @include(themeView().'common.breadcrumb')
        </div>
    </div>
</section>

<div class="container mt-5">
    <h2>{{$sign->name}} {{$page->name}}</h2>
    @if($page->image)
        <img src="{{$page->image->image(1150, 400)}}" alt="img-fluid">
    @endif
    {!! $page->description !!}
    @include(themeView().'astrologers.top')
    @include(themeView().'blog.recent')
</div>
</div>
@endsection
