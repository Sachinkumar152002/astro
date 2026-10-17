@extends('layouts.app')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-tagsinput.css')}}">
@endpush

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.account.partials.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::model($user, ['route' => 'account.profile.update', 'method'=>'post', 'id'=>'profile-form']) !!}
                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4> My Profile</h4>
                                <div class="bg-white">
                                    <div class="col-md-12 mb-30">
                                        <div class="col-md-4 col-md-offset-4">

                                        <h3 class="mb-30 text-center">Profile Image <em class="text-danger">*</em></h3>

                                        <div class="profile-image1 text-center">
                                            <img src="{{auth()->user()->details ? (auth()->user()->details->avatar ? auth()->user()->details->avatar->image(200,200) : auth()->user()->no_image) : auth()->user()->no_image}}">
                                            {{ Form::hidden('details[image]', old('details[image]'), ['required'=>'required', 'id'=>'image']) }}
                                        </div>
                                        <div class="change-button">
                                            <button type="button" class="btn btn-primary" onclick="$('#media-img-manager').modal('show');">Change Image</button>
                                        </div>
                                        </div>

                                    </div>

                                    <div class="clearfix mb-30" style="height: 20px;"></div>

                                    <div class="col-md-6 border-right text-left">
                                        <h3 class="mb-30">Personal Details</h3>
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('name','Name') !!}
                                                <em class="text-danger">*</em>
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('email','Email') !!}
                                                <em class="text-danger">*</em>
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::email('email', old('email'),['class'=>'form-control', 'required'=>'required', 'disabled'=>'disabled']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('phone','Phone') !!}
                                                <em class="text-danger">*</em>
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('details[phone]', old('phone'),['class'=>'form-control', 'required'=>'required', 'minlength'=>10, 'maxlength'=>10]) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('gender','Gender') !!}
                                                <em class="text-danger">*</em>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-check">
                                                    <input type="radio" name="details[gender]" class="form-check-input" id="male" value="1" {{$user->details && $user->details->gender==1?'checked':''}} required>
                                                    {!! Form::label('male','Male') !!}
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="details[gender]" class="form-check-input" id="female" value="2" {{$user->details && $user->details->gender==2?'checked':''}} required>
                                                    {!! Form::label('female','Female') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('aadhar','Aadhar') !!}
                                                <em class="text-danger">*</em>
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::number('details[aadhar]', old('aadhar'),['class'=>'form-control', 'required'=>'required', 'minlength'=>12, 'maxlength'=>12]) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                {!! Form::label('hobbies','Hobbies') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::text('details[hobbies]', old('hobbies'),['class'=>'form-control', "id"=>"hobbies",
                                                 "data-role"=>"tagsinput"]) !!}
                                                 <small>Type next value by type comma (,)</small>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6 text-left">
                                        <h3 class="mb-30">Contact Details</h3>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('address','Address') !!} <em class="text-danger">*</em></div>
                                            <div class="col-md-8">{!! Form::textarea('details[address]', old('address'),['class'=>'form-control', 'required'=>'required', 'rows'=>'3']) !!}</div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('city','City') !!} <em class="text-danger">*</em></div>
                                            <div class="col-md-8">
                                                {!! Form::text('details[city]', old('city'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('state','State') !!} <em class="text-danger">*</em></div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[state]', $states, old('state'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('pincode','Pincode') !!} <em class="text-danger">*</em></div>
                                            <div class="col-md-8">
                                                {!! Form::text('details[pincode]', old('pincode'),['class'=>'form-control', 'required'=>'required','minlength'=>6, 'maxlength'=>6]) !!}
                                            </div>
                                        </div>

                                     </div>
                                    {{--
                                    <div class="clearfix"></div>
                                    <h3 class="mb-30">Job Profile</h3>
                                    <hr>

                                    <!-- Job Profile  -->
                                    <div class="col-md-6 border-right">
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('qualification_id','Qualification') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[qualification_id]', $qualifications, old('qualification_id'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('industry_id','Prefered job role') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[industry_id]', $industries, old('industry_id'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('skills','Major/Specialized') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[skills][]', $skills, old('skills', $user->details && $user->details->skills ? explode(',',$user->details->skills) : [] ),['class'=>'form-control', 'multiple']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('prefer_shift','Prefered Time') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[prefer_shift]', $shifts, old('prefer_shift'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>

                                         <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('experience','Experience (years)') !!}</div>
                                            <div class="col-md-8">
                                                {!! Form::number('details[experience]', old('experience'),['class'=>'form-control', 'min'=>0]) !!}

                                            </div>
                                        </div>
                                         <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('cv_video','YouTube Video (Embed URL)') !!}</div>
                                            <div class="col-md-8">
                                                {!! Form::text('details[cv_video]', old('cv_video'),['class'=>'form-control']) !!}

                                            </div>
                                        </div>
                                        {{\--
                                         <div class="form-group">
                                          <div class="col-md-4">
                                        {!! Form::hidden('details[cv_file]',old('cv_file'),['id'=>'cv_file_data']) !!}
                                        {!! Form::label('cv_file', 'CV File (doc, pdf)'); !!}
                                        </div>
                                        <div class="col-md-8">
                                            @if($cv = $user->details->cv)
                                                <a href="{{$cv}}" target="_blank">View</a>
                                            @endif
                                        </div>
                                       <div class="dropzone" id="file-uploadCV"></div>
                                          <div id="cvprogress" class="progress">
                                            <div class="progress-bar progress-bar-success"></div>
                                          </div>
                                        </div>
                                        --\}}
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('prefer_location','Prefered Location') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[prefer_location][]', $locations, old('prefer_location', $user->details && $user->details->prefer_location ? explode(',',$user->details->prefer_location) : [] ),['class'=>'form-control', 'multiple']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('expected_salary','Expected Salary per month') !!}</div>
                                            <div class="col-md-8">
                                                {!! Form::number('details[expected_salary]', old('expected_salary'),['class'=>'form-control', 'min'=>0]) !!}

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('can_speak','Language Can Speak') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[can_speak][]', $languages, old('can_speak', $user->details && $user->details->can_speake ? explode(',',$user->details->can_speake) : [] ),['class'=>'form-control', 'multiple']) !!}
                                            </div>
                                        </div>

                                         <div class="form-group row">
                                            <div class="col-md-4">{!! Form::label('can_write','Language Can Write') !!}</div>
                                            <div class="col-md-8">
                                            {!! Form::select('details[can_write][]', $languages, old('can_write', $user->details && $user->details->can_write ? explode(',',$user->details->can_write) : [] ),['class'=>'form-control', 'multiple']) !!}
                                            </div>
                                        </div>
                                         <div class="form-group">

                                              <div class="col-md-4">
                                            {!! Form::hidden('details[video]',old('video'),['id'=>'video_data']) !!}
                                            {!! Form::label('video', 'Video Resume (mp4)'); !!}
                                            </div>
                                            <div class="col-md-8">
                                                @if($user->details && $video_file = $user->details->video_file)
                                                    <a href="{{$video_file}}" target="_blank">View</a>
                                                @endif
                                            </div>
                                           <div class="dropzone" id="file-video"></div>
                                              <div id="videoprogress" class="progress">
                                                <div class="progress-bar progress-bar-success"></div>
                                              </div>
                                        </div>
                                    </div>
                                    --}}
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
<script src="{{asset('js/dropzone.js')}}"></script>
<script src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>
<script type="text/javascript">
var media_image_type = 'user';
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
                // console.log(errors);
            });
             return false;
        }
});

 var meida_upload_url = '{{route('user.media.upload')}}';

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

            $('.profile-image1').html('<img src="'+data['img']+'" alt="'+data['file']+'" ><input required="required" id="image" name="details[image]" type="hidden" value="'+data['media_id']+'">')
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
  {{--
/*
var studen_file_upload_url = '{{route('user.cv.upload')}}';
var myDropzoneVC = new Dropzone("div#file-uploadCV",
    {
        url: studen_file_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: 'application/pdf,.doc,.docx,.ppt,.pptx',
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
         success: function(file,data) {
              toastr.success('file uploaded successfully');
              $('#cv_file_data').val(JSON.stringify(data));
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#cvprogress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            formData.append("_token", window.Laravel.csrfToken);
        }
    }
  );
*/
/*
var studen_file_upload_url = '{{route('user.video.upload')}}';
var myDropzoneVC = new Dropzone("div#file-video",
    {
        url: studen_file_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: ".mp4",
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
         success: function(file,data) {
              toastr.success('file uploaded successfully');
          $('#video_data').val(JSON.stringify(data));
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#videoprogress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            formData.append("_token", window.Laravel.csrfToken);
        }
    }
  );
  */
--}}
  Dropzone.autoDiscover = false;
$('#hobbies').tagsinput({
  confirmKeys: [44],
   trimValue: true
});

</script>
@endpush