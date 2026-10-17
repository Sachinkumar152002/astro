@extends('layouts.app')

@section('content')
    <div class="jwc">
        @include('main.partials.breadcrumb')
        <section class="blog-detail">
            <div class="container prod-cont">
                <h5>{{$article['title']}}</h5>
                <div class="row">
                    <div class="col-md-8">
                        <figure><img alt="{{$article['title']}}" src="{{$article['image']}}"></figure>
                        <p>{!! $article['description'] !!}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="listing-coures">
                            <h5>Recent Blogs</h5>
                            @if($articles)
                                <ul style="height: 500px;overflow: auto;">
                                    @foreach($articles as $article)
                                        <li>
                                            <a class="{{ ($title == $article->slug) ? 'active':''  }}" href="{{route('blog.detail', [$article->slug])}}">{{$article->title}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span>No more blogs</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
