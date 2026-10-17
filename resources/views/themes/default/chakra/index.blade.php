@extends(themeView().'layout')
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@section('content')
<section class="page-header" @if($page->bannerImage)style="background-image:url('{{$page->bannerImage->originalPath}}')"@endif>
<div class="container">
    <h1 class="text-center">{{$page->name}}</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
    </div>
</div>
</section>
<div class="container mt-5">
    <div class="row">
      <div class="col-sm-8">
        <h2>{{$page->name}}</h2>
        @if($page->image)
            <img src="{{$page->image->image(1150, 400)}}" alt="img-fluid">
        @endif
        {!! $page->description !!}
      </div>
      <div class="col-sm-4 mb-4">
        <h4>Other Chakra</h4>
        <div class="card">
          <div class="card-body bg-light">
            <ul class="astro-list-group h-100">
              @foreach($subPages as $item)
              <li class="list-group-item">
                  <a href="{{route('chakra.index', $item->slug)}}">{{$item->name}}</a>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    @include(themeView().'astrologers.top')
    @include(themeView().'blog.recent')
  </div>
@endsection
