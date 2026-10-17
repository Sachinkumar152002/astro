@if($astrologer)
  <div class="">{{ $astrologer?$astrologer->display_name:"Astrologer" }} is now online.</div>
  <div class="">You can book consultaion now!</div>
  <a href="{{route('astrologers.publicview', [$astrologer->type,$astrologer->slug])}}">Click to book</a>
@endif
