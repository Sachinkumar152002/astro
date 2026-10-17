@extends('layouts.admin')

@section('content')

<div class="clearfix"></div>

<div class="row mb-3">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Referral Settings</h1>
  </div>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#app-referral" role="tab">
      App Referral Management
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#product-referral" role="tab">
      Product / Service Referral Configuration
    </a>
  </li>
</ul>

<div class="tab-content p-3 border border-top-0 rounded-bottom bg-white">
  {{-- App Referral Management --}}
  <div class="tab-pane fade show active" id="app-referral" role="tabpanel">
    <div class="row">
      {{-- Customer Referral Settings --}}
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Customer Referral Settings</h5>
          </div>
          <div class="card-body">
            @if($canCustomerReferral)
              {!! Form::model($customerReferral, ['route' => 'admin.customer_referral.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}

              {{-- Enable app referral toggle (radio style) --}}
              <div class="row mb-3">
                <div class="col-md-12">
                  <label class="d-block font-weight-bold mb-2">Enable App Referral</label>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio"
                           id="app_referral_enable_yes"
                           name="is_enabled"
                           class="custom-control-input"
                           value="1"
                           {{ old('is_enabled', $customerReferral->is_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="app_referral_enable_yes">Enable</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio"
                           id="app_referral_enable_no"
                           name="is_enabled"
                           class="custom-control-input"
                           value="0"
                           {{ !old('is_enabled', $customerReferral->is_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="app_referral_enable_no">Disable</label>
                  </div>
                </div>
              </div>

              <div id="app-referral-fields" style="{{ old('is_enabled', $customerReferral->is_enabled ?? 0) ? '' : 'display:none;' }}">
                {{-- Row 1: Client Types + Referrer Reward Type + Referrer Reward Amount --}}
                <div class="row">
                  {{-- Client Types (multi select) --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('participant_types','Client Types', []) !!}
                    @php
                      $selectedTypes = old('participant_types', isset($customerReferral->participant_types) && $customerReferral->participant_types
                        ? explode(',', $customerReferral->participant_types)
                        : ['user']);
                    @endphp
                    {!! Form::select(
                      'participant_types[]',
                      ['user' => 'Client Users', 'astrologer' => 'Astrologer'],
                      $selectedTypes,
                      ['class'=>'form-control select2', 'multiple'=>true, 'id'=>'participant_types']
                    ) !!}
                  </div>

                  {{-- Referrer Reward Type --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('referrer_reward_type','Referrer Reward Type', ['class'=>'required']) !!}
                    {!! Form::select(
                      'referrer_reward_type',
                      ['fixed' => 'Fixed Amount'],
                      old('referrer_reward_type', $customerReferral->referrer_reward_type ?? 'fixed'),
                      ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'referrer_reward_type']
                    ) !!}
                  </div>

                  {{-- Referrer Reward Amount --}}
                  <div class="form-group col-md-4" id="referrer-amount-wrapper">
                    {!! Form::label('referrer_reward_amount','Referrer Reward Amount (₹)', ['class'=>'required']) !!}
                    {!! Form::number(
                      'referrer_reward_amount',
                      old('referrer_reward_amount', $customerReferral->referrer_reward_amount ?? $customerReferral->referrer_reward ?? 0),
                      ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'referrer_reward_amount']
                    ) !!}
                  </div>
                </div>

                {{-- Row 2: Referee toggle + Referee Amount + Reward Wallet Type --}}
                <div class="row">
                  {{-- Referee (new user) reward toggle --}}
                  <div class="form-group col-md-4 d-flex align-items-center">
                    <div class="custom-control custom-switch">
                      <input type="checkbox"
                             class="custom-control-input"
                             id="has_referee_reward"
                             name="has_referee_reward"
                             value="1"
                             {{ old('has_referee_reward', $customerReferral->has_referee_reward ?? 0) ? 'checked' : '' }}>
                      <label class="custom-control-label" for="has_referee_reward">
                        Referee (New User) Reward
                      </label>
                    </div>
                  </div>

                  {{-- Referee reward amount --}}
                  <div class="form-group col-md-4" id="referee-amount-wrapper" style="{{ old('has_referee_reward', $customerReferral->has_referee_reward ?? 0) ? '' : 'display:none;' }}">
                    {!! Form::label('referee_reward_amount','Referee Reward Amount (₹)', []) !!}
                    {!! Form::number(
                      'referee_reward_amount',
                      old('referee_reward_amount', $customerReferral->referee_reward_amount ?? $customerReferral->user_reward ?? 0),
                      ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'id'=>'referee_reward_amount']
                    ) !!}
                  </div>

                  {{-- Reward wallet type --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('reward_wallet_type','Reward Wallet Type', ['class'=>'required']) !!}
                    {!! Form::select(
                      'reward_wallet_type',
                      ['app_wallet' => 'App Wallet', 'withdraw_wallet' => 'Withdraw Wallet'],
                      old('reward_wallet_type', $customerReferral->reward_wallet_type ?? 'app_wallet'),
                      ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'reward_wallet_type']
                    ) !!}
                  </div>
                </div>

                {{-- Row 3: Reward Trigger + Max Reward/User (+no limit) + Max Referrals (+no limit) --}}
                <div class="row">
                  {{-- Reward trigger --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('reward_trigger','Reward Trigger', ['class'=>'required']) !!}
                    {!! Form::select(
                      'reward_trigger',
                      ['signup' => 'Signup', 'first_login' => 'First Login', 'first_purchase' => 'First Purchase'],
                      old('reward_trigger', $customerReferral->reward_trigger ?? 'signup'),
                      ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'reward_trigger']
                    ) !!}
                  </div>

                  {{-- Max reward per user + no limit --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('max_reward_per_user','Max Reward per User (₹)', []) !!}
                    <div class="d-flex align-items-center">
                      {!! Form::number(
                        'max_reward_per_user',
                        old('max_reward_per_user', $customerReferral->max_reward_per_user),
                        ['class'=>'form-control mr-2', 'step'=>'1', 'min'=>'0', 'id'=>'max_reward_per_user']
                      ) !!}
                      <div class="form-check mb-0">
                        <input type="checkbox"
                               class="form-check-input"
                               id="no_limit_max_reward"
                               name="no_limit_max_reward"
                               value="1"
                               {{ is_null(old('max_reward_per_user', $customerReferral->max_reward_per_user)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="no_limit_max_reward">No limit</label>
                      </div>
                    </div>
                  </div>

                  {{-- Max referrals + no limit --}}
                  <div class="form-group col-md-4">
                    {!! Form::label('max_referrals_per_user','Max Referrals Allowed', []) !!}
                    <div class="d-flex align-items-center">
                      {!! Form::number(
                        'max_referrals_per_user',
                        old('max_referrals_per_user', $customerReferral->max_referrals_per_user),
                        ['class'=>'form-control mr-2', 'step'=>'1', 'min'=>'0', 'id'=>'max_referrals_per_user']
                      ) !!}
                      <div class="form-check mb-0">
                        <input type="checkbox"
                               class="form-check-input"
                               id="no_limit_max_referrals"
                               name="no_limit_max_referrals"
                               value="1"
                               {{ is_null(old('max_referrals_per_user', $customerReferral->max_referrals_per_user)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="no_limit_max_referrals">No limit</label>
                      </div>
                    </div>
                  </div>
                </div>

              </div> {{-- #app-referral-fields --}}

              <div class="row">
                <div class="col-md-12 text-right">
                  <button type="submit" name="button" value="submit" class="btn btn-primary">
                    <i class="bi bi-floppy"></i> Update App Referral Settings
                  </button>
                </div>
              </div>

              {!! Form::close() !!}
            @else
              <p class="text-muted mb-0">You do not have permission to manage customer referral settings.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Product / Service Referral Configuration --}}
  <div class="tab-pane fade" id="product-referral" role="tabpanel">
    <div class="row">
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Astrologer Referral Settings (Product / Service)</h5>
          </div>
          <div class="card-body">
            @if($canAstrologerReferral)
              {!! Form::model($astrologerReferral, ['route' => 'admin.astrologer_referral.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
              <div class="row">
                <div class="form-group col-md-6">
                  {!! Form::label('astrologer_reward','Astrologer Reward (₹)', ['class'=>'required']) !!}
                  {!! Form::number('astrologer_reward', old('astrologer_reward', $astrologerReferral ? $astrologerReferral->astrologer_reward : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'astrologer_reward']) !!}
                  <small class="text-muted">Amount astrologer gets when their referral code is used</small>
                </div>
                <div class="form-group col-md-6">
                  {!! Form::label('user_reward','User Reward (₹)', ['class'=>'required']) !!}
                  {!! Form::number('user_reward', old('user_reward', $astrologerReferral ? $astrologerReferral->user_reward : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'user_reward']) !!}
                  <small class="text-muted">Amount user gets when using this referral code</small>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  {!! Form::label('minimum_price','Minimum Price for Apply Referral Code (₹)', ['class'=>'required']) !!}
                  {!! Form::number('minimum_price', old('minimum_price', $astrologerReferral ? ($astrologerReferral->minimum_price ?? 0) : 0), ['class'=>'form-control', 'required'=>'required', 'step'=>'0.01', 'min'=>'0', 'id'=>'minimum_price']) !!}
                  <small class="text-muted">Minimum order amount required to apply referral code</small>
                </div>
                <div class="form-group col-md-6">
                  {!! Form::label('product_discount','Product Discount (%)', []) !!}
                  {!! Form::number('product_discount', old('product_discount', $astrologerReferral ? $astrologerReferral->product_discount : 0), ['class'=>'form-control', 'step'=>'0.01', 'min'=>'0', 'max'=>'100', 'id'=>'product_discount']) !!}
                  <small class="text-muted">Discount percentage on products/services when using this referral code</small>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  {!! Form::label('status', 'Status', ['class'=>'required']) !!}
                  {!! Form::select('status', [1 => 'Active', 0 => 'Inactive'], old('status', $astrologerReferral ? $astrologerReferral->status : 1), ['class'=>'form-control nosearch', 'required'=>'required', 'id'=>'status']) !!}
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button type="submit" name="button" value="submit" class="btn btn-primary">
                    <i class="bi bi-floppy"></i> Update Product / Service Referral
                  </button>
                </div>
              </div>
              {!! Form::close() !!}
            @else
              <p class="text-muted mb-0">You do not have permission to manage astrologer referral settings.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
  (function () {
    // Enable/Disable app referral - show/hide fields
    function toggleAppReferralFields() {
      var enabled = document.querySelector('input[name="is_enabled"]:checked');
      var container = document.getElementById('app-referral-fields');
      if (enabled && enabled.value === '1') {
        container.style.display = '';
      } else {
        container.style.display = 'none';
      }
    }

    // Referee reward toggle
    function toggleRefereeAmount() {
      var checkbox = document.getElementById('has_referee_reward');
      var wrapper = document.getElementById('referee-amount-wrapper');
      if (checkbox && wrapper) {
        wrapper.style.display = checkbox.checked ? '' : 'none';
      }
    }

    // No limit checkboxes
    function bindNoLimitToggle(checkboxId, inputId) {
      var checkbox = document.getElementById(checkboxId);
      var input = document.getElementById(inputId);
      if (!checkbox || !input) return;
      function update() {
        if (checkbox.checked) {
          input.value = '';
          input.setAttribute('readonly', 'readonly');
        } else {
          input.removeAttribute('readonly');
        }
      }
      checkbox.addEventListener('change', update);
      update();
    }

    document.addEventListener('DOMContentLoaded', function () {
      toggleAppReferralFields();
      toggleRefereeAmount();
      bindNoLimitToggle('no_limit_max_reward', 'max_reward_per_user');
      bindNoLimitToggle('no_limit_max_referrals', 'max_referrals_per_user');

      var enableRadios = document.querySelectorAll('input[name="is_enabled"]');
      enableRadios.forEach(function (r) {
        r.addEventListener('change', toggleAppReferralFields);
      });

      var hasRefCheckbox = document.getElementById('has_referee_reward');
      if (hasRefCheckbox) {
        hasRefCheckbox.addEventListener('change', toggleRefereeAmount);
      }
    });
  })();
</script>
@endpush
