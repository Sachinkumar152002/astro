
 <style>
.password-wrapper {
           position: relative;
           display: inline-block;
           width: 100%;
       }
       .password-wrapper input {
           width: 100%;
           padding-right: 40px;
       }
       .password-wrapper .toggle-password {
           position: absolute;
           right: 10px;
           top: 50%;
           transform: translateY(-50%);
           cursor: pointer;
       }
   </style>
@extends('layouts.admin')

@section('content')
{!! Form::model($user, ['route' => 'admin.user.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($user)
{{ Form::hidden('id', old('id')) }}
@endif

<!-- BEGIN TABLE HOVER -->
<section>
<div class="section-body contain-lg">
    <!-- BEGIN INTRO -->
         <div class="row">
      <div class="col-md-6">
        <h1 class="text-primary float-left">{{$user?'Edit':'Add'}} Admin</h1>
      <div class="clearfix"></div>
      </div>
      <div class="col-md-6 text-right mt-2">
         <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>


      </div>
    </div>

    <!-- END INTRO -->
             <div class="col-lg-12 ">
            <!-- BEGIN HORIZONTAL FORM - BASIC ELEMENTS-->
    <div class="card">
    <div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::model($user, ['route'=>'admin.user.submit', 'method'=>'post', 'class'=>'form-horizontal']) !!}
                <div class="row">
    <div class="col-md-4">
    <div class="form-group">
    {!!  Form::label('name', 'Name', ['class'=>'control-label']) !!}
    {!! Form::text('name',old('name'),['class'=>'form-control'] ) !!}
    </div>
    </div>

    <div class="col-md-4">
    <div class="form-group">
    {!!  Form::label('email', 'Email', ['class'=>'control-label']) !!}
    {!! Form::email('email',old('email'),['class'=>'form-control '] ) !!}
    </div>
    </div>
                  <div class="col-md-4">
                   <div class="form-group">
                     {!!  Form::label('password', 'Password', ['class'=>'control-label']) !!}
                     <div class="password-wrapper">
                         <input type="password" name="password" id="password" class="form-control">
                         <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                     </div>
                     </div>
               </div>
                    <div class="col-md-4">
    <div class="form-group">
    {!!  Form::label('role_id', 'Role', ['class'=>'control-label']) !!}
    {!! Form::select('role_id', $roles, old('role_id'), ['class'=>'form-control']) !!}
    </div>
    </div>


    <div class="col-md-4">
    <div class="form-group">
    {!!  Form::label('status', 'Status', ['class'=>'control-label']) !!}
    {!! Form::select('status', statusArray(), old('status'), ['class'=>'form-control']) !!}
    </div>
    </div>


                  <!-- <div class="col-2">
                    <button type="button" class="btn btn-primary" onclick="togglePassword(this)">Show Password</button>
                  </div> -->
    </div>
    <div class="form-group">

    <div class="col-sm-3 col-sm-offset-2 ">
    @if($user)
    {!! Form::hidden('user_id', $user->id) !!}
    @endif
    </div>
    </div>
    {!! Form::close() !!}
    </div><!--end .card-body -->
    </div><!--end .card -->
    <!-- END HORIZONTAL FORM - BASIC ELEMENTS-->

    </div><!--end .col -->
    </div><!--end .row -->
    <!-- END BASIC ELEMENTS -->
    </div><!--end .section-body -->
    </section>

    <!-- END CONTENT -->
        {!! Form::close() !!}
@endsection
@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector(".toggle-password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
@endpush
