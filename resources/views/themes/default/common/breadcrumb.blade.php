<nav aria-label="breadcrumb" d-none>
    <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item text-white"><a href="{{route('home')}}" class="text-white">Home</a></li>
        @if(isset($breads) && $breads)
        @foreach ($breads as $bread)
            @if(isset($bread['is_last']) && $bread['is_last'])
                <li class="breadcrumb-item active text-white" aria-current="page">{{$bread['name']}}</li>
            @else
                <li class="breadcrumb-item text-white"><a href="{{$bread['url']}}" class="text-white">{{$bread['name']}}</a></li>
            @endif
        @endforeach
        @endif
    </ol>
</nav>
