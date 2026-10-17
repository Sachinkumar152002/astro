@extends(themeView().'layout')
  @push('style')
  <style media="screen">
    html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
    }

    .video-section {
    position: relative;
    width: 100%;
    height: 100vh;
    background: black;
    overflow: hidden;
    }

    #local-player {
    width: 100%;
    height: 100%;
    background: #000;
    object-fit: cover;
    }

    .video-section .viewer-count {
      position: absolute;
      top: 1%;
      left: 1%;
      color: white;
      font-size: 16px;
      font-weight: bold;
      background: rgba(0, 0, 0, 0.4);
      padding: 5px 10px;
      border-radius: 8px;
      z-index: 10;
    }
    .video-section .time-counter {
      position: absolute;
      top: 1%;
      right: 1%;
      color: white;
      font-size: 16px;
      font-weight: bold;
      background: rgba(0, 0, 0, 0.4);
      padding: 5px 10px;
      border-radius: 8px;
      z-index: 10;
    }


    .video-section .leave-btn {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: red;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.4);
    transition: 0.2s;
    z-index: 10;
    }
    .video-section .leave-btn:hover {
    background: darkred;
    }

    .video-section .chat-box {
    position: absolute;
    bottom: 80px;
    left: 15px;
    width: 300px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    color: white;
    z-index: 10;
    }

    .video-section .chat-messages {
    flex: 1;
    max-height: 100px;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
    line-height: 1.4;
    color: white !important;
    }

    .video-section .chat-messages::-webkit-scrollbar {
    width: 4px;
    }
    .video-section .chat-messages::-webkit-scrollbar-track {
    background: transparent;
    }
    .video-section .chat-messages::-webkit-scrollbar-thumb {
    background: white;
    border-radius: 4px;
    }

    .video-section .chat-messages {
      color: white;
    }


    .video-section .chat-input {
      color: white;
      background: transparent;
    }
    .video-section .chat-input-container {
    display: flex;
    align-items: center;
    border-top: 1px solid rgba(255,255,255,0.2);
    padding: 6px;
    background: rgba(0,0,0,0.6);
    }
    .video-section .chat-input {
    flex: 1;
    padding: 8px;
    border-radius: 6px;
    border: none;
    outline: none;
    font-size: 14px;
    width: 80%;
    }
    .video-section .chat-send-btn {
    margin-left: 8px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 12px;
    height: 36px;
    cursor: pointer;
    }
    .video-section .chat-send-btn:hover {
    background: #45a049;
    }


    @media (max-width: 600px) {
    .video-section .chat-box {
      width: 90%;
      left: 5%;
      bottom: 70px;
    }
    .video-section .leave-btn {
      padding: 10px 20px;
      font-size: 14px;
    }
    }
  </style>
  @endpush

  @section('title', 'Live Video')

  @section('content')
  <section class="mt-3 mt-lg-0 mb-5 mt-mobile video-section">
      <!-- <div id="root" class="text-center vedio-sec mt-5 mt-lg-0">Please wait</div> -->
      <div id="viewer-count" class="viewer-count">👁️ 0</div>
      <div id="time-counter" class="time-counter">00:00:00</div>
      <div id="start-live-prompt" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:15;text-align:center;color:#fff;">
        <p class="mb-3">Camera and microphone will be used when you start the live stream.</p>
        <button type="button" class="btn btn-success btn-lg" id="start-live-btn"><i class="bi bi-camera-video-fill me-2"></i>Start live stream</button>
      </div>
      <div id="local-player"></div>
      <button type="button" id="leave" class="leave-btn d-none">Leave</button>
      <div id="container" class="chat-box">
        <div>
          <div id="textDisplay" class="chat-messages"></div>
        </div>
        <div id="inputContainer" class="chat-input-container d-none">
          <input type="text" id="textInput" class="chat-input" placeholder="Enter text">
          <button id="submitButton" class="chat-send-btn" onclick="buttonClick()"> Send </button>
        </div>
      </div>

    </section>



  @endsection
  @push('scripts')
  <!-- Agora SDKs loaded only when astrologer clicks "Start live stream" to avoid camera/mic on page load -->
  <script type="text/javascript">
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
      if(remainingTime>0){
        $('#time-counter').text(formatTime(remainingTime));
      }else{
        $('#time-counter').addClass("d-none").text('00:00:00');
        clearInterval(clearTimeInterval);
      }
    }
    let liveStreamEndsIn="{{ $liveStreamEndsIn }}"
    let clearTimeInterval = setInterval(()=>{
      liveStreamEndsIn--;
      updateRemainingTimeDisplay(liveStreamEndsIn);
    }, 1000);
  </script>
  <script>
    const astrologer_live_id = @json($astrologerLive->id);
    const data = @json($data);
    let uid = data.uid;
    let viewerCount = 0;

    let updateInterval=null;
    var redirectTo="{{ route('astrologer.dashboard') }}";

    function update(status){
      let formData = new FormData();
      data.astrologer_live_id = astrologer_live_id;
      data.status = status;
      $.ajax({
        url: "{{route('astrologer.live.update')}}",
        method: 'POST',
        data:data,
        headers:{
          'X-CSRF-TOKEN' : '{{csrf_token()}}'
        },
        success: function(response){
          console.log(response);
          viewerCount=response.audience.total;
          document.getElementById("viewer-count").innerText = `👁️ ${viewerCount}`;
          if(response.data.status == "end"){
            leaveChannel();
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }

    let client = null;
    let localAudioTrack = null;
    let localVideoTrack = null;
    let rtmClient = null;
    let rtm = null;
    let rtmChannel = null;

    function initializeClient() {
        const {RTM} = AgoraRTM;
        client = AgoraRTC.createClient({ mode: "live", codec: "vp8", role: "host" });
        joinAsHost();
        rtm = new RTM(data.app_id, data.uid);
        joinHostRTM();
        // publishMessage(["message"]);
        // publishMessage("message");
    }

    async function joinAsHost(){
      await client.join(data.app_id, data.room_id, data.app_secret, data.uid);
      client.setClientRole("host");
      localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
      localVideoTrack = await AgoraRTC.createCameraVideoTrack();
      localVideoTrack.play("local-player");
      await client.publish([localAudioTrack, localVideoTrack]);
      if(updateInterval) clearInterval(updateInterval);
      updateInterval = setInterval(function (){
        update('started');
      }, 5000);
    }

    async function joinHostRTM(){
      rtm.addEventListener("message", event => {
        console.log(event);
        let message = JSON.parse(event.message);
        if(message.userName){
          showMessage(message.userName, message.message);
        }else{
          showMessage(event.publisher, message.message);
        }
      });
      // rtm.addEventListener("presence", event => {
      //   if(event.eventType === "SNAPSHOT"){
      //     console.log('i joined');
      //   }else{
      //     let message = event.eventType == "REMOTE_JOIN" ? "Joined" : "Leaved";
      //     showMessage(event.publisher, message);
      //   }
      // });
      // rtm.addEventListener("status", event => {
      //   console.log("status event", event);
      //   const currentState = event.state;
      //   const changeReason = event.reason;
      //   showMessage("INFO", JSON.stringify(event));
      // });
      await rtm.login({ token: data.rtm_token });
      const result = await rtm.subscribe(data.room_id);
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
      const localPlayerContainer = document.getElementById(uid);
      localPlayerContainer && localPlayerContainer.remove();
      client.remoteUsers.forEach((user) => {
        const playerContainer = document.getElementById(user.uid);
        playerContainer && playerContainer.remove();
      });
      clearInterval(updateInterval);
      update('leaved');
      await client.leave();
      $('#local-player').html("Video has ended.");
      setTimeout(function(){
        window.location.href = redirectTo;
      }, 3000);
    }

    function startBasicLiveStreaming() {
      initializeClient();
      document.getElementById("leave").onclick = leaveChannel;
    }
    const publishMessage = async (message) => {
      const payload = { type: "text", message: message, userName:data.astrologer_name };
      const publishMessage = JSON.stringify(payload);
      const publishOptions = { channelType: 'MESSAGE'}
      try {
        const result = await rtm.publish(data.room_id, publishMessage, publishOptions);
        // showMessage(data.uid, publishMessage);
        // console.log(result);
      } catch (status) {
        console.log(status);
      }
    }
    const textDisplay = document.getElementById('textDisplay');
    const showMessage = (user, msg) => {
      let div = document.createElement('div');
      div.innerHTML=`<h6>${user}</h6><p class="m-0 p-0">${msg}</p>`;
      textDisplay.appendChild(div);
      const newLine = document.createElement("br");
      textDisplay.appendChild(newLine);
    }
    const buttonClick = () => {
      var input = document.getElementById("textInput");
      publishMessage(input.value);
      input.value = '';
    }

    function loadAgoraAndStartLive() {
      if (window.AgoraRTC && window.AgoraRTM) {
        $('#start-live-prompt').addClass('d-none');
        $('#leave').removeClass('d-none');
        startBasicLiveStreaming();
        return;
      }
      function runAfterScripts() {
        if (!window.AgoraRTC || !window.AgoraRTM) return;
        $('#start-live-prompt').addClass('d-none');
        $('#leave').removeClass('d-none');
        startBasicLiveStreaming();
      }
      if (window.AgoraRTC && window.AgoraRTM) {
        runAfterScripts();
        return;
      }
      if (!window.AgoraRTC) {
        var s1 = document.createElement('script');
        s1.src = "{{ asset('assets/js/agora/AgoraRTC_N-4.23.1.js') }}";
        s1.onload = function() {
          if (!window.AgoraRTM) {
            var s2 = document.createElement('script');
            s2.src = "{{ asset('assets/js/agora/agora-rtm-2.2.2.min.js') }}";
            s2.onload = runAfterScripts;
            document.head.appendChild(s2);
          } else runAfterScripts();
        };
        document.head.appendChild(s1);
      } else {
        var s2 = document.createElement('script');
        s2.src = "{{ asset('assets/js/agora/agora-rtm-2.2.2.min.js') }}";
        s2.onload = runAfterScripts;
        document.head.appendChild(s2);
      }
    }

    document.getElementById('start-live-btn').addEventListener('click', function() {
      this.disabled = true;
      this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Starting...';
      loadAgoraAndStartLive();
    });

    $('document').ready(function(){
      // Show "Start live stream" button; camera/mic only after user clicks (fixes webview permission on any page)
      // startBasicLiveStreaming() is called only from loadAgoraAndStartLive() after user click
    });


    // function createZegoCloud(data){
    //   const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(data.app_id, data.app_secret, data.room_id, data.astrologer_id, data.astrologer_name);
    //   zp = ZegoUIKitPrebuilt.create(kitToken);
    //   zp.joinRoom({
    //     container: document.querySelector("#root"),
    //     sharedLinks: [],
    //     showPreJoinView: false,
    //     autoHideFooter: false,
    //     scenario: {
    //         mode: ZegoUIKitPrebuilt.LiveStreaming,
    //         config: {
    //             'role':'Host',
    //         },
    //     },
    //     turnOnMicrophoneWhenJoining: true,
    //     turnOnCameraWhenJoining: true,
    //     showMyCameraToggleButton: true,
    //     showMyMicrophoneToggleButton: true,
    //     showAudioVideoSettingsButton: true,
    //     showScreenSharingButton: false,
    //     showTextChat: false,
    //     showUserList: true,
    //     maxUsers: 10,
    //     layout: "Auto",
    //     showLayoutButton: false,
    //     joinRoomCallback:()=>{
    //       setTimeout(()=>{
    //         $('#ZegoLiveButton').find('span').click();
    //         $('#ZegoLiveButton').click();
    //         // if($('#ZegoLiveButton').find('span')){
    //         //   $('#ZegoLiveButton').find('span').click();
    //         // }else{
    //         //   $('#ZegoLiveButton').click();
    //         // }
    //       },1000);
    //       if(updateInterval) clearInterval(updateInterval);
    //       updateInterval = setInterval(function (){
    //         update('started');
    //       }, 5000);
    //     },
    //     leaveRoomCallback : ()=>{
    //       clearInterval(updateInterval);
    //       update('leaved');
    //       zp.destroy();
    //       $('#root').html("Video has ended.");
    //       setTimeout(function(){
    //         window.location.href = redirectTo;
    //       }, 3000);
    //     },
    //     branding: {
    //       logoURL:data.logo
    //     },
    //     showRoomTimer: false,
    //     showWaitingCallAcceptAudioVideoView: true,
    //   });
    // }
  </script>

  @endpush
