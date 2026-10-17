@extends(themeView().'layout')

@section('title', 'Support | '.config("app.name"))

@section('content')
<section class="dashboad_section">
<div class="container">
<div class="row justify-content-between">
     @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12 mt-5 mt-md-0 pad-ap3">
      @php
        $closedCount = $user->supports->where('status', 'closed')->count();
        $openCount = $user->supports->where('status', 'open')->count();
      @endphp
      <div class="container mt-2 mb-2 px-1">
       <div class="row g-3">

           <!-- Item -->


           <div class="col-6 col-md-3">
             <a href="">
               <div class="same-card">
                   <div class="same-icon" style="background:#0d6efd;">
                       <img src="/assets/img/mail.png" alt="">
                   </div>
                   <p>{{$openCount}} Open Conversation</p>
               </div>
             </a>
           </div>



           <div class="col-6 col-md-3 ">
             <a href="">
               <div class="same-card">
                   <div class="same-icon" style="background:#28a745;">
                       <img src="/assets/img/envelope.png" alt="">
                   </div>
                   <p> {{$closedCount}} Close Conversation</p>
               </div>
             </a>
           </div>



           <div class="col-6 col-md-3">
             <a href="">
               <div class="same-card">
                   <div class="same-icon" style="background:#6f42c1;">
                       <img src="/assets/img/check.png" alt="">
                   </div>
                   <p>{{$openCount + $closedCount}} Total Conversation</p>
               </div>
             </a>
           </div>



       </div>
   </div>
      <div class="row my-2 dashbord p-2 p-md-0 mt-0">
          {{--
            <div class="col-lg-3 mb-2 mb-md-0">
              <a href="javascript:void(0)">
                <div class="dashbord_card bg pt-3 mb-1 d-flex justify-content-center align-items-center">
                  <i class="bi bi-envelope-arrow-down fs-1 text-black"></i> <p class="text-white fw-bold mt-3">1 New Conversation </p>
                </div>
              </a>
            </div>
            --}}
            <!-- <div class="col-lg-3 col-4 mb-2 mb-md-0 p-1 p-md-2 pt-md-0 mt-md-0">
              @php
                $closedCount = $user->supports->where('status', 'closed')->count();
                $openCount = $user->supports->where('status', 'open')->count();
              @endphp
              <a href="javascript:void(0)">
                <div class="card bg pt-3 mb-1 d-flex justify-content-center align-items-center text-center text-md-start">
                  <i class="bi bi-envelope-paper text-black fs-1 text"></i> <p class="text-black fw-bold mt-3">{{$openCount}} Open Conversation</p>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-4 mb-2 mb-md-0 p-1 p-md-2 pt-md-0 mt-md-0">
              <a href="javascript:void(0)">
                <div class="card bg-2 pt-3 mb-1 d-flex justify-content-center align-items-center text-center text-md-start">
                  <i class="bi bi bi-envelope fs-1 text-black"></i> <p class="text-black fw-bold mt-3">{{$closedCount}} Closed Conversation</p>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-4 mb-2 mb-md-0 p-1 p-md-2 pt-md-0 mt-md-0">
              <a href="javascript:void(0)">
                <div class="card bg-3 pt-3 mb-1 d-flex justify-content-center align-items-center text-center text-md-start">
                  <i class="bi bi-envelope-check fs-1 text-black"></i> <p class="fw-bold mt-3 text-black">{{$openCount + $closedCount}} Total Conversation</p>
                </div>
              </a>
            </div> -->
          </div>
          <div class="p-0  pt-2 mt-3">
            <div class="card-body">
              <ul class="nav nav-tabs dashboard_tab dash-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Request for Support</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark" id="tickets-tab" data-bs-toggle="tab" href="#tickets" role="tab" aria-controls="tickets" aria-selected="true">Tickets</a>
                </li>
                @if(count($tickets))
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark" id="summary-tab" data-bs-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false" tabindex="-1">Summary</a>
                </li>
                @endif
              </ul>
              <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <form method="POST" action="{{route('user.support')}}" id="submitSupport">
                    <div class="card p-3 bg-light border-n">
                      <div class="form-group">
                        <label>Subject<em class="text-danger">*</em></label>
                        <input type="text" class="form-control" placeholder="Subject" id="subject" name="subject">
                        <div class="text-danger fs-6 d-none validation" id="subject-validate">This is required</div>
                      </div>
                      <div class="form-group mt-2">
                        <label>Type <em class="text-danger">*</em></label>
                        <select class="form-select" id="type" name="type">
                          @foreach(supportType() as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                        <div class="text-danger fs-6 d-none validation" id="type-validate">This is required</div>
                      </div>
                      <div class="form-group mt-3">
                        <label for="exampleFormControlTextarea1" class="fs-7">Message <em class="text-danger">*</em></label>
                        <textarea class="form-control" id="message" name="message" placeholder="Explain in brief" rows="3"></textarea>
                        <div class="text-danger fs-6 d-none validation" id="message-validate">This is required</div>
                      </div>
                      <div class="form-group my-3">
                        <label for="exampleFormControlFile1" class="mb-2">Upload File</label>
                        <input type="file" class="form-control-file form-control" id="file" name="file">
                      </div>
                      <div class="">
                        <button type="submit" id="submit_support_btn" name="button" class="main_button">Submit</button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
                  <div class="card bg-light p-3 pt-4 my-4 table-responsive border-n">
                    <div class="row" id="tickets-list">
   @forelse($tickets as $key => $item)
     <div class="col-md-4 mb-4">
       <div class="earning-card border-n p-3 bg-light rounded h-100">
         <div class="d-flex justify-content-between align-items-center mb-2">
           <span class="fw-bold text-golda">#{{ $key+1 }}</span>
           <span class="badge k_bg_badge text-white">{{ $item->status }}</span>
         </div>

         <p class="mb-1"><strong>Ticket No:</strong> {{ $item->ticket_number }}</p>

         <p class="mb-1">
           <strong>Subject:</strong>
           <a href="javascript:void(0)"
              onclick="javascript:fetchSupportReplies('{{ $item->id }}'); document.getElementById('summary-tab').click();"
              class="text-decoration-underline text-primary fw-semibold">
              {{ $item->subject }}
           </a>
         </p>

         <p class="mb-1"><strong>Type:</strong> {{ getSupportTypeName($item->type) }}</p>
         <p class="mb-1"><strong>Date:</strong> {{ date('d-m-Y', strtotime($item->created_at)) }}</p>
         <p class="mb-0"><strong>Time:</strong> {{ date('h:i A', strtotime($item->created_at)) }}</p>
       </div>
     </div>
   @empty
     <div class="col-12">
       <div class="no-data text-center">
         <img src="/assets/images/no-data.png" alt="No Tickets Found" class="no-img">
         <h5 class="mt-3 text-dark">No Tickets Found!</h5>
         <p class="text-secondary">Tickets will be displayed here.</p>
       </div>
     </div>
   @endforelse
 </div>

                  </div>
                </div>
              <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                <div class="row">
                  <div class="col-md-12 col-12">
                  <div class="chat-container border-n">
                      <div class="chat-header p-3 btn-bg  radius">
                        <h2 class="m-0 p-0 fs-8 text-white" id="ticket-status">Status:</h2>
                        <p class="text-white-100 m-0 p-0 fs-8" id="ticket-message"></p>
                      </div>
                      <div class="chat-box" id="chat-box">
                      </div>
                      <div class="row chat-btn submit-support-replies bg-white">
                        <div class="col-md-12 col-12 p-0">
                          <form method="post" id="send-support-reply-form" class="d-flex align-items-center">
                            <div class="input-group w-100 text-black bor-top-black p-3 py-2">
                              <div class="d-flex w-100">
                                <div class="w-100 me-2">
                                  <input type="hidden" name="support_id" value="">
                                  <textarea name="support_reply" id="support_reply" class="form-control w-100 chat-in" placeholder="Type a message..." rows="1" onkeyup="handleMessage(this)" onkeypress="javascript: if (event.key === 'Enter') { event.preventDefault(); document.getElementById('send-message-btn').click(); }"
                                  ></textarea>
                                </div>
                                <div class="">

                                  <button id="send-support-reply-btn" type="submit" class="send-icon-btn">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 24 24">
                                    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                                  </svg>
                                </button>

                                </div>
                              </div>
                            </div>
                          <!-- <div class="chat-input">
                              <div class="w-100">
                                <input type="hidden" name="support_id" value="">
                                <textarea name="support_reply" id="support_reply" class="send-textera p-2" placeholder="Type your answer" rows="2" cols="80"></textarea>
                                <div class="d-none validation text-danger" id="support_reply-validate">
                                </div>
                              </div>
                              <div class="">
                                <button class="main_button" id="send-support-reply-btn" type="submit">Send</button>
                              </div>

                          </div> -->
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
@endsection
@push('scripts')
<script type="text/javascript">
  $('#submitSupport').on('submit', function(e){
      e.preventDefault();
      var formData = new FormData(this);
        $('#submit_support_btn').attr('disabled', 'disabled');
        $('.validation').addClass('d-none')
      $.ajax({
          url: "{{route('user.support')}}",
          enctype: 'multipart/form-data',
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            document.getElementById('submitSupport').reset();
            $('#submit_support_btn').removeAttr('disabled');
            toastr.success(response.message);
            $('#tickets-list').append(response.data);
            $('#no-ticket-tr').hide();
            setTimeout(function(){
              window.location.reload()
            }, 2000)
          },
          error: function(xhr) {
            $('#submit_support_btn').removeAttr('disabled');
            for (var key in xhr.responseJSON.errors) {
            toastr.error(xhr.responseJSON.errors[key][0]);
              // $('#'+key+'-validate').removeClass('d-none').text(xhr.responseJSON.errors[key][0])
            }

          }
      });
  });
    $('#send-support-reply-form').on('submit', function(e){
          e.preventDefault();
          var formData = new FormData(this);
          $('#send-support-reply-btn').attr('disabled', 'disabled');
          $('.validation').addClass('d-none')
          let url = @json(asset('/account'));
          let support_id = $('input[name=support_id]').val();
          url += `/support/${support_id}/replies/submit`
          console.log(formData);
          // return false;
          $.ajax({
              url: url,
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                $('#support_reply').val('');
                $('#send-support-reply-btn').removeAttr('disabled');
                fetchSupportReplies(response.support_id);
              },
              error: function(xhr) {
                $('#send-support-reply-btn').removeAttr('disabled');
                for (var key in xhr.responseJSON.errors) {
                  toastr.error(xhr.responseJSON.errors[key][0]);
                  // $('#'+key+'-validate').removeClass('d-none').text(xhr.responseJSON.errors[key][0])
                }
                console.log(xhr)
              }
          });
    });
  $(document).ready(function (){
    fetchSupportReplies(@json(isset($tickets[0])?$tickets[0]->id:0))
    // fetchSupportReplies(0)
  });
  function fetchSupportReplies(support_id){
    let url = @json(asset('/account'));
    url += `/support/${support_id}/replies`;
    $.ajax({
        url: url,
        method: "POST",
        data: {},
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          $('input[name=support_id]').val(support_id)
          $('#ticket-status').html('Status: '+response.status)
          $('#ticket-message').html(response.message)
          $('#chat-box').html(response.data)
          $('.submit-support-replies').removeClass('d-none')
          $('#chat-box').css('max-height', '370px')
          if(response.status == 'closed'){
            // $('.submit-support-replies').html('<div>closed</div>')
            $('.submit-support-replies').addClass('d-none')
            $('#chat-box').css('max-height', '100%')
          }
          $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        },
        error: function(xhr) {
          console.log(xhr)
        }
    });
  }
</script>
<script>
document.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        let activeTab = document.querySelector(".tab-pane.active");

        if (!activeTab) return;

        // Check if Enter is pressed inside a textarea → Allow newline
        if (e.target.tagName === "TEXTAREA") return;

        // Find form inside active tab
        let form = activeTab.querySelector("form");
        if (!form) return;

        e.preventDefault(); // Stop default submit

        // Trigger normal submit event (which will run your AJAX + validation)
        form.dispatchEvent(new Event("submit"));
    }
});
</script>

@endpush
