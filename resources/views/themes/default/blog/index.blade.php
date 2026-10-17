@extends(themeView() . 'layout')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/articles-ui.css') }}">
@endpush

@section('title', "$pageName | ".config("app.name"))
@section('keywords', "$pageName")
@section('description', "$pageName")

@section('image')
{{asset('assets/img/blog_banner.jpg')}}
@endsection

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"CollectionPage",
    "@id":"{{ url()->current() }}#webpage",
    "mainEntity":{
      "@type":"ItemList",
      "itemListElement":[
        @foreach($articles as $key => $article)
          {
            "@type":"ListItem",
            "position":"{{ $key+1 }}",
            "url":"{{ route('blog.post',[$article->category?$article->category->slug:'all',$article->slug]) }}",
            "name":"Post 1 Title"
          }
          @if($key!=($articles->count()-1))
          ,
          @endif
        @endforeach
      ]
    }
  }
  </script>
@endpush

@section('content')

<section class="page-header about-page articles-hero">

    <div class="container">

  <div class="head-sec">
    <h1 class="text-center text-white">{{$pageName}}</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
    </div>
  </div>
</div>
</section>

<div class="container mt-0 mt-md-5 side-bar">
    <div class="row mb-0  mt-0 mt-lg-4 ">
      <div class="col-md-3 sticky_top sticky-sidebar1 pb-4   d-none">
        <div class="main-filter-box ">
          <div class="btn-bg p-3 radius">
            <h5 class="h5 ms-2 mb-0">Categories</h5>
          </div>
          <div class="media border-bottom p-2 blog-list">
            @if(isset($categories))
              <div class="side-scrollbar max-scroll">
              <div class="item">
               <ul class="category-list sidebar_ul">
                 @foreach($categories as $key=>$cat)
                   @if(count($cat->childrens))
                     <li class="category-item mb-2 border-bottom link link__0 pb-2">

                      <div class="sub-btn sub-m d-flex justify-content-between">
                         <a class="" href="javascript:void(0)">{{$cat->name}}</a>
                      </div>
                       <div class="sub-menu" style="{{($parent_category ? $parent_category->id == $cat->id : ($category ? $category->id == $cat->id : 0 ) ) ? 'display:block' : 'display:none'}}">
                         <ul class="p-0">
                           @foreach($cat->childrens as $cKey => $cCategory)
                           <li class="category-item {{ $cKey == count($cat->childrens)-1 ? '' : 'border-bottom pb-2 mb-2' }} link link__0 d-flex">
                            <a href="{{route('blog.category', [$cCategory->slug])}}" class="sub-item" style="{{$category && $category->id == $cCategory->id ? 'color:#ff0000 !important' : ''}}">{{$cCategory->name}}</a>
                           </li>
                           @endforeach
                         </ul>
                       </div>
                     </li>
                   @else
                     <li class="category-item mb-2 border-bottom link link__0 pb-2">
                       <a class="sub-btn sub-m" href="{{route('blog.category', [$cat->slug])}}" style="{{$category && $category->id == $cat->id ? 'color:#ff0000 !important' : ''}}">{{$cat->name}}</a>
                     </li>
                   @endif
                 @endforeach
               </ul>
           </div>
         </div>
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
      <!-- phone-sidebar-start -->
      <div class="col-md-3 d-none  pt-0 pt-lg-3 mt-md-0 mb-0 pb-0">

        <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

          <div class="offcanvas-body off-body-m">
            <div class="p-2">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="btn-bg p-3 radius">
              <h5 class="h5 ms-2 mb-0">Categories</h5>
            </div>
            <div class="media border-bottom p-2 blog-list">
              <div class="side-scrollbar max-scroll">
                @if(isset($categories))
                <div class="item">
                 <ul class="p-0">
                   @foreach($categories as $key=>$cat)
                     @if(count($cat->childrens))
                       <li class="category-item mb-2 border-bottom pb-2">
                         <div class="sub-btn sub-m d-flex justify-content-between">
                            <a class="" href="javascript:void(0)">{{$cat->name}}</a><i class="bi bi-caret-down-fill ms-1 text-dark"></i>
                         </div>
                         <div class="sub-menu" style="{{($parent_category ? $parent_category->id == $cat->id : ($category ? $category->id == $cat->id : 0 ) ) ? 'display:block' : 'display:none'}}">
                           <ul class="p-0">
                             @foreach($cat->childrens as $cKey => $cCategory)
                             <li class="link link__0 category-item {{ $cKey == count($cat->childrens)-1 ? '' : 'border-bottom pb-2 mb-2' }} d-flex">
                              <a href="{{route('blog.category', [$cCategory->slug])}}" class="sub-item" style="{{$category && $category->id == $cCategory->id ? 'color:#ff0000 !important' : ''}}">{{$cCategory->name}}</a>
                             </li>
                             @endforeach
                           </ul>
                         </div>

                       </li>
                     @else
                       <li class="link link__0 category-item mb-2 border-bottom pb-2">
                         <a class="sub-btn sub-m" href="{{route('blog.category', [$cat->slug])}}" style="{{$category && $category->id == $cat->id ? 'color:#ff0000 !important' : ''}}">{{$cat->name}}</a>
                       </li>
                     @endif
                   @endforeach
                 </ul>
             </div>
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
        </div>

    <!-- phone-sidebar-end -->
      <div class="col-md-12 col-12">
        <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
    <div class="d-md-none d-block v-btn-box">
        <button type="button" name="button text-white" class="arrow-b btn verticalButton" id="scrollButton">Categories</button>
    </div>
      </a>
        <div class="row">
        @forelse ($articles as $article)
        <div class="col-md-4 col-12 mb-3">
            <div class="card blog_card">
                <a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}">
                    @if($article->image)
                        <img src="{{$article->image->image(350,210)}}" class="card-img-top" alt="{{$article->title}}">
                    @else
                        <img src="{{no_image()}}" class="card-img-top" alt="{{$article->title}}">
                    @endif
                </a>
                <div class="card-body p-2">
                  <!-- <div class="strip">

                      <h6><span>{{$article->created_at->format('d')}}</span><br>
                      {{$article->created_at->format('M')}}</h6>

                  </div> -->
                  <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-truncate blog-he"><a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}">{{$article->title}}</a></h5>
                    <a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}"> <button type="button" class="p-add text-center res_btn" name="button">Read More</button></a>
                  </div>

                    <!-- <small><i class="">{{dateFmt($article->posted_on)}}</i></small> -->
                    <!-- <p class="card-text blog-text short-text mt-2">{{mySubStr(strip_tags($article->description), 70)}}</p> -->
                </div>
            </div>
        </div>

        @empty
        <div class="col-md-12 col-12">
            <div class="text-center">
                  <img src="/assets/images/blog-data.jpg" alt="no blog found" class="nodata">
                  <h5 class="mt text-dark"> No Blog Found!</h5>
                  <p class="text-secondary"> Blog and post will be displayed here.</p>
            </div>
        </div>
        @endforelse

        <div class="col-md-12">
            {{-- $articles->links() --}}
              {!! $articles->links('vendor.pagination.bootstrap-5') !!}
        </div>
        </div>
        </div>
    </div>
    {{--@include(themeView().'astrologers.top')--}}
    {{--
    @include(themeView().'blog.recent')
    --}}
</div>
@include(themeView().'common.adpopup', ['page'=>'blog'])
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('.sub-btn').click(function(){
    $(this).next('.sub-menu').slideToggle();

  });
});
</script>

<script>
  let scrollTimeout;

  window.addEventListener('scroll', () => {
    const button = document.getElementById('scrollButton');

    // Show the button when scrolling
    button.style.display = 'block';

    // Clear the timeout if it's already set
    if (scrollTimeout) clearTimeout(scrollTimeout);

    // Set a timeout to hide the button after a delay
    scrollTimeout = setTimeout(() => {
      button.style.display = 'none';
    }, 2000); // Adjust time (ms) to suit your preference
  });
</script>
@endpush
