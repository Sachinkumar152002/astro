@extends('layouts.admin')

@section('content')
{!! Form::model($cms, ['route' => 'admin.cms.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)", 'autocomplete'=>"off"]) !!}
@if($cms)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
</div>
<div class="clearfix"></div>
  <ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
    @if(!$cms || ($cms && $cms->template!='default') && false)
    <li class="nav-item"><a data-toggle="tab" class=" content_cond  nav-link" href="#qa_tab">Q A</a></li>
    @endif
    @if(!$cms || ($cms && $cms->template=='content'))
      <li class="nav-item"><a data-toggle="tab" class="content_cond nav-link" href="#content_tab">Content Data</a></li>
    @endif
  </ul>

  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
        <div class="row">
        <div class="form-group col-md-4">
            {!! Form::label('name','Name', ['class'=>'required']) !!}
            {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required', 'readonly'=>$cms->template=='fixed'?true:false]) !!}
        </div>


        <div class="form-group col-md-4 {{in_array($cms->template, ['fixed', 'contact'])?'d-none':''}}">
            {!! Form::label('template', 'Template') !!}
            {!! Form::select('template',pageTemplates(), old('template'), ['class'=>'form-control nosearch', 'onchange'=>'changeTabs(this.value)']) !!}
        </div>

        <div class="form-group col-md-4 {{in_array($cms->template, ['fixed', 'contact'])?'d-none':''}}">
            {!! Form::label('status', 'Status') !!}
            {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control nosearch']) !!}
        </div>

        <div class="form-group col-md-4 {{in_array($cms->template, ['fixed', 'contact'])?'d-none':''}}">
            {!! Form::label('show_in_navigation', 'Show in Navigation') !!}
            {!! Form::select('show_in_navigation',yesNoArray(), old('show_in_navigation'), ['class'=>'form-control nosearch']) !!}
        </div>
        @if(!$cms->hide_from_list)
        <div class="form-group col-md-12 {{in_array($cms->template ?? '', ['fixed', 'contact'])?'d-none':''}}">
            <label class="d-block mb-2"><strong>Display in Apps</strong></label>
            <div class="form-check form-switch d-inline-block mr-4">
                <input class="form-check-input" type="checkbox" name="display_in_user_app" id="display_in_user_app" value="1" {{ old('display_in_user_app', $cms->display_in_user_app ?? 0) ? 'checked' : '' }}>
                <label class="form-check-label" for="display_in_user_app">Display in User App</label>
            </div>
            <div class="form-check form-switch d-inline-block">
                <input class="form-check-input" type="checkbox" name="display_in_customer_app" id="display_in_customer_app" value="1" {{ old('display_in_customer_app', $cms->display_in_customer_app ?? 0) ? 'checked' : '' }}>
                <label class="form-check-label" for="display_in_customer_app">Display in Customer App</label>
            </div>
        </div>
        @endif
        <div class="col-md-4">
          <div class="form-group ">
              {{ Form::label('cms_image_id', 'Image', ['class' => 'col-sm-12 control-label']) }}
              <div class="d-flex align-items-center">
                 <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('cms_image')">Upload Image</button>
                 <div class="clearfix"></div>
                {{ Form::hidden('image_id', old('image_id'), ['required'=>'required', 'id'=>'cms_image_id']) }}
                {{ Form::hidden('cms_image_src', old('cms_image_src',  $cms && $cms->image ? $cms->image->image(100,100) : ''),['id'=>'cms_image_src']) }}
                @if(old('image_id', $cms && $cms->image_id ? $cms->image_id : ''))
                <div class="profile_image text-center cms_image ml-2" data-mediaid="{{old('image', $cms && $cms->image ? $cms->cms_image_id : '')}}">
                  <img src="{{old('cms_image_src', $cms && $cms->image ? $cms->image->image(100,100) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this, 'cms_image')"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="form-group ">
            {{ Form::label('banner_image_id', 'Banner Image', ['class' => 'col-sm-12 control-label']) }}
            <div class="d-flex align-items-center">
               <button type="button" class="btn float-left" id="select-banner-image" onclick="openMediaManager('banner_image')">Upload Image</button>
               <div class="clearfix"></div>
              {{ Form::hidden('banner_image_id', old('banner_image_id'), ['required'=>'required']) }}
              {{ Form::hidden('banner_image_src', old('banner_image_src',  $cms && $cms->bannerImage ? $cms->bannerImage->image(100,100) : ''),['id'=>'banner_image_src']) }}
              @if(old('banner_image_id', $cms ? $cms->banner_image_id : ''))
              <div class="banner_image profile_image ml-2" data-mediaid="{{old('banner_image', $cms ? $cms->banner_image_id : '')}}">
                <img src="{{old('banner_image_src', $cms && $cms->bannerImage ? $cms->bannerImage->image(100,100) : '')}}" alt="image" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeImage(this, 'banner_image')"></i>
              </div>
              @endif
            </div>
        </div>
    </div>
      </div>
      <div class="form-group">
          {!! Form::label('short_description','Short Description') !!}
          {!! Form::textarea('short_description', old('short_description'),['class'=>'form-control']) !!}
      </div>
        <div class="form-group">
            {!! Form::label('content','Content') !!}
            {!! Form::textarea('content', old('content'),['class'=>'form-control']) !!}
        </div>

      <div class="clear clearfix"></div>
    </div>
    <div id="seo_tab" class="tab-pane fade">
    <div class="row">
        <div class="col-md-6">
          <div class="form-group {{$cms->template=='fixed'?'d-none':''}}">
              {!! Form::label('slug','Page Slug') !!}
              {!! Form::text('slug', old('slug'),['class'=>'form-control']) !!}
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
              {!! Form::label('meta_title','Meta Title') !!}
              {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
          </div>
      </div>
          <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('meta_keywords','Meta Keywords') !!}
            {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
        </div>
        </div>
            <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('meta_description','Meta Description') !!}
            {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
        </div>
        </div>
    </div>
    </div>

  @if(!$cms || ($cms && $cms->template!='default') && false)
     <div id="qa_tab" class="tab-pane fade content_cond">
        @include('admin.cms.qna')
    </div>
    @endif
    @if(!$cms || ($cms && $cms->template=='content'))
     <div id="content_tab" class="tab-pane fade content_cond">
        @include('admin.cms.content')
    </div>
    @endif
  </div>
  <div class="form-group" style="margin-top: 10px;">
  </div>
  {!! Form::close() !!}
  @include('admin.media.manager')
@endsection

@push('scripts')

<script src="{{asset('js/vue.min.js')}}"></script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
$('#name').on('keyup', function(){
  var value = $(this).val();
  $('#slug').val(value.trim().replaceAll(' ', '-'));
});
var media_image_type = 'cms';
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
    var newImg  = selectedImg.clone()
    if(imageset==='image' || imageset==='logo' || imageset==='banner_image' || imageset==='cms_image'){
      $('#'+imageset+'_id').val(mediaId);
      $('#'+imageset+'_src').val(imgSrc);
      $('.profile_image.'+imageset).remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image text-center '+imageset);
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
      $('#'+imageset+'_id').after(newImg);
    }else if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'editor';
    }
    $('#media-img-manager').modal('hide');
  });

var changeTabs = function(tab) {
  $('.content_cond').addClass('d-none disabled');
  if(tab=='content') {
    $('.content_cond').removeClass('d-none disabled');
  }
}
@if($cms)
changeTabs('{{$cms->template}}');
@else
changeTabs('');
@endif

var openMediaManager = function(imgOp) {
  imageset = imgOp;
  $('#media-img-manager').modal('show');
}
var removeImage = function(elm, imgOp){
  $(elm).parents('.profile_image.'+imgOp).remove();
  $('#'+imgOp+'_id').val('');
  $('#'+imgOp+'_src').val('');
}
</script>
@include('admin._partials.editor')
@endpush
