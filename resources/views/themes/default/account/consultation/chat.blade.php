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
font-size: 17px;
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

@section('title', 'User Chat | ASTROVYRA')

@section('content')
<div class="d-flex justify-content-center mt-3  chat-bg mt-mobile2 pro-mt">
<div class="container">
    <div class="row justify-content-center hh mt-2 pt-5 mt-lg-0 pt-lg-0">
      <div class="col-12 col-lg-4 mt-lg-0 mt-3 order-1 order-md-2 mt-mobile2 pad-p mb-5 d-none d-lg-block">
        <div class="profile-card text-center mb-3 d-none">
          <img src="{{ $astrologer->image ? $astrologer->image->image(100, 100) : asset('assets/images/user-n.png') }}" alt="Profile Image" class="profile-img">
          <h3 class="user-name text-black mt-2">
            {{$astrologer->display_name}}
          </h3>
          <h3 class="user-name text-black pt-3 pb-2 ms-3">
          Astrologer Details
          </h3>
          <div class="d-flex justify-content-center">
            <ul class="list-group list-group-flush charge-list de-card2 p-0">
              <li class="list-group-item fs-md-6 fs-7 lh-base d-flex align-items-center  text-break gap-2">
                <i class="bi bi-menu-button-wide text-black"></i>
                <span>{{ $astrologer->my_language }}</span>
              </li>
              <li class="list-group-item fs-md-6 fs-7 lh-base d-flex align-items-center text-break gap-2"><i class="bi bi-award text-black"></i>
                <span> {{ $astrologer->my_expertise_full }}</span>
              </li>
              @if($astrologer->chat==1 && $astrologer->call==1 && $astrologer->video==1)
                <li class="list-group-item fs-md-6 fs-7 lh-base gap-2 align-items-start" >
                  <a href="{{ route('astrologers.publicview', [$astrologer->type, $astrologer->slug, 'type' => 'chat']) }}" class="book_nowbut pointer chat-b">
                    <div class="ii me-2">
                      <i class="fa fa-commenting" aria-hidden="true"></i>
                    </div>
                    Chat Again
                  </a>
                </li>
              @endif
            </ul>
          </div>
        </div>

        {{--<div class="time-card p-3 py-3  text-center my-3 my-md-0 d-none">
          @if($appointment->scheduled_for)
            <h5 class="fw-semibold m-0 text-white">Remaining Time: <span id="remainingTime">00:00</span> </h5>
          @else
            <h5 class="fw-semibold m-0 text-white">Time: <span id="remainingTime">00:00</span> </h5>
          @endif
        </div>
        <div class="time-end-card mt-3">
          <button id="end-chat-btn" type="button" class="mb-3 mb-md-0 fs-p bg-red text-black w-100 fw-bold p-3 py-2 fs-bb hh-b border-0 radius" onclick="handleEndChat()">End Consultation</button>
        </div>--}}
        <div class="time-end-card mt-3">
          <button id="new-btn" type="button" class="mb-3 mb-md-0 fs-p bg-blue text-white w-100 fw-bold p-3 py-2 fs-bb hh-b border-0 radius" onclick="handleNewMessage(this)">New</button>
        </div>
      </div>
      <div class="col-12 col-lg-8 mt-md-0 mt-3 order-2 order-md-2 mt-mobile2 mb-4 mb-md-0 padd">
        <div class="card">
          <div class="ai_chat_header card-header d-flex  justify-content-between py-md-3 py-2 fs-5 gap-2 bor-b-black">
            <div class="d-flex justify-content-between w-100">
            <div class="">
              <p class="fw-semibold m-0 text-white">Chating with {{$astrologer->display_name}}</p>
              <p class="mb-0 pb-0 fw-semibold d-none">
                @if($appointment->scheduled_for)
                  <span class="text-white">Remaining Time: <span id="remainingTime"> 00:00</span></span>
                @else
                  <span class="text-white">Time: <span id="remainingTime"> 00:00</span></span>
                @endif
              </p>
            </div>
            <button id="end-chat-btn" type="button" class="bg-white end-cbtn text-black py-3" onclick="handleEndChat(this)">End </button>
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
var canChatTimeout = null;
let showWaitingPopup = true;
let closeForcefully = true;
let updateWaitingTextInterval = null;
var fetchChatsInterval = null;
var totalChatLogsCount = 0;
var talkTimeInterval = null;
let talkTimeInSeconds = Number("{{ $talk_time_in_seconds }}");
var startTime = '{{$start_time}}';
var endTime = new Date('{{$end_time}}');
var clearTimeInterval = null;
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
function canChat(){
  let formData = new FormData();
  formData.append('room_id', "{{ $room_id }}")
  $.ajax({
      url: "{{ route('user.canchat') }}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{ csrf_token() }}'
      },
      success:function(response){
        let status = response.status;
        let text = "Time is over";
        if(status=='wait'){
          canChatTimeout = setTimeout(canChat, 1000);
          if(showWaitingPopup){
            let countdown = response.count_down;
            Swal.fire({
              title: 'Please wait for astrologer',
              html: `wait: <b>00:03:00</b>`,
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading();
                let content = Swal.getHtmlContainer().querySelector('b');
                updateWaitingTextInterval = setInterval(() => {
                  countdown--;
                  content.textContent = formatTime(countdown);
                  if(countdown <= 0){
                    if(canChatTimeout) clearTimeout(canChatTimeout);
                    if(updateWaitingTextInterval) clearInterval(updateWaitingTextInterval);
                    if(closeForcefully){
                      fetchChatLogs('cancel');
                      Swal.close();
                    }
                    $('#remainingTime').text('00:00:00');
                    if(clearTimeInterval) clearInterval(clearTimeInterval);
                    Swal.fire({
                      title: 'The astrologer cannot join the chat.',
                      confirmButtonText: 'Ok',
                      allowOutsideClick: false,
                      allowEscapeKey: false
                    }).then(() => {
                      window.location.href = "{{route('astrologers.chatwithastrologers')}}";
                    });
                  }
                }, 1000);
              }
            });
            showWaitingPopup = false;
          }
        }else{
          closeForcefully=false;
          Swal.close();
          if(!showWaitingPopup){
            if(canChatTimeout) clearTimeout(canChatTimeout);
            if(updateWaitingTextInterval) clearInterval(updateWaitingTextInterval);
            $('#remainingTime').text('00:00:00');
            if(clearTimeInterval) clearInterval(clearTimeInterval);
          }
          @if($appointment->scheduled_for)
            clearTimeInterval = setInterval(()=>{
              var currentTime = new Date();
              var differenceTime = endTime - currentTime;
              var timeDifferenceSecs = Math.floor(differenceTime / 1000);
              updateRemainingTimeDisplay(timeDifferenceSecs);
            }, 1000);
          @else
            clearTimeInterval = setInterval(()=>{
              startTime++;
              updateRemainingTimeDisplay(startTime);
            }, 1000);
          @endif

          if(status=='not now'){
            $('#sendchatsection').html("You cannot message at this time.");
          }
          if(status=='start' || status=='done'){
            $('#remainingTime').parent().parent().removeClass('d-none');
            if(status=='done'){
              $('#end-chat-btn').addClass('d-none');
              $('#sendchatsection').html('Chat has ended.');
              $('#remainingTime').parent().parent().addClass('d-none');
            }
            if(status == "start"){
              countDownToRechargeAlert();
              console.log("count down start");
            }
            fetchChatsInterval = setInterval(fetchChatLogs, 2000);
          }
        }
      },
      error:function(error){
        let message = error.responseJSON.message;
        if(message){
          toastr.error(message);
        }
        console.log("can chat error: ", error);
      },

  });
}
canChat();
function fetchChatLogs(status='continue'){
  let chatBox = $('#chat-box');
  let formData = new FormData();
  formData.append('consultation_id', "{{$consultation_id}}");
  formData.append('status', status);
  $.ajax({
      url: "{{route('user.chatlogs')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success: function(response){
        if(response.status == 'done'){
          if(fetchChatsInterval) clearInterval(fetchChatsInterval);
          // $('#remainingTime').text('00:00:00');
          // $('#remainingTime').parent().parent().addClass('d-none');
          if(clearTimeInterval) clearInterval(clearTimeInterval);
          $('#end-chat-btn').addClass('d-none');
          $('#sendchatsection').html('chat has ended.');
          if(talkTimeInterval) clearInterval(talkTimeInterval);
        }
        chatBox.empty();
        chatBox.append(response.data);
        if(totalChatLogsCount < response.chatLogsCount){
          if(totalChatLogsCount>1){
            handleNewMessage();
          }
          totalChatLogsCount = response.chatLogsCount;
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

function sendMessage(){
  let formData = new FormData();
  formData.append('consultation_id', "{{$consultation_id}}");
  formData.append('message', $('#chat-message').val());
  $.ajax({
      url: "{{route('user.sendmessage')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success:function(response){
        console.log("send message response: ", response);
      },
      error:function(error){
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
  $(that).hide();
  $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
}
function handleEndChat(){
  fetchChatLogs('leave');
  setTimeout(function(){
    window.location.href = "{{route('user.consultations')}}";
  }, 2000);
}

function countDownToRechargeAlert(){
  @if($appointment->scheduled_for == 0)
    if(talkTimeInSeconds > 0){
      talkTimeInterval = setInterval(function(){
        if(talkTimeInSeconds <= 0){
          if(talkTimeInterval) clearInterval(talkTimeInterval);
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
            handleEndChat();
          }, 3000);
        }
        console.log(talkTimeInSeconds);
        talkTimeInSeconds--;
      }, 1000);
    }
  @endif
}
</script>
@endpush
