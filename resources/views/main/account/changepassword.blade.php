@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.account.partials.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::open(['route' => 'password.update', 'method'=>'post', 'id'=>'profile-form']) !!}
                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4> Change Password</h4>
                                <div class="bg-white">

                                    <div class="col-md-8">
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('old_password','Old Password') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::password('old_password', old('old_password'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('new_password','New Password') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::password('new_password', old('new_password'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                         <div class="form-group row">

                                            <div class="col-md-4">
                                                {!! Form::label('cnf_password','Cnf Password') !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::password('cnf_password', old('cnf_password'),['class'=>'form-control', 'required'=>'required']) !!}
                                            </div>
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

@endsection

@push('scripts')
<script type="text/javascript">
$("#profile-form").validate({
     rules: {
        cnf_password: {
          equalTo: "#new_password"
        }
    },
    submitHandler : function(form){
        $('#profile-btn-submit').addClass('disable').attr('disabled', 'disabled');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                toastr.success('Password Changed Successfully');
                setTimeout(function(){
                    window.location.reload();
                }, 3000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                console.log(errors);
                 $('#profile-btn-submit').removeClass('disable').removeAttr('disabled');
            });
             return false;
        }
});
</script>
@endpush