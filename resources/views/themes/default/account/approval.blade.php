@extends(themeView().'layout')

@section('title', 'Call')
@section('keywords', '')
@section('description','')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-12">
        <div class="card" style="margin-top: 50px; margin-bottom: 50px;">
          <h5 class="card-header">Waiting for approval</h5>
          <div class="card-body">
            <p class="card-text">Waiting for chat approval from astrologer.</p>
          </div>
        </div>
    </div>
  </div>
</div>

@endsection

<script>
setInterval(() => {
  checkChatApproved();
},1000);

function checkChatApproved() {
  $.ajax({
    url: '{{route('user.chat.approval', [$chat->chat_key])}}',
    type: 'POST',
    data:{
      "_token": "{{ csrf_token() }}",
    },
    Accept: 'application/json',
    success: function(data) {
      if(data.status) {
        window.location.href = data.message;
      }
    }
  });
  //alert('{{$chat->chat_key}}');
}
</script>
