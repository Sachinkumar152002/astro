
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.software.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#detail_tab">Description</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('title','Page Title', ['class'=>'required']) !!}
            {!! Form::text('title', old('title'),['class'=>'form-control', 'id'=>'title']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('page', 'Page Category', ['class'=>'required']) !!}
          {!! Form::select('page',cms2(), old('page'), ['class'=>'form-control nosearch']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('blog_category_id', 'Blog Category') !!}
          {!! Form::select('blog_category_id',$categories, old('blog_category_id'), ['class'=>'form-control nosearch']) !!}
        </div>
      </div>
      <div class="col-md-6">
          <div class="form-group ">
              {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
              <div class="d-flex align-items-center">
                 <button type="button" class="btn btn-sm" id="select-image" onclick="openMediaManager('featured')">Upload Image</button>

                {{ Form::hidden('image_id', old('image_id')) }}
                {{ Form::hidden('image_src', old('image_src',  $item && $item->image ? $item->image->image(400,400) : ''),['id'=>'image_src']) }}
                @if(old('image_id', $item ? $item->image_id : ''))
                <div class="profile_image ml-2" data-mediaid="{{old('image', $item ? $item->image_id : '')}}">
                  <img src="{{old('image_src', $item && $item->image ? $item->image->image(100,100) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="form-group ">
            {{ Form::label('banner_image_id', 'Banner', ['class' => 'control-label required']) }}
            <div class="d-flex align-items-center">
               <button type="button" class="btn btn-sm" id="select-image" onclick="openMediaManager('banner')">Upload Image</button>
              {{ Form::hidden('banner_image_id', old('banner_image_id'), ['required'=>'required']) }}
              {{ Form::hidden('banner_image_src', old('banner_image_src',  $item && $item->bannerImage ? $item->bannerImage->image(400,400) : ''),['id'=>'banner_image_src']) }}
              @if($item && $item->bannerImage)
              <div class="banner profile_image ml-2" data-mediaid="{{old('banner_image', $item ? $item->banner_image_id : '')}}">
                <img src="{{old('banner_image_src', $item && $item->bannerImage ? $item->bannerImage->image(400,400) : '')}}" alt="image" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeBannerImage(this)"></i>
              </div>
              @endif
            </div>
        </div>
    </div>
    </div>
  </div>
  <div id="detail_tab" class="tab-pane fade">
    <div class="row">
  <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('short_description', 'Short Description', ['class'=>'required']) !!}
          {!! Form::textarea('short_description', old('short_description'), ['class'=>'form-control editor', 'id'=>'editor']) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('description', 'Description', ['class'=>'required']) !!}
          {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'id'=>'editor2']) !!}
        </div>
      </div>
</div>
</div>
  <div id="seo_tab" class="tab-pane fade">
    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
        {!! Form::label('slug','Slug', ['class'=>'required']) !!}
        {!! Form::text('slug', old('slug'),['class'=>'form-control', 'id'=>'slug']) !!}
    </div>
  </div>
    <div class="col-md-6">
    <div class="form-group">
        {!! Form::label('meta_title','Meta Title') !!}
        {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
    </div>
    </div>
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
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
@if(!$item)
  $('#title').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif
  var media_image_type = 'software';
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

</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
