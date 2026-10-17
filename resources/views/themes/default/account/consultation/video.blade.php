@extends(themeView().'layout')

@section('title', 'Video Consultation | '.config("app.name"))

@push('style')
  <style media="screen">
    body{
      overflow:hidden;
    }
    .video-section {
      position: relative;
      width: 100%;
      height: 100vh;
      background: #000;
      overflow: hidden;
    }

    /* Remote video full screen */
    #remote-container {
      position: absolute;
      inset: 0; /* top:0; right:0; bottom:0; left:0 */
      width: 100%;
      height: 100%;
      background: #000;
      z-index: 1;
    }

    #remote-container video,
    #remote-container > div {
      width: 100%;
      height: 100%;
      object-fit: cover; /* full screen fit */
    }

    /* Local video small thumbnail */
    #local-player {
      position: absolute;
      top:10%;
      right:2%;
      width: 180px;
      height: 120px;
      border-radius: 12px;
      overflow: hidden;
      background: #222;
      z-index: 2;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
    }

    #local-player video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Call action buttons */
    .call-actions {
      position: absolute;
      bottom: 20%;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 15px;
      z-index: 3;
    }

    .btn-action {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: none;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .btn-action.gray {
      background: rgba(255,255,255,0.2);
      color: #fff;
    }

    .btn-action.red {
      background: #e53935;
      color: #fff;
    }

  @media(max-width:768px) {
      .call-actions {
        bottom:30px;
      }
    }
  </style>
@endpush

@section('title', 'User Video Consultation | ASTROVYRA')

@section('content')
<section class="mt-3 mt-lg-0 mb-5 mt-mobile video-section">
    <p class="v-status text-white p-0 m-0 mt-1 d-none" id="time-counter">00:00:00</p>
    <div id="local-player"></div>
    <div id="join-video-prompt" class="d-none text-center text-white py-4 px-2" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:5;">
      <p class="mb-3">Video call is ready. Camera and microphone will be used when you join.</p>
      <button type="button" class="btn btn-success btn-lg" id="join-video-btn"><i class="bi bi-camera-video-fill me-2"></i>Join video call</button>
    </div>
    <div id="remote-container" style="display: flex; flex-wrap: wrap;"></div>
    <div style="margin-top: 20px;" class="call-actions">
      <button type="button" class="btn-action gray" id="toggleAudio"><i class="bi bi-mic-fill"></i></button>
      <button type="button" class="btn-action gray" id="toggleVideo"><i class="bi bi-camera-video-fill"></i></button>
      <!-- <button type="button" class="btn-action gray" id="switchCamera"><i class="bi bi-camera-video-fill"></i></button> -->
      <button type="button" class="btn-action red" id="leave" class="leave-btn"><i class="bi bi-telephone-fill text-white"></i></button>
  </div>
</section>
@endsection
@push('scripts')
<!-- Agora SDK is loaded only when user clicks "Join video call" to avoid camera/mic access on page load -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var consultation_id = "{{$consultation_id}}";
var end_time = new Date('{{$end_time}}').getTime();
var currentTime = 0;
// var zp;
var dSetInterval = null;
var canVideoTimeout = null;
let showWaitingPopup = true;
let closeForcefully = true;
let updateWaitingTextInterval = null;
var talkTimeInterval = null;
let talkTimeInSeconds = Number("{{ $talk_time_in_seconds }}");
var startTime = '{{ $start_time }}';
var endTime = new Date('{{ $end_time }}');
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

function updateVideoCallStatus(status='continue'){
  let formData = new FormData();
  formData.append('consultation_id', consultation_id)
  formData.append('status', status)
  $.ajax({
    url: "{{route('user.updatevideocallstatus')}}",
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
          if(dSetInterval) clearInterval(dSetInterval);
          if(clearTimeInterval) clearInterval(clearTimeInterval);
          if(talkTimeInterval) clearInterval(talkTimeInterval);
          $('#local-player').html("Video has ended.");
          setTimeout(function(){
            window.location.href = "{{route('user.consultations')}}";
          }, 3000);
        }
      },
      error: function(error) {
        console.log(error);
        toastr.error('error in video call');
      }
    });
}

