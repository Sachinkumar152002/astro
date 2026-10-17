@extends('layouts.admin')

@section('content')
<h1 class="text-primary float-left">{{$item->name}} Details</h1>
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.zodiacsigns.page.save', $item->id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)", 'autocomplete'=>"off"]) !!}

<div class="clearfix"></div>
  <ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
  </ul>

  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
        <div class="form-group">
            {!! Form::label('name','Name') !!}
            {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description','Description') !!}
            {!! Form::textarea('description', old('description'),['class'=>'form-control']) !!}
        </div>
        <div class="col-md-6">
          <div class="form-group ">
              {{ Form::label('banner_image_id', 'Banner Image', ['class' => 'col-sm-12 control-label']) }}
              <div class="col-sm-12 float-right">
                 <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('banner')">Upload Image</button>
                 <div class="clearfix"></div>
                 <hr>
                {{ Form::hidden('banner_image_id', old('banner_image_id'), ['required'=>'required']) }}
                {{ Form::hidden('banner_image_src', old('banner_image_src',  $item && $item->bannerImage ? $item->bannerImage->image(400,400) : ''),['id'=>'banner_image_src']) }}
                @if(old('banner_image', $item ? $item->bannerImage : ''))
                <div class="banner profile_image" data-mediaid="{{old('banner_image', $item ? $item->banner_image_id : '')}}">
                  <img src="{{old('banner_image_src', $item && $item->bannerImage ? $item->bannerImage->image(400,400) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeBannerImage(this)"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
      <div class="form-group col-md-6">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control']) !!}
      </div>
      <div class="clear clearfix"></div>
    </div>
    <div id="seo_tab" class="tab-pane fade">

        <div class="form-group">
            {!! Form::label('meta_title','Meta Title') !!}
            {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('meta_keywords','Meta Keywords') !!}
            {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('meta_description','Meta Description') !!}
            {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
        </div>
    </div>
  </div>
  <div class="form-group" style="margin-top: 10px;">
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  </div>
  {!! Form::close() !!}
  @include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
  var media_image_type = 'zodiacsign';
  var imageset = 'banner';
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
    if(imageset==='banner'){
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
      imageset = 'banner';
    }
    $('#media-img-manager').modal('hide');
  });
  var removeImage = function(elm){
        $(elm).parents('.profile_image').remove();
        $('#image_id').val('');
        $('#image_src').val('');
    }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'banner';
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
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
