
<style media="screen">
  .text-golda a{
    color: #ff5900 !important;
    font-size: 15px !important;
  }
  .text-golda{

    font-size: 15px !important;
  }
</style>
@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
          <div class="row my-2 dashbord">
              {{--
                <div class="col-lg-3 mb-2 mb-md-0">
                  <a href="javascript:void(0)">
                    <div class="card bg pt-3 mb-1 d-flex justify-content-center align-items-center">
                      <i class="bi bi-envelope-arrow-down fs-1 text-black"></i> <p class="text-black fw-bold mt-3 text-center">1 New Conversation </p>
                    </div>
                  </a>
                </div>
                --}}
                <div class="col-6 col-md-3">
                  @php
                    $openCount = $user->supports->where('status', 'open')->count();
                    $closedCount = $user->supports->where('status', 'closed')->count();
                  @endphp
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
                        <p> {{$closedCount}} Closed Conversation</p>
                    </div>
                  </a>
                </div>



                <div class="col-6 col-md-3">
                  <a href="">
                    <div class="same-card">
                        <div class="same-icon" style="background:#6f42c1;">
                            <img src="/assets/img/check.png" alt="">
                        </div>
                        <p>{{$openCount+$closedCount}} Total Conversation</p>
                    </div>
                  </a>
                </div>


              </div>
              <div class="pt-2 mt-3">
                <div class="card-body">
                    <div class="tab-scroll-wrapper">
                  <ul class="nav dashboard_tab nav-tabs dash-tab astro-account-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Request for Support</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark" id="tickets-tab" data-bs-toggle="tab" href="#tickets" role="tab" aria-controls="tickets" aria-selected="true">Tickets</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark" id="summary-tab" data-bs-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false" tabindex="-1">Summary</a>
                    </li>
                  </ul>
                </div>
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
                              @foreach(supportTypeAstrologer() as $key => $value)
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
                            <button type="submit" id="submit_support_btn" name="button" class="main_button mb-2 mt-3">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
                      <div class="card bg-light border-n p-3 pt-4 my-4 table-responsive">
                        <div class="row" id="tickets-list">
                            @forelse($tickets as $key => $item)
                                <div class="col-md-4 mb-4">
                                    <div class="earning-card shadow-sm p-3 bg-light rounded h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-golda">#{{ $key+1 }}</span>
                                            <span class="badge bg-primary text-white">{{ $item->status }}</span>
                                        </div>
                                        <p class="mb-1"><strong>Ticket No:</strong> {{ $item->ticket_number }}</p>
                                        <p class="mb-1">
                                            <strong>Subject:</strong>
                                            <a href="javascript:void(0)"
                                               onclick="javascript:fetchSupportReplies('{{ $item->id }}'); document.getElementById('summary-tab').click();"
                                               class="text-decoration-underline text-primary">
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
                                        <img src="/assets/images/add.webp" alt="No Tickets Found" class="nodata">
                                        <h5 class="mt-3 text-dark"> No Tickets Found!</h5>
                                        <p class="text-secondary"> Tickets will be displayed here.</p>
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
                            <!-- <p class="text-black m-0 p-0 fs-10" id="ticket-message">fdbdgbdf</p> -->
                          </div>
                          <div class="chat-box" id="chat-box">
                          </div>
                          <div class="row submit-support-replies">
                            <div class="col-md-12 col-12">
                              <div class="chat-input">
                                <form method="post" id="send-support-reply-form" class="d-flex align-items-center">
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
          url: "{{route('astrologer.support')}}",
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
          },
          error: function(xhr) {
            $('#submit_support_btn').removeAttr('disabled');
            for (var key in xhr.responseJSON.errors) {
            toastr.error(xhr.responseJSON.errors[key][0]);
              // $('#'+key+'-validate').removeClass('d-none').text(xhr.responseJSON.errors[key][0])
            }
            console.log(xhr)
          }
      });
  });
    $('#send-support-reply-form').on('submit', function(e){
          e.preventDefault();
          var formData = new FormData(this);
          $('#send-support-reply-btn').attr('disabled', 'disabled');
          $('.validation').addClass('d-none')
          let url = @json(asset('/astrologer'));
          let support_id = $('input[name=support_id]').val();
          url += `/support/${support_id}/replies/submit`
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
    let url = @json(asset('/astrologer'));
    url += `/support/${support_id}/replies`;
    $.ajax({
        url: url,
        method: "POST",
        data: {},
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          if(response.success){
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
          }else{
            $('#summary-tab').hide();
            $('#ummary').hide();
          }
        },
        error: function(xhr) {
          console.log(xhr)
        }
    });
  }
</script>
<script>
// ENTER press submit handler
document.addEventListener("keydown", function (e) {

    // Enter detect
    if (e.key === "Enter") {

        // Stop default enter (textarea new line)
        e.preventDefault();

        // Find currently active tab
        let activeTabPane = document.querySelector(".tab-pane.active");
        if (!activeTabPane) return;

        // Find form inside active tab
        let form = activeTabPane.querySelector("form");
        if (!form) return;

        // If SUPPORT REPLY form in summary tab
        if (form.id === "send-support-reply-form") {
            document.getElementById("send-support-reply-btn").click();
            return;
        }

        // If MAIN SUPPORT form in profile tab
        if (form.id === "submitSupport") {
            document.getElementById("submit_support_btn").click();
            return;
        }
    }
});

</script>
@endpush
