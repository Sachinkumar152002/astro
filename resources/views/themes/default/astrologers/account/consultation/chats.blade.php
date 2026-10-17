<style>
.chat-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.chat-user-bubble {
  max-width: 75%;
  background-color:#E8F6EF;
  padding: 10px 15px;
  border-radius: 15px;
  font-size: 14px;
  line-height: 1.5;
  word-wrap: break-word;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  border-top-right-radius: 0;
  color: black;
}

.chat-astro-bubble {
  max-width: 75%;
  background-color:#FBE8EB;
  padding: 10px 15px;
  border-radius: 15px;
    color: black;
  font-size: 14px;
  line-height: 1.5;
  word-wrap: break-word;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  border-top-left-radius: 0;
}

.justify-content-start .chat-bubble {
  border-top-left-radius: 0;
}

.justify-content-end .chat-bubble {
  border-top-right-radius: 0;
}
.c-cardd{
  background: transparent;
}
.ai_chat_header{
  background: rgb(252, 215, 197);
}
.ai_card_body{
  background: #f9f9f9;
}
</style>

@php
 $astrologer = $appointment->astrologer;
 $astrologerImage = $astrologer->image ? $astrologer->image->image(100, 100) : asset('assets/images/profile-n.png');
 $user = $appointment->user;
 $userImage = $user->image ? $user->image->image(100, 100) : asset('assets/images/user-n.png');
@endphp

  <div class="card border-0 my-3 user-bb c-cardd">
    <div class="d-flex justify-content-start align-items-start">
      <img src="{{ $userImage }}" class="chat-avatar me-2" alt="You">
      <div class="chat-astro-bubble text-start">
            <ul style="list-style-type:none" class="m-0 p-0">
              <li>Hi,</li>
              <li>Name: {{$appointment->name}}</li>
              @if($appointment->gender)
              <li>Gender: {{$appointment->gender}}</li>
              @endif
              @if($appointment->dateofbirth)
              <li>Birth Date: {{date('d-m-Y', strtotime($appointment->dateofbirth))}}</li>
              @endif
              @if($appointment->birthtime)
              <li>Birth Time: {{date('h:i a', strtotime($appointment->birthtime))}}</li>
              @endif
              @if($appointment->birthplace)
              <li>Birth Place: {{ $appointment->birthplace }}</li>
              @endif
              @if($appointment->occupation)
              <li>Occupation: {{$appointment->occupation}}</li>
              @endif
              @if($appointment->maritial_status)
              <li>Maritial Status: {{$appointment->maritial_status}}</li>
              @endif
            </ul>
          </div>
        </div>
      </div>

      {{--<div class="card border-0 my-2 c-cardd">
        <div class="d-flex justify-content-start align-items-start">
        <div class="chat-astro-bubble text-start">
            This is an automated message to confirm that chat has started
          </div>
        </div>
      </div>--}}

@foreach($results as $key => $row)
    @if($row->message_by == 'user')
      <div class="card border-0 my-2 c-cardd">
        <div class="d-flex justify-content-start align-items-start">
          <img src="{{ $userImage }}" class="chat-avatar me-2" alt="You">
          <div class="chat-astro-bubble text-start">
            <div class="">
              {!! nl2br($row->message) !!}
            </div>
            <div class="chat-time text-end">{{ date('h:i A', strtotime($row->created_at)) }}</div>
          </div>
        </div>
      </div>
    @else
      <div class="card border-0 my-2 c-cardd">
        <div class="d-flex justify-content-end align-items-start">
          <div class="chat-user-bubble text-start">
            <div class="">
              {!! nl2br($row->message) !!}
            </div>
            <div class="chat-time text-end">{{ date('h:i A', strtotime($row->created_at)) }}</div>
          </div>
          <img src="{{ $astrologerImage }}" class="chat-avatar ms-2" alt="Astrologer">
        </div>
      </div>
    @endif
@endforeach