let client = null;
let localAudioTrack = null;
let localVideoTrack = null;
let consultationDetail={};

let isAudioMuted = false;
let isVideoMuted = false;
let currentCameraDeviceId = null;
let videoDevices = [];

function canVideoCall(){
  let formData = new FormData();
  formData.append('consultation_id', consultation_id)
  $.ajax({
      url: "{{route('user.canvideocall')}}",
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
          canVideoTimeout = setTimeout(canVideoCall, 3000);
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
                    if(canVideoTimeout) clearTimeout(canVideoTimeout);
                    if(updateWaitingTextInterval) clearInterval(updateWaitingTextInterval);
                    if(closeForcefully){
                      updateVideoCallStatus('cancel');
                      Swal.close();
                    }
                    Swal.fire({
                      title: 'The astrologer cannot join the call.',
                      confirmButtonText: 'Ok',
                      allowOutsideClick: false,
                      allowEscapeKey: false
                    }).then(() => {
                      window.location.href = "{{route('astrologers.talktoastrologers')}}";
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
            if(canVideoTimeout) clearTimeout(canVideoTimeout);
            if(updateWaitingTextInterval) clearInterval(updateWaitingTextInterval);
            $('#time-counter').addClass("d-none").text('00:00:00');
            if(clearTimeInterval) clearInterval(clearTimeInterval);
          }
          if(status=='not now'){
            $('#local-player').html("You cannot access this link at this time.");
          }
          if(status=='done'){
            $('#local-player').html("Video has ended.");
          }
          if(status=='start'){
            countDownToRechargeAlert();
            consultationDetail=response.consultation_detail;
            // Show "Join" button instead of auto-starting — camera/mic only after user taps (fixes webview permission on any page)
            $('#join-video-prompt').removeClass('d-none');
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

function loadAgoraAndStartVideoCall() {
  if (window.AgoraRTC) {
    $('#join-video-prompt').addClass('d-none');
    startBasicLiveStreaming();
    return;
  }
  var s = document.createElement('script');
  s.src = "{{ asset('assets/js/agora/AgoraRTC_N-4.23.1.js') }}";
  s.onload = function() {
    $('#join-video-prompt').addClass('d-none');
    startBasicLiveStreaming();
  };
  document.head.appendChild(s);
}

document.getElementById('join-video-btn').addEventListener('click', function() {
  this.disabled = true;
  this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connecting...';
  loadAgoraAndStartVideoCall();
});

canVideoCall();

function initializeClient() {
    client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
    client.on("user-published", async (user, mediaType) => {
      console.log("User joined:", user);
      await client.subscribe(user, mediaType);
      console.log("subscribe success for mediaType:", mediaType);
      if(mediaType === "video"){
        displayRemoteVideo(user);
      }
      if(mediaType === "audio"){
        user.audioTrack.play();
      }
    });
    joinVideoCall();
}

async function joinVideoCall(){
  console.log(consultationDetail);
  await client.join(consultationDetail.app_id, consultationDetail.room_id, consultationDetail.app_token, consultationDetail.user_id);
  // Get available cameras
  videoDevices = (await AgoraRTC.getCameras()) || [];
  console.log("videoDevices", videoDevices);
  if(videoDevices.length > 0){
    currentCameraDeviceId = videoDevices[0].deviceId;
  }
  localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
  // localVideoTrack = await AgoraRTC.createCameraVideoTrack();
  localVideoTrack = await AgoraRTC.createCameraVideoTrack({ cameraId: currentCameraDeviceId });
  $('#local-player').html("");
  localVideoTrack.play("local-player");
  await client.publish([localAudioTrack, localVideoTrack]);
  if(dSetInterval) clearInterval(dSetInterval);
  dSetInterval = setInterval(function (){
    updateVideoCallStatus();
  }, 5000);
}

function displayRemoteVideo(user) {
  console.log('remote user', user)
  const remoteVideoTrack = user.videoTrack;
  const containerId = `remote-${user.uid}`;
  let remotePlayerContainer = document.getElementById(containerId);

  // If container doesn't exist, create it
  if (!remotePlayerContainer) {
    remotePlayerContainer = document.createElement("div");
    remotePlayerContainer.id = containerId;
    remotePlayerContainer.style.width = "100%";
    remotePlayerContainer.style.height = "100%";
    remotePlayerContainer.style.margin = "10px";
    remotePlayerContainer.style.backgroundColor = "#000";
    document.getElementById("remote-container").appendChild(remotePlayerContainer);
  }
  // Play video again into same container
  remoteVideoTrack.play(remotePlayerContainer);

}

async function leaveChannel() {
  if (localAudioTrack) {
      localAudioTrack.close();
      localAudioTrack = null;
  }
  if (localVideoTrack) {
      localVideoTrack.close();
      localVideoTrack = null;
  }
  // const localPlayerContainer = document.getElementById("local-player");
  // localPlayerContainer && localPlayerContainer.remove();
  // client.remoteUsers.forEach((user) => {
  //   const playerContainer = document.getElementById(user.uid);
  //   playerContainer && playerContainer.remove();
  // });
  if(dSetInterval) clearInterval(dSetInterval);
  updateVideoCallStatus('leave');
  await client.leave();
  $('#local-player').html("Video has ended.");
  setTimeout(function(){
    window.location.href = "{{route('user.consultations')}}";
  }, 3000);
}

function startBasicLiveStreaming() {
  initializeClient();
  document.getElementById("leave").onclick = leaveChannel;
  document.getElementById("toggleAudio").onclick = function(){
    if(localAudioTrack){
      if(isAudioMuted){
        localAudioTrack.setEnabled(true);
        $(this).html('<i class="bi bi-mic-fill"></i>');
      }else{
        localAudioTrack.setEnabled(false);
        $(this).html('<i class="bi bi-mic-mute-fill"></i>');
      }
      isAudioMuted = !isAudioMuted;
    }
  };
  document.getElementById("toggleVideo").onclick = function(){
    if(localVideoTrack){
      if(isVideoMuted){
        localVideoTrack.setEnabled(true);
        $(this).html('<i class="bi bi-camera-video-fill"></i>');
      }else{
        localVideoTrack.setEnabled(false);
        $(this).html('<i class="bi bi-camera-video-off-fill"></i>');
      }
      isVideoMuted = !isVideoMuted;
    }
  }


  // document.getElementById("switchCamera").onclick = async function(){
  //   if(!videoDevices.length) return;
  //   const nextDevice = videoDevices.find(device => device.deviceId !== currentCameraDeviceId);
  //   if(!nextDevice) return;
  //   const newTrack = await AgoraRTC.createCameraVideoTrack({
  //     cameraId: nextDevice.deviceId,
  //   });
  //   await client.unpublish(localVideoTrack);
  //   localVideoTrack.stop();
  //   localVideoTrack.close();
  //   localVideoTrack = newTrack;
  //   currentCameraDeviceId = nextDevice.deviceId;
  //   await client.publish([localVideoTrack]);
  //   localVideoTrack.play("local-player");
  // };
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
        talkTimeInSeconds--;
      }, 1000);
    }
  @endif
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
//     container: document.querySelector("#local-player"),
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
//         updateVideoCallStatus();
//       }, 5000);
//     },
//     leaveRoomCallback : ()=>{
//       clearInterval(dSetInterval);
//       updateVideoCallStatus('leave');
//       zp.destroy();
//       $('#local-player').html("Video has ended.");
//       setTimeout(function(){
//         window.location.href = "{{route('user.consultations')}}";
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
@endpush
