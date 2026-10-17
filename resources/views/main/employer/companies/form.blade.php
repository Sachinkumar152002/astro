@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.employer.account.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::model($company, ['route' => 'employer.company.save', 'method'=>'post', 'id'=>'profile-form']) !!}
                        @if($company)
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        @endif
                        <input type="hidden" name="country_id" value="101">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Company Profile</h4>
                                <div class="bg-white">

                                    <div class="col-md-6 border-right">
                                        <h3 class="mb-30">Details</h3>
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
                                                {!! Form::label('about','Description') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::textarea('about', old('about'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

    <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('industry_type','Industry Type') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('industry_type', $industries, old('industry_type'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('address','Address') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('address', old('address'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('state_id','State') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('state_id', $states, old('state_id'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('city','City') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('city', old('city'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('pincode','Pincode') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('pincode', old('pincode'),['class'=>'form-control', 'required'=>'required', 'minlength'=>6, 'maxlength'=>6]) !!}
                                            </div>
                                        </div>

                                        <h3>Contact Details</h3>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('contact_name','Name') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('contact_name', old('contact_name'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

                                         <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('contact_email','Email') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::email('contact_email', old('contact_email'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('landline','Landline') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('landline', old('landline'),['class'=>'form-control']) !!}
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


     <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('website','Website') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('website', old('website'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('facebook','Facebook') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('facebook', old('facebook'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('linkedin','Linkedin') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('linkedin', old('linkedin'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('twitter','Twitter') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('twitter', old('twitter'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">


                                        <h3 class="mb-30 text-center">Logo</h3>
                                        <div class="profile-image1 text-center">
                                            <img src="{{$company && $company->logo && $company->logoimage ? $company->logoimage->image(200,200) : asset('images/user/default.png')}}">
                                            {{ Form::hidden('logo', old('logo'), ['required'=>'required', 'id'=>'image']) }}
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
var media_image_type = 'company';
$("#profile-form").validate({
    rules: {
        pincode: {
          required: true,
          number: true,
          minlength :6,
          maxlength :6
        },
        mobile: {
          required: true,
          number: true,
          minlength :10,
          maxlength :10
        }
    },
    submitHandler : function(form){
        $('#profile-btn-submit').addClass('disable').attr('disabled', 'disabled');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                toastr.success('Successfully Updated');
                toastr.success('Redirecting to list page');
                setTimeout(function(){
                    window.location = '{{route('employer.account.companies')}}';
                }, 5000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                // console.log(errors);
                for(i in errors){
                    toastr.error(errors[i]);
                }
                // console.log(errors);
                $('#profile-btn-submit').removeClass('disable').removeAttr('disabled');
            });
             return false;
        }
});

 var media_upload_url = '{{route('employer.media.upload')}}';

  var myDropzone = new Dropzone("div#file-upload",
    {
        url: media_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: 'image/*',
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
         success: function(file,data) {

            $('.profile-image1').html('<img src="'+data['img']+'" alt="'+data['file']+'" ><input required="required" id="image" name="logo" type="hidden" value="'+data['media_id']+'">')
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