@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>

{!! Form::model($item, ['route' => 'admin.product_referral_configuration.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Product Referral Configuration</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Update</button>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('enable_product_referral','Enable Product Referral', []) !!}
    {!! Form::select('enable_product_referral', [1 => 'Yes', 0 => 'No'], old('enable_product_referral', $item ? $item->enable_product_referral : 0), ['class'=>'form-control nosearch', 'id'=>'enable_product_referral']) !!}
    <small class="text-muted">Enable or disable the product referral commission program</small>
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('applicable_user_types','Applicable User Types', []) !!}
    {!! Form::select('applicable_user_types[]', ['user' => 'Users', 'astrologer' => 'Astrologers'], old('applicable_user_types', $item ? ($item->applicable_user_types ?? []) : []), ['class'=>'form-control', 'multiple'=>'multiple', 'id'=>'applicable_user_types']) !!}
    <small class="text-muted">Select which user types can earn product commissions</small>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('commission_mode','Commission Mode', ['class'=>'required']) !!}
    {!! Form::select('commission_mode', ['PERCENTAGE' => 'Percentage', 'FIXED' => 'Fixed'], old('commission_mode', $item ? $item->commission_mode : 'PERCENTAGE'), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'commission_mode']) !!}
    <small class="text-muted">Only ONE mode can be active globally (Percentage OR Fixed)</small>
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('trigger','Trigger', []) !!}
    {!! Form::select('trigger', ['payment_success' => 'Payment Success'], old('trigger', $item ? $item->trigger : 'payment_success'), ['class'=>'form-control nosearch', 'id'=>'trigger', 'readonly'=>'readonly']) !!}
    <small class="text-muted">Commissions are triggered on payment success</small>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('credit_time','Credit Time', ['class'=>'required']) !!}
    {!! Form::select('credit_time', ['instant' => 'Instant', 'after_completion' => 'After Order Completion'], old('credit_time', $item ? $item->credit_time : 'instant'), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'credit_time']) !!}
    <small class="text-muted">When should commissions be credited to wallet</small>
  </div>
  <div class="form-group col-md-6">
    {!! Form::label('status', 'Status', ['class'=>'required']) !!}
    {!! Form::select('status', [1 => 'Active', 0 => 'Inactive'], old('status', $item ? $item->status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'status']) !!}
  </div>
</div>

<div class="alert alert-info">
  <i class="bi bi-info-circle"></i>
  <strong>Note:</strong> Commission rates are configured per category. Please manage category commissions separately.
</div>

{!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
