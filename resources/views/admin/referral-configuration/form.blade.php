@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>

{!! Form::model($item, ['route' => 'admin.referral_configuration.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
<div class="row">
  <div class="col-md-12">
    <h1 class="text-primary float-left">App Referral Configuration</h1>
  </div>
</div>

<div class="row">
  <div class="form-group col-md-6">
    {!! Form::label('enable_app_referral','Enable App Referral', []) !!}
    {!! Form::select('enable_app_referral', [0 => 'No', 1 => 'Yes'], old('enable_app_referral', $item ? $item->enable_app_referral : 0), ['class'=>'form-control nosearch', 'id'=>'enable_app_referral']) !!}
    <small class="text-muted">Enable or disable the app referral program</small>
  </div>
</div>

<hr class="my-4">

<!-- Radio Buttons Section (shown only when Enable App Referral is Yes) -->
<div id="section-selector-wrapper" style="display: none;">
  <div class="row mb-4">
    <div class="form-group col-md-12">
      <label class="d-block mb-3">Select Section to Configure:</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="section_selector" id="section_astrologer" value="astrologer">
        <label class="form-check-label" for="section_astrologer">
          Astrologer Settings
        </label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="section_selector" id="section_customer" value="customer">
        <label class="form-check-label" for="section_customer">
          Customer Settings
        </label>
      </div>
    </div>
  </div>
</div>

<!-- Astrologer Section -->
<div id="astrologer-section" class="referral-section" style="display: none;">
  <h3 class="section-heading mb-3">Astrologer Settings</h3>

  <!-- Astrologer to Astrologer -->
  <h4 class="subsection-heading mb-3">Astrologer to Astrologer</h4>
  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_reward_amount','Reward Amount (₹)', ['class'=>'required']) !!}
      {!! Form::number('astrologer_to_astrologer_reward_amount', old('astrologer_to_astrologer_reward_amount', $item ? $item->astrologer_to_astrologer_reward_amount : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_astrologer_reward_amount']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_trigger','Reward Trigger', ['class'=>'required']) !!}
      {!! Form::select('astrologer_to_astrologer_trigger', [
        'signup' => 'On Signup',
        'account_verify' => 'Account Verify and Activate',
        'first_consultation' => 'On First Consultation',
        'min_consultation_amount' => 'Min. Consultation Amount'
      ], old('astrologer_to_astrologer_trigger', $item ? $item->astrologer_to_astrologer_trigger : 'signup'), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'astrologer_to_astrologer_trigger']) !!}
    </div>
  </div>

  <div class="row" id="astrologer_to_astrologer_min_amount_row" style="display: none;">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_min_consultation_amount','Min. Consultation Amount (₹)', []) !!}
      {!! Form::number('astrologer_to_astrologer_min_consultation_amount', old('astrologer_to_astrologer_min_consultation_amount', $item ? $item->astrologer_to_astrologer_min_consultation_amount : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_astrologer_min_consultation_amount']) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_max_referrals','Max Referrals Per User', []) !!}
      {!! Form::number('astrologer_to_astrologer_max_referrals', old('astrologer_to_astrologer_max_referrals', $item ? $item->astrologer_to_astrologer_max_referrals : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'astrologer_to_astrologer_max_referrals']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_max_earning','Max Total Earning (₹)', []) !!}
      {!! Form::number('astrologer_to_astrologer_max_earning', old('astrologer_to_astrologer_max_earning', $item ? $item->astrologer_to_astrologer_max_earning : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_astrologer_max_earning']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_expiry_days','Reward Expiry Days', []) !!}
      {!! Form::number('astrologer_to_astrologer_expiry_days', old('astrologer_to_astrologer_expiry_days', $item ? $item->astrologer_to_astrologer_expiry_days : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'astrologer_to_astrologer_expiry_days']) !!}
      <small class="text-muted">Leave empty for no expiry</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_astrologer_status', 'Status', ['class'=>'required']) !!}
      {!! Form::select('astrologer_to_astrologer_status', [1 => 'Active', 0 => 'Inactive'], old('astrologer_to_astrologer_status', $item ? $item->astrologer_to_astrologer_status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'astrologer_to_astrologer_status']) !!}
    </div>
  </div>

  <hr class="my-4">

  <!-- Astrologer to Customer -->
  <h4 class="subsection-heading mb-3">Astrologer to Customer</h4>
  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_reward_amount','Reward Amount (₹)', ['class'=>'required']) !!}
      {!! Form::number('astrologer_to_customer_reward_amount', old('astrologer_to_customer_reward_amount', $item ? $item->astrologer_to_customer_reward_amount : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_customer_reward_amount']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_trigger','Reward Trigger', ['class'=>'required']) !!}
      {!! Form::select('astrologer_to_customer_trigger', [
        'signup' => 'On Signup',
        'first_purchase' => 'First Purchase'
      ], old('astrologer_to_customer_trigger', $item ? $item->astrologer_to_customer_trigger : 'signup'), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'astrologer_to_customer_trigger']) !!}
    </div>
  </div>

  <div class="row" id="astrologer_to_customer_min_amount_row" style="display: none;">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_min_purchase_amount','Min. Purchase Amount (₹)', []) !!}
      {!! Form::number('astrologer_to_customer_min_purchase_amount', old('astrologer_to_customer_min_purchase_amount', $item ? $item->astrologer_to_customer_min_purchase_amount : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_customer_min_purchase_amount']) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_max_referrals','Max Referrals Per User', []) !!}
      {!! Form::number('astrologer_to_customer_max_referrals', old('astrologer_to_customer_max_referrals', $item ? $item->astrologer_to_customer_max_referrals : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'astrologer_to_customer_max_referrals']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_max_earning','Max Total Earning (₹)', []) !!}
      {!! Form::number('astrologer_to_customer_max_earning', old('astrologer_to_customer_max_earning', $item ? $item->astrologer_to_customer_max_earning : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_to_customer_max_earning']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_expiry_days','Reward Expiry Days', []) !!}
      {!! Form::number('astrologer_to_customer_expiry_days', old('astrologer_to_customer_expiry_days', $item ? $item->astrologer_to_customer_expiry_days : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'astrologer_to_customer_expiry_days']) !!}
      <small class="text-muted">Leave empty for no expiry</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('astrologer_to_customer_status', 'Status', ['class'=>'required']) !!}
      {!! Form::select('astrologer_to_customer_status', [1 => 'Active', 0 => 'Inactive'], old('astrologer_to_customer_status', $item ? $item->astrologer_to_customer_status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'astrologer_to_customer_status']) !!}
    </div>
  </div>
</div>

<!-- Customer Section -->
<div id="customer-section" class="referral-section" style="display: none;">
  <h3 class="section-heading mb-3">Customer Settings</h3>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('customer_reward_amount','Reward Amount (₹)', ['class'=>'required']) !!}
      {!! Form::number('customer_reward_amount', old('customer_reward_amount', $item ? $item->customer_reward_amount : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'customer_reward_amount']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('customer_trigger','Reward Trigger', ['class'=>'required']) !!}
      {!! Form::select('customer_trigger', [
        'signup' => 'On Signup',
        'first_purchase' => 'First Purchase'
      ], old('customer_trigger', $item ? $item->customer_trigger : 'signup'), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'customer_trigger']) !!}
    </div>
  </div>

  <div class="row" id="customer_min_amount_row" style="display: none;">
    <div class="form-group col-md-6">
      {!! Form::label('customer_min_purchase_amount','Min. Purchase Amount (₹)', []) !!}
      {!! Form::number('customer_min_purchase_amount', old('customer_min_purchase_amount', $item ? $item->customer_min_purchase_amount : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'customer_min_purchase_amount']) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('customer_max_referrals','Max Referrals Per User', []) !!}
      {!! Form::number('customer_max_referrals', old('customer_max_referrals', $item ? $item->customer_max_referrals : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'customer_max_referrals']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('customer_max_earning','Max Total Earning (₹)', []) !!}
      {!! Form::number('customer_max_earning', old('customer_max_earning', $item ? $item->customer_max_earning : ''), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'customer_max_earning']) !!}
      <small class="text-muted">Leave empty for unlimited</small>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      {!! Form::label('customer_expiry_days','Reward Expiry Days', []) !!}
      {!! Form::number('customer_expiry_days', old('customer_expiry_days', $item ? $item->customer_expiry_days : ''), ['class'=>'form-control', 'min'=>'0', 'id'=>'customer_expiry_days']) !!}
      <small class="text-muted">Leave empty for no expiry</small>
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('customer_status', 'Status', ['class'=>'required']) !!}
      {!! Form::select('customer_status', [1 => 'Active', 0 => 'Inactive'], old('customer_status', $item ? $item->customer_status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'customer_status']) !!}
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12 text-right">
    <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Update</button>
  </div>
</div>

{!! Form::close() !!}

@endsection

@push('styles')
<style>
  .section-heading {
    color: #007bff;
    font-weight: 600;
    font-size: 1.5rem;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
  }

  .subsection-heading {
    color: #495057;
    font-weight: 600;
    font-size: 1.25rem;
    margin-top: 20px;
  }

  .referral-section {
    margin-bottom: 30px;
  }
</style>
@endpush

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
$(document).ready(function() {
  // Toggle sections based on Enable App Referral
  function toggleAppReferralSections() {
    var enabled = $('#enable_app_referral').val() == '1';

    if(enabled) {
      $('#section-selector-wrapper').show();
      // If a radio is already selected, show that section
      var selectedSection = $('input[name="section_selector"]:checked').val();
      if(selectedSection) {
        showSelectedSection(selectedSection);
      } else {
        // By default, select Astrologer Settings
        $('#section_astrologer').prop('checked', true);
        showSelectedSection('astrologer');
      }
    } else {
      $('#section-selector-wrapper').hide();
      $('#astrologer-section').hide();
      $('#customer-section').hide();
      // Uncheck radio buttons
      $('input[name="section_selector"]').prop('checked', false);
    }
  }

  // Show selected section based on radio button
  function showSelectedSection(section) {
    if(section === 'astrologer') {
      $('#astrologer-section').show();
      $('#customer-section').hide();
    } else if(section === 'customer') {
      $('#astrologer-section').hide();
      $('#customer-section').show();
    }
  }

  // Show/hide min consultation amount field for Astrologer to Astrologer
  function toggleAstrologerToAstrologerMinAmount() {
    var trigger = $('#astrologer_to_astrologer_trigger').val();
    if(trigger === 'min_consultation_amount') {
      $('#astrologer_to_astrologer_min_amount_row').show();
      $('#astrologer_to_astrologer_min_consultation_amount').prop('required', true);
    } else {
      $('#astrologer_to_astrologer_min_amount_row').hide();
      $('#astrologer_to_astrologer_min_consultation_amount').prop('required', false);
      $('#astrologer_to_astrologer_min_consultation_amount').val('');
    }
  }

  // Show/hide min purchase amount field for Astrologer to Customer
  function toggleAstrologerToCustomerMinAmount() {
    var trigger = $('#astrologer_to_customer_trigger').val();
    if(trigger === 'first_purchase') {
      $('#astrologer_to_customer_min_amount_row').show();
      $('#astrologer_to_customer_min_purchase_amount').prop('required', true);
    } else {
      $('#astrologer_to_customer_min_amount_row').hide();
      $('#astrologer_to_customer_min_purchase_amount').prop('required', false);
      $('#astrologer_to_customer_min_purchase_amount').val('');
    }
  }

  // Show/hide min purchase amount field for Customer
  function toggleCustomerMinAmount() {
    var trigger = $('#customer_trigger').val();
    if(trigger === 'first_purchase') {
      $('#customer_min_amount_row').show();
      $('#customer_min_purchase_amount').prop('required', true);
    } else {
      $('#customer_min_amount_row').hide();
      $('#customer_min_purchase_amount').prop('required', false);
      $('#customer_min_purchase_amount').val('');
    }
  }

  // Initialize on page load
  toggleAppReferralSections();
  toggleAstrologerToAstrologerMinAmount();
  toggleAstrologerToCustomerMinAmount();
  toggleCustomerMinAmount();

  // Bind change events
  $('#enable_app_referral').on('change', toggleAppReferralSections);
  $('input[name="section_selector"]').on('change', function() {
    showSelectedSection($(this).val());
  });
  $('#astrologer_to_astrologer_trigger').on('change', toggleAstrologerToAstrologerMinAmount);
  $('#astrologer_to_customer_trigger').on('change', toggleAstrologerToCustomerMinAmount);
  $('#customer_trigger').on('change', toggleCustomerMinAmount);
});
</script>
@endpush
