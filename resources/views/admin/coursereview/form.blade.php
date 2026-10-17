@extends('layouts.admin')
@section('content')



{!! Form::model($review, ['route' => 'admin.course.review.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($review)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Review Detail</h1>
    <div class="clearfix"></div>
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
      <div class="col-md-4 col-12">
        <div class="form-group">
            {!! Form::label('review_by','Review By', ['class'=>'required']) !!}
            {!! Form::text('review_by', $review->user_type == 'user' ? ($review->user ? $review->user->name : '') : ($review->astrologer ? $review->astrologer->fullname : '') ,['class'=>'form-control', 'readonly'=>'readonly']) !!}
        </div>
      </div>
        <div class="col-md-4 col-12">
          <div class="form-group">
              {!! Form::label('course_id','Course') !!}
              {!! Form::text('course_id', $review->courseReviews->name ,['class'=>'form-control', 'readonly'=>'readonly' ]) !!}
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="form-group">
            {!! Form::label('rating', 'Rating', ['class'=>'required']) !!}
            {!! Form::text('rating',$review->rating, ['class'=>'form-control']) !!}
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            {!! Form::label('review', 'Review', ['class'=>'required']) !!}
            {!! Form::textarea('review', old('review'), ['class'=>'form-control mceNoEditor']) !!}
          </div>
        </div>
      <div class="form-group col-md-6">
        <span class="ml-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$review && $review->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable" for="status"></label>
      </div>
      </div>
    </div>
  <div class="text-center">
  {{-- {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!--}}
  </div>

  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
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
      console.log(errors)
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
  });

  return false;
}
</script>

@endpush
