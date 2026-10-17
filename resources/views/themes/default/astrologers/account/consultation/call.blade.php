@extends(themeView().'layout')
@push('style')
<style>
.call-section {
  display: flex;
   flex-direction: column;
   justify-content: center;
   align-items: center;
   height:60vh;
   min-height:84vh;
   background: #111;
   color: #fff;
   max-width: 400px;
   overflow: hidden;
   margin: auto;
   border-radius:20px;
}

.call-container {
  text-align: center;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 40px 20px;
  box-sizing: border-box;
}

.call-status {
  color: #4caf50;
  font-size: 1rem;
  margin-top: 20px;
}

.profile-wrapper {
  margin-top: auto;
  margin-bottom: auto;
}

.profile-img {
  width: 120px;
  height: 120px;
  background: #1976d2;
  border-radius: 50%;
  margin: 0 auto 20px auto;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  font-weight: bold;
  color: #fff;
}

.username {
  font-size: 1.4rem;
  margin: 5px 0;
}

.sub-text {
  color: #bbb;
  font-size: 1rem;
}

.call-actions {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin-bottom: 30px;
}

.btn-action {
  width: 65px;
  height: 65px;
  border-radius: 50%;
  border: none;
  font-size: 1.6rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-action.gray {
  background: #333;
  color: #fff;
}

.btn-action.red {
  background: #e53935;
  color: #fff;
}

.btn-action:active {
  transform: scale(0.9);
}

/* Mobile specific */
@media (max-width: 768px) {
  .call-container {
    justify-content: space-between;
    padding: 20px;
  }
  .call-actions {
    margin-bottom: 15px;
  }
  .call-section {
    display: flex;
     flex-direction: column;
     justify-content: center;
     align-items: center;
     height:100vh;        /* 👈 90vh की जगह 100vh */
     min-height:100vh;    /* 👈 safe रखने के लिए */
     background: #111;
     color: #fff;
     overflow: hidden;
  }
}

</style>
@endpush

@section('title', 'Astrologer Call Consultation | ASTROVYRA')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<section class="my-3">
<div class="call-section">
  <div class="call-container">
    <!-- Status -->
    <p class="connected text-white p-0 m-0 mt-2" id="root">Ringing...</p>
    <p class="call-status-2 text-white p-0 m-0 mt-1 d-none" id="time-counter">00:00:00</p>

    <!-- Profile -->
    <div class="profile-wrapper">
      <div class="profile-img">{{ substr($user->name, 0, 1) }}</div>
      <h4 class="username">{{ $user->name }}</h4>
      {{--<p class="sub-text">Audio Call</p>--}}
    </div>

    <!-- Join call prompt: mic only used after astrologer taps Join -->
    <div id="join-call-prompt" class="d-none text-center text-white py-4 px-2">
      <p class="mb-3">Call is ready. Microphone will be used when you join.</p>
      <button type="button" class="btn btn-success btn-lg" id="join-call-btn"><i class="bi bi-telephone-fill me-2"></i>Join call</button>
    </div>
    <!-- Actions -->
    <div class="call-actions">
      <button class="btn-action gray" id="toggleAudio">
        <i class="bi bi-mic-fill"></i>
      </button>
      <button class="btn-action red" id="leave">
        <i class="bi bi-telephone-fill"></i>
      </button>
      @php
        $gender=$appointment->gender?$appointment->gender:'male';
      @endphp
      <button type="button" class="btn-action gray" name="button" id="generateKundaliEnglishBtn" onclick="generateKundali(this,'{{ $appointment->name }}','{{ $gender }}','{{ $appointment->dateofbirth }}','{{ $appointment->birthtime }}','{{ $appointment->birthplace }}','en')">
        <img src="/assets/img/english-language.png" alt="" class="v-iconn">
      </button>
      <button type="button" class="btn-action gray" name="button" id="generateKundaliHindiBtn" onclick="generateKundali(this,'{{ $appointment->name }}','{{ $gender }}','{{ $appointment->dateofbirth }}','{{ $appointment->birthtime }}','{{ $appointment->birthplace }}','hi')">
      <img src="/assets/img/vowel.png" alt="" class="v-iconn">
      </button>
      {{--<button class="btn-action gray">
        <i class="bi bi-volume-up-fill"></i>
      </button>--}}
    </div>
  </div>
</div>
</section>





@endsection
@push('scripts')
<!-- Agora SDK loaded only when astrologer clicks "Join call" to avoid mic access on page load -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// var zp;
var consultation_id = "{{$consultation_id}}";
var currentTime = 0;
var dSetInterval = null;
var canPhoneTimeout = null;
let showWaitingPopup = true;
let closeForcefully = true;
let updateWaitingTextInterval = null;
var startTime = '{{ $start_time }}';
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
    $('#time-counter').text(formatTime(remainingTime));
  }else{
    $('#time-counter').addClass("d-none").text('00:00:00');
  }
}

