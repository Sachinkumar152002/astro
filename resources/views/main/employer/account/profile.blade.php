@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.employer.account.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::model($user, ['route' => 'employer.account.profile.update', 'method'=>'post', 'id'=>'profile-form']) !!}
                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4> My Profile</h4>
                                <div class="bg-white">

                                    <div class="col-md-6 border-right">
                                        <h3 class="mb-30">Personal Details</h3>
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('name','Name') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('email','Email') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::email('email', old('email'),['class'=>'form-control', 'required'=>'required', 'disabled'=>'disabled']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('mobile','Mobile') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('mobile', old('mobile'),['class'=>'form-control', 'required'=>'required', 'minlength'=>10, 'maxlength'=>10]) !!}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">


                                        <h3 class="mb-30 text-center">Profile Image</h3>
                                        <div class="profile-image1 text-center">
                                            <img src="{{$user->image_id ? ($user->avatar ? $user->avatar->image(200,200) : $user->no_image) : $user->no_image}}">
                                            {{ Form::hidden('image_id', old('image_id'), ['required'=>'required', 'id'=>'image']) }}
                                        </div>
                                        <div class="change-button">
                                            <button type="button" class="btn btn-primary" onclick="$('#media-img-manager').modal('show');">Change Image</button>
                                        </div>


                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3 text-center col-md-offset-5">
                                        <div class="change-button"><button type="submit" class="btn btn-primary" id="profile-btn-submit">Submit</button></div>
                                    </div>
                                </div>
                                <!-- Split button -->
                            </div>
                        </div>
                       {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<div id="media-img-manager" tabindex="-1" role="dialog" aria-labelledby="MediaImageManager" aria-hidden="true" class="modal fade text-center">
  <div role="document" class="modal-dialog modal-media">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="MediaImageManager" class="modal-title">Upload Image</h4>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
          <div   class="dropzone" id="file-upload"></div>
          <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('js/dropzone.js')}}"> </script>
<script type="text/javascript">
var media_image_type = 'employer';
$("#profile-form").validate({
    submitHandler : function(form){
        $('#profile-btn-submit').addClass('disable').attr('disabled', 'disabled');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                toastr.success('Successfully Updated');
                setTimeout(function(){
                    window.location.reload();
                }, 3000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                $('#profile-btn-submit').removeClass('disable').removeAttr('disabled');
            });
             return false;
        }
});

 var meida_upload_url = '{{route('employer.media.upload')}}';

  var myDropzone = new Dropzone("div#file-upload",
    {
        url: meida_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: 'image/*',
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
        success: function(file,data) {

            $('.profile-image1').html('<img src="'+data['img']+'" alt="'+data['file']+'" ><input required="required" id="image" name="image_id" type="hidden" value="'+data['media_id']+'">')
              $('#progress .progress-bar').css(
                      'width',
                       '0%'
              );
             $('#media-img-manager').modal('hide');
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            formData.append("_token", window.Laravel.csrfToken);
            formData.append("type", media_image_type);
        }
    }
  );

  Dropzone.autoDiscover = false;
</script>
@endpush