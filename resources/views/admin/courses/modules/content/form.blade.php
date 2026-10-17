@extends('layouts.admin_two')


@section('content')

<!-- <h1 class="text-primary float-left"> Content Details</h1> -->
<div class="clearfix"></div>

{!! Form::model($content , ['id'=>"submit-form"]) !!}
  @if($content)
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
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#mains">Main</a></li>
    <li class="active nav-item"><a data-toggle="tab" class="nav-link" href="#previews">Preview</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#videos">Video</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#files">File</a></li>
  </ul>

  <div class="tab-content">
  <div id="mains" class="tab-pane fade in active show">
    <div id="mains-div" class="row">
      <div class="form-group col-12">
        {!! Form::label('title','Title', ['class'=>'required']) !!}
        {!! Form::text('title', $content ? old('title', $content->title) : old('title')  ,['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-12">
        {!! Form::label('description', 'Description', ['class'=>'required']) !!}
        {!! Form::textarea('description',$content ? $content->description : '' , ['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-6">
        {!! Form::label('order', 'Order') !!}
        {!! Form::number('order',$content ? $content->order : '', ['class'=>'form-control', 'min'=>'0']) !!}
      </div>

      <div class="col-md-6">
        <div class="form-group">
           <span class="ml-2"><strong>Status</strong></span>
              <input type="checkbox" class="toggle-check mt-4" id="status" {{$content && $content->status ? 'checked' : ''}} value="1" name="status">
              <label class="mx-2 t-lable" for="status"></label>
        </div>
      </div>
    </div>
  </div>
    <div id="previews" class="tab-pane fade">
      <div id="previews-div">
        <div class="form-group">
          {!! Form::label('preview_type', 'Platform', ['class'=>'required']) !!}
          {!! Form::select('preview_type',[''=>'Select', 'vimeo'=>'Vimeo', 'youtube'=>'Youtube'], $content && $content->preview ? $content->preview->type : old('preview_type'), ['class'=>'form-control',  'id'=>'select-preveiw-type']) !!}
        </div>
        <div id="preview-vimeo-div" class="preview-type-div d-none">
          <div class="form-group">
            {!! Form::label('preview_other_video_url','Video URL', []) !!}
            <div class="row">
              <div class="col-md-10">
                {!! Form::text('preview_other_video_url', $content && $content->preview ? old('preview_other_video_url', $content->preview->video_url) : old('preview_other_video_url'),['class'=>'form-control', 'id'=>"vimeo_preview_url" ]) !!}
                <small>Paste any vimeo url</small>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-primary" onclick="fetchVideoDetails('vimeo')">Fetch Details</button>
              </div>
            </div>
          </div>
        </div>
        <div id="preview-youtube-div" class="preview-type-div d-none">
          <div class="form-group">
            {!! Form::label('preview_youtube_video_url','Video URL', []) !!}
            <div class="row">
              <div class="col-md-10">
                {!! Form::text('preview_youtube_video_url', $content && $content->preview ? $content->preview->video_url : '' ,['class'=>'form-control', 'id'=>'youtube_preview_url']) !!}
                <small>Paste any youtube id or youtube url</small>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-primary" onclick="fetchVideoDetails('youtube')">Fetch Details</button>
              </div>
            </div>
          </div>
        </div>
        <div id="preview_preview" class="preview-type-div-x">

          <div class="form-group">
            {!! Form::label('preview_video_id','Video ID', []) !!}
            {!! Form::text('preview_video_id', $content && $content->preview ? old('preview_video_id', $content->preview->video_id) : old('preview_video_id'),['class'=>'form-control', 'id'=>'preview_video_id']) !!}
          </div>
          <div class="form-group">
            {!! Form::label('preview_title','Title', []) !!}
            {!! Form::text('preview_title', $content && $content->preview ? old('preview_title', $content->preview->title) : old('preview_title'),['class'=>'form-control', 'id'=>'preview_title']) !!}
          </div>
          <div class="form-group">
            {!! Form::label('preview_thumbnail','Thumbnail', []) !!}
            <img src="{{old('preview_thumbnail_src', $content && $content->preview ? $content->preview->thumbnail:'')}}" alt="admin image" id="preview_thumbnail_src" class="float-right img-fluid" style="max-width:200px;">
            {!! Form::text('preview_thumbnail', $content && $content->preview ? old('preview_thumbnail', $content->preview->thumbnail) : old('preview_thumbnail'),['class'=>'form-control', 'id'=>'preview_thumbnail']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('preview_description', 'Video description', []) !!}
              {!! Form::textarea('preview_description', $content && $content->preview ? old('preview_description', $content->preview->description) : old('preview_description'), ['class'=>'form-control mceNoEditor', 'id'=>'preview_description']) !!}
          </div>
        </div>
        <div id="preview-upload-div" class="preview-type-div d-none">
          <div class="form-group">
            {!! Form::label('preview_file','Video URL', ['class'=>'required']) !!}
            <input type="file" class="form-control" name="preview_file" value=""/>
          </div>
          @if($content && $content->preview && ($content->preview->type == 'upload'))
           <div class="form-group">
             {!! Form::label('file_url2', 'Video', ['class'=>'required']) !!}
             <a href="{{asset($content->preview->video_url)}}" target="_blank">{{asset($content->preview->video_url)}}</a>
              {!! Form::hidden('preview_video_url1',$content->preview->video_url, ['class'=>'form-control', 'id'=>'preview_video_url1']) !!}
           </div>
          @endif
        </div>
      </div>
    </div>
      <div id="videos" class="tab-pane fade">
        <div id="videos-div">
          <div class="form-group">
            {!! Form::label('video_type', 'Platform', ['class'=>'required']) !!}
            {!! Form::select('video_type',[''=>'Select', 'vimeo'=>'Vimeo', 'youtube'=>'Youtube'], $content && $content->videos ? old('video_type', $content->videos->type) : old('video_type'), ['class'=>'form-control',  'id'=>'select-video-type']) !!}
          </div>
          <div id="video-vimeo-div" class="video-type-div d-none">
            <div class="form-group">
              {!! Form::label('video_other_video_url','Video URL', []) !!}
              <div class="row">
                <div class="col-md-10">
                  {!! Form::text('video_other_video_url', $content && $content->videos ? old('video_other_video_url', $content->videos->video_url) : old('video_other_video_url'),['class'=>'form-control', 'id'=>'vimeo_main_url']) !!}
                  <small>Paste any vimeo url</small>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-primary" onclick="fetchVideoDetails2('vimeo')">Fetch Details</button>
                </div>
              </div>
            </div>
          </div>
          <div id="video-youtube-div" class="video-type-div d-none">
            <div class="form-group">
              {!! Form::label('video_youtube_video_url','Video URL', []) !!}
              <div class="row">
                <div class="col-md-10">
                  {!! Form::text('video_youtube_video_url', $content && $content->videos ? $content->videos->video_url : '',['class'=>'form-control', 'id'=>'youtube_main_url']) !!}
                  <small>Paste any youtube id or youtube url</small>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-primary" onclick="fetchVideoDetails2('youtube')">Fetch Details</button>
                </div>
              </div>
            </div>
          </div>
          <div id="video-div" class="video-type-div-x">

            <div class="form-group">
              {!! Form::label('video_video_id','Video ID', []) !!}
              {!! Form::text('video_video_id', $content && $content->videos ? old('video_video_id', $content->videos->video_id) : old('video_video_id'),['class'=>'form-control', 'id'=>'video_video_id']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('video_title','Title', []) !!}
              {!! Form::text('video_title', $content && $content->videos ? old('video_title', $content->videos->title) : old('video_title'),['class'=>'form-control', 'id'=>'video_title']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('video_thumbnail','Thumbnail', []) !!}
              <img src="{{old('video_thumbnail_src', $content && $content->videos ? $content->videos->thumbnail:'')}}" alt="admin image" id="video_thumbnail_src" class="float-right img-fluid" style="max-width:200px;">
              {!! Form::text('video_thumbnail', $content && $content->videos ? old('video_thumbnail', $content->videos->thumbnail) : old('video_thumbnail'),['class'=>'form-control', 'id'=>'video_thumbnail']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('video_description', 'Video description', []) !!}
                {!! Form::textarea('video_description', $content && $content->videos ? old('video_description', $content->videos->description) : old('video_description'), ['class'=>'form-control mceNoEditor', 'id'=>'video_description']) !!}
            </div>
          </div>
          <div id="video-upload-div" class="video-type-div d-none">
            <div class="form-group">
              {!! Form::label('video_file1','Video URL', ['class'=>'required']) !!}
              <input type="file" class="form-control" name="video_file" value=""/>
            </div>
            @if($content && $content->videos && ($content->videos->type == 'upload'))
             <div class="form-group">
               {!! Form::label('file_url1', 'Video', ['class'=>'required']) !!}
               <a href="{{asset($content->videos->video_url)}}" target="_blank">{{asset($content->videos->video_url)}}</a>
                {!! Form::hidden('video_video_url1',$content->videos->video_url, ['class'=>'form-control', 'id'=>'video_video_url1']) !!}
             </div>
            @endif
          </div>
        </div>
      </div>
    <div id="files" class="tab-pane fade">
      <div id="files-div">
      <div class="form-group">
        {!! Form::label('file', 'File') !!}
       <div class="dropzone" id="file-upload"></div>
          <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
          </div>
        </div>
        @if(true)
       <div class="form-group">
         {!! Form::label('file_url', 'File') !!}

         <a href="{{$content && $content->files ? asset($content->files->file) : ''}}" id="file_orginal_name_show" target="_blank">{{$content && $content->files ? asset($content->files->file) : ''}}</a>
         <p> <iframe src="{{$content && $content->files ? asset($content->files->file) : no_image()}}" width="500" height="500" id="file_orginal_name_show_previw"></iframe></p>
        <div class="form-group">
         {!! Form::hidden('file_url',$content && $content->files ? asset($content->files->file) : '', ['class'=>'form-control', 'id'=>'file_url']) !!}
       </div>
       </div>
        @endif
       <div class="form-group">
        {!! Form::hidden('file_name',$content && $content->files ? old('file_name', $content->files->file) : old('file_name'), ['class'=>'form-control', 'id'=>'file_name']) !!}
      </div>
        <div class="form-group">
         {!! Form::hidden('file_type',$content && $content->files ? old('file_type', $content->files->file_type) : old('file_type'), ['class'=>'form-control', 'id'=>'file_type']) !!}
       </div>
       <div class="form-group">
        {!! Form::hidden('file_folder',$content && $content->files ? old('file_folder', $content->files->title) : old('file_folder'), ['class'=>'form-control', 'id'=>'file_folder']) !!}
      </div>
      <div class="form-group">
        <input type="hidden" class="form-control" name="file_orginal_name" value="{{$content && $content->files ? $content->files->file_orginal_name : ''}}" id="file_orginal_name"/>
     </div>
      </div>
    </div>
    <div class="text-center">
      {!! Form::submit('Submit',['class'=>'btn btn-primary', 'id'=>'submit-btn']) !!}
    </div>
  </div>
  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('js/dropzone.js')}}"> </script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
  var fetchVideoDetails = function(platform){
      $('#preview_title').val('');
      $('#preview_thumbnail').val('');
      $('#preview_thumbnail_src').attr('src', '{{no_image()}}');
      $('#preview_description').val('');
      $('#preview_video_id').val('');
      var preview_video_url = $.trim($('#'+platform+'_preview_url').val());
      $.get('{{route('admin.videos.fetch')}}', {url: preview_video_url, platform:platform}, function(response){
          $('#preview_title').val(response.title);
          $('#preview_thumbnail').val(response.image);
          $('#preview_thumbnail_src').attr('src', response.image);
          $('#preview_description').val(response.description);
          $('#preview_video_id').val(response.video_id);
      });
  }
  var fetchVideoDetails2 = function(platform){
      var video_video_url = $.trim($('#'+platform+'_main_url').val());
      $('#video_title').val('');
      $('#video_thumbnail').val('');
      $('#video_thumbnail_src').attr('src', '{{no_image()}}');
      $('#video_description').val('');
      $('#video_video_id').val('');
      $.get('{{route('admin.videos.fetch')}}', {url: video_video_url, platform:platform}, function(response){
          $('#video_title').val(response.title);
          $('#video_thumbnail').val(response.image);
          $('#video_thumbnail_src').attr('src', response.image);
          $('#video_description').val(response.description);
          $('#video_video_id').val(response.video_id);
      });
  }

  $(document).ready(function (){
      let previewType = '{{$content && $content->preview ? $content->preview->type : "other"}}';
      let fileType = '{{$content && $content->videos ? $content->videos->type : "other"}}';
      $('#preview-'+previewType+'-div').removeClass('d-none');
      $('#video-'+fileType+'-div').removeClass('d-none');
  })

  $('#select-preveiw-type').change(function (){
    $('.preview-type-div').addClass('d-none');
    $('#preview-'+$(this).val()+'-div').removeClass('d-none');
  })

  $('#select-video-type').change(function (){
    $('.video-type-div').addClass('d-none');
    $('#video-'+$(this).val()+'-div').removeClass('d-none');
  })

  var media_upload_url = '{{route('admin.course.module.content.file.upload', ["course_id"=>$course_id, "module_id"=>$module_id])}}';
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
                $('#file_name').val(data.file_name);
                $('#file_type').val(data.file_type);
                $('#file_folder').val(data.folder);

                // $('#file_orginal_name').val('course/'+'{{$course_id}}/{{$module_id}}/'+data.folder+'/'+data.file_name);
                $('#file_orginal_name').val(data.fileUpload.split('public/')[1]+'/'+data.file_name);
                $('#file_orginal_name_show').text('/'+data.fileUpload.split('public/')[1]+'/'+data.file_name);
                $('#file_orginal_name_show').attr('href', '/'+data.fileUpload.split('public/')[1]+'/'+data.file_name);
                $('#file_orginal_name_show_previw').attr('src', '/'+data.fileUpload.split('public/')[1]+'/'+data.file_name);

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

    $('#submit-form').submit(function(e) {
      e.preventDefault();
      toastr.success("Please Wait Form is submitting");
      tinyMCE.triggerSave();
      $('#submit-btn').attr('disabled','disabled');
      var data = new FormData($('form')[0]);
      $.ajax({
        url: "{{route('admin.course.module.content.submit',['course_id'=>$course_id, 'module_id'=>$module_id])}}",
        enctype: 'multipart/form-data',
        type: 'POST',
        dataType: 'json',
        data:data,
        async: false,
        contentType: false,
        processData: false,
    })
    .done(function(response) {
      console.log(response)
      // $('#submit-btn').removeAttr('disabled');
      toastr.success(response.message);
      window.location.href = response.redirectto;
      // toastr.success('response.message');
    })
    .fail(function(error, code) {
      $('#submit-btn').removeAttr('disabled');
      for (var key in error.responseJSON.errors) {
        toastr.error(error.responseJSON.errors[key][0]);
        // console.log(error.responseJSON.errors[key][0]);
      }
    })
  });
</script>
@endpush
