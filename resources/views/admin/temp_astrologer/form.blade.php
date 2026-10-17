
@extends('layouts.admin')
@section('content')
<div id="profile-app">
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
{{ Form::hidden('astrologer_id', old('astrologer_id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Temp Astrologer Detail</h1>
    <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
    <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#prices">Prices</a></li>
</ul>
<div class="tab-content">
  <div id="prices-tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="form-group col-md-3 {{$astrologer->price!=$item->price?'blink-text':''}}">
        {!! Form::label('price','Video Call Price',['class'=>'required']) !!} <em><small>{{$astrologer->price?:'na'}}</small></em>
        {!! Form::text('price', old('price'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-3 {{$astrologer->special_price!=$item->special_price?'blink-text':''}}">
        {!! Form::label('special_price','Video Call Special price') !!}  <em><small>{{$astrologer->special_price?:'na'}}</small></em>
        {!! Form::text('special_price', old('special_price'),['class'=>'form-control']) !!}
      </div>
      <div class="clearfix w-100"></div>
      <div class="form-group col-md-3 {{$astrologer->chat_price!=$item->chat_price?'blink-text':''}}">
        {!! Form::label('chat_price','Chat Price',['class'=>'required']) !!}  <em><small>{{$astrologer->chat_price?:'na'}}</small></em>
        {!! Form::text('chat_price', old('chat_price'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-3 {{$astrologer->chat_special_price!=$item->chat_special_price?'blink-text':''}}">
        {!! Form::label('chat_special_price','Chat Special price') !!}  <em><small>{{$astrologer->chat_special_price?:'na'}}</small></em>
        {!! Form::text('chat_special_price', old('chat_special_price'),['class'=>'form-control']) !!}
      </div>
      <div class="clearfix w-100"></div>
      <div class="form-group col-md-3 {{$astrologer->call_price!=$item->call_price?'blink-text':''}}">
        {!! Form::label('call_price','Call Price',['class'=>'required']) !!}  <em><small>{{$astrologer->call_price?:'na'}}</small></em>
        {!! Form::text('call_price', old('call_price'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-3 {{$astrologer->call_special_price!=$item->call_special_price?'blink-text':''}}">
        {!! Form::label('call_special_price',' Call Special price') !!}  <em><small>{{$astrologer->call_special_price?:'na'}}</small></em>
        {!! Form::text('call_special_price', old('call_special_price'),['class'=>'form-control']) !!}
      </div>
      <div class="clearfix w-100"></div>
      <div class="form-group col-md-3 {{$astrologer->sp_from_date!=$item->sp_from_date?'blink-text':''}}">
        {!! Form::label('sp_from_date','Special Price From Date') !!}  <em><small>{{$astrologer->sp_from_date?:'na'}}</small></em>
        {!! Form::date('sp_from_date', old('sp_from_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
      </div>
      <div class="form-group col-md-3 {{$astrologer->sp_to_date!=$item->sp_to_date?'blink-text':''}}">
        {!! Form::label('sp_to_date','Special Price To Date') !!}  <em><small>{{$astrologer->sp_to_date?:'na'}}</small></em>
        {!! Form::date('sp_to_date', old('sp_to_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</div>
{{--
@include('admin.media.manager')
--}}
@endsection

@push('scripts')
{{--<script type="text/javascript">
  var media_image_type = 'categories';
  var imageset = 'featured';
  $('document').ready(function (){
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
      if (imageset === 'featured') {
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
       }else{
         $('#'+imageset+'_id').val(mediaId);
         $('#'+imageset+'_src').attr('src', imgSrc);
         $('.'+imageset+'_img_div').find('i').remove();
         $('.'+imageset+'_img_div').append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
       }
      $('#media-img-manager').modal('hide');
    });
  });

  var removeImage = function(elm, imagesett){
    console.log(imagesett)
    $('.'+imagesett+'_img_div').find('img').attr('src', @json(no_image()));
    $('.'+imagesett+'_img_div').find('i').remove();
    $('#'+imagesett+'_id').val(0);
  }

  var addImageToSlide = function() {
    imageset = 'slide';
    $('#media-img-manager').modal('show');
  }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });

  var openMediaManager = function(image = null) {
    imageset = image ? image : 'featured';
    console.log(imageset)
    $('#media-img-manager').modal('show');
  }
</script>--}}
<script src="{{asset('theme-admin/js/form.js')}}"></script>

@endpush
