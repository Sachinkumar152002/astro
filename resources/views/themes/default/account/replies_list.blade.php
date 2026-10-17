<style media="screen">
  .chat-user{
    height:35px;
    width:35px;
  }
  .chat-user img{
    height: 30px;
    width: 30px;
  }
</style>
@php
$image = asset('assets/images/profile.png');
if($support->{$user_type} && $support->{$user_type}->image){
  $image = $support->{$user_type}->image->image(200, 200);
}
@endphp
<div class="message sent">
    <div class="d-flex">
        <div class="col-2 chat-user d-flex align-items-center">
            <img src="{{$image}}" class="img-fluid astro-img rounded-circle" alt="User-A">
        </div>
        <div class="chat-content">
            <p class="m-0"><strong>You:  </strong>{{$support->message}}</p>
            <small class="text-muted">
              {{ date('d M Y, h:i A', strtotime($support->created_at)) }}
            </small>
        </div>
    </div>
</div>
@forelse($replies as $key => $value)
  @if($value->reply_type=='admin')
  <div class="message received mb-2">
    <div class="d-flex">
      <div class="col-2 chat-user d-flex align-items-center">
        <img src="{{asset('assets/images/profile.png')}}" class="img-fluid astro-img rounded-circle" alt="User-A">
      </div>
      <div class="chat-content">
            <p class="m-0"><strong>ASTROVYRA Team: </strong>{{$value->reply}}</p>
            <small class="text-muted">
              {{ date('d M Y, h:i A', strtotime($value->created_at)) }}
          </small>
      </div>
    </div>
  </div>
  @else
  <div class="message sent">
      <div class="d-flex">
          <div class="col-2 chat-user d-flex align-items-center">
              <img src="{{$image}}" class="img-fluid astro-img rounded-circle" alt="User-A">
          </div>
          <div class="chat-content">
              <p class="m-0"><strong>You:  </strong>{{$value->reply}}</p>
              <small class="text-muted">
                {{ date('d M Y, h:i A', strtotime($value->created_at)) }}
              </small>
          </div>
      </div>
  </div>

  @endif
@empty
<div class="message received">
  <p class="m-0">No replies yet</p>
</div>
@endforelse
