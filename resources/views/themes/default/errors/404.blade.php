@extends(themeView().'layout')
@section('title', 'Not found')

@section('content')
<div class="container mt-3" style="height:100vh;">
  {{--  <div class="card bg-light text-center pt-3 pb-3 mb-3">
        <h2 class="text-uppercase mb-1">Not found</h2>
    </div>  --}}

        <img src="{{ asset('assets/images/404_err.png') }}" alt="404" class="img-fluid">
 </div>
@endsection
