@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">{{$course->name}}  {{$module->name}} Question</h1>
<div class="clearfix"></div>

{!! Form::model($course_module_question, ['route' => ['admin.course.module.question.submit', $course_id, $module_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module_question)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="clearfix"></div>

  <div class="form-group">
    {!! Form::label('type', 'Type') !!}
    {!! Form::select('type',$_types, old('type'), ['class'=>'form-control', 'onchange'=>'changeQuestionType(this.value)']) !!}
  </div>

  <div class="form-group">
      {!! Form::label('question','Question') !!}
      {!! Form::text('question', old('question'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group">
    {!! Form::label('time', 'Time') !!}
    {!! Form::number('time',old('time'), ['class'=>'form-control', 'min'=>'1',  'required'=>'required']) !!}
  </div>
  <div class="form-group">
    {!! Form::label('details', 'Details') !!}
    {!! Form::textarea('details',old('details'), ['class'=>'form-control']) !!}
  </div>
  <div class="form-group type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
      {!! Form::label('option_a','Option A') !!}
      {!! Form::text('option_a', old('option_a'),['class'=>'form-control']) !!}
  </div>
  <div class="form-group type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
      {!! Form::label('option_b','Option B') !!}
      {!! Form::text('option_b', old('option_b'),['class'=>'form-control']) !!}
  </div>
  <div class="form-group type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
      {!! Form::label('option_c','Option C') !!}
      {!! Form::text('option_c', old('option_c'),['class'=>'form-control']) !!}
  </div>
  <div class="form-group type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
      {!! Form::label('option_d','Option D') !!}
      {!! Form::text('option_d', old('option_d'),['class'=>'form-control']) !!}
  </div>
  <div class="form-group type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
    {!! Form::label('answer', 'Answer') !!}
    {!! Form::select('answer',$_options, old('answer'), ['class'=>'form-control']) !!}
  </div>

  <div class="form-group type_1 {{$course_module_question && $course_module_question->type==0?'hidden':(!$course_module_question?'hidden':'')}} ">
      {!! Form::label('keywords','Keywords') !!}
      <small class="text-danger"> comma(,) seperated</small>
      {!! Form::text('keywords', old('keywords'),['class'=>'form-control']) !!}
  </div>

   <div class="form-group">
    {!! Form::label('order', 'Order') !!}
    {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
  </div>
    <div class="form-group">
    {!! Form::label('status', 'Status') !!}
    {!! Form::select('status',$_status, old('status'), ['class'=>'form-control']) !!}
  </div>

  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
  var _f = $(form);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: _f.serialize()
  })
  .done(function( msg ) {
      toastr.success(msg.message);
       button.removeClass('disabled').removeAttr('disabled').val('Submit');
       toastr.success("Please wait redirecting to listing page");
       setTimeout(function(){
          window.location.href=msg.redirectto;
       }, 3000);
  })
  .fail(function(errors){
      var err = $.parseJSON(errors.responseText);
      for(i in err){
          $('#'+i).addClass('btn-outline-danger');
          toastr.error(err[i][0]);
      }
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
  });
  return false;
}

var changeQuestionType = function(t){
   $('.type_0, .type_1').addClass('hidden');
   $('.type_'+t).removeClass('hidden');
   $('.type_'+t).find('input').attr('required', 'required');
   $('.type_'+(1-t)).find('input').removeAttr('required');
}
</script>

@endpush
