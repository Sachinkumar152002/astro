@php
$astrologer = auth()->guard('astrologer')->user();
@endphp
<div class="card mb-3">
    <div class="card-body bg-warning">
        <div class="row">
            @if($astrologer->is_verified)
            <div class="col-sm-6 text-center mb-2">
                <h6><i class="fa fa-phone-volume"></i> Phone Call Status</h6>
                <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'online'])}}"><span class="badge rounded-pill {{$astrologer->call==1?'bg-success text-white':'bg-light'}}">I'm Online</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'offline'])}}"><span class="badge rounded-pill {{$astrologer->call==2?'bg-dark text-white':'bg-light'}}">I'm Offine</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'busy'])}}"><span class="badge rounded-pill {{$astrologer->call==3?'bg-danger text-white':'bg-light'}}">I'm Busy</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'hide'])}}"><span class="badge rounded-pill {{$astrologer->call==0?'bg-info text-white':'bg-light'}}">Hide</span></a>
            </div>
            <div class="col-sm-6 text-center">
                <h6><i class="fa fa-comments"></i> Chat Status</h6>
                <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'online'])}}"><span class="badge rounded-pill {{$astrologer->chat==1?'bg-success text-white':'bg-light'}}">I'm Online</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'offline'])}}"><span class="badge rounded-pill {{$astrologer->chat==2?'bg-dark text-white':'bg-light'}}">I'm Offine</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'busy'])}}"><span class="badge rounded-pill {{$astrologer->chat==3?'bg-danger text-white':'bg-light'}}">I'm Busy</span></a>
                <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'hide'])}}"><span class="badge rounded-pill {{$astrologer->chat==0?'bg-info text-white':'bg-light'}}">Hide</span></a>
            </div>
            @else
            <div class="col-sm-12 text-center mb-2">
                <h4 class="h4"> Your account is under review</h4>
            </div>
            @endif
        </div>
    </div>
</div>