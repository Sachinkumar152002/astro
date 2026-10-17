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
                    $('#chat-message').val(''); // Clear input
                    fetchChatLogs(); // Reload chat logs
                }
            });
        }
    });

    // Auto-fetch chat logs every 2 seconds
    setInterval(fetchChatLogs, 2000);
});
</script>
@endpush
