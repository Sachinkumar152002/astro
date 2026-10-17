@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left"> Module Preview {{$moduleName}} Details</h1>
<div class="clearfix"></div>

{!! Form::model($item, ['route' => ['admin.course.module.preview.submit', $course_id, $module_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
  @if($item)
  {{ Form::hidden('id', old('id')) }}
  @endif
<div class="clearfix"></div>
    <div class="form-group">
      {!! Form::label('video_url','Video URL') !!}
      <div class="row">
        <div class="col-md-10">
          {!! Form::text('video_url', old('video_url'),['class'=>'form-control', 'required']) !!}
          <small>Paste any youtube id or youtube url</small>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-primary" onclick="fetchVideoDetails()">Fetch Details</button>
        </div>
      </div>
    </div>

    <div class="form-group">
      {!! Form::label('video_id','Video URL') !!}
      {!! Form::text('video_id', old('video_id'),['class'=>'form-control', 'readonly', 'required']) !!}
    </div>

  <div class="form-group">
      {!! Form::label('title','Title') !!}
      {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required']) !!}
      <input type="hidden" name="course_id" value="{{ $course_id}}">
      <input type="hidden" name="course_module_id" value="{{$module_id}}">
    </div>
  <div class="form-group">
    {!! Form::label('thumbnail','Thumbnail') !!}
    <img src="{{old('thumbnail', $item?$item->thumbnail:'')}}" alt="admin image" id="thumbnail_src" class="float-right img-fluid" style="max-width:200px;">
    {!! Form::text('thumbnail', old('thumbnail'),['class'=>'form-control', 'required'=>'required']) !!}
</div>

  <div class="form-group">
      {!! Form::label('description', 'Video description') !!}
      {!! Form::textarea('description', old('description'), ['class'=>'form-control']) !!}
  </div>
  <div class="form-group">
    {!! Form::label('category', 'Category') !!}
    {!! Form::select('category',['daily'=>'Daily', 'weekly'=>'Weekly','yearly'=>'Yearly'], old('category'), ['class'=>'form-control']) !!}
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('status', 'Status') !!}
    {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control']) !!}
  </div>
  <div class="text-center">
    {!! Form::submit('Submit',['class'=>'btn btn-primary', 'id'=>'submit-btn']) !!}
  </div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
  var fetchVideoDetails = function(){
      var video_url = $.trim($('#video_url').val());
      $.get('{{route('admin.'.$nav.'.fetch')}}', {url: video_url}, function(response){
          $('#title').val(response.title);
          $('#thumbnail').val(response.image);
          $('#thumbnail_src').attr('src', response.image);
          $('#description').val(response.description);
          $('#video_id').val(response.video_id);
      });
  }
</script>
@endpush
