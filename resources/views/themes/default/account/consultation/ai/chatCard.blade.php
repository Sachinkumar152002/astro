@if($row->message_by == 'user')
  <div class="card border-0 my-2 c-cardd">
    <div class="d-flex justify-content-end align-items-start">
      <div class="chat-user-bubble text-start">
        <div class="">
          {!! nl2br($row->message) !!}
        </div>
        <div class="chat-time text-end">{{ date('h:i A', strtotime($row->created_at)) }}</div>
      </div>
      <img src="{{ $userImage }}" class="chat-avatar ms-2">
    </div>
  </div>
@else
  <div class="card border-0 my-2 c-cardd">
    <div class="d-flex justify-content-start align-items-start">
      <img src="{{ $astrologerImage }}" class="chat-avatar me-2">
      <div class="chat-astro-bubble text-start">
        <div class="">
          {!! nl2br($row->message) !!}
        </div>
        <div class="chat-time text-end">{{ date('h:i A', strtotime($row->created_at)) }}</div>
      </div>
    </div>
  </div>
@endif
