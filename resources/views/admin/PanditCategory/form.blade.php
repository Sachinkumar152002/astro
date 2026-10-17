@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $moduleName }} Detail</h1>
  </div>
  <div class="col-md-6 text-right">
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
      <div class="row">
        <div class="form-group col-md-4">
          {!! Form::label('name','Name', ['class'=>'required']) !!}
          {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
        </div>
        @if(false)
          <div class="form-group col-md-3">
            {!! Form::label('parent_id', 'Parent') !!}
            {!! Form::select('parent_id', $categories,  old('parent_id'),['class'=>'form-control']) !!}
          </div>
        @endif
        <div class="form-group col-md-3">
          <div class="">
            <div class="">
            {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
          </div>
            <div class="d-flex align-items-center">
               <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
              {{ Form::hidden('image_id', old('image_id')) }}
              {{ Form::hidden('image_src', old('image_src',  $item && $item->image ? $item->image->image(200,200) : ''),['id'=>'image_src']) }}
              @if(old('image_id', $item ? $item->image_id : ''))
              <div class="profile_image float-right ml-2" data-mediaid="{{old('image', $item ? $item->image_id : '')}}">
                <img src="{{old('image_src', $item && $item->image ? $item->image->image(70,70) : '')}}" alt="image" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
              </div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-2">
           <div class="form-group">
            {!! Form::label('order', 'Order', ['class'=>'']) !!}
            {!! Form::number('order', old('order'), ['class'=>'form-control']) !!}
          </div>
        </div>
  <div class="col-md-12">
      <div class="row">
        {{--<div class="col-md-2">
          <div class="form-group">
              <span class="ml-2 mt-2"><strong>Show in footer</strong></span>
               <input type="checkbox" class="toggle-check" id="show_in_navigation" {{$item && $item->show_in_navigation ? 'checked' : ''}} value="1" name="show_in_navigation">
               <label class="mx-2 t-lable mt-2" for="show_in_navigation"></label>
          </div>
        </div>--}}
        <div class="col-md-2">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable mt-2" for="status"></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="seo_tab" class="tab-pane fade">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('slug', 'Slug', ['class'=>'']) !!}
        {!! Form::text('slug', old('slug'),['class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('meta_title','Meta Title') !!}
        {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-12">
      <div class="form-group">
        {!! Form::label('meta_keywords','Meta Keywords') !!}
        {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-12">
      <div class="form-group">
        {!! Form::label('meta_description','Meta Description') !!}
        {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
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
@if(!$item)
  $('#name').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif

    var media_image_type = "{{ $module }}";
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
       }
      $('#media-img-manager').modal('hide');
    });
    var removeImage = function(elm){
        $(elm).parents('.profile_image').remove();
        $('#image_id').val('');
        $('#image_src').val('');
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
      var openMediaManager = function() {
        imageset = 'featured';
        $('#media-img-manager').modal('show');
      }
</script>
@endpush
