@php
$astrologer = auth()->guard('astrologer')->user();
@endphp
<div class="card mb-3">
    <div class="card-body bg-dash">
        <div class="row">
            @if($astrologer->is_verified)
              @if(isset($availability) && $availability)
                <div class="col-sm-4 text-center mb-2">
                    <h6 class="text-white"><i class="fa fa-phone-volume"></i> Phone Call Status</h6>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'online'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->call==1?'bg-success text-white':'bg-light text-dark'}}">I'm Online</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'offline'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->call==2?'bg-dark text-white':'bg-light text-dark'}}">I'm Offine</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'busy'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->call==3?'bg-danger text-white':'bg-light text-dark'}}">I'm Busy</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['call', 'hide'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->call==0?'bg-info text-white':'bg-light text-dark'}}">Hide</span></a>
                </div>
                <div class="col-sm-4 text-center mb-2">
                    <h6 class="text-white"><i class="fa fa-phone-volume"></i> Video Call Status</h6>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['video', 'online'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->video==1?'bg-success text-white':'bg-light text-dark'}}">I'm Online</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['video', 'offline'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->video==2?'bg-dark text-white':'bg-light text-dark'}}">I'm Offine</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['video', 'busy'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->video==3?'bg-danger text-white':'bg-light text-dark'}}">I'm Busy</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['video', 'hide'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->video==0?'bg-info text-white':'bg-light text-dark'}}">Hide</span></a>
                </div>
                <div class="col-sm-4 text-center">
                    <h6 class="text-white"><i class="fa fa-comments"></i> Chat Status</h6>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'online'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->chat==1?'bg-success text-white':'bg-light text-dark'}}">I'm Online</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'offline'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->chat==2?'bg-dark text-white':'bg-light text-dark'}}">I'm Offine</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'busy'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->chat==3?'bg-danger text-white':'bg-light text-dark'}}">I'm Busy</span></a>
                    <a class="text-dark" href="{{route('astrologer.im.status', ['chat', 'hide'])}}"><span class="badge p-2 px-3 rounded-pill {{$astrologer->chat==0?'bg-info text-white':'bg-light text-dark'}}">Hide</span></a>
                </div>
                @else
                  <div class="col-sm-4 text-center text-white mb-2">
                    Your are unavailabe for now.
                  </div>
              @endif
            @else
            <div class="col-sm-12 text-center mb-2">
                <h4 class="h4"> Your account is under review</h4>
            </div>
            @endif
        </div>
    </div>
</div>
