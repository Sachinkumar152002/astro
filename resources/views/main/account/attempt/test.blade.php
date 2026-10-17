@extends('layouts.assesment')
@push('styles')
<style>
.bg-green {background:#084d89; color:#fff; float:left; width:100%; padding:10px 0;}
.bg-green h3 {padding:0 !important; color:#fff; margin:0; margin-top:4px;}
.bg-white-2 {background:#fff; padding:20px; float:left; width:100%;}
.test-mod-section {top:20%; width:75%; margin:auto; left:25%;}
.btn-quiz-section {border-top:1px solid #ddd; padding-top:10px; width:100%; background:#fff; padding:15px 30px; float:left;}
.btn-quiz-section .btn-group .btn {}
</style>
@endpush
@section('content')
<div class="container">
  <div class="test-mod-section">
    <form action="{{route('account.assessment.question.submit', [$attempt_id, $question_id,  $order])}}" method="post" id="question_form">
      {{csrf_field()}}
    <div class="bg-green">
      <div class="col-md-12">
        <div class="col-md-6">
          <h3>Question # {{$question->order}}</h3>

        </div>
        <div class="col-md-6">
          <p class="text-right" id="time-remaining">--:--</p></div>
        </div>
      </div>
      <div class="bg-white-2">
        <div class="col-md-12">
          <h4>{!! $question->question !!}</h4>
        </div>
        <div class="col-md-12">
          @if($question->type==0)
          <div class="radio">
            <label>
              <input value="1" type="radio" name="answer">
              {!! $question->option_a !!}
            </label>
          </div>
          <div class="radio">
            <label>
              <input value="2" type="radio" name="answer">
              {!! $question->option_b !!}
            </label>
          </div>
          @if($question->option_c)
          <div class="radio">
            <label>
              <input value="3" type="radio" name="answer">
              {!! $question->option_c !!}
            </label>
          </div>
          @endif
          @if($question->option_d)
          <div class="radio">
            <label>
              <input value="4" type="radio" name="answer">
              {!! $question->option_d !!}
            </label>
          </div>
          @endif
          @elseif($question->type==1)
          <div class="form-group">
            <hr>
            <h4 for="answer">Your Answer</h4>
            <textarea class="form-control" id="answer" rows="10" name="answer"></textarea>
          </div>
          @endif
        </div>
      </div>
      <div class="col-d-12">
        <div class="btn-quiz-section" role="group" aria-label="...">
          <div class="btn-group col-md-2" role="group">
            <button type="submit" class="btn btn-success w100">Submit</button>
          </div>
          <div class="btn-group col-md-2" role="group">
            <button type="button" onclick="skipQuestion()" class="btn btn-default w100">Skip</button>
          </div>

          <div class="btn-group col-md-2" role="group">
            <a  onclick="$('#alertModalComplete').modal('show')" class="btn btn-warning">Complete</a>
          </div>

          <div class="btn-group col-md-2 hidden" role="group">
            <a href="" class="btn btn-danger">Cancel</a>
          </div>

        </div>
      </div>
      <input type="hidden" name="allow_submit" value="1" id="allow_submit">
    </form>
    </div>
  </div>

  <div class="modal fade" id="alertModalComplete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Alert!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <h3 class="text-success">Are you want to submit your assessment?</h3>
      </div>
      <div class="modal-footer">
        <a href="{{route('account.assessment.submit', [$attempt_id])}}" class="btn btn-success">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

  @endsection
  @push('scripts')
  <script>
  var skipQuestion = function(){
      $('#allow_submit').val(2);
      $('#question_form').submit();
  }
var betRemaineLastTime = 30;
var elmRemainingTime = $('#time-remaining');
var betRemaineTime = 2*60;
setInterval(function(){
    betRemaineTime--;
    var totalMin = parseInt(betRemaineTime/60);
    if(totalMin<10) totalMin = "0"+totalMin;
    var totalSec = parseInt(betRemaineTime%60);
    if(totalSec<10) totalSec = "0"+totalSec;
    elmRemainingTime.text(totalMin+":"+totalSec);
    if(betRemaineTime<betRemaineLastTime) {
        elmRemainingTime.toggleClass('text-danger');
    }
    if(betRemaineTime<=0) {
        $('#question_form').submit();
    }
},1000);

  </script>
  @endpush