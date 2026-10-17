@extends(themeView().'layout')

@section('title', 'Chat')
@section('keywords', '')
@section('description','')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Chat with Astrologer
                </div>
                <div class="card-body">
                    <div id="chat-box" class="border rounded p-3 mb-3" style="height: 300px; overflow-y: auto; background-color: #f9f9f9;">
                        <!-- Chat messages will be loaded here -->
                    </div>

                    <div class="input-group">
                        <textarea id="chat-message" class="form-control" placeholder="Type a message..." rows="1"></textarea>
                        <div class="input-group-append">
                            <button id="send-message-btn" class="btn btn-primary">Send</button>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="chat_id" value="{{$chat->id}}"> <!-- Replace with actual chat session ID -->
                    <input type="hidden" id="user_id" value="{{$chat->user_id}}">
                    <input type="hidden" id="astrologer_id" value="{{auth()->guard('astrologer')->user()->id}}">
                    <input type="hidden" id="message_by" value="{{$user_type}}">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/agora/agora-rtm-2.2.2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Fetch chat logs periodically
    function fetchChatLogs() {
        let chatId = $('#chat_id').val();
        $.ajax({
            url: '/chat-logs/' + chatId,
            method: 'GET',
            success: function(data) {
                let chatBox = $('#chat-box');
                chatBox.empty();

                data.forEach(function(log) {
                    let messageClass = log.message_by === 'user' ? 'text-end' : 'text-right';
                    let bgClass = log.message_by === 'user' ? 'bg-light' : 'bg-primary text-white';
                    let sender = log.message_by === 'user' ? 'You' : 'Astrologer';

                    let messageHtml = `
                        <div class="mb-2 ${messageClass}">
                            <div class="d-inline-block p-2 rounded ${bgClass}" style="max-width: 75%;">
                                <strong>${sender}:</strong> ${log.message}
                            </div>
                        </div>
                    `;
                    chatBox.append(messageHtml);
                });

                // Scroll to the bottom of the chat
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }
        });
    }

    // Send a message
    $('#send-message-btn').on('click', function() {
        let message = $('#chat-message').val();
        let chatId = $('#chat_id').val();
        let userId = $('#user_id').val();
        let astrologerId = $('#astrologer_id').val();
        let messageBy = $('#message_by').val();

        if (message.trim() !== '') {
            $.ajax({
                url: '/send-message',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    chat_id: chatId,
                    user_id: userId,
                    astrologer_id: astrologerId,
                    message: message,
                    message_by: messageBy,
                },
                success: function(data) {
                  publishMessage(message, messageBy);
                  $('#chat-message').val(''); // Clear input
                }
            });
        }
    });

    let rtmClient = null;
    const {RTM} = AgoraRTM;
    let rtm = null;
    let rtmChannel = null;

    function initializeClient() {
        rtm = new RTM(data.app_id, data.uid);
        joinHostRTM();
    }

    async function joinRTM(){
      rtm.addEventListener("message", event => {
        console.log(event);
        let data = JSON.parse(event.message);
        let message = data.message;
        let userType = data.userType;
        let createdAt = event.timestamp;
        showMessage(message, userType, createdAt);
      });
      // rtm.addEventListener("presence", event => {
      //   if (event.eventType === "SNAPSHOT") {
      //     console.log('i joined');
      //   }
      //   else {
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

    async function publishMessage(message){
      const payload = { type: "text", message: message, userType:"user" };
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

    const textDisplay = document.getElementById('chat-box');

    async function showMessage(message, userType, createdAt){
      message=message.replace(/\n/g, "<br>");
      let time = new Date(createdAt * 1000);
      const options = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
      };
      time = time.toLocaleTimeString('en-US', options);
      let div = document.createElement('div');
      div.classList.add('card', 'border-0', 'my-2', 'c-cardd');
      if(userType=="user"){
        div.innerHTML=`<div class="chat-astro-bubble text-start">
          <div class="">${message}</div>
          <div class="chat-time text-end">${time}</div>
        </div>`;
      }else{
        div.innerHTML=`<div class="chat-user-bubble text-start">
          <div class="">${message}</div>
          <div class="chat-time text-end">${time}</div>
        </div>`;
      }
      textDisplay.appendChild(div);
    }
    const buttonClick = () => {
      var input = document.getElementById("textInput");
      publishMessage(input.value);
      input.value = '';
    }

    // Auto-fetch chat logs every 2 seconds
    // setInterval(fetchChatLogs, 2000);
    initializeClient();
});
</script>
@endpush
