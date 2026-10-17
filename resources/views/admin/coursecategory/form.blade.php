@extends('layouts.admin')

@section('content')

{!! Form::model($category, ['route' => ['admin.coursecategory.submit',$lang], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($category)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Category Details </h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
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
    <div class="col-md-5 col-12">
      <div class="form-group">
        {!! Form::label('name','Name', ['class'=>'required']) !!}
        {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
      </div>
    </div>
  <div class="col-md-5">
      <div class="form-group">
          {!! Form::label('parent_id','Parent Category') !!}
          {!! Form::select('parent_id',$categories,  old('parent_id'),['class'=>'form-control']) !!}
        </div>
  </div>
  <div class="col-md-2">
   <div class="form-group">
    {!! Form::label('order', 'Order', ['class'=>'required']) !!}
    {!! Form::number('order', old('order') , ['class'=>'form-control', 'min'=>'0']) !!}
  </div>
</div>
    <div class="form-group col-md-3">
      <div class="">
        {{ Form::label('icon_image_id', 'Icon', ['class' => 'control-label required']) }}
      </div>
        <div class="">
           <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('icon_image')">Browse Icon</button>
          {{ Form::hidden('icon_image_id', old('icon_image_id')) }}
          {{ Form::hidden('icon_image_src', old('icon_image_src',  $category && $category->iconImage ? $category->iconImage->image(200,200) : ''),['id'=>'image_src']) }}
          @if(old('icon_image_id', $category ? $category->icon_image_id : ''))
          <div class="icon_profile_image float-right" data-mediaid="{{old('image', $category ? $category->icon_image_id : '')}}">
            <img src="{{old('icon_image_src', $category && $category->iconImage ? $category->iconImage->image(100,100) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'icon_profile_image')"></i>
          </div>
          @endif
        </div>
    </div>
      <div class="form-group col-md-3">
        <div class="">
          {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
        </div>
          <div class="">
             <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Browse Image</button>
            {{ Form::hidden('image_id', old('image_id')) }}
            {{ Form::hidden('image_src', old('image_src',  $category && $category->image ? $category->image->image(200,200) : ''),['id'=>'image_src']) }}
            @if(old('image_id', $category ? $category->image_id : ''))
            <div class="profile_image float-right" data-mediaid="{{old('image', $category ? $category->image_id : '')}}">
              <img src="{{old('image_src', $category && $category->image ? $category->image->image(100,100) : '')}}" alt="image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeImage(this, 'profile_image')"></i>
            </div>
            @endif
          </div>
      </div>


    <div class="col-md d-flex justify-content-between mt-6">
        <div class="form-group">
             <span class="ml-2"><strong>Show in navigation</strong></span>
              <input type="checkbox" class="toggle-check" id="show_in_navigation" {{$category && $category->show_in_navigation ? 'checked' : ''}} value="1" name="show_in_navigation">
              <label class="mx-2 t-lable mt-2" for="show_in_navigation"></label>
        </div>
        <div class="form-group">
            <span class="ml-2"><strong>Featured</strong></span>
             <input type="checkbox" class="toggle-check" id="is_featured" {{$category && $category->is_featured ? 'checked' : ''}} value="1" name="is_featured">
             <label class="mx-2 t-lable mt-2" for="is_featured"></label>
        </div>
        <div class="form-group">
            <span class="ml-2"><strong>Status</strong></span>
             <input type="checkbox" class="toggle-check" id="status" {{$category && $category->status ? 'checked' : ''}} value="1" name="status">
             <label class="mx-2 t-lable mt-2" for="status"></label>
        </div>
    </div>
{{--
    <div class="col-md-3">
        <div class="form-group">
            <label>&nbsp;&nbsp;&nbsp;</label>
        </div>
        <div class="form-group">
            <input type="checkbox" class="" id="show_in_navigation" {{$category && $category->show_in_navigation ? 'checked' : ''}} value="1" name="show_in_navigation">
            <label class="mx-2" for="show_in_navigation"><strong>Show in navigation</strong></label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input type="checkbox" class="" id="is_featured" {{$category && $category->is_featured ? 'checked' : ''}} value="1" name="is_featured">
            <label class="mx-2 " for="is_featured"><strong>Featured</strong></label>
        </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
          <input type="checkbox" class="" id="status" {{$category && $category->status ? 'checked' : ''}} value="1" name="status">
          <label class="mx-2" for="status"><strong>Status</strong></label>
      </div>
    </div>
    --}}
  </div>

    </div>
    <div id="seo_tab" class="tab-pane fade">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
          {!! Form::label('slug','Url Key', ['class'=>'required']) !!}
          {!! Form::text('slug', old('slug'),['class'=>'form-control', 'id'=>'slug']) !!}
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
  </div>

{{-- <div class="form-group">
  {!! Form::label('form_type', 'From Type'); !!}
  {!! Form::select('form_type',formTypes(), old('form_type'), ['class'=>'form-control', 'required']) !!}
</div> --}}


{{--{!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}--}}
{!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  @if(!$category)
    $('#name').on('keyup', function(){
      var value = $(this).val();
      $('#slug').val(value.trim().replaceAll(' ', '-'));
    });
  @endif

    var media_image_type = 'categories';
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
      if(imageset === 'icon_image') {
        console.log('im', imageset)
        $('#icon_image_id').val(mediaId);
        $('#icon_image_src').val(imgSrc);
        $('.icon_profile_image').remove(); // Remove the old image if it exists
        newImg.find('.action').remove();
        newImg.removeClass('img-box-choice selected').addClass('icon_profile_image float-right');
        newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
        $('#icon_image_id').after(newImg);
      }
      else if (imageset === 'featured') {
        console.log('f', imageset)
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
    var removeImage = function(elm, clas){
      $(elm).parents('.'+clas).remove();
      if(clas == 'profile_image'){
        $('#image_id').val('');
        $('#image_src').val('');
      }else{
        $('#icon_image_id').val('');
        $('#icon_image_src').val('');
      }
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
@endpush
