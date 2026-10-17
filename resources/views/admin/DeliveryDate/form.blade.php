@extends('layouts.admin')

@section('content')

{!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}

@if($item)
{{ Form::hidden('id', old('id')) }}
@endif

<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $moduleName }} Detail</h1>
  </div>
  <div class="col-md-6 text-right">
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('name','Name', ['class'=>'required']) !!}
        {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('from','From', ['class'=>'required']) !!}
        {!! Form::number('from', old('from'), ['class'=>'form-control', 'id'=>'from']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('to','To', ['class'=>'required']) !!}
        {!! Form::number('to', old('to'), ['class'=>'form-control', 'id'=>'to']) !!}
      </div>
    </div>
  </div>
</div>

{!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
