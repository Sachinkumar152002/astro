@php
    $recentArticles = recentposts();
@endphp
@if($recentArticles)
<div class="row mb-5 my-0 my-md-4">
    <h3 class="h3 col-md-12 ">Latest Blog</h3>
@forelse ($recentArticles as $article)
<div class="col-md-4 col-12 mb-3">
    <div class="card blog_card h-100">
        <a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}">
            @if($article->image)
                <img src="{{$article->image->image(350,210)}}" class="card-img-top" alt="{{$article->title}}">
            @else
                <img src="{{no_image()}}" class="card-img-top" alt="{{$article->title}}">
            @endif
        </a>
        <div class="card-body p-2">
          <div class="d-flex justify-content-between align-items-center">


            <h5 class="card-title text-truncate blog-he text-black"><a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}" class="text-black">{{$article->title}}</a></h5>

            <a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}"> <button type="button" class="p-add text-center res_btn" name="button">Read More</button></a>
  </div>
        </div>
    </div>
</div>
@empty
@endforelse
</div>
@endif
