@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">{{$course->name}}  {{$module->name}} File</h1>
<div class="clearfix"></div>

{!! Form::model($course_module_file, ['route' => ['admin.course.module.file.submit', $course_id, $module_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module_file)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="clearfix"></div>

 @if($course_module_file)
<div class="form-group">
  {!! Form::label('file_type', 'Type') !!}
  {{$course_module_file->file_type}}
</div>
<div class="form-group">
  {!! Form::label('file_type', 'Name') !!}
  <a href="{{$course_module_file->link}}" target="_blank">{{$course_module_file->file_orginal_name}}</a>
</div>
 @else

  {!! Form::hidden('file',old('file'),['id'=>'file_data']) !!}

  <div class="form-group">
    {!! Form::label('file', 'File') !!}
   <div class="dropzone" id="file-upload"></div>
      <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
      </div>
    </div>
 @endif


   <div class="form-group">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title',old('title'), ['class'=>'form-control', 'required'=>'required']) !!}
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
<script src="{{asset('js/dropzone.js')}}"> </script>
<script type="text/javascript">
var button = $('input[type="submit"]');
@if(!$course_module_file)
var media_upload_url = '{{route('admin.course.module.file.upload')}}';
var myDropzone = new Dropzone("div#file-upload",
    {
        url: media_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: 'application/pdf,.zip',
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
         success: function(file,data) {
              toastr.success('file uploaded successfully');
              $('#file_data').val(JSON.stringify(data));
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            formData.append("_token", window.Laravel.csrfToken);
        }
    }
  );
  Dropzone.autoDiscover = false;
@endif

var validateAndSubmit = function(form){

  var _f = $(form);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
  var formData = _f.serialize();

  console.log(formData);
  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: formData,
    enctype: 'multipart/form-data'
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
