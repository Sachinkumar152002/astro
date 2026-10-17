@extends(themeView().'layout')

@section('title', 'Chat Consultation | '.config("app.name"))

@push("style")
  <style media="screen">
  .bg-light-blue{
    background-color: #f5fcff !important;
  }
  .profile-card {
  background-color: #f9f9f9 !important;
  padding: 20px;
  text-align: center;
  border-radius:7px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  }
  .bg-g{
    background-color: #f9f9f9 !important;

  }
  .time-card{
  background-color:#0C2E3B;
  border-radius:7px;
  }
  .profile-card:hover {
  transform: translateY(-5px);
  }

  .profile-img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #b3b7bc;
  margin-bottom: 15px;
  }
  .bor-top-black{
    border-top: 1px solid gray;
  }
  .user-name {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #333;
  }

  .chat-time-box {
  margin-top: 15px;
    background-color:#0C2E3B;
  padding: 10px;
  border-radius: 6px;
  font-size: 14px;
  color: #555;
  }
  .time-end-card{
    border-radius:7px;
  }
  .send-message-btn{
    background: white !important;
    color:black;
    opacity: 0.9 !important;
  }
  </style>

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

  </style>
@endpush

@section('title', 'User Chat Consultation | ASTROVYRA')

@php
  $aiImage = $astrologer->image;
  $aiImageUrl = asset('assets/images/user-n.png');
  if($aiImage){
    $aiImageUrl = $aiImage->image(100, 100);
  }
@endphp

@section('content')
<div class="d-flex justify-content-center mt-3  chat-bg mt-mobile2 pro-mt">
<div class="container">
    <div class="row justify-content-center hh mt-5 mt-lg-0 pt-lg-0">
      <div class="col-12 col-12 col-lg-4 order-2 order-md-1 mt-md-5 mt-lg-0 mt-3 d-none d-lg-block">
        <div class="profile-card text-center mb-3  border-n">
          <img src="{{ $aiImageUrl }}" alt="Profile Image" class="profile-img">
          <h3 class="user-name text-black mt-2">
            {{$astrologer->display_name}}
          </h3>
          <div class="">
            <h3 class="user-name text-black pt-3 pb-2 ms-3">
            Astrologer Details
            </h3>
          <div class="d-flex justify-content-center">
            <ul class="list-group list-group-flush charge-list de-card2 p-0" style="text-align:left;">
                        <li class="list-group-item fs-md-6 fs-7 lh-base d-flex align-items-start text-break gap-2">
              <i class="bi bi-translate iii text-black icon-bold"></i>
              <span>{{ $astrologer->my_language }}</span>
          </li>

          <li class="list-group-item fs-md-6 fs-7 lh-base d-flex align-items-start text-break gap-2">
              <i class="bi bi-award iii text-black icon-bold"></i>
              <span>{{ $astrologer->my_expertise_full }}</span>
          </li>

          <li class="list-group-item fs-md-6 fs-7 lh-base gap-2 align-items-start text-left d-flex">
              <i class="bi bi-mortarboard iii text-black icon-bold"></i>
              <span>{{ $astrologer->year_exp }} years</span>
          </li>

            </ul>
          </div>
          </div>
        </div>

        {{--<div class="time-card p-3 py-3  text-center my-3 my-md-0">
          <h5 class="fw-semibold m-0 text-white">Time: <span id="remainingTimeM">00:00</span> </h5>
        </div>
        <div class="time-end-card mt-3">
          <button id="end-chat-btnM" type="button" class="main_button w-100 py-2 px-5 fs-17 f6" onclick="handleEndChat(this)">End Consultation</button>
        </div>--}}
        {{--<div class="time-end-card mt-3">
          <button id="new-btn" type="button" class="mb-3 mb-md-0 fs-p bg-blue text-white w-100 fw-bold p-3 py-2 fs-bb hh-b border-0 radius" onclick="handleNewMessage(this)">New</button>
        </div>--}}
      </div>
        <div class="col-12 col-lg-8 mt-lg-0 mt-3 order-1 order-md-2 mt-mobile2 pad-p mb-5">
          <div class="col-12 col-12 col-lg-4 order-2 order-md-1 mt-md-5 mt-lg-0 mt-3 d-lg-none d-lg-block d-none">
            <div class="bg-light-blue radius text-center">
              <h3 class="user-name text-black pt-3 pb-2 ms-3">
              Astrologer Details
              </h3>
              <div class="m-auto">
                <ul class="list-group list-group-flush charge-list de-card2 p-0" style="text-align:left;">
                  <li class="list-group-item fs-md-6 fs-7 lh-base d-flex align-items-center justify-content-left text-break gap-2">
                    <i class="bi bi-menu-button-black"></i>
                    <span>{{ $astrologer->my_language }}</span>
                  </li>
                  <li class="list-group-item fs-md-6 fs-7 lh-base d-flex  justify-content-left text-break gap-2"><i class="bi bi-award"></i>
                    <span> {{ $astrologer->my_expertise_full }}</span>
                  </li>
                  <li class="list-group-item fs-md-6 fs-7 lh-base d-flex  justify-content-left text-break gap-2 text-left">
                    <i class="bi bi-mortarboard"></i>
                    <span>{{ $astrologer->year_exp }} years</span>
                  </li>
                </ul>
              </div>
            </div>
            {{--<div class="time-card p-3 py-3  text-center my-3 my-md-0">
              <h5 class="fw-semibold m-0 text-white">Time: <span id="remainingTime">00:00</span> </h5>
            </div>
            <div class="time-end-card mt-3 mb-3">
              <button id="end-chat-btn" type="button" class="main_button w-100 py-2 px-5 fs-17 f6" onclick="handleEndChat(this)">End Consultation</button>
            </div>--}}
          </div>
            <div class="card border-n">
                <div class="ai_chat_header card-header  py-md-3 py-2 fs-5 gap-2 bor-b-black">
                    <div class="d-flex justify-content-between">
                    <div class="">
                      <p class="fw-semibold m-0 text-white">Chating with {{$astrologer->display_name}}</p>
                      <p class="mb-0 pb-0 fw-semibold">
                        <span class="text-white">Time: <span id="remainingTime"> 00:00</span></span>
                      </p>
                    </div>
                    <button id="end-chat-btn" type="button" class="bg-white end-cbtn text-black" onclick="handleEndChat(this)">End </button>
                    </div>
                    </div>
                <div class="card-body ai_card_body p-0 radius-2 re-bottom">
                    <div id="chat-box" class=" rounded p-3 mb-0 chat-box-n" style="height:420px; overflow-y: auto;">
                        <!-- Chat messages will be loaded here -->
                    </div>
                    <div class="input-group w-100 text-black bor-top-black p-3 py-2" id="sendchatsection">
                      <div class="d-flex w-100">
                        <div class="w-100 me-2">
                          <textarea id="chat-message" class="form-control w-100 chat-in" placeholder="Type a message..." rows="1" onkeyup="handleMessage(this)" onkeypress="javascript: if (event.key === 'Enter') { event.preventDefault(); document.getElementById('send-message-btn').click(); }"
                          ></textarea>
                        </div>
                        <div class="">
                          <button id="send-message-btn" class="send-icon-btn" onclick="sendMessage(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 24 24">
                              <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#new-btn').hide();
