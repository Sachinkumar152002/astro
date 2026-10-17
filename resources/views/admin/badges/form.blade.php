@extends('layouts.admin')

@section('content')

<div class="clearfix"></div>

{!! Form::model($badge, ['route' => 'admin.badge.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($badge)
{{ Form::hidden('id', old('id', $badge->id)) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Badge Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('name','Badge Name', ['class'=>'required']) !!}
    {!! Form::text('name', old('name', $badge ? $badge->name : ''),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('status', 'Status') !!}
    {!! Form::select('status', $_status, old('status', $badge ? $badge->status : 1), ['class'=>'form-control nosearch']) !!}
  </div>
</div>
  {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush











