
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <!-- <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
   </div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">

<div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('name','Name', ['class'=>'required']) !!}
      {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
    </div>
    <div class="col-12 col-md-4 form-group px-0">
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-primary" id="select-image" onclick="openMediaManager('icon')">
        Upload Icon
      </button>
      {{ Form::hidden('icon_id', '0', ['id'=>'icon_id']) }}
      <div class="profile_image text-center icon icon_div mx-2" data-mediaid="0">
        <img src="{{ $item && $item->image ? $item->image->image() : no_image() }}" alt="image" class="img-responsive" style="height:100px; width: 100px;">
        <i class="fa fa-trash text-danger d-none" onclick="removeImage(this, 'icon')"></i>
      </div>
      {{ Form::hidden('icon_src', no_image(), ['id'=>'icon_src']) }}
    </div>
  </div>
    <div class="form-group col-md-4">
      {!! Form::label('price','Price', ['class'=>'required']) !!}
      {!! Form::number('price', old('price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-4 px-1">
      <span class="ml-2 mt-2"><strong>Status</strong></span>
      <input type="checkbox" class="toggle-check" name="status" {{$item && $item->status ? 'checked' : ''}} id="status" value="1">
      <label class="mx-2 t-lable mt-2" for="status"></label>
    </div>
  </div>
</div>
</div>
  {!! Form::close() !!}
  @include('admin.media.manager')
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
var media_image_type = 'services';
var imageset = 'editor';
$('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
        toastr.warning('Please select an image');
      return false;
    }
    var selectedImg = $('.img-box-choice.selected');
    var mediaId = selectedImg.data('mediaid');
    var imgSrc = $('.img-box-choice.selected img').attr('src')
    var imgSrcOriginal = $('.img-box-choice.selected img').data('original')
    var newImg  = selectedImg.clone();
    if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'editor';
    }else{
      $('#'+imageset+'_id').val(mediaId);
      $('#'+imageset+'_src').val(imgSrc);
      $('.profile_image.'+imageset+'_div').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image  text-center '+imageset+'_div');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
      $('#'+imageset+'_id').after(newImg);
    }
    $('#media-img-manager').modal('hide');
  });

var openMediaManager = function(imgOp) {
    imageset = imgOp;
    $('#media-img-manager').modal('show');
}
var removeImage = function(elm, imageset){
      $(elm).parents('.profile_image.'+imageset+'_div').remove();
      $('#'+imageset+'_id').val('');
      $('#'+imageset+'_src').val('');
}
</script>
@endpush