function updatePhoneCallStatus(status='continue'){
  let formData = new FormData();
  formData.append('consultation_id', consultation_id)
  formData.append('status', status)
  $.ajax({
    url: "{{route('astrologer.updatephonecallstatus')}}",
    method: 'POST',
    data:formData,
    cache : false,
    processData: false,
    contentType: false,
    headers:{
      'X-CSRF-TOKEN' : '{{csrf_token()}}'
    },
    success: function(response){
      let status = response.status;
        if(status =='done'){
          if (client) client.leave();
          clearInterval(dSetInterval);
          $('#root').html("Disconnected");
          if(clearTimeInterval) clearInterval(clearTimeInterval);
          setTimeout(function(){
            window.location.href = "{{route('astrologer.consultations')}}";
          }, 3000);
        }
      },
      error: function(error) {
        console.log(error);
        toastr.error('error in phone call');
      }
    });
}

let client = null;
let localAudioTrack = null;
let consultationDetail={};

let isAudioMuted = false;
let currentCameraDeviceId = null;

function canPhoneCall(){
  let formData = new FormData();
  formData.append('consultation_id', consultation_id)
  $.ajax({
      url: "{{route('astrologer.canphonecall')}}",
      method: 'POST',
      data:formData,
      cache : false,
      processData: false,
      contentType: false,
      headers:{
        'X-CSRF-TOKEN' : '{{csrf_token()}}'
      },
      success:function(response){
        let status = response.status;
        if(status=='wait'){
          canPhoneTimeout = setTimeout(canPhoneCall, 3000);
          if(showWaitingPopup){
            let countdown = response.count_down;
            Swal.fire({
              title: 'Please wait for user',
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
                    clearTimeout(canPhoneTimeout);
                    clearInterval(updateWaitingTextInterval);
                    if(closeForcefully){
                      updatePhoneCallStatus('cancel');
                      Swal.close();
                    }
                    Swal.fire({
                      title: 'The user cannot join the call.',
                      confirmButtonText: 'Ok',
                      allowOutsideClick: false,
                      allowEscapeKey: false
                    }).then(() => {
                      window.location.href = "{{route('astrologer.consultations')}}";
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
            clearTimeout(canPhoneTimeout);
            clearInterval(updateWaitingTextInterval);
            $('#time-counter').addClass("d-none").text('00:00:00');
            if(clearTimeInterval) clearInterval(clearTimeInterval);
          }
          if(status=='not now'){
            $('#root').html("Calling");
          }
          if(status=='done'){
            $('#root').html("Disconnected");
          }
          if(status=='start'){
            $('#root').html("Ready to join");
            consultationDetail = response.consultation_detail;
            $('#join-call-prompt').removeClass('d-none');
            clearTimeInterval = setInterval(()=>{
              startTime++;
              updateRemainingTimeDisplay(startTime);
            }, 1000);
            $('#time-counter').removeClass("d-none").text('00:00:00');
          }
        }

      },
      error:function(error){
        toastr.error(error.responseJSON.message);
      },

  });
}

function loadAgoraAndStartPhoneCall() {
  if (window.AgoraRTC) {
    $('#join-call-prompt').addClass('d-none');
    startBasicLiveStreaming();
    return;
  }
  var s = document.createElement('script');
  s.src = "{{ asset('assets/js/agora/AgoraRTC_N-4.23.1.js') }}";
  s.onload = function() {
    $('#join-call-prompt').addClass('d-none');
    startBasicLiveStreaming();
  };
  document.head.appendChild(s);
}

document.getElementById('join-call-btn').addEventListener('click', function() {
  this.disabled = true;
  this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connecting...';
  loadAgoraAndStartPhoneCall();
});

canPhoneCall();

function initializeClient() {
    client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
    client.on("user-published", async (user, mediaType) => {
      // console.log("User joined:", user);
      await client.subscribe(user, mediaType);
      // console.log("subscribe success for mediaType:", mediaType);
      if(mediaType === "audio"){
        user.audioTrack.play();
      }
    });
    joinPhoneCall();
}

async function joinPhoneCall(){
  await client.join(consultationDetail.app_id, consultationDetail.room_id, consultationDetail.app_token, consultationDetail.user_id);
  localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
  await client.publish([localAudioTrack]);
  if(dSetInterval) clearInterval(dSetInterval);
  dSetInterval = setInterval(function (){
    updatePhoneCallStatus();
  }, 5000);
}

async function leaveChannel() {
  if(localAudioTrack){
    localAudioTrack.close();
    localAudioTrack = null;
  }
  clearInterval(dSetInterval);
  updatePhoneCallStatus('leave');
  await client.leave();
  $('#root').html("Disconnected");
  setTimeout(function(){
    window.location.href = "{{route('astrologer.consultations')}}";
  }, 3000);
}

function startBasicLiveStreaming() {
  initializeClient();
  document.getElementById("leave").onclick = leaveChannel;
  document.getElementById("toggleAudio").onclick = function(){
    if(localAudioTrack){
      if(isAudioMuted){
        localAudioTrack.setEnabled(true);
        this.textContent = "Mute Audio";
        $('#toggleAudio').html('<i class="bi bi-mic-fill"></i>');
      }else{
        localAudioTrack.setEnabled(false);
        this.textContent = "Unmute Audio";
        $('#toggleAudio').html('<i class="bi bi-mic-mute-fill"></i>');
      }
      isAudioMuted = !isAudioMuted;
    }
  };

}

// function createZegoCloud(consultation_detail){
//   const app_id = consultation_detail.app_id;
//   const app_sign = consultation_detail.app_sign;
//   const room_id = consultation_detail.room_id;
//   const user_id = consultation_detail.user_id;
//   const user_name = consultation_detail.user_name;
//   const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(app_id, app_sign, room_id, user_id, user_name);
//   zp = ZegoUIKitPrebuilt.create(kitToken);
//   zp.joinRoom({
//     container: document.querySelector("#root"),
//     sharedLinks: [],
//     scenario: {
//       mode: ZegoUIKitPrebuilt.VideoConference,
//     },
//     turnOnMicrophoneWhenJoining: true,
//     turnOnCameraWhenJoining: true,
//     showMyCameraToggleButton: true,
//     showMyMicrophoneToggleButton: true,
//     showAudioVideoSettingsButton: true,
//     showScreenSharingButton: false,
//     showTextChat: false,
//     showUserList: true,
//     maxUsers: 2,
//     layout: "Auto",
//     showLayoutButton: false,
//     joinRoomCallback:()=>{
//       if(dSetInterval) clearInterval(dSetInterval);
//       dSetInterval = setInterval(function (){
//         updatePhoneCallStatus();
//       }, 5000);
//     },
//     leaveRoomCallback : ()=>{
//       clearInterval(dSetInterval);
//       updatePhoneCallStatus('leave');
//       zp.destroy();
//       $('#root').html("Phone call has ended.");
//       setTimeout(function(){
//         window.location.href = "{{route('astrologer.consultations')}}";
//       }, 3000);
//     },
//     branding: {
//       logoURL:consultation_detail.logo_url
//     },
//     showRoomTimer: true,
//     showWaitingCallAcceptAudioVideoView: true,
//   });
// }
</script>
<script type="text/javascript">
  function generateKundali(that,name,gender,date,time,city,lang){
    $(that).prop('disabled',true).text("Kundali Generating...");
    let formData={
      name,gender,date,time,city,lang
    };
    $.ajax({
      url:"{{ route('kundali.kundalienquiry') }}",
      type:"POST",
      data:formData,
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
      },
      success:function(response){
        $(that).prop('disabled',false).text(`Kundali (${lang})`);
        toastr.success(response.message);
        if(response.redirectTo){
          window.open(response.redirectTo, '_blank');
        }
      },
      error:function(xhr){
        $(that).prop('disabled',false).text(`Kundali (${lang})`);
        console.log(xhr);
        let errors=xhr.responseJSON.errors;
        for(let key in errors){
          toastr.error(errors[key][0]);
        }
        submitBtn.prop("disabled",false);
      }
    });
    return false;
  }
</script>
@endpush
