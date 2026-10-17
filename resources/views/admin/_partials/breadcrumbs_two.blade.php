<div class="section-header">
    <ol class="breadcrumb">
        <li>
            <a href="{{route('admin.dashboard')}}">Content</a>
        </li>

        @if(isset($breads)) @foreach($breads as $bread)
        <li>
            <span>></span>
        </li>
        <li class="{{!isset($bread['url']) ? 'active': '' }}">
            @if(isset($bread['url']))
            <a href="{{$bread['url']}}">{{$bread['name']}}</a>
            @else {{$bread['name']}} @endif
        </li>
        @endforeach @endif
    </ol>
</div>
