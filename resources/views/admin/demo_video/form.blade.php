
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.software.video.submit', $data_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('url','Video URL', ['class'=>'required']) !!}
        <div class="row">
          <div class="col-md-8">
            {!! Form::text('url', $item ? $item->url : '' ,['class'=>'form-control', 'id'=>'url']) !!}
            <small>Paste any youtube id or youtube url</small>
          </div>
          <div class="col-md-4">
            <button type="button" class="btn btn-primary" onclick="fetchVideoDetails()">Fetch Details</button>
          </div>
        </div>
      </div>
        <div class="form-group col-md-4">
          {!! Form::label('video_id','Video Id', ['class'=>'required']) !!}
          {!! Form::text('video_id', $item ? $item->video_id : '' ,['class'=>'form-control', 'readonly', 'id'=>'video_id']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('title','Title', ['class'=>'required']) !!}
          {!! Form::text('title', $item ? $item->title  : '' ,['class'=>'form-control', 'id'=>'title']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('thumbnail','Thumbnail', ['class'=>'required']) !!}
          <div class="d-flex align-items-center">
            <img src="{{$item ? $item->thumbnail : ''}}" alt="admin image" id="thumbnail_src" class="float-right img-fluid mr-3" style="max-width:100px;">
            {!! Form::text('thumbnail', $item ? $item->thumbnail : '' ,['class'=>'form-control', 'id'=>'thumbnail']) !!}
          </div>
        </div>
        <div class="form-group col-md-4">
         {!! Form::label('order', 'Order', ['class'=>'required']) !!}
         {!! Form::number('order',$item ? $item->order : '', ['class'=>'form-control mt-4', 'min'=>'0']) !!}
       </div>

       <div class="form-group col-md-2">
         <span class="ml-2"><strong>Status</strong></span>
           <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
           <label class="mx-2 t-lable mt-4" for="status"></label>
       </div>
        <div class="form-group col-md-12">
            {!! Form::label('description', 'description', ['class'=>'required']) !!}
            {!! Form::textarea('description', $item ? $item->description : '', ['class'=>'form-control mceNoEditor', 'id'=>'description']) !!}
        </div>

    </div>
  </div>
</div>
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
  var media_image_type = 'module_procedure';
  var imageset = 'featured';
  $('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
        toastr.warning('Please select an image');
      return false;
    }
    var selectedImg = $('.img-box-choice.selected');
    var mediaId = selectedImg.data('mediaid');
    var imgSrc = $('.img-box-choice.selected img').attr('src')
    var imgSrcOriginal = $('.img-box-choice.selected img').data('original')
    var newImg  = selectedImg.clone()
    if(imageset==='featured'){
      $('#image_id').val(mediaId);
      $('#image_src').val(imgSrc);
      $('.profile_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
      $('#image_id').after(newImg)
    }
    else if(imageset==='banner'){
    $('#banner_image_id').val(mediaId);
    $('#banner_image_src').val(imgSrc);
    $('.banner.profile_image').remove();
    newImg.find('.action').remove();
    newImg.removeClass('img-box-choice selected').addClass('banner profile_image float-right');
    newImg.append('<i class="fa fa-trash text-danger" onclick="removeBannerImage(this)"></i>');
    $('#banner_image_id').after(newImg)
  }
    else if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'featured';
    }
    $('#media-img-manager').modal('hide');
  });
  var removeImage = function(elm){
        $(elm).parents('.profile_image').remove();
        $('#image_id').val('');
        $('#image_src').val('');
    }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });
  var openMediaManager = function(option) {
    imageset = option;
    $('#media-img-manager').modal('show');
  }
  var removeBannerImage = function(elm){
    $(elm).parents('.profile_image').remove();
    $('#banner_image_id').val('');
    $('#banner_image_src').val('');
}
  var fetchVideoDetails = function(){
      var url = $.trim($('#url').val());
      $.get('{{route('admin.videos.fetch')}}', {url: url}, function(response){
          $('#title').val(response.title);
          $('#thumbnail').val(response.image);
          $('#thumbnail_src').attr('src', response.image);
          $('#description').val(response.description);
          $('#video_id').val(response.video_id);
      });
  }

</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
