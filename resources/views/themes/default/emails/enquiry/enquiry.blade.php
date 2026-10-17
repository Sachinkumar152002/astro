@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    @php
    $enquiryy = json_decode($enquiry->data);
    @endphp

    <div class="">
      Form: {{ $enquiry->form }}
    </div>
    @foreach($enquiryy as $key => $value)
    <div class="">
      {{$key}}: {{ $value }}
    </div>
    @endforeach
  </div>
@endsection
