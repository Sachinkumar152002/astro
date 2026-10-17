@extends(themeView() . 'layout')
@section('title', 'Astrologers Register')
@section('keywords', '')
@section('description', '')
@section('content')
<div class="container" style="
    height: 261px;
    width: 264px;
">


<form action="/click-to-call" method="POST" >
    @csrf
    <label for="from">Your Number (From):</label>
    <input type="text" name="from" id="from" required>

    <label for="to">Recipient Number (To):</label>
    <input type="text" name="to" id="to" required>

    <button type="submit">Call Now</button>
</form>
</div>
@endsection
