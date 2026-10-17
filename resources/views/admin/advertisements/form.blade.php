@extends('layouts.admin')


@section('content')
{!! Form::model($advertisement, ['route' => 'admin.advertisement.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($advertisement)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Advertisement Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>

<div class="row">
  <div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title','Title', ['class'=>'required']) !!}
        {!! Form::text('title', old('title'),['class'=>'form-control']) !!}
    </div>
  </div>

  <div class="col-md-2">
    <div class="form-group">
      {!! Form::label('page', 'Page', ['class'=>'required']) !!}
      {!! Form::select('page', pages(), old('page'), ['class'=>'form-control']) !!}
    </div>
  </div>
  <div class="col-md-2">
    <div class="form-group">
      {!! Form::label('display_on', 'Display Type', ['class'=>'required']) !!}
      {!! Form::select('display_on',['page'=>'Page', 'popup'=>'Popup Modal Window'], old('display_on'), ['class'=>'form-control']) !!}
    </div>
  </div>

  <div class="col-md-3">
    {{ Form::label('image_id', 'Advertisement Image', ['class' => 'control-label required']) }}
    <div class="form-group ">

        <div class="">
           <button type="button" class="btn float-left" id="select-image"  data-toggle="modal" data-target="#media-img-manager">Upload Image</button>
          {{ Form::hidden('image_id', old('image_id'), ['required'=>'required']) }}
          {{ Form::hidden('image_src', old('image_src',  $advertisement && $advertisement->image ? $advertisement->image->image(200,200) : ''),['id'=>'image_src']) }}
          @if(old('image', $advertisement ? $advertisement->image_id : ''))
          <div class="profile_image float-right" data-mediaid="{{old('image', $advertisement ? $advertisement->image_id : '')}}">
            <img src="{{old('image_src', $advertisement && $advertisement->image ? $advertisement->image->image(80,80) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
          </div>
          @endif


        </div>
    </div>
  </div>
  </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('link','Link') !!}
            {!! Form::url('link', old('link'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('button_text','Button Text') !!}
            {!! Form::text('button_text', old('button_text'),['class'=>'form-control']) !!}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('start_date', 'Start Date') !!}
          {!! Form::date('start_date', old('start_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('end_date', 'End Date') !!}
          {!! Form::date('end_date', old('end_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('duration', 'Popup Display after (seconds)') !!}
          {!! Form::number('duration', old('duration'), ['class'=>'form-control', 'min'=> '0']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('status', 'Status') !!}
          {!! Form::select('status',$_status, old('status'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
        {!! Form::label('order', 'Order') !!}
        {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
      </div>
      </div>
    </div>
      <div class="form-group">
      {!! Form::label('content', 'Script Content') !!}
      {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => 5]) !!}

    </div>
  {!! Form::close() !!}

@include('admin.media.manager')
@endsection

@push('scripts')
<script type="text/javascript">
  var media_image_type = 'advertisement';
$('#insert-image').on('click', function(){

  if($('.img-box-choice.selected').length==0) {
      toastr.warning('Please select an image');
    return false;
  }

  $('.profile_image').remove();

  var selectedImg = $('.img-box-choice.selected');
  $('#image_id').val(selectedImg.data('mediaid'));
  $('#image_src').val($('.img-box-choice.selected img').attr('src'));
  var newImg  = selectedImg.clone()
  newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
  newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
  $('#image_id').after(newImg)
  $('#media-img-manager').modal('hide');
});
 var removeImage = function(elm){
      $(elm).parents('.profile_image').remove();
      $('#image_id').val('');
      $('#image_src').val('');
   }
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  var _f = $(form);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: _f.serialize()
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
