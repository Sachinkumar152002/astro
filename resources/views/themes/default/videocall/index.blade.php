@extends(themeView().'layout')

@section('title', 'Chat')
@section('keywords', '')
@section('description','')

@section('content')
<section class="mt-3 mb-5">
    <div id="root" ></div>
</section>
@endsection
@push('scripts')

<script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
<script>
let zp = '';
let currentTime = 0;
let dSetInterval = '';
let appointment_id = @json($appointment->id);
var endTime = new Date('{{$end_time}}').getTime();
window.onload = function () {
    function updateStatus(status, currentTime) {
      $.ajax({
          url: @json(url('/'))+'/consult/'+appointment_id+'/update-appointment-status/'+status+'/'+currentTime,
          method: 'GET',
          success: function(response) {
            if(status =='done'){
              console.log('done');
              clearInterval(dSetInterval)
            }
            console.log(response);
          },
          error: function(xhr, status, error) {
              console.error("Error fetching update status:", error);
          }
        });
    }
    const roomID = '{{$roomID}}';
    const userID = '{{$user->id}}';
    // const userID = Math.floor(Math.random() * 10000) + "";
    const userName = "{{$user_name}}";
    const appID = parseInt(@json(config('vrdcms.zego_app_id')));
    const serverSecret = @json(config('vrdcms.zego_server_secret'));
    const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);
    zp = ZegoUIKitPrebuilt.create(kitToken);
    zp.joinRoom({
      container: document.querySelector("#root"),
      // sharedLinks: [{
      //   name: 'IIAG meeting link',
      //   url: "{{$videoCallUrl}}",
      // }],
      sharedLinks: [],
      scenario: {
        mode: ZegoUIKitPrebuilt.VideoConference,
      },
    turnOnMicrophoneWhenJoining: true,
    turnOnCameraWhenJoining: true,
    showMyCameraToggleButton: true,
    showMyMicrophoneToggleButton: true,
    showAudioVideoSettingsButton: true,
    showScreenSharingButton: false,
    showTextChat: false,
    showUserList: true,
    maxUsers: 2,
    layout: "Auto",
    showLayoutButton: false,
      joinRoomCallback : ()=>{
          currentTime = new Date().getTime();
          console.log('ct', currentTime)
          console.log('et', endTime)
          updateStatus('joined', currentTime);
          dSetInterval = setInterval(function (){
            let currentTime = new Date().getTime();
            if(currentTime >= endTime){
              console.log('si-ct', currentTime)
              console.log('si-et', endTime)
              updateStatus('done', currentTime);
              zp.destroy();
            }
          }, 5000);
      },
      leaveRoomCallback : ()=>{
        currentTime = new Date().getTime();
        updateStatus('done', currentTime);
        zp.destroy();
      },
      branding: {
        logoURL: @json(url('/'))+"assets/images/IIAG_New_Logo-For_Web.svg"
      },
      showRoomTimer: true,
      showWaitingCallAcceptAudioVideoView: true,
    });
}
</script>
@endpush
