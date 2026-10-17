@if($astrologer)
  <div class="">{{ $astrologer?$astrologer->display_name:"Astrologer" }} is live now.</div>
  <div class="">You can join through {{ config("app.name") }} Application!</div>
@endif
