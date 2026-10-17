@extends('layouts.admin_two')
@section('content')
{{--
<h1 class="text-primary float-left">{{$course->name}}  {{$module->name}} Question </h1>
--}}
<div class="clearfix"></div>
{!! Form::model($course_module_question, ['route' => ['admin.course.module.content.question.submit', $course_id, $module_id, $content_id, $faq_type], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module_question)
{{ Form::hidden('id', old('id')) }}
@endif
<!-- <div class="row">
  <div class="col-md-6">
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div> -->
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information </a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-12 col-12">
        <div class="form-group">
          {!! Form::label('type', 'Type', ['class'=>'required']) !!}
          {!! Form::select('type',$_types, old('type'), ['class'=>'form-control', 'onchange'=>'changeQuestionType(this.value)']) !!}
        </div>
      </div>
      {{--

      <div class="col-md-6 col-12">
        <div class="form-group">
          {!! Form::label('show_in', 'Show In', ['class'=>'required']) !!}
          {!! Form::select('show_in',courseQuestionShowIn() , old('show_in'), ['class'=>'form-control', 'id'=>'show_in']) !!}
        </div>
      </div>
      --}}
      <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('question','Question', ['class'=>'required']) !!}
            {!! Form::text('question', old('question'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('details', 'Details', ['class'=>'required']) !!}
          {!! Form::textarea('details',old('details'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3 type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
        <div class="form-group">
            {!! Form::label('option_a','Option A', ['class'=>'required']) !!}
            {!! Form::text('option_a', old('option_a'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3 type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
        <div class="form-group">
            {!! Form::label('option_b','Option B', ['class'=>'required']) !!}
            {!! Form::text('option_b', old('option_b'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3 type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
        <div class="form-group">
            {!! Form::label('option_c','Option C', ['class'=>'required']) !!}
            {!! Form::text('option_c', old('option_c'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3 type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
        <div class="form-group">
            {!! Form::label('option_d','Option D', ['class'=>'required']) !!}
            {!! Form::text('option_d', old('option_d'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">
        <div class="form-group">
          {!! Form::label('answer', 'Answer', ['class'=>'required']) !!}
          {!! Form::select('answer',$_options, old('answer'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 type_1 {{$course_module_question && $course_module_question->type==0?'hidden':(!$course_module_question?'hidden':'')}} ">
        <div class="form-group">
            {!! Form::label('keywords','Keywords', ['class'=>'required']) !!}
            <small class="text-danger"> comma(,) seperated</small>
            {!! Form::text('keywords', old('keywords'),['class'=>'form-control']) !!}
        </div>
      </div>
      {{--
      <div class="col-md-4">
        <div class="form-group time-label">
          {!! Form::label('time', 'Time', ['class'=>'required']) !!}
          <em class="type_0 {{$course_module_question && $course_module_question->type==1?'hidden':''}}">(seconds)</em>
          <em class="type_1 {{$course_module_question && $course_module_question->type==0?'hidden':(!$course_module_question?'hidden':'')}}">(minutes)</em>
          {!! Form::number('time',old('time'), ['class'=>'form-control', 'min'=>'1']) !!}
        </div>
      </div>
      --}}
      <div class="col-md-4">
         <div class="form-group">
          {!! Form::label('order', 'Order') !!}
          {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
        </div>
      </div>

      <div class="form-group col-md-4">
          <span class="ml-2 mt-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status"{{$course_module_question && $course_module_question->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable mt-2" for="status"></label>
      </div>
    </div>
  </div>
</div>
<div class="text-center">
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
</div>
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
    if(errors.responseJSON.errors){
      var err = errors.responseJSON.errors;
      for(i in err){
          $('#'+i).addClass('btn-outline-danger');
          toastr.error(err[i][0]);
      }
    }else if(errors.responseJSON.message){
      toastr.error(errors.responseJSON.message);
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
