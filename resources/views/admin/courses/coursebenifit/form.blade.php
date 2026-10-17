@extends('layouts.admin')


@section('content')
{!! Form::model($course_module, ['route' => ['admin.course.benifit.submit', $course_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course_module)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$course->name}} Video Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-12 mb-3">
          <div class="form-group ">
            <div class="">
              {{ Form::label('image_id', 'Image', ['class' => 'col-sm-2 control-label required']) }}
            </div>
              <div class="">
                 <button type="button" class="btn btn-sm float-left" id="select-image" onclick="openMediaManager()">Browse Image</button>
                 <hr>
                {{ Form::hidden('image_id', old('image_id')) }}
                {{ Form::hidden('image_src', old('image_src',  $course_module && $course_module->image ? $course_module->image->image(200,200) : ''),['id'=>'image_src']) }}
                @if(old('image', $course_module ? $course_module->image_id : ''))
                <div class="profile_image" data-mediaid="{{old('image', $course_module ? $course_module->image_id : '')}}">
                  <img src="{{old('image_src', $course_module && $course_module->image ? $course_module->image->image(200,200) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('title', 'Title', ['class'=>'required']) !!}
            {!! Form::text('title', old('title'), ['class' => 'form-control']) !!}
            {{-- {!! Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) !!} --}}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('description', 'Description', ['class'=>'required']) !!}
            {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
        </div>
      </div>

      <div class="form-group col-md-4 mt-4">
        <span class="ml-2"><strong>Status</strong></span>
          <input type="checkbox" class="toggle-check" id="status" {{$course_module && $course_module->status ? 'checked' : ''}} value="1" name="status">
          <label class="mx-2 t-lable" for="status"></label>
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
var media_image_type = 'courseBenifits';
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
    newImg.removeClass('img-box-choice selected').addClass('profile_image');
    newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
    $('#image_id').after(newImg)
  }
  // else if(imageset==='slide'){
  //     myVue.updateSideImage(mediaId, imgSrc.replace('200-200/', ''));
  // }
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


var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
  var _f = $(form);
  var formData = new FormData(_f[0]);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');

  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: formData,
    processData: false,
    contentType: false
  })
  .done(function( msg ) {
      toastr.success(msg.message);
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
      toastr.success("Please wait redirecting to listing page");
      setTimeout(function(){
         window.location.href=msg.redirectto;
      }, 3000);
  })
  .fail(function(errors){
    if(errors.responseJSON.errors){
      var err = errors.responseJSON.errors;
      for(i in err){
          $('#'+i).addClass('btn-outline-danger');
          toastr.error(err[i][0]);
      }
    }else if(errors.responseJSON.message){
      toastr.error(errors.responseJSON.message);
    }
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
  });
  return false;
}
</script>

@endpush
