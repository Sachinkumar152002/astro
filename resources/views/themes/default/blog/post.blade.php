@extends(themeView().'layout')

@section('title', $post->meta_title." |".config("app.name"))
@section('keywords', $post->meta_keywords)
@section('description',$post->meta_description)

@if($post->image && $post->image->original_path)
  @section('image',$post->image->original_path)
@endif

@push("og_tags")
  <meta property="og:type" content="article" />
  <meta property="og:title" content="{{ $post->title }}" />
  <meta property="og:description" content="{{ strip_tags($post->description) }}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  @if($post->image && $post->image->original_path)
    <meta property="og:image" content="{{ $post->image->original_path }}" />
  @else
    <meta property="og:image" content="{{ logo() }}" />
  @endif
  <meta property="article:author" content="{{ config('app.name') }}" />
  <meta property="article:published_time" content="{{ $post->posted_on }}" />
  <meta property="article:modified_time" content="{{ $post->updated_at }}" />

  <meta name="twitter:title" content="{{ $post->title }}" />
  <meta name="twitter:description" content="{{ strip_tags($post->description) }}" />
  @if($post->image && $post->image->original_path)
    <meta name="twitter:image" content="{{ $post->image->original_path }}" />
  @else
    <meta name="twitter:image" content="{{ logo() }}" />
  @endif
@endpush

@push("schema")
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"Blog",
    "@id":"{{ url()->current() }}#blog",
    "url":"{{ url()->current() }}",
    "name":"{{ $post->title }}",
    "description":"{{ strip_tags($post->description) }}",
    "publisher":{"@id":"{{ url('/') }}/#org"}
  }
  </script>
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"BlogPosting",
    "@id":"{{ url()->current() }}#article",
    "mainEntityOfPage":{
      "@type":"WebPage",
      "@id":"{{ url()->current() }}#webpage"
    },
    "headline":"{{ $post->title }}",
    "description":"{{ strip_tags($post->description) }}",
    "articleSection":"{{ $category->name }}",
    "image":[
      @if($post->image && $post->image->original_path)
      "{{ $post->image->original_path }}"
      @endif
    ],
    "author":{
      "@type":"Person",
      "name":"{{ config('app.name') }}",
      "url":"{{ url('/') }}"
    },
    "publisher":{
      "@type":"Organization",
      "name":"{{ config('app.name') }}",
      "logo":{
        "@type":"ImageObject",
        "url":"{{ logo() }}"
      }
    },
    "datePublished":"{{ $post->posted_on }}",
    "dateModified":"{{ $post->updated_at }}",
    "isPartOf":{
      "@type":"Blog",
      "@id":"{{ route('blog.index') }}#blog",
      "name":"{{ $post->title }}"
    },
  }
  </script>
@endpush

@section('content')

<section class="page-header about-page" style="background-image: url('https://moon-astro.dixinfotech.in/media/5/1752058165.png'); background-repeat: no-repeat; width: 100%; background-size: cover;">

    <div class="container">

  <div class="head-sec">
    <h1 class="text-center text-white">{{$post->title}}</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
    </div>
  </div>
</div>
</section>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-3 col-12  d-none">
      <div class=" main-filter-box pb-3">
        <div class="f-heading-main btn-bg mb-0">
        <h5 class="h5 p-3">Related Blogs</h5>
        </div>
        @php
            //$recentArticles = recentposts(10);
        @endphp
        @if(isset($relatedPost) && $relatedPost)
          @forelse ($relatedPost as $article)
            <div class="media mb-2 border-bottom p-2 pb-2">
                <p class="ml-2 mb-0 "><a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}" class="text-dark">{{$article->title}}</a></p>
            </div>
          @empty
            <div class="media mb-2 border-bottom p-2 pb-2">
                <p class="ml-2 mb-0 ">No Blog Found</p>
            </div>
          @endforelse
        @endif
        @foreach(ads('blog') as $ad)
          @if($ad->image)
           <a href="{{$ad->link}}" target="_blank">
            <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
          </a>
          @else
            {!! $ad->content !!}
          @endif
        @endforeach
    </div>
    </div>

    <!-- phone-sidebar-start -->
    <div class="col-md-3 d-none  pt-3 mt-md-0 mb-0 pb-0">

      <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

        <div class="offcanvas-body off-body-m">
          <div class="p-2">
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class=" main-filter-box pb-3">
            <div class="f-heading-main btn-bg mb-0">
            <h5 class="h5 p-3">Related Blogs</h5>
            </div>
            @php
                //$recentArticles = recentposts(10);
            @endphp
            @if(isset($relatedPost) && $relatedPost)
              @forelse ($relatedPost as $article)


                <div class="media mb-2 border-bottom p-2 pb-2">
                    <p class="ml-2 mb-0 "><a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}" class="text-dark">{{$article->title}}</a></p>
                </div>
                @empty
              @endforelse
            @endif

            @foreach(ads('blog') as $ad)
              @if($ad->image)
               <a href="{{$ad->link}}" target="_blank">
                <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
              </a>
              @else
                {!! $ad->content !!}
              @endif
            @endforeach
        </div>

        </div>


        </div>
      </div>

  <!-- phone-sidebar-end -->
  <div class="col-md-12 col-12">
    <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <div class="d-md-none d-block v-btn-box">
        <button type="button" name="button text-white" class="arrow-b btn verticalButton">Related Blogs</button>
      </div>
    </a>
    <div class="">
      @if($post->image)
      <div class="text-center img-bg"
   style="max-width:100%;
   background-image: url('{{ $post->image ? $post->image->image(1920,550) : '' }}');
   background-size: cover;
   background-position: center;
   background-repeat: no-repeat;
   height: 300px;">
</div>
      @endif
      <div class="blog-desc">
        <div class="clearfix pe-3">{!! $post->description !!}</div>
      </div>
    </div>
  </div>
@include(themeView().'blog.recent')
</div>
</div>
{{--@include(themeView().'astrologers.top')--}}
{{--@include(themeView().'blog.recent')--}}
</div>
@if(isset($faqs) && count($faqs))
  <section class="py-0 py-md-5 mt-3">
      <div class="container bg-light container-xl pt-5 ">
          <div class="row justify-content-center">
              <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center">
                  <h2 class="fs-3 heading">Frequently Asked Questions</h2>
                  <p class="fs-6 text-center">Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
              </div>
          </div>
          <div class="row justify-content-center py-3">
              <div class="col-lg-8">
                  <div class="accordion" id="accordionPanelsStayOpenExample">
                    @foreach($faqs as $key => $faq)
                      <div class="accordion-item">
                          <h2 class="accordion-header">
                              <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                  data-bs-target="#panelsStayOpen-collapse{{$key}}" aria-expanded="false"
                                  aria-controls="panelsStayOpen-collapse{{$key}}">
                                {{$faq->question}}
                              </button>
                          </h2>
                          <div id="panelsStayOpen-collapse{{$key}}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                              <div class="accordion-body">
                            {!! $faq->answer !!}
                              </div>
                          </div>
                      </div>
                    @endforeach()
                  </div>
              </div>
          </div>
      </div>
  </section>
@endif
@include(themeView().'common.adpopup', ['page'=>'blog'])
@endsection
