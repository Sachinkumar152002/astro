
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">

  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-12">
        <p>
          <strong><span class="text-danger">Style:</span> Bootstrap 5</strong>
        </p>
      </div>

      <div class="col-4 form-group">
        {{ Form::label('icon_id', 'Image', ['class' => 'col-sm-12 control-label']) }}
        <div class="d-flex align-items-center">
           <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('icon')">Upload Image</button>
           <div class="clearfix"></div>
          {{ Form::hidden('icon_id', old('icon_id'), ['id'=>'icon_id']) }}
          {{ Form::hidden('icon_src', '', ['id'=>'icon_src']) }}
          @if($item && $item->icon_id)
             <div class="profile_image text-center icon ml-2" data-mediaid="{{$item->icon_id}}">
               <img src="{{$item->icon ? $item->icon->image(100,100) : ''}}" alt="image" class="img-responsive">
               <i class="fa fa-trash text-danger" onclick="removeImage(this, 'icon')"></i>
             </div>
           @endif
        </div>
      </div>

        <div class="form-group col-md-4">
          {!! Form::label('title','Title', ['class'=>'required']) !!}
          {!! Form::text('title', old('title'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-2">
            @if(!empty($item->id))
                <a class="btn btn-primary text-black m-1" href="{{ route('admin.' . $nav . '.editor', $item->id) }}">Edit with Editor</a>
            @endif
        </div>

        <div class="form-group col-md-12">
          {!! Form::label('style','Style', ['class'=>'']) !!}
          {!! Form::textarea('style', old('styles'),['class'=>'form-control mceNoEditor']) !!}
        </div>
        {{-- <div class="form-group col-md-12">
          {!! Form::label('header_script','Header Script', ['class'=>'']) !!}
          {!! Form::textarea('header_script', old('header_script'),['class'=>'form-control mceNoEditor']) !!}
        </div> --}}
        {{-- <div class="form-group col-md-12">
          {!! Form::label('content','Content', ['class'=>'required']) !!}
          {!! Form::textarea('content', old('content'),['class'=>'form-control']) !!}
        </div> --}}
        {{-- <div class="form-group col-md-12">
          {!! Form::label('footer_script','Footer Script', ['class'=>'']) !!}
          {!! Form::textarea('footer_script', old('footer_script'),['class'=>'form-control mceNoEditor']) !!}
        </div> --}}
        <div class="form-group col-md-4 px-1">
          <span class="ml-2 mt-2"><strong>Status</strong></span>
          <input type="checkbox" class="toggle-check" name="status" {{$item && $item->status ? 'checked' : ''}} id="status" value="1">
          <label class="mx-2 t-lable mt-2" for="status"></label>
        </div>
        {{-- <div class="form-group col-md-4 px-1">
          <span class="ml-2 mt-2"><strong>Show in navigation</strong></span>
          <input type="checkbox" class="toggle-check" name="show_in_navigation" {{$item && $item->show_in_navigation ? 'checked' : ''}} id="show_in_navigation" value="1">
          <label class="mx-2 t-lable mt-2" for="show_in_navigation"></label>
        </div> --}}
      </div>
    </div>

    <div id="seo_tab" class="tab-pane fade">
      <div class="row">
        <div class="col-md-6 col-12">
          <div class="form-group">
            {!! Form::label('slug', 'Slug', ['class'=>'required']) !!}
            {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'id'=>'slug']) !!}
          </div>
        </div>
        <div class="col-md-6 col-12">
           <div class="form-group">
              {!! Form::label('meta_title','Meta Title') !!}
              {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
          </div>
        </div>
          <div class="col-md-12 col-12">
            <div class="form-group">
                {!! Form::label('meta_keywords','Meta Keywords') !!}
                {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
            </div>
          </div>
            <div class="col-md-12 col-12">
              <div class="form-group">
                  {!! Form::label('meta_description','Meta Description') !!}
                  {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
              </div>
            </div>
      </div>
    </div>
</div>
  {!! Form::close() !!}

  @include('admin.media.manager')

@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>

<script type="text/javascript">
var media_image_type = 'landing';
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
      $('.profile_image.'+imageset).remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image text-center '+imageset);
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
      $('#'+imageset+'_id').after(newImg);
    }
    $('#media-img-manager').modal('hide');
  });

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

<script type="text/javascript">
@if(!$item)
  $('#title').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif
</script>
@endpush
