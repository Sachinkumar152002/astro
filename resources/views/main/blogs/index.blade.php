@extends('layouts.app')
@section('content')<!--banner start-->

@include('main.partials.breadcrumb')

<section class="container">
    <div class="row" style="margin-top: 25px;">
        <?php $courseCount = 1; ?>
        @if($articles)
            @foreach ($articles as $index => $article)
                <div class="col-md-4">

                        <div class="item-box-blog" style="background: #ffffff;border: 1px solid #d0d0d0;">
                            <div class="item-box-blog-image">
                                <figure>
                                    <a href="{{ route('blog.detail', [$article->slug]) }}"
                                       tabindex="0"><img style="height:150px" alt="{{$article->title}}" src="{{$article->image_thumb}}"></a>
                                </figure>
                            </div>
                            <div class="item-box-blog-body">
                                <div class="item-box-blog-heading" style="min-height: 55px;">
                                    <a href="{{ ($article->status) ? route('blog.detail', [$article->slug]) : 'javascript:void(0)'}}"
                                       tabindex="0">
                                    <h5>{{$article->title}} </h5>
                                    </a>
                                </div>
                                <div style="padding: 10px;min-height: 104px;">
                                    {{$article->description}}
                                    <small>{{$article->posted_on}}</small>
                                </div>
                                <div style="margin: 0px 15px 15px 15px;" class="text-left">
                                    <a style="color: #fff !important;font-size: 14px;padding: 6px 15px !important;box-shadow: 0px 1px 5px #989898;border: none;background: #ff412d;margin-top: 0;display: inline-block;"
                                       href="{{route('blog.detail', [$article->slug])}}">Read More</a>
                                </div>
                            </div>
                        </div>
                </div>
                <?php if ( ($courseCount % 3) == 0) {?>
    </div>
    <div class="row">
        <?php } ?>
        <?php $courseCount++; ?>
        @endforeach
        @else
            <h4>No blogs yet!</h4>
        @endif
    </div>
</section>

@endsection
