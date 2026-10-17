@if(count($product->productGall))
  <div id="slider-wrapper">
    <div id="image-slider">
      <ul class="text-center">
        @foreach($product->productGall as $key => $gallery)
        @if($key==0 && $gallery->image)
          @section('image')
          {{$gallery->image->image(600, 800)}}
          @endsection
        @endif
          <li class="{{$key==0 ? 'active-img' : ''}}">
            <img id="mainImage" src="{{$gallery->image ? $gallery->image->image(600, 800) : ''}}" alt="{{$product->title}}" />
          </li>
        @endforeach
        @if($product->video)
          <li>
            <div class="plyr__video-embed" id="player" style="border-radius: 10px;width:100%">
                <iframe src="https://www.youtube.com/embed/{{$product->video->video_url}}?origin=https://plyr.io&iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1"></iframe>
            </div>
          </li>
        @endif
      </ul>
    </div>
    <div id="thumbnail">
      <ul>
        @foreach($product->productGall as $key => $gallery)
          <li class="{{$key==0 ? 'active' : ''}} gallery-photo">
            <img class="thumbnail" src="{{$gallery->image ? $gallery->image->image(600, 800) : ''}}" alt="{{$product->title}}" data-main-src="{{$gallery->image ? $gallery->image->image(800, 800) : ''}}">
          </li>
        @endforeach
        @if($product->video)
        <li>
          <img class="thumbnail" src="{{$product->video->image}}" alt="{{$product->title}}">
        </li>
        @endif
      </ul>
    </div>
  </div>
@endif
