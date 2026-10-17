@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>

{!! Form::model($item, ['route' => 'admin.category_commissions.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $item && $item->id ? 'Edit' : 'Add' }} Category Commission</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <a href="{{route('admin.category_commissions.index')}}" class="btn btn-secondary mr-2"><i class="bi bi-arrow-left"></i> Back</a>
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> {{ $item && $item->id ? 'Update' : 'Save' }}</button>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('category_id','Category', ['class'=>'required']) !!}
    {!! Form::select('category_id', $categories ?? [], old('category_id', $item ? $item->category_id : ''), ['class'=>'form-control', 'required'=>'required', 'id'=>'category_id']) !!}
    <small class="text-muted">Select the product category</small>
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('commission_value','Commission Value', ['class'=>'required']) !!}
    {!! Form::number('commission_value', old('commission_value', $item ? $item->commission_value : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'commission_value']) !!}
    <small class="text-muted">Percentage or Fixed amount (based on global commission mode)</small>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('max_cap','Max Cap (₹)', []) !!}
    {!! Form::number('max_cap', old('max_cap', $item ? $item->max_cap : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'max_cap']) !!}
    <small class="text-muted">Maximum commission cap for this category (leave empty for no limit)</small>
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('status', 'Status', ['class'=>'required']) !!}
    {!! Form::select('status', [1 => 'Active', 0 => 'Inactive'], old('status', $item ? $item->status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'status']) !!}
  </div>
</div>

<div class="alert alert-info">
  <i class="bi bi-info-circle"></i>
  <strong>Note:</strong> Commission calculation: If mode is PERCENTAGE, commission = order_amount × (value/100). If mode is FIXED, commission = value. If commission > max_cap, commission = max_cap.
</div>

{!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
