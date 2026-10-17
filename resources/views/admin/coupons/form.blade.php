
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <!-- <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">

<div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('name','Name', ['class'=>'required']) !!}
      {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('code','Code', ['class'=>'required']) !!}
      {!! Form::text('code', old('code'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('fixed_perc', 'Fixed Or Percentage', ['class'=>'required']) !!}
      {!! Form::select('fixed_perc',[0=>'Fixed', 1=>'Percentage'], old('fixed_perc'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('amount','Amount', ['class'=>'required']) !!}
      {!! Form::number('amount', old('amount'),['class'=>'form-control', 'min'=>'0']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('minimum_amount','Minimum Amount', ['class'=>'required']) !!}
      {!! Form::number('minimum_amount', old('minimum_amount'),['class'=>'form-control', 'min'=>'0']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('from_date','From date') !!}
      {!! Form::date('from_date', old('from_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('to_date','To date') !!}
      {!! Form::date('to_date', old('to_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-12">
      {!! Form::label('description','Description', ['class'=>'required']) !!}
      {!! Form::textarea('description', old('description'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('apply_on', 'Apply On', ['class'=>'required']) !!}
      {!! Form::select('apply_on',couponApplyOn(), old('apply_on'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('apply_on', 'Apply', ['class'=>'required']) !!}
      <select class="time_select nosearch form-control" name="how_many_time">
        <option value="unlimited">Unlimited</option>
        <option value="one_time" {{$item && $item->one_time ? 'selected' : ''}}>One Time</option>
        <option value="only_one_time" {{$item && $item->only_one_time ? 'selected' : ''}}>Only One Time</option>
      </select>
    </div>
    <div class="form-group col-md-4 px-1">
      <span class="ml-2 mt-2"><strong>Status</strong></span>
      <input type="checkbox" class="toggle-check" name="status" {{$item && $item->status ? 'checked' : ''}} id="status" value="1">
      <label class="mx-2 t-lable mt-2" for="status"></label>
    </div>
  </div>
</div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
