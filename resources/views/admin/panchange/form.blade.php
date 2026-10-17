
@extends('layouts.admin')
@section('content')

{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
    <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
    <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
    <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>

<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-4 col-12">
        <div class="form-group">
          {!! Form::label('date', 'Date', ['class' => 'required']) !!}
          {!! Form::date('date', old('date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('english', 'English') !!}
          {!! Form::textarea('english', old('english'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('hindi', 'Hindi') !!}
          {!! Form::textarea('hindi', old('hindi'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
          <span class="ml-2"><strong>Status</strong></span>
          <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
          <label class="mx-2 t-lable" for="status"></label>
        </div>
      </div>
    </div>
  </div>
</div>
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