var updateAiChatTimeInterval = null;
var startTime = '{{ $start_time }}';
var talkTimeInterval = null;

function formatTime(seconds){
  let hours = Math.floor(seconds / 3600);
  let minutes = Math.floor((seconds % 3600) / 60);
  let secs = seconds % 60;
  return [
      String(hours).padStart(2, '0'),
      String(minutes).padStart(2, '0'),
      String(secs).padStart(2, '0')
  ].join(':');
}

function updateRemainingTimeDisplay(remainingTime){
  if(remainingTime > 0){
    $('#remainingTime').text(formatTime(remainingTime));
  }else{
    $('#remainingTime').text('00:00:00');
  }
}

var clearTimeInterval = setInterval(()=>{
  startTime++;
  updateRemainingTimeDisplay(startTime);
}, 1000);

function fetchAiChatLogs(){
  let chatBox = $('#chat-box');
  let formData = new FormData();
  formData.append('consultation_id', "{{$consultation_id}}");
  $.ajax({
      url: "{{route('user.aichatlogs')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success: function(response){
        chatBox.empty();
        chatBox.append(response.data);
        handleNewMessage(null);
        if(response.status == 'completed'){
          if(clearTimeInterval) clearInterval(clearTimeInterval);
          if(talkTimeInterval) clearInterval(talkTimeInterval);
          $('#remainingTime').text('00:00:00');
          $('#remainingTime').parent().parent().addClass('d-none');
          $('#end-chat-btn').addClass('d-none');
          $('#sendchatsection').html('chat has ended.');
        }else{
          updateAiChatTimeInterval = setInterval(()=>{
            updateAiChatTime("continue");
          }, 5000);
          countDownToRechargeAlert(Number(response.talk_time_in_seconds));
        }
      },
      error:function(error){
        let message = error.responseJSON.message;
        if(message){
          toastr.error(message);
        }else{
          toastr.error('error in chat logs');
        }
        console.log("fetch chat logs error: ", error);
      }
  });
}

function updateAiChatTime(status = "continue"){
  let formData = new FormData();
  formData.append('consultation_id', "{{$consultation_id}}");
  formData.append('status', status);
  $.ajax({
      url: "{{route('user.updateaichattime')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success: function(response){
        if(response.status == 'completed'){
          if(clearTimeInterval) clearInterval(clearTimeInterval);
          $('#remainingTime').text('00:00:00');
          $('#remainingTime').parent().parent().addClass('d-none');
          $('#end-chat-btn').addClass('d-none');
          if(updateAiChatTimeInterval) clearInterval(updateAiChatTimeInterval);
          $('#sendchatsection').html(response.message);
          if(talkTimeInterval) clearInterval(talkTimeInterval);
        }
      },
      error:function(error){
        let message = error.responseJSON.message;
        if(message){
          toastr.error(message);
        }else{
          toastr.error('error in update end time');
        }
        console.log("fetch chat logs error: ", error);
      }
  });
}

fetchAiChatLogs();

function nl2br(str) {
  return str.replace(/\n/g, "<br>");
}

function formatTimeTwo(datetime) {
  const date = new Date(datetime);
  return date.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
}

const userImage = "{{ $userImage }}";
const aiImage = "{{ $aiImageUrl }}";
const chatBox = $('#chat-box');

let aiDummyTypingInterval = null;
let aiDummyTypingIntervalCount = 2;

function handleDummyTypingAi(){
  let div = document.createElement("div");
  div.classList.add("card", "border-0", "my-2", "c-cardd");
  div.id = "aiDummyTypingMessageBox";

  div.innerHTML = `
    <div class="d-flex justify-content-start align-items-start">
      <img src="${ aiImage }" class="chat-avatar me-2">
      <div class="chat-astro-bubble text-start">
        <div class="fw-bold" id="aiDummyTypingMessage">.</div>
        <div class="chat-time text-end"></div>
      </div>
    </div>
    `;
    chatBox.append(div);
    aiDummyTypingInterval = setInterval(function (){
      if(aiDummyTypingIntervalCount == 2){
        $("#aiDummyTypingMessage").text("..");
      }else if(aiDummyTypingIntervalCount == 3){
        $("#aiDummyTypingMessage").text("...");
      }else if(aiDummyTypingIntervalCount == 4){
        $("#aiDummyTypingMessage").text("....");
        aiDummyTypingIntervalCount == 1;
      }
      aiDummyTypingIntervalCount++;
    }, 1000);
    handleNewMessage(null);
}

function sendMessage(that){
  let message = messageString = $('#chat-message').val();

  if(message){
    let createdAt = new Date();
    let div = document.createElement("div");

    div.classList.add("card", "border-0", "my-2", "c-cardd");

    div.innerHTML = `
      <div class="d-flex justify-content-end align-items-start">
        <div class="chat-user-bubble text-start">
          <div class="">
            ${ nl2br(messageString) }
          </div>
          <div class="chat-time text-end">${ formatTimeTwo(createdAt) }</div>
        </div>
        <img src="${ userImage }" class="chat-avatar ms-2">
      </div>
      `;

    chatBox.append(div);
    handleDummyTypingAi();
  }

  $(that).prop("disabled", true);
  let formData = new FormData();
  formData.append('consultation_id', "{{$consultation_id}}");
  formData.append('message', $('#chat-message').val());
  $.ajax({
      url: "{{route('user.sendmessagetoai')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success:function(response){
        $("#aiDummyTypingMessageBox").remove();
        clearInterval(aiDummyTypingInterval);
        $(that).prop("disabled", false);
        chatBox.append(response.response);
        handleNewMessage(null);
      },
      error:function(error){
        $("#aiDummyTypingMessageBox").remove();
        clearInterval(aiDummyTypingInterval);
        $(that).prop("disabled", false);
        let errors = error.responseJSON.errors;
        if(errors){
          for(let key in errors){
            toastr.error(errors[key][0]);
          }
        }
        console.log("send message error: ", error);
      }
  });
  $('#chat-message').val('');
  $('#send-message-btn').prop('disabled', true);
}

function handleMessage(that){
  let value = $(that).val();
  if(value && value.length){
    $('#send-message-btn').prop('disabled', false);
  }else{
    $('#send-message-btn').prop('disabled', true);
  }
}

function handleNewMessage(that){
  if(that){
    $(that).hide();
  }
  $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
}

function handleEndChat(that){
  $(that).prop("disabled", true);
  updateAiChatTime('end');
  setTimeout(function(){
    window.location.href = "{{route('user.aiconsultations')}}";
  }, 2000);
}

function countDownToRechargeAlert(talkTimeInSeconds){
  if(talkTimeInSeconds > 0){
    talkTimeInterval = setInterval(function(){
      if(talkTimeInSeconds <= 0){
        clearInterval(talkTimeInterval);
        Swal.fire({
          text: 'Your wallet balance is low. Please recharge your wallet to avoid any interruption during the consultation.',
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Recharge",
          cancelButtonColor: "#d33",
        }).then((result)=>{
          window.location.href = "{{route('user.recharge')}}";
        });
        setTimeout(function (){
          handleEndChat("#end-chat-btn");
        }, 3000);
      }
      talkTimeInSeconds--;
    }, 1000);
  }
}
</script>
@endpush
