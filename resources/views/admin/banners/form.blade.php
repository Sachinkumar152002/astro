@extends('layouts.admin')


@section('content')

<div class="clearfix"></div>

{!! Form::model($banner, ['route' => 'admin.banner.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($banner)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Banner Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<div class="row">
  <div class="form-group col-md-4">
    {!! Form::label('title','Title', ['class'=>'required']) !!}
    {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group col-md-4">
    {!! Form::label('alt','Image Alt', ['class'=>'required']) !!}
    {!! Form::text('alt', old('alt'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group col-md-4">
    {!! Form::label('link','Image link') !!}
    {!! Form::url('link', old('link'),['class'=>'form-control']) !!}
  </div>
  <div class="col-md-4">
    {{ Form::label('image_id', 'Desktop Banner', ['class' => ' control-label required']) }}
    <div class="form-group">
      <div class="">
        <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
        {{ Form::hidden('image_id', old('image_id'), ['required'=>'required']) }}
        {{ Form::hidden('image_src', old('image_src',  $banner && $banner->image ? $banner->image->image(200,200) : ''),['id'=>'image_src']) }}
        @if(old('image_id', $banner ? $banner->image_id : ''))
          <div class="profile_image float-right" data-mediaid="{{old('image', $banner ? $banner->image_id : '')}}">
            <img src="{{old('image_src', $banner && $banner->image ? $banner->image->image(150,150) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
          </div>
        @endif

      </div>
    </div>
  </div>
  <div class="col-md-4">
    {{ Form::label('mobile_image_id', 'Mobile Banner', ['class' => ' control-label required']) }}
    <div class="form-group">
      <div class="">
        <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('mobile_image')">Upload Image</button>
        {{ Form::hidden('mobile_image_id', old('mobile_image_id'), ['required'=>'required']) }}
        {{ Form::hidden('mobile_image_src', old('mobile_image_src',  $banner && $banner->mobileImage ? $banner->mobileImage->image(100,100) : ''),['id'=>'mobile_image_src']) }}
        @if(old('mobile_image_id', $banner ? $banner->mobile_image_id : ''))
          <div class="mobile_profile_image float-right" data-mediaid="{{old('mobile_image_id', $banner ? $banner->mobile_image_id : '')}}">
            <img src="{{old('mobile_image_src', $banner && $banner->mobileImage ? $banner->mobileImage->image(100,100) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
          </div>
        @endif

      </div>
    </div>
  </div>
  <div class="form-group col-md-4">
    {!! Form::label('start_date', 'Start Date') !!}
    {!! Form::date('start_date', old('start_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
  </div>
  <div class="form-group col-md-4">
    {!! Form::label('end_date', 'End Date') !!}
    {!! Form::date('end_date', old('end_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
  </div>
  <div class="form-group col-md-4">
    {!! Form::label('type', 'Page', ['class'=>'required']) !!}
    {!! Form::select('type', pageTypes(), old('type'), ['class'=>'form-control nosearch', 'required'=>'required']) !!}
  </div>
  <div class="form-group col-md-2">
    {!! Form::label('order', 'Order') !!}
    {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
  </div>
  <div class="form-group col-md-2">
    {!! Form::label('status', 'Status') !!}
    {!! Form::select('status',$_status, old('status'), ['class'=>'form-control nosearch']) !!}
  </div>
  {{--<div class="form-group col-md-12 p-0">
    {!! Form::label('caption', 'Banner Caption') !!}
    {!! Form::textarea('caption', old('caption'), ['class'=>'form-control']) !!}
  </div>--}}

</div>
  {!! Form::close() !!}
  <div class="row">
<div class="col-md-6">
<p><strong>Homepage main banner</strong></p>
<ul>
  <li>Desktop image size 1920x550px @72DPI</li>
  <li>Mobile image size 800x400px @72DPI</li>
  </ul>
  </div>

  <div class="col-md-6">
<p><strong>Inner Pages (Astromall, Reports, Puja, Kundli Reports, Kundli Matching, Panchang, Horoscope, etc.) main banner</strong></p>
<ul>
  <li>Desktop image size 1250x250 @72DPI</li>
  <li>Mobile image size 800x300px @72DPI</li>
  </ul>
  </div>
</div>

@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
var media_image_type = 'banner';
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
  if (imageset === 'mobile_image') {
       $('#mobile_image_id').val(mediaId);
       $('#mobile_image_src').val(imgSrc);
       $('.mobile_profile_image').remove(); // Remove the old image if it exists
       newImg.find('.action').remove();
       newImg.removeClass('img-box-choice selected').addClass('mobile_profile_image float-right');
       newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
       $('#mobile_image_id').after(newImg);
   } else if (imageset === 'featured') {
       $('#image_id').val(mediaId);
       $('#image_src').val(imgSrc);
       $('.profile_image').remove(); // Remove the old image if it exists
       newImg.find('.action').remove();
       newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
       newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
       $('#image_id').after(newImg);
   } else if (imageset === 'editor') {
       tinyMCE.activeEditor.execCommand('mceInsertContent', true, '<img src="' + imgSrcOriginal.replace(window.location.origin, '') + '" alt="editor-insert-image"/>');
       imageset = 'featured';
   }
  // else if(imageset==='slide'){
  //     myVue.updateSideImage(mediaId, imgSrc.replace('200-200/', ''));
  // }
  $('#media-img-manager').modal('hide');
});
var removeImage = function(elm){
    $(elm).parents('.profile_image').remove();
    $('#image_id').val('');
    $('#image_src').val('');
    $(elm).parents('.mobile_profile_image').remove();
    $('#mobile_image_id').val('');
    $('#mobile_image_src').val('');
}
$('#title').on('keyup blur', function(){
    var value = $(this).val();
    $('#slug').val(value);
});

  var addImageToSlide = function() {
    imageset = 'slide';
    $('#media-img-manager').modal('show');
  }
  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });
  var openMediaManager = function(image = null) {
    imageset = image ? image : 'featured';
    $('#media-img-manager').modal('show');
  }
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
