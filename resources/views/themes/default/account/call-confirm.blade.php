@extends(themeView().'layout')

@section('content')
<div class="container">
    <div class="card mt-5 mb-5">
      <div class="card-body">
        <div class="text-center">
          <img src="{{$astrologer->image?$astrologer->image->image(100):no_image()}}" class="astro-img rounded-circle" width="90" alt="{{$astrologer->display_name}}">
          <div class="astro-name">
            <h5>{{$astrologer->display_name}}</h5>
          </div>
          <div class="lead mt-4">Your Available Wallet balance is <span
              class="badge bg-success text-white">Rs.{{$user->wallet}}</span></div>
             @php
                 $hour = 00;
                 $minutes = round($user->wallet/((int)$astrologer->per_minute_rate?$astrologer->per_minute_rate:1), 0);
                 if($minutes>=60){
                    $hour = (int)($minutes/60);
                    $minutes = round($minutes%60, 0);
                 }
             @endphp
          <p class="lead text-center">Maximum Call duration for this consultation is <span
              class="badge badge-warning">{{$hour}}:{{$minutes}}:00</span></p>
          <p class="mt-5 text-center">
            @if($option == 'call')
            <a class="btn btn-rounded btn-outline-success btn-lg pl-5 pr-5" href="{{route('astrologers.make.request', [$astrologer->slug, $option, 'confirm'])}}"><i
                class="fa fa-phone-volume text-success" aria-hidden="true"></i> Call
            </a>
            @elseif($option == 'chat')
            <a class="btn btn-rounded btn-outline-success btn-lg pl-5 pr-5" href="{{route('astrologers.make.request', [$astrologer->slug, $option, 'confirm'])}}"><i
                class="fa fa-comment text-success" aria-hidden="true"></i> Chat
            </a>
            @endif
          </p>
        </div>
      </div>
    </div>
</div>
@endsection
