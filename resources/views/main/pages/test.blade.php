@extends('layouts.assesment')
@push('styles')
<style>
/*body {padding:20px !important; background:#ededed;}*/
/*.bg-green {background:#084d89; color:#fff; float:left; width:100%; padding:10px 0;}
.bg-green h3 {padding:0 !important; color:#fff; margin:0; margin-top:4px;}
.bg-white-2 {background:#fff; padding:20px; float:left; width:100%;}
.test-mod-section {position:absolute; top:20%; width:50%; margin:auto; left:25%;}
.btn-quiz-section {border-top:1px solid #ddd; padding-top:10px; width:100%; background:#fff; padding:15px 30px; float:left;}
.btn-quiz-section .btn-group .btn {}*/
</style>
@endpush
@section('content')
<div class="container">
    <div class="test-mod-section">
  <div class="bg-green">
    <div class="col-md-12">
     <div class="col-md-6">
      <h3>Quiz Test</h3>
    </div>
    <div class="col-md-6">
      <p class="text-right"> Timer will be placed here: </p></div>
      </div>
  </div>
  <div class="bg-white-2">
  <div class="col-md-12">
    <h4>{{$question->question}}</h4>
  </div>
  <div class="col-md-12">
         <div class="radio">
      <label>
        <input value="1" type="radio" name="answer">
          {{$question->option_a}}
      </label>
    </div>
    <div class="radio">
      <label>
        <input value="2" type="radio" name="answer">
        {{$question->option_b}}
      </label>
    </div>
    @if($question->option_c)
     <div class="radio">
      <label>
        <input value="3" type="radio" name="answer">
        {{$question->option_c}}
      </label>
    </div>
    @endif
    @if($question->option_d)
     <div class="radio">
      <label>
        <input value="4" type="radio" name="answer">
        {{$question->option_d}}
      </label>
    </div>
    @endif
  </div>
   </div>
<div class="col-d-12">

 <div class="btn-quiz-section" role="group" aria-label="...">
  <div class="btn-group col-md-10" role="group">
    <button type="button" class="btn btn-danger">Cancel</button>
    <button type="button" class="btn btn-success">Complete</button>
    <button type="button" class="btn btn-default">Skip</button>
  </div>




   <div class="btn-group col-md-2" role="group">

    <button type="button" class="btn btn-success w100">Next</button>
  </div>
</div>


  </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
$('#openBtn').click(function(){
    $('#myModal').modal({show:true})
});
</script>
@endpush