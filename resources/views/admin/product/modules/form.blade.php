@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">{{$course_module->title}} Detail</h1>
<div class="clearfix"></div>

{!! Form::model($course_module, ['route' => ['admin.course.video.submit', $course_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="clearfix"></div>

  <div class="form-group">
      {!! Form::label('title', 'Title') !!}
      {!! Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) !!}
      {{-- {!! Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) !!} --}}
  </div>


  @if($course_module && $course_module->file_name)
      <div class="mt-2">
          <p>Current file: {{ $course_module->file_name }}</p>
          <input type="file"  name="coursevideo" value="{{ $course_module->file_name }}">
      </div>
      @else
      <div class="form-group">
        {!! Form::label('coursevideo', 'Upload Course Video') !!}
        {!! Form::file('coursevideo', ['class' => 'form-control-file', 'accept' => 'video/*']) !!}
    </div>
  @endif


  <div class="form-group">
      {!! Form::label('caption', 'Caption') !!}
      {!! Form::text('caption', old('caption'), ['class' => 'form-control']) !!}
  </div>

  <div class="form-group">
      {!! Form::label('status', 'Status') !!}
      {!! Form::select('status', [1 => 'Active', 0 => 'Inactive'], old('status'), ['class' => 'form-control']) !!}
  </div>

  {!! Form::submit('Submit', ['class' => 'btn btn-primary', 'disabled' => 'disabled']) !!}

  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
  var _f = $(form);
  var formData = new FormData(_f[0]);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');

  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: formData,
    processData: false,
    contentType: false
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
</script>

@endpush
