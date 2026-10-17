<style media="screen">
.ch-imag {
    width: 40px;
    height: 40px;
    object-fit: cover;
}
.ch-imag-box {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}
.sc-box{
  max-height: 500px;
  overflow: auto;
  width: 100%;
}
.sc-box::-webkit-scrollbar {
  width: 2px;
}

.sc-box::-webkit-scrollbar-thumb {
  background-color: #7A70BA;
  border-radius: 2px;
}

.sc-box::-webkit-scrollbar-track {
  background-color: transparent;
}


.sc-box {
  scrollbar-width: thin;
  scrollbar-color: #7A70BA transparent;
}
</style>

@extends('layouts.admin')


@section('content')
{!! Form::model($ticket, ['route' => 'admin.support.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($ticket)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Support Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#conversation">Conversation</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">

    <div class="col-md-4">
      <div class="form-group">
          {!! Form::label('ticket_number','Ticket Number', ['class'=>'required']) !!}
          {!! Form::text('ticket_number', old('ticket_number') ,['class'=>'form-control', 'disabled'=>'disabled']) !!}
      </div>
    </div>
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('user_name','User Name', ['class'=>'required']) !!}
            @php
            $userName = '';
            $userPhone = '';
            $userEmail = '';
            $image = asset('assets/images/profile.png');
            if($ticket->user_type=='user'){
              $userName = $ticket->user?$ticket->user->name:'';
              $userPhone = $ticket->user?$ticket->user->phone:'';
              $userEmail = $ticket->user?$ticket->user->email:'';
              $image = $ticket->user && $ticket->user->image? $ticket->user->image->image(200, 200) : asset('assets/images/profile.png');
            }elseif($ticket->user_type=='astrologer'){
              $userName = $ticket->astrologer?$ticket->astrologer->display_name:'';
              $userPhone = $ticket->astrologer?$ticket->astrologer->phone:'';
              $userEmail = $ticket->astrologer?$ticket->astrologer->email:'';
              $image = $ticket->astrologer && $ticket->astrologer->image? $ticket->astrologer->image->image(200, 200) : asset('assets/images/profile.png');
            }
            @endphp
            {!! Form::text('user_name', $userName ,['class'=>'form-control', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('user_phone','User Phone', ['class'=>'required']) !!}
            {!! Form::text('user_phone', $userPhone,['class'=>'form-control', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('user_mail','User Email',['class'=>'required']) !!}
          {!! Form::email('user_mail', $userEmail,['class'=>'form-control', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('subject','Subject', ['class'=>'required']) !!}
          {!! Form::text('subject', old('subject'),['class'=>'form-control', 'readonly'=>'readyonly']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('type', 'Type', ['class'=>'required']) !!}
          {!! Form::select('type', supportType(), old('type'), ['class' => 'form-control nosearch', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      @if($ticket->file)
        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('file', 'File', ['class'=>'required']) !!}
            <div class="">
              <a href="{{asset($ticket->file)}}" target="_blank" id="file">Show</a>
            </div>
          </div>
        </div>
      @endif
      <div class="col-12 {{$ticket->replies ? 'd-none':''}}">
        <div class="form-group">
          {!! Form::label('message', 'Message', ['class'=>'required']) !!}
          {!! Form::textarea('message', old('message'), ['class' => 'form-control', 'readonly'=>'readonly']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('status', 'Status', ['class'=>'required']) !!}
          {!! Form::select('status', [''=>'Select Status', 'open'=>'Open', 'closed'=>'Closed',], old('status'), ['class' => 'form-control nosearch']) !!}
        </div>
      </div>
  </div>
    </div>

<div id="conversation" class="tab-pane fade in">
    <div class="row">
<div class="col-md-7 m-auto borders pb-4">
  <div class="row">
    @if($ticket && $replies=$ticket->replies)
        <div class="col-12 text-center mb-3 py-2 form-text-bg">
          <div>
            <h4 class="p-0 m-0 text-white">Converstation</h4>
          </div>
        </div>
        <div class="sc-box" id="scroll-box">
          @include('admin.support.replies')
        </div>
          @endif
      @if($ticket && $ticket->status=='open')
    <div class="col-11 mt-4">
      <textarea rows="2"  name="reply" id="reply" class="form-control"></textarea>
    </div>
    <div class="col-1 m-auto d-flex align-items-center">
      <button id="reply-btn" onclick="submitReply()" type="submit" class="send-icon-btn">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="22"
    height="22"
    viewBox="0 0 24 24"
    fill="white"
  >
    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"></path>
  </svg>
</button>
    </div>
    @endif
  </div>
</div>

  </div>
    </div>
</div>

  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
var id = 0;

var scrollBotm = function(){
  setTimeout(() => {
    $('#scroll-box').scrollTop($('#scroll-box')[0].scrollHeight);
  }, 300);
}
function submitReply(){
  let formData = new FormData();
  formData.append('id', id);
  formData.append('reply', $('#reply').val().trim());
  $.ajax({
    url:"{{route('admin.support.replies.submit', [$ticket->id])}}",
    type:"POST",
    headers:{
      "X-CSRF-TOKEN":"{{csrf_token()}}"
    },
    data:formData,
    processData: false,
    cache : false,
    contentType: false,
    success:function(msg){
      toastr.success(msg.message);
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
      if (msg.redirectto!='' && $('#edit_back').length && $('#edit_back').val()=='0' || $('#edit_back').length == 0) {
          toastr.success("Please wait redirecting...");
      }
      if(msg.redirectto!='' && msg.redirectto!='undefined' && msg.redirectto!=null) {
        $('#reply').val('');
          // setTimeout(function () {
          //     window.location.href = msg.redirectto;
          // }, 1000);
          replies();
          // scrollBotm();
      }
    },
    error:function(errors){
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
      if(errors.responseJSON.error){
        toastr.error(errors.responseJSON.error);
        return false;
      }
      var err = $.parseJSON(errors.responseText);
      for (i in err.errors) {
          $('#' + i).addClass('btn-outline-danger');
          toastr.error(err.errors[i][0]);
      }
    }
  });

}
function replies(){
  let formData = new FormData();
  formData.append('id', '{{$ticket->id}}');
  $.ajax({
    url:"{{route('admin.support.replies.list', [$ticket->id])}}",
    type:"POST",
    headers:{
      "X-CSRF-TOKEN":"{{csrf_token()}}"
    },
    data:formData,
    processData: false,
    cache : false,
    contentType: false,
    success:function(msg){
          $('#scroll-box').html(msg.data)
          scrollBotm();
    },
    error:function(errors){

    }
  });

}

   function editReply(reply_id, message) {
     id = reply_id;
     $('#reply').val(message);
   }

   function deleteReply(that, reply_id) {
     $.ajax({
       url:"{{route('admin.support.replies.delete', [$ticket->id])}}",
       type:"DELETE",
       data : {id:reply_id, _token:window.Laravel.csrfToken},
       dataType: 'json',
       success:function(msg){
         toastr.success(msg.message);
         $(that).parent().parent().parent().remove();
       },
       error:function(errors){
         toastr.error('try again later');
         console.log(errors);
       }
     });
    }

   $('document').ready(function(){
     scrollBotm();
   });


</script>


@endpush
