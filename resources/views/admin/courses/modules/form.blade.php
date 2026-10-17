@extends('layouts.admin')


@section('content')
{!! Form::model($course_module, ['route' => ['admin.course.module.submit', $course_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$course->name}} Module Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
  @if($course_module)
  <li class="active nav-item"><a data-toggle="tab" class="nav-link" href="#content">Content</a></li>
  @endif
</ul>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name','Name', ['class'=>'required']) !!}
            {!! Form::text('name', old('name'),['class'=>'form-control', ]) !!}
        </div>
      </div>
      <div class="col-md-12">
         <div class="form-group">
          {!! Form::label('details', 'Details', ['class'=>'required']) !!}
          {!! Form::textarea('details', old('details'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('total_time', 'Time (minutes)', ['class'=>'required']) !!}
          {!! Form::number('total_time',old('total_time'), ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      {{--
      <div class="col-md-3">
         <div class="form-group">
          {!! Form::label('questions', 'Questions', ['class'=>'required']) !!}
          {!! Form::number('questions',old('questions'), ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      --}}
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('minus_marks', 'Marks (each question)', ['class'=>'required']) !!}
          {!! Form::number('minus_marks',old('minus_marks'), ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      {{--
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('passing_marks', 'Marks (passing)', ['class'=>'required']) !!}
          {!! Form::number('passing_marks',old('passing_marks'), ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('attempt_allow', 'Attempt', ['class'=>'required']) !!}
          {!! Form::number('attempt_allow',old('attempt_allow'), ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      --}}
      <div class="col-md-3 d-none">
        <div class="form-group">
          {!! Form::label('instant_result', 'Result', ['class'=>'required']) !!}
          {!! Form::select('instant_result',['0'=>'Later', '1'=>'Instant'],1, ['class'=>'form-control', 'min'=>'0', ]) !!}
        </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
          {!! Form::label('order', 'Order') !!}
          {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
           <span class="ml-2"><strong>Status</strong></span>
              <input type="checkbox" class="toggle-check mt-4" id="status" {{$course_module && $course_module->status ? 'checked' : ''}} value="1" name="status">
              <label class="mx-2 t-lable" for="status"></label>
        </div>
      </div>
    </div>
  </div>
  @if($course_module)
  <div id="content" class="tab-pane fade in">
    <div class="w-100" style="height:700px;">
      <iframe style="border:none" src="{{route('admin.course.module.content', [$course_module->course_id, $course_module->id]).'?header=no&sidebar=no&breadcrumb=no'}}" width="100%" height="100%"></iframe>
    </div>
  </div>
  @endif
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
</script>

@endpush
