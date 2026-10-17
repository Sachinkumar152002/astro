@extends('layouts.admin')

@section('content')
<h1 class="text-primary float-left">Route Details</h1>
<div class="clearfix"></div>
{!! Form::model($templateMeta, ['route' => ['admin.meta.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($templateMeta)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="clearfix"></div>
<ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
  <div class="row">
    <div class="col-md-12">
    <h3>USE Variable :  </h3>
    <ul>
      <li>%%NAME%% <small>Change  page name  with %%NAME%% </small></li>
      <li>%%PHONE%% <small>Change phone  with %%PHONE%% </small></li>
      <li>%%ADDRESS%% <small>Change address  with %%ADDRESS%% </small></li>
      <li>%%CITY%% <small>Change city name with %%CITY%% </small></li>
      <li>%%STATE%% <small>Change state name with %%STATE%% </small></li>
      <li>%%COUNTRY%% <small>Change country name with %%COUNTRY%% </small></li>
      <li>%%ZIPCODE%% <small>Change zipcode name with %%ZIPCODE%% </small></li>
      <li>%%CURRENT_URL%% <small>Change CURRENT_URL with %%CURRENT_URL%% </small></li>
      <li>%%PARENT_URL%% <small>Change PARENT_URL with %%PARENT_URL%% </small></li>
      <li>%%PARENT_NAME%% <small>Change parent page name with %%PARENT_NAME%% </small></li>
      <li>%%COUNT_DESTINATIONS%% <small>Change total_destinations count with %%COUNT_DESTINATIONS%% </small></li>
      <li>%%WEBSITE%% <small>Change website url of  with %%WEBSITE%% </small></li>
      <li>%%IATA_CODE%% <small>Change iata code with %%IATA_CODE%% </small></li>
      <li>%%TYPE%% <small>Change page type with %%TYPE%% </small></li>
      <li>%%TIME_ZONE%% <small>Change page timezone with %%TIME_ZONE%% </small></li>
      <li>%%LON%% <small>Change page timezone with %%LON%% </small></li>
    </ul>
      <div class="form-group">
        {!! Form::label('overwrite', 'overwrite') !!}
        {!! Form::select('overwrite[]',pageTemplates(), old('overwrite', $templateMeta ? json_decode($templateMeta->overwrite) : []), ['class'=>'form-control', 'multiple']) !!}
       </div>
      <div class="form-group">
        {!! Form::label('meta_title','Meta Title') !!}
        {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('meta_keywords','Meta Keywords') !!}
        {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('meta_description','Meta Description') !!}
        {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('status', 'Status') !!}
      {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control']) !!}
  </div>
    </div>
  </div>
    </div>
  </div>
{!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
{!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
