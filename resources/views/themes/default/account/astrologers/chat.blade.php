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
                    <div class="mb-3">
                        <span id="remainingTime" class="text-danger font-weight-bold"></span>
                    </div>
                    <div id="chat-box" class="border rounded p-3 mb-3" style="height: 300px; overflow-y: auto; background-color: #f9f9f9;">
                        <!-- Chat messages will be loaded here -->
                    </div>

                    <div class="input-group" id="sendchatsection">
                      <div class="row w-100">
                        <div class="col-10">
                          <textarea id="chat-message" class="form-control" placeholder="Type a message..." rows="1"></textarea>
                        </div>
                        <div class="input-group-append col-2">
                            <button id="send-message-btn" class="main_button w-100 p-3 py-2">Send</button>
                        </div>
                        <a href="{{route('astrologer.dashboard')}}" id="redirect_astro" style="color: red; cursor: pointer"></a>
                    </div>
                  </div>
                    <div class="d-none">
                      <a href="#">Pay to Proceed chat</a>
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
<script>
$(document).ready(function() {
  var remainingTime2 = 0;
    let remainingTime = 0; // Time in seconds
    let timerInterval = null;

    // Function to format remaining time into HH:MM:SS
    function formatTime(seconds) {
        let hours = Math.floor(seconds / 3600);
        let minutes = Math.floor((seconds % 3600) / 60);
        let secs = seconds % 60;

        // Pad with leading zeros if necessary
        return [
            String(hours).padStart(2, '0'),
            String(minutes).padStart(2, '0'),
            String(secs).padStart(2, '0')
        ].join(':');
    }

    // Function to update the remaining time display
    function updateRemainingTimeDisplay() {
        $('#remainingTime').text(formatTime(remainingTime));
    }

    // Function to start the timer
    function startTimer(duration) {
        if (timerInterval) return; // Prevent starting multiple timers
        remainingTime = duration;
        updateRemainingTimeDisplay();

        timerInterval = setInterval(function() {
          if(remainingTime == 0){
            document.getElementById('chat-message').style = "display: none";
            document.getElementById('send-message-btn').style = "display: none";
            document.getElementById('redirect_astro').innerText = "Time is over click here to go to dashboard";
          }else if (remainingTime <= 0) {
                clearInterval(timerInterval);
                timerInterval = null; // Reset timer interval
            } else {
                remainingTime--;
                updateRemainingTimeDisplay();
            }
        }, 1000);
    }

    // Fetch chat logs periodically
    function fetchChatLogs() {
        let chatId = $('#chat_id').val();
        $.ajax({
            url: '/chat-logs/' + chatId,
            method: 'GET',
            success: function(data) {
            // if(!remainingTime2){
              remainingTime2 = $('#remainingTime').text();
              remainingTime2 = parseInt(data.remaining_time.toString().split(':')[1])*60;
            // }
                let chatBox = $('#chat-box');
                chatBox.empty();

                // Display chat logs
                data.chat_logs.forEach(function(log) {
                    let messageClass = log.message_by === 'user' ? 'text-start' : 'text-end';
                    let bgClass = log.message_by === 'user' ? 'text-white bg-primary' : 'bg-light';
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

                // Check if the last message is from the astrologer
                if (data.chat_logs.length > 0) {
                    let lastLog = data.chat_logs[data.chat_logs.length - 1];
                    if (lastLog.message_by === 'astrologer' && !timerInterval) {
                        startTimer(remainingTime2); // Start a 5-minute timer (300 seconds)
                    }
                }

                // Scroll to the bottom of the chat
                chatBox.scrollTop(chatBox[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching chat logs:", error);
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
                    $('#chat-message').val(''); // Clear input
                    fetchChatLogs(); // Reload chat logs
                },
                error: function(xhr, status, error) {
                    console.error("Error sending message:", error);
                }
            });
        } else {
            alert('Please enter a message.');
        }
    });

    // Auto-fetch chat logs every 2 seconds
    setInterval(fetchChatLogs, 2000);
});
</script>
@endpush
