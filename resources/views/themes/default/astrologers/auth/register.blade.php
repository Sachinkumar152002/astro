@extends(themeView() . 'layout')

@section('title', 'Astrologer Sign Up | '.config("app.name"))
@section('keywords', '')
@section('description', '')

@push("style")
  <link rel="stylesheet" href="{{ asset('assets/css/auth-register-ui.css') }}?v={{ filemtime(public_path('assets/css/auth-register-ui.css')) }}">
  <style>
    .form-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 30px;
      border: 2px solid gainsboro;
      border-radius: 10px;
      background: #fff;
    }
    .register-btn {
      background: #0e4677;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
      color: #fff;
    }
    .form-control.image-upload {
     width: 100px;
     height: 100px;
     border-radius: 50%;
     padding: 0;
     background-color: #f1f1f1;
     cursor: pointer;
     display: inline-block;
   }
   .form-control.image-upload::file-selector-button {
     display: none;
   }
  .regis{
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
    margin-top: 4px;
  }
  label{
    font-weight: 500 !important;
  }
  .image-upload::-webkit-file-upload-button {
    display: none !important;
  }
  .image-upload::file-selector-button {
    display: none !important;
  }
  .form-check-input[type=radio] {
    border-radius: 50% !important;
  }
  #registerStepsTab .step-tab { font-size: 13px; color: #666; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
  #registerStepsTab .step-tab.active { background: #0e4677; color: #fff; font-weight: 600; }
  #registerStepsTab .step-tab.completed { background: #e8f5e9; color: #2e7d32; font-weight: 500; }
  #registerStepsTab .step-tab.completed .step-done-icon { display: inline-block !important; font-size: 1.15em; color: #198754 !important; }
  #registerStepsTab .step-tab .step-done-icon { display: none; }
  #registerStepsTab .step-tab.no-click { cursor: default; pointer-events: none; }
  #registerStepsTab .step-tab.allowed { cursor: pointer; pointer-events: auto; }
  .form-control.is-invalid { border-color: #dc3545; }

  /* Dropzone: profile (step 1) – preview stays inside the circle */
  #dropzone-profile {
    border: 2px dashed #0e4677;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    min-height: 120px;
    padding: 0;
    background: #f8fafc;
    position: relative;
    overflow: hidden;
  }
  #dropzone-profile .dz-message {
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    font-size: 12px;
    color: #666;
  }
  #dropzone-profile.dz-started .dz-message { display: none; }
  #dropzone-profile .dz-preview {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  #dropzone-profile .dz-preview .dz-image {
    border-radius: 50%;
    width: 100px !important;
    height: 100px !important;
    flex-shrink: 0;
  }
  #dropzone-profile .dz-preview .dz-image img {
    border-radius: 50%;
    object-fit: cover;
    width: 100px !important;
    height: 100px !important;
  }
  #dropzone-profile .dz-preview .dz-details,
  #dropzone-profile .dz-preview .dz-size { display: none; }
  #dropzone-profile .dz-preview .dz-remove {
    font-size: 10px;
    margin-top: 2px;
    position: relative;
    z-index: 2;
  }
  #dropzone-profile .dz-preview .dz-success-mark,
  #dropzone-profile .dz-preview .dz-error-mark { margin-left: -35px; margin-top: -35px; }

  /* Dropzone: documents (step 4) - image or PDF with preview */
  .dropzone-doc { border: 2px dashed #ced4da; border-radius: 8px; min-height: 140px; padding: 12px; background: #fafafa; }
  .dropzone-doc.dz-drag-hover { border-color: #0e4677; background: #f0f6fa; }
  .dropzone-doc .dz-message { margin: 24px 0; font-size: 13px; color: #666; }
  .dropzone-doc .dz-preview { margin: 8px; }
  .dropzone-doc .dz-preview .dz-image { border-radius: 6px; width: 100px; height: 100px; }
  .dropzone-doc .dz-preview .dz-image img { object-fit: cover; width: 100px; height: 100px; border-radius: 6px; }
  .dropzone-doc .dz-preview .dz-details .dz-filename span { font-size: 12px; }
  .dropzone-doc .dz-preview .dz-remove { font-size: 12px; color: #dc3545; }
  .dropzone-doc .dz-preview .dz-file-preview .dz-image { background: #e9ecef; }
  .dropzone-doc .dz-preview[data-dz-file-type="application/pdf"] .dz-image { background: #e9ecef url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/%3E%3C/svg%3E") center/50% no-repeat; }
  #dropzone-profile.is-invalid, .dropzone-doc.is-invalid { border-color: #dc3545 !important; }
  .checkbox-list.is-invalid { border-color: #dc3545 !important; }
  .otp-boxes-wrap { gap: 8px; }
  .otp-digit-box {
    width: 42px !important;
    height: 42px !important;
    max-width: 42px;
    padding: 0;
    border-radius: 50% !important;
    font-size: 18px;
    font-weight: 600;
    line-height: 42px;
  }
  .otp-digit-box:focus {
    border-color: #0e4677;
    box-shadow: 0 0 0 0.15rem rgba(14, 70, 119, 0.2);
  }
  body.astrologer-register-route { background:#fffaf4!important;color:#24143a; }
  body.astrologer-register-route .form-container { max-width:1100px;margin:28px auto 48px!important;padding:0;border:1px solid #eee3d8;border-radius:20px;background:#fff;box-shadow:0 16px 42px rgba(49,27,67,.1);overflow:hidden; }
  body.astrologer-register-route .register-btn { border:0;border-radius:10px;background:linear-gradient(120deg,#f6a20a,#e88700);color:#fff;box-shadow:0 6px 15px rgba(236,145,0,.2); }
  body.astrologer-register-route .register-btn:hover { filter:brightness(1.04);color:#fff; }
  body.astrologer-register-route #registerStepsTab { padding-top:8px;padding-bottom:8px;border-color:#eee5f0!important;background:#fffdfb; }
  body.astrologer-register-route #registerStepsTab .step-tab { min-height:44px;margin:3px;border:1px solid #eee5f0;border-radius:10px!important;color:#655a70;font-weight:600; }
  body.astrologer-register-route #registerStepsTab .step-tab.active { border-color:#4d1b6b;background:#4d1b6b;color:#fff; }
  body.astrologer-register-route #registerStepsTab .step-tab.completed { border-color:#d5eadc;background:#edf8f0;color:#34764a; }
  body.astrologer-register-route .as-register { padding:28px 32px!important; }
  body.astrologer-register-route .as-register label,body.astrologer-register-route .form-container>form label { color:#342747;font-weight:600!important; }
  body.astrologer-register-route .as-register .form-control,body.astrologer-register-route .as-register .form-select { min-height:44px;border:1px solid #e5dce9;border-radius:9px;background-color:#fff;color:#30243d; }
  body.astrologer-register-route .as-register textarea.form-control { min-height:100px; }
  body.astrologer-register-route .as-register .form-control:focus,body.astrologer-register-route .as-register .form-select:focus { border-color:#9d75b8;box-shadow:0 0 0 3px rgba(91,39,126,.1); }
  body.astrologer-register-route #dropzone-profile { border-color:#9b72b4;background:#fbf7fd; }
  body.astrologer-register-route .dropzone-doc { border-color:#d9c8e2;background:#fffdfa; }
  body.astrologer-register-route .dropzone-doc.dz-drag-hover { border-color:#9b72b4;background:#f9f3fc; }
  body.astrologer-register-route .login-left { min-height:440px;border-radius:0;background:linear-gradient(150deg,#ffe2bd,#fff2df 76%); }
  body.astrologer-register-route .login-left-inner h3 { color:#21183e;font-family:Georgia,serif; }
  body.astrologer-register-route .login-left-inner h4 { color:#56366f; }
  body.astrologer-register-route .mx-600 { max-width:930px;margin:0 auto;border:1px solid #eee3d8;border-radius:22px;background:#fff;box-shadow:0 18px 48px rgba(49,27,67,.12);overflow:hidden; }
  body.astrologer-register-route .mx-600>.row { margin:0!important;border:0!important;border-radius:22px; }
  body.astrologer-register-route .mx-600 .col-md-6.bg-white { padding:28px!important; }
  body.astrologer-register-route .mx-600 h5 { color:#21183e!important;font-family:Georgia,serif;font-size:24px;font-weight:700; }
  body.astrologer-register-route .mx-600 .login-boxx .form-control { min-height:44px;border-color:#e5dce9;border-radius:9px; }
  body.astrologer-register-route .mx-600 .main_button { min-width:160px;border:0;border-radius:10px;background:linear-gradient(120deg,#f6a20a,#e88700);color:#fff; }
  @media(max-width:767px) {
    body.astrologer-register-route .form-container { margin:18px 12px 32px!important; }
    body.astrologer-register-route .as-register { padding:20px 16px!important; }
    body.astrologer-register-route #registerStepsTab { flex-wrap:wrap; }
    body.astrologer-register-route #registerStepsTab .nav-item { flex:1 1 45%; }
    body.astrologer-register-route #registerStepsTab .step-tab { padding:9px 5px!important;font-size:11px; }
    body.astrologer-register-route .login-left { display:flex!important;min-height:0;padding:18px 14px; }
    body.astrologer-register-route .login-left-inner h4 { margin:0;font-size:13px; }
    body.astrologer-register-route .login-left-inner h3 { margin:5px 0;font-size:19px; }
    body.astrologer-register-route .guru-img { width:95px;height:95px;margin:5px auto; }
    body.astrologer-register-route .login-left-inner p { margin:0;font-size:12px; }
    body.astrologer-register-route .store-btns img { max-width:112px;height:28px;margin:6px 3px 0; }
    body.astrologer-register-route .mx-600 .col-md-6.bg-white { padding:20px 16px!important; }
    body.astrologer-register-route .otp-boxes-wrap { gap:5px!important; }
    body.astrologer-register-route .otp-digit-box { flex:1;width:auto!important;max-width:none;height:42px!important;line-height:42px; }
  }
  </style>
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.css" type="text/css" />
@endpush

@section('content')
@if($verifyUser)
<div class="container mt-5 mt-lg-0">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="form-container p-0 mt-5 pt-md-0">
        {{-- Step indicator (tabs are display-only; use Next to proceed) --}}
        <ul class="nav nav-pills nav-fill border-bottom px-2 px-md-4 mb-0" id="registerStepsTab" role="tablist">
          <li class="nav-item" role="presentation">
            <span class="nav-link rounded-0 py-3 step-tab active no-click" id="step1-tab" data-step="1"><i class="bi bi-check-circle-fill step-done-icon" aria-hidden="true"></i><span>1. Basic Information</span></span>
          </li>
          <li class="nav-item" role="presentation">
            <span class="nav-link rounded-0 py-3 step-tab no-click" id="step2-tab" data-step="2"><i class="bi bi-check-circle-fill step-done-icon" aria-hidden="true"></i><span>2. Skills</span></span>
          </li>
          <li class="nav-item" role="presentation">
            <span class="nav-link rounded-0 py-3 step-tab no-click" id="step3-tab" data-step="3"><i class="bi bi-check-circle-fill step-done-icon" aria-hidden="true"></i><span>3. Bank Details</span></span>
          </li>
          <li class="nav-item" role="presentation">
            <span class="nav-link rounded-0 py-3 step-tab no-click" id="step4-tab" data-step="4"><i class="bi bi-check-circle-fill step-done-icon" aria-hidden="true"></i><span>4. Documents</span></span>
          </li>
        </ul>

        <form class="as-register p-4" id="as-register-form">

          {{-- Step 1: Basic Information --}}
          <div class="register-step" id="step1" data-step="1">
            <div class="row g-3">
              <div class="col-12 mb-2 d-flex align-items-center">
                <div id="dropzone-profile" class="dropzone"></div>
                <div class="ms-3">
                  <p class="form-label mb-1">Profile Photo <span class="text-danger">*</span></p>
                  <p class="form-text text-muted mb-0 small">Max 2MB. Images only: JPG, PNG, WebP, GIF</p>
                  <p class="form-text text-danger mb-0 small">DOC, Excel, PDF not allowed for profile</p>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Primary Number <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center border rounded bg-light">
                  <select class="form-select border-0 bg-transparent" name="phone_code" id="phone_code" style="max-width:90px;">
                    @foreach($countries as $item)
                      <option value="{{ $item->phonecode }}">+{{ $item->phonecode }}</option>
                    @endforeach
                  </select>
                  <input type="text" class="form-control border-0 bg-transparent" placeholder="Mobile Number" name="phone" id="phone" value="{{ $phone ?? '' }}" readonly>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="{{ $step1['first_name'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="{{ $step1['last_name'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Gender <span class="text-danger">*</span></label>
                <select name="gender" class="form-select" id="gender">
                  @foreach (genders() as $index => $item)
                    <option value="{{ $index }}" {{ (isset($step1['gender']) && (string)$step1['gender'] === (string)$index) ? 'selected' : '' }}>{{ $item }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="dob" id="dob" max="{{ date('Y-m-d', strtotime('now-1day')) }}" value="{{ $step1['dob'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ $step1['email'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Qualification <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="e.g. MA, PhD" name="qualification" id="qualification" value="{{ $step1['qualification'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Experience <span class="text-danger">*</span></label>
                <select class="form-select" name="job_start_year" id="job_start_year">
                  <option value="">Select Years</option>
                  @for($i = 0; $i <= 50; $i++)
                    <option value="{{ $i }}" {{ (isset($step1['job_start_year']) && (string)$step1['job_start_year'] === (string)$i) ? 'selected' : '' }}>{{ $i }} Year(s)</option>
                  @endfor
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Working with any other app? <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 pt-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="working_with_other_app" id="other_app_yes" value="1" {{ (isset($step1['working_with_other_app']) && $step1['working_with_other_app'] == 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="other_app_yes">Yes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="working_with_other_app" id="other_app_no" value="0" {{ !isset($step1['working_with_other_app']) || $step1['working_with_other_app'] == 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="other_app_no">No</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="{{ $step1['address'] ?? '' }}">
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Area <small class="text-muted">(Optional)</small></label>
                <input type="text" class="form-control" placeholder="Area" name="area" id="area" value="{{ $step1['area'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Landmark <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Landmark" name="landmark" id="landmark" value="{{ $step1['landmark'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Country <span class="text-danger">*</span></label>
                <select class="form-select" name="country_id" id="country_id" onchange="fetchLocations('State', this, 'state_id')">
                  @foreach($countries as $item)
                    <option value="{{ $item->id }}" {{ ($item->id == 101) ? 'selected' : '' }}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">State <span class="text-danger">*</span></label>
                <select class="form-select" name="state_id" id="state_id" onchange="fetchLocations('City', this, 'city_id')">
                  <option value="">Select State</option>
                  @foreach($states as $item)
                    <option value="{{ $item->id }}" {{ (isset($step1['state_id']) && (string)$step1['state_id'] === (string)$item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">City <span class="text-danger">*</span></label>
                <select class="form-select" name="city_id" id="city_id">
                  <option value="">Select City</option>
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Pin Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Pin Code" name="pincode" id="pincode" maxlength="6" pattern="[0-9]*" value="{{ $step1['pincode'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Instagram <small class="text-muted">(Optional)</small></label>
                <input type="url" class="form-control" placeholder="https://instagram.com/..." name="instagram" id="instagram" value="{{ $step1['instagram'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Facebook <small class="text-muted">(Optional)</small></label>
                <input type="url" class="form-control" placeholder="https://facebook.com/..." name="facebook" id="facebook" value="{{ $step1['facebook'] ?? '' }}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Youtube <small class="text-muted">(Optional)</small></label>
                <input type="url" class="form-control" placeholder="https://youtube.com/..." name="youtube" id="youtube" value="{{ $step1['youtube'] ?? '' }}">
              </div>
            </div>
          </div>

          {{-- Step 2: Skills --}}
          <div class="register-step d-none" id="step2" data-step="2">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label f4 fs-14">Skills <span class="text-danger">*</span></label>
                <div id="service-ul" class="border rounded p-3 bg-light checkbox-list">
                  <div class="row g-2">
                    @foreach($services as $service)
                      <div class="col-6 col-md-4 col-lg-3">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $service->id }}" data-text="{{ $service->name }}" id="service-{{ $service->id }}" name="services[]" onchange="handleService(this)" {{ in_array($service->id, $step2['services'] ?? []) ? 'checked' : '' }}>
                          <label class="form-check-label" for="service-{{ $service->id }}">{{ $service->name }}</label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <p class="small text-muted mt-2 mb-0" id="service-summary">Select one or more skills</p>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label f4 fs-14">Language <span class="text-danger">*</span></label>
                <div id="language-ul" class="border rounded p-3 bg-light checkbox-list">
                  <div class="row g-2">
                    @foreach($languages as $language)
                      <div class="col-6 col-md-4 col-lg-3">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $language->id }}" data-text="{{ $language->name }}" id="language-{{ $language->id }}" name="languages[]" onchange="handleLanguage(this)" {{ in_array($language->id, $step2['languages'] ?? []) ? 'checked' : '' }}>
                          <label class="form-check-label" for="language-{{ $language->id }}">{{ $language->name }}</label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <p class="small text-muted mt-2 mb-0" id="language-summary">Select one or more languages</p>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label f4 fs-14">Any Healing? <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 pt-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_healing" id="healing_yes" value="1">
                    <label class="form-check-label" for="healing_yes">Yes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_healing" id="healing_no" value="0" checked>
                    <label class="form-check-label" for="healing_no">No</label>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label f4 fs-14">About yourself <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" placeholder="Write about yourself" name="about" id="about">{{ $step2['about'] ?? '' }}</textarea>
              </div>
            </div>
          </div>

          {{-- Step 3: Bank Details --}}
          <div class="register-step d-none" id="step3" data-step="3">
            <div class="row g-3">
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Account Number" name="bank_account_number" id="bank_account_number" value="{{ $step3['bank_account_number'] ?? '' }}">
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Confirm Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Confirm Account Number" name="bank_account_number_confirm" id="bank_account_number_confirm" value="{{ $step3['bank_account_number_confirm'] ?? '' }}">
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Account Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Account Holder Name" name="bank_account_name" id="bank_account_name" value="{{ $step3['bank_account_name'] ?? '' }}">
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" placeholder="e.g. YESB0DNB002" name="bank_ifsc_code" id="bank_ifsc_code" maxlength="11" autocomplete="off" value="{{ $step3['bank_ifsc_code'] ?? '' }}">
                <small class="text-muted">Enter 11-character IFSC to auto-fill bank details</small>
                <div id="ifsc-lookup-loading" class="small text-primary mt-1 d-none">Verifying IFSC...</div>
                <div id="ifsc-lookup-error" class="small text-danger mt-1 d-none"></div>
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Bank Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Fetched from IFSC" name="bank_name" id="bank_name" readonly value="{{ $step3['bank_name'] ?? '' }}">
              </div>
              <div class="col-md-3 col-12">
                <label class="form-label f4 fs-14">City <small class="text-muted">(Optional)</small></label>
                <input type="text" class="form-control" placeholder="Fetched from IFSC" name="bank_city" id="bank_city" readonly value="{{ $step3['bank_city'] ?? '' }}">
              </div>
              <div class="col-md-3 col-12">
                <label class="form-label f4 fs-14">State <small class="text-muted">(Optional)</small></label>
                <input type="text" class="form-control" placeholder="Fetched from IFSC" name="bank_state" id="bank_state" readonly value="{{ $step3['bank_state'] ?? '' }}">
              </div>
            </div>
          </div>

          {{-- Step 4: Documents --}}
          <div class="register-step d-none" id="step4" data-step="4">
            <div class="row g-3">
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Aadhaar Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="12 digit Aadhaar" name="aadhaar_number" id="aadhaar_number" maxlength="12" pattern="[0-9]*">
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">PAN Card Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" placeholder="e.g. ABCDE1234F" name="pan_card" id="pan_card" maxlength="10">
              </div>
              <div class="col-12"><hr class="my-2"><h6 class="mb-2">Upload Aadhaar (Image or PDF only)</h6></div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Aadhaar Front <span class="text-danger">*</span></label>
                <div id="dropzone-aadhaar-front" class="dropzone dropzone-doc"></div>
              </div>
              <div class="col-md-6 col-12">
                <label class="form-label f4 fs-14">Aadhaar Back <span class="text-danger">*</span></label>
                <div id="dropzone-aadhaar-back" class="dropzone dropzone-doc"></div>
              </div>
              <div class="col-12"><h6 class="mb-2">Upload PAN Card, Cancel Cheque/Passbook &amp; Astrology Certificate (Image or PDF only)</h6></div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">PAN Card <span class="text-danger">*</span></label>
                <div id="dropzone-pancard" class="dropzone dropzone-doc"></div>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Cancel Cheque / Passbook <small class="text-muted">(Optional)</small></label>
                <div id="dropzone-cancel-cheque" class="dropzone dropzone-doc"></div>
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label f4 fs-14">Astrology Certificate <small class="text-muted">(Optional)</small></label>
                <div id="dropzone-astrology-certificate" class="dropzone dropzone-doc"></div>
              </div>
              <div class="col-12">
                <label class="form-label f4 fs-14">Referral Code <small class="text-muted">(Optional)</small></label>
                <input type="text" class="form-control" placeholder="Referral Code" name="referral_code" id="referral_code" maxlength="20">
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="terms_and_conditions" name="terms_and_conditions">
                  <label class="form-check-label" for="terms_and_conditions">
                    I agree to the <a href="{{ route('pages', 'astrologer-registration-terms-and-conditions') }}" target="_blank" class="text-primary">Terms and Conditions</a>
                  </label>
                </div>
              </div>
              <div class="col-12 mt-4 pt-3 border-top">
                <button type="button" class="btn register-btn btn-lg px-4" id="btn-submit-documents" onclick="submitRegister()" style="display:none;">Submit Application</button>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <button type="button" class="btn btn-outline-secondary" id="btn-prev" style="display:none;">Previous</button>
            <div class="ms-auto">
              <button type="button" class="btn register-btn" id="btn-next">Next</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
          @else
          <div class="container mt-5 mt-lg-0 py-5">
            <div class="mx-600">
              <div class="row mx-3 mx-md-0 border-n radius-8">
                <!-- ================= LEFT DESIGN PANEL ================= -->
                <div class="col-md-6  d-md-flex login-left">
                  <div class="login-left-inner text-center">

                    <h4>Have Question about</h4>
                    <h3>Marriage, Career or Relationship?</h3>

                    <img src="/assets/img/login-banner-4.png" class="guru-img" alt="Astrologer">

                    <p class="fw-bold">Talk to India’s best Astrologers</p>

                    <div class="store-btns">
                      <img src="/assets/img/google-1.svg">
                      <img src="/assets/img/google-2.svg">
                    </div>

                  </div>
                </div>
            <div class="col-md-6 bg-white ms-0 ps-0 radius-8">
              <div class="">
                <div class="">
                    <div class="" style="margin:auto">
                      <div class="p-3 radius mb-0 w-100 pb-1">
                        <h5 class="text-black text-center mt-3">Astrologer Registration</h5>
                      </div>
                      <div class="login-boxx">
                      <div class="form-group px-3" id="phone-div">
                        <label class="form-label fw-semibold fs-14">Verify Your Mobile Number <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center border radius bg-white">
                          <div class="">
                            <select class="form-control border-0" name="phone_code" id="phone_code">
                              @foreach($countries as $key => $item)
                                <option value="{{$item->phonecode}}">+{{ $item->phonecode }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-7 p-0 m-0">
                            <input type="text" class="form-control fs-7 border-0 ps-0" placeholder="Enter Mobile Number" id="phone" name="phone" onkeypress="handleEnter(this,event,'get-otp-btn')">
                          </div>
                        </div>
                      </div>
                    </div>
                      <div class="px-3">
                        <div class="d-none" id="otp-phone-number-div">
                          <span>
                            Please check, OTP sent on <span id="otp-phone-number" class="text-black fw-bold"></span>
                          </span>
                        </div>
                        <div class="form-group mb-2 d-none" id="otp-div">
                          <label class="fw-semibold mb-1">OTP <span class="text-danger">*</span></label>
                          <div class="d-flex align-items-center gap-2 otp-boxes-wrap">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                          </div>
                        </div>
                        <div class="d-none" id="resend-otp-count-down">
                          <span>
                            Resend OTP in <span id="otp-count-down" class="text-danger fs-8 fw-bold">120</span>
                          </span>
                        </div>
                      </div>
                      <div class="my-2 text-center">
                        <button type="button" class="main_button w-40 my-3 py-2 px-5 fs-17 f6" id="get-otp-btn" onclick="getOTP()">Get Otp</button>
                        <button type="button" class="main_button w-40 mt-3 py-2 fs-6 d-none" id="submit-otp-btn" onclick="verifyOTP()">Sign Up</button>
                      </div>
                      <div class="text-center">
                      <p>Already have an account?
                      <a href="{{ route('astrologer.login') }}" class="text-primary-n fw-semibold">Sign In</a></p>
                         <p><a class="text-primary-n fw-semibold" href="{{route('home')}}">Go back to home</a></p>
                      </div>
                    </div>
              </div>
            </div>
            </div>
            </div>
            </div>
          </div>
@endif
@endsection

@push("scripts")
  @if($verifyUser)
    <script src="https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
    <script type="text/javascript">
      Dropzone.autoDiscover = false;
      var currentStep = 1;
      var totalSteps = 4;
      var dropzoneProfile, dropzoneAadhaarFront, dropzoneAadhaarBack, dropzonePancard, dropzoneCancelCheque, dropzoneAstrologyCert;
      window.savedStep1 = @json($step1 ?? []);
      window.savedStep2 = @json($step2 ?? []);
      window.savedStep3 = @json($step3 ?? []);

      function fetchLocations(table, that, elementId, callback){
        var url = "{{ route('location') }}/?table=" + table + "&relation_table_id=" + $(that).val();
        var data = '<option value="">Select ' + table + '</option>';
        $.get(url).done(function(response){
          for(var item in response){ data += '<option value="'+item+'">'+response[item]+'</option>'; }
          $('#'+elementId).html(data);
          if (typeof callback === 'function') callback();
        }).fail(function(){ $('#'+elementId).html(data); if (typeof callback === 'function') callback(); });
      }

      let languagesIdArray = [];
      let languagesNameArray = [];
      function handleLanguage(that){
        var value = $(that).val(), name = $(that).attr("data-text");
        if($(that).is(":checked")){ languagesIdArray.push(value); languagesNameArray.push(name); }
        else{
          languagesIdArray.splice(languagesIdArray.indexOf(value), 1);
          languagesNameArray.splice(languagesNameArray.indexOf(name), 1);
        }
        $("#language-summary").text(languagesNameArray.length ? "Selected: " + languagesNameArray.join(", ") : "Select one or more languages");
        if(languagesNameArray.length) $("#language-ul").removeClass("is-invalid");
      }
      let servicesIdArray = [];
      let servicesNameArray = [];
      function handleService(that){
        var value = $(that).val(), name = $(that).attr("data-text");
        if($(that).is(":checked")){ servicesIdArray.push(value); servicesNameArray.push(name); }
        else{
          servicesIdArray.splice(servicesIdArray.indexOf(value), 1);
          servicesNameArray.splice(servicesNameArray.indexOf(name), 1);
        }
        $("#service-summary").text(servicesNameArray.length ? "Selected: " + servicesNameArray.join(", ") : "Select one or more skills");
        if(servicesNameArray.length) $("#service-ul").removeClass("is-invalid");
      }

      $(function(){
        var acceptedImages = "image/jpeg,image/png,image/webp,image/gif,image/bmp";
        var acceptedImageOrPdf = "image/jpeg,image/png,image/webp,image/gif,image/bmp,application/pdf";
        var maxSizeMB = 5;

        function dropzoneErrorWithFieldName(fieldName){
          return function(file, errorMessage){
            var msg = (errorMessage && (errorMessage.indexOf("big") !== -1 || errorMessage.indexOf("size") !== -1 || errorMessage.indexOf("MiB") !== -1))
              ? fieldName + ": File size must not exceed " + maxSizeMB + " MB."
              : fieldName + ": " + (errorMessage || "Invalid file.");
            toastr.error(msg);
            if(file && this.removeFile) this.removeFile(file);
          };
        }

        dropzoneProfile = new Dropzone("#dropzone-profile", {
          url: "#",
          autoProcessQueue: false,
          maxFiles: 1,
          maxFilesize: maxSizeMB,
          acceptedFiles: acceptedImages,
          addRemoveLinks: true,
          dictDefaultMessage: "Drop photo or click",
          dictRemoveFile: "Remove",
          dictFileTooBig: "Profile Photo: File size must not exceed 5 MB.",
          dictInvalidFileType: "Only images allowed (JPG, PNG, WebP, GIF). No DOC, Excel or PDF.",
          init: function(){
            var dz = this;
            this.on("addedfile", function(file){
              if(dz.files.length > 1) dz.removeFile(dz.files[0]);
              $("#dropzone-profile").removeClass("is-invalid");
            });
            this.on("error", dropzoneErrorWithFieldName("Profile Photo"));
          }
        });

        function makeDocDropzone(selector, fieldName){
          var opts = {
            url: "#",
            autoProcessQueue: false,
            maxFiles: 1,
            maxFilesize: maxSizeMB,
            acceptedFiles: acceptedImageOrPdf,
            addRemoveLinks: true,
            dictDefaultMessage: "Drop file or click (Image or PDF)",
            dictRemoveFile: "Remove",
            dictFileTooBig: fieldName + ": File size must not exceed 5 MB.",
            dictInvalidFileType: "Only images and PDF allowed. No DOC, Excel or other formats.",
            init: function(){
              var dz = this;
              this.on("addedfile", function(file){
                if(dz.files.length > 1) dz.removeFile(dz.files[0]);
                if(file.type === "application/pdf") file.previewElement.setAttribute("data-dz-file-type", "application/pdf");
              });
              this.on("error", dropzoneErrorWithFieldName(fieldName));
            }
          };
          return new Dropzone(selector, opts);
        }
        dropzoneAadhaarFront = makeDocDropzone("#dropzone-aadhaar-front", "Aadhaar Front");
        dropzoneAadhaarBack = makeDocDropzone("#dropzone-aadhaar-back", "Aadhaar Back");
        dropzonePancard = makeDocDropzone("#dropzone-pancard", "PAN Card");
        dropzoneCancelCheque = makeDocDropzone("#dropzone-cancel-cheque", "Cancel Cheque");
        dropzoneAstrologyCert = makeDocDropzone("#dropzone-astrology-certificate", "Astrology Certificate");

        // Restore from session: sync language/service arrays from pre-checked boxes
        $("#language-ul input:checkbox:checked").each(function(){
          languagesIdArray.push($(this).val());
          languagesNameArray.push($(this).attr("data-text"));
        });
        if (languagesNameArray.length) $("#language-summary").text("Selected: " + languagesNameArray.join(", "));
        $("#service-ul input:checkbox:checked").each(function(){
          servicesIdArray.push($(this).val());
          servicesNameArray.push($(this).attr("data-text"));
        });
        if (servicesNameArray.length) $("#service-summary").text("Selected: " + servicesNameArray.join(", "));

        // Load city dropdown and select saved city when state is pre-filled
        if (window.savedStep1 && window.savedStep1.state_id) {
          var $state = $("#state_id");
          if ($state.length && $state.val() == window.savedStep1.state_id) {
            fetchLocations("City", $state[0], "city_id", function(){
              if (window.savedStep1.city_id) $("#city_id").val(window.savedStep1.city_id);
            });
          }
        }

        // Restore current step so user continues where they left off after refresh
        var initialStep = 1;
        if (window.savedStep3 && window.savedStep3.bank_account_number) initialStep = 3;
        else if (window.savedStep2 && (window.savedStep2.languages || []).length) initialStep = 2;
        else if (window.savedStep1 && window.savedStep1.first_name) initialStep = 1;
        if (initialStep > 1) goToStep(initialStep);
      });

      function goToStep(step){
        currentStep = step;
        $(".register-step").addClass("d-none");
        $("#step" + step).removeClass("d-none");
        $(".step-tab").removeClass("active completed");
        $("#step" + step + "-tab").addClass("active");
        for(var s = 1; s < step; s++){
          $("#step" + s + "-tab").addClass("completed");
        }
        if(step > 1){ $("#btn-prev").show(); } else { $("#btn-prev").hide(); }
        if(step < totalSteps){
          $("#btn-next").show();
          $("#btn-submit-documents").hide();
        } else {
          $("#btn-next").hide();
          $("#btn-submit-documents").show();
        }
      }

      function validateStep1(){
        $(".register-step#step1 .form-control, .register-step#step1 .form-select").removeClass("is-invalid");
        var err = [];
        var hasSavedProfile = window.savedStep1 && window.savedStep1.profile_image_media_id;
        if(!hasSavedProfile && (!dropzoneProfile || !dropzoneProfile.getAcceptedFiles().length)){ err.push("Profile photo is required (images only: JPG, PNG, WebP, GIF)."); $("#dropzone-profile").addClass("is-invalid"); }
        if(!$.trim($("#first_name").val())){ err.push("First name is required."); $("#first_name").addClass("is-invalid"); }
        if(!$.trim($("#last_name").val())){ err.push("Last name is required."); $("#last_name").addClass("is-invalid"); }
        if(!$.trim($("#email").val())){ err.push("Email is required."); $("#email").addClass("is-invalid"); }
        if(!$.trim($("#phone").val())){ err.push("Primary number is required."); $("#phone").addClass("is-invalid"); }
        if(!$("#gender").val() || $("#gender").val() == "0"){ err.push("Gender is required."); $("#gender").addClass("is-invalid"); }
        if(!$("#dob").val()){ err.push("Date of birth is required."); $("#dob").addClass("is-invalid"); }
        if(!$.trim($("#qualification").val())){ err.push("Qualification is required."); $("#qualification").addClass("is-invalid"); }
        var jy = $("#job_start_year").val();
        if(jy === "" || jy === null || jy === undefined){ err.push("Experience is required."); $("#job_start_year").addClass("is-invalid"); }
        if(!$("input[name='working_with_other_app']:checked").length){ err.push("Please select if you work with any other app."); }
        if(!$.trim($("#address").val())){ err.push("Address is required."); $("#address").addClass("is-invalid"); }
        if(!$.trim($("#landmark").val())){ err.push("Landmark is required."); $("#landmark").addClass("is-invalid"); }
        if(!$("#country_id").val() || $("#country_id").val() == "0"){ err.push("Country is required."); $("#country_id").addClass("is-invalid"); }
        if(!$("#state_id").val() || $("#state_id").val() == "0" || $("#state_id").val() === ""){ err.push("State is required."); $("#state_id").addClass("is-invalid"); }
        if(!$("#city_id").val() || $("#city_id").val() == "0" || $("#city_id").val() === ""){ err.push("City is required."); $("#city_id").addClass("is-invalid"); }
        if(!$.trim($("#pincode").val()) || $("#pincode").val().length !== 6){ err.push("Valid 6-digit pin code is required."); $("#pincode").addClass("is-invalid"); }
        if(err.length){ toastr.error(err[0]); return false; }
        return true;
      }
      function validateStep2(){
        $(".register-step#step2 .form-control, .register-step#step2 .form-select").removeClass("is-invalid");
        var err = [];
        if(!languagesIdArray.length){ err.push("Please select at least one language."); $("#language-ul").addClass("is-invalid"); }
        if(!servicesIdArray.length){ err.push("Please select at least one skill."); $("#service-ul").addClass("is-invalid"); }
        if(!$("input[name='any_healing']:checked").length){ err.push("Please select if you offer any healing."); }
        if(!$.trim($("#about").val()) || $("#about").val().length < 10){ err.push("About yourself is required (min 10 characters)."); $("#about").addClass("is-invalid"); }
        if(err.length){ toastr.error(err[0]); return false; }
        return true;
      }
      function validateStep3(){
        $(".register-step#step3 .form-control").removeClass("is-invalid");
        var err = [];
        if(!$.trim($("#bank_account_number").val())){ err.push("Account number is required."); $("#bank_account_number").addClass("is-invalid"); }
        if($("#bank_account_number").val() !== $("#bank_account_number_confirm").val()){ err.push("Account number and confirm must match."); $("#bank_account_number_confirm").addClass("is-invalid"); }
        if(!$.trim($("#bank_account_name").val())){ err.push("Account name is required."); $("#bank_account_name").addClass("is-invalid"); }
        if(!$.trim($("#bank_ifsc_code").val())){ err.push("IFSC code is required."); $("#bank_ifsc_code").addClass("is-invalid"); }
        if(!$.trim($("#bank_name").val())){ err.push("Please enter a valid IFSC code to fetch bank details."); $("#bank_ifsc_code").addClass("is-invalid"); }
        if(err.length){ toastr.error(err[0]); return false; }
        return true;
      }

      function fetchIfscDetails(){
        var ifsc = $.trim($("#bank_ifsc_code").val()).toUpperCase().replace(/\s/g, "");
        if(ifsc.length !== 11){
          $("#ifsc-lookup-loading").addClass("d-none");
          $("#ifsc-lookup-result").addClass("d-none").empty();
          $("#ifsc-lookup-error").addClass("d-none").text("");
          $("#bank_name, #bank_city, #bank_state").val("");
          if(ifsc.length > 0) $("#ifsc-lookup-error").removeClass("d-none").text("IFSC must be 11 characters.").show();
          return;
        }
        $("#ifsc-lookup-loading").removeClass("d-none");
        $("#ifsc-lookup-error").addClass("d-none").text("");
        var ifscUrl = "{{ url('astrologer/ifsc') }}/" + ifsc;
        $.ajax({
          url: ifscUrl,
          type: "GET",
          dataType: "json",
          success: function(data){
            $("#ifsc-lookup-loading").addClass("d-none");
            if(data && data.BANK){
              $("#bank_name").val(data.BANK || "");
              $("#bank_city").val(data.CITY || "");
              $("#bank_state").val(data.STATE || "");
              $("#bank_ifsc_code").removeClass("is-invalid");
            } else {
              $("#bank_name, #bank_city, #bank_state").val("");
              $("#ifsc-lookup-error").removeClass("d-none").text("Invalid IFSC or no details found.").show();
            }
          },
          error: function(xhr){
            $("#ifsc-lookup-loading").addClass("d-none");
            $("#bank_name, #bank_city, #bank_state").val("");
            var msg = (xhr.status === 404) ? "Invalid IFSC code. Please check and try again." : "Could not verify IFSC. Try again.";
            $("#ifsc-lookup-error").removeClass("d-none").text(msg).show();
          }
        });
      }
      $(document).on("blur", "#bank_ifsc_code", fetchIfscDetails);
      $(document).on("input", "#bank_ifsc_code", function(){
        var v = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, "");
        $(this).val(v);
        if(v.length !== 11){ $("#ifsc-lookup-error").addClass("d-none"); $("#bank_name, #bank_city, #bank_state").val(""); }
      });

      $("#btn-next").on("click", function(){
        if(currentStep === 1){
          if(!validateStep1()) return;
          var fd = new FormData();
          fd.append("_token", "{{ csrf_token() }}");
          var profileFile = dropzoneProfile.getAcceptedFiles()[0];
          if(profileFile) fd.append("profile_image", profileFile);
          fd.append("first_name", $("#first_name").val());
          fd.append("last_name", $("#last_name").val());
          fd.append("email", $("#email").val());
          fd.append("phone_code", $("#phone_code").val());
          fd.append("phone", $("#phone").val());
          fd.append("gender", $("#gender").val());
          fd.append("dob", $("#dob").val());
          fd.append("qualification", $("#qualification").val());
          fd.append("working_with_other_app", $("input[name='working_with_other_app']:checked").val());
          fd.append("job_start_year", $("#job_start_year").val());
          fd.append("address", $("#address").val());
          fd.append("area", $("#area").val());
          fd.append("landmark", $("#landmark").val());
          fd.append("country_id", $("#country_id").val());
          fd.append("state_id", $("#state_id").val());
          fd.append("city_id", $("#city_id").val());
          fd.append("pincode", $("#pincode").val());
          fd.append("instagram", $("#instagram").val());
          fd.append("facebook", $("#facebook").val());
          fd.append("youtube", $("#youtube").val());
          $("#btn-next").prop("disabled", true).text("Saving...");
          $.ajax({ url: "{{ route('astrologer.register.step1') }}", type: "POST", data: fd, processData: false, contentType: false,
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success: function(){ toastr.success("Step 1 saved."); goToStep(2); },
            error: function(xhr){ var e = xhr.responseJSON && xhr.responseJSON.errors; if(e){ for(var k in e) toastr.error(e[k][0]); } else toastr.error("Please fix the errors and try again."); },
            complete: function(){ $("#btn-next").prop("disabled", false).text("Next"); }
          });
        } else if(currentStep === 2){
          if(!validateStep2()) return;
          var payload = { _token: "{{ csrf_token() }}", languages: languagesIdArray, services: servicesIdArray, any_healing: $("input[name='any_healing']:checked").val(), about: $("#about").val() };
          $("#btn-next").prop("disabled", true).text("Saving...");
          $.ajax({ url: "{{ route('astrologer.register.step2') }}", type: "POST", data: payload,
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success: function(){ toastr.success("Step 2 saved."); goToStep(3); },
            error: function(xhr){ var e = xhr.responseJSON && xhr.responseJSON.errors; if(e){ for(var k in e) toastr.error(e[k][0]); } else toastr.error("Please fix the errors."); },
            complete: function(){ $("#btn-next").prop("disabled", false).text("Next"); }
          });
        } else if(currentStep === 3){
          if(!validateStep3()) return;
          var payload = { _token: "{{ csrf_token() }}", bank_account_number: $("#bank_account_number").val(), bank_account_number_confirm: $("#bank_account_number_confirm").val(), bank_account_name: $("#bank_account_name").val(), bank_name: $("#bank_name").val(), bank_ifsc_code: $("#bank_ifsc_code").val(), bank_city: $("#bank_city").val(), bank_state: $("#bank_state").val() };
          $("#btn-next").prop("disabled", true).text("Saving...");
          $.ajax({ url: "{{ route('astrologer.register.step3') }}", type: "POST", data: payload,
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success: function(){ toastr.success("Step 3 saved."); goToStep(4); },
            error: function(xhr){ var e = xhr.responseJSON && xhr.responseJSON.errors; if(e){ for(var k in e) toastr.error(e[k][0]); } else toastr.error("Please fix the errors."); },
            complete: function(){ $("#btn-next").prop("disabled", false).text("Next"); }
          });
        }
      });
      $("#btn-prev").on("click", function(){
        if(currentStep > 1) goToStep(currentStep - 1);
      });

      function validateStep4Documents(){
        var err = [];
        if(!dropzoneAadhaarFront.getAcceptedFiles().length) err.push("Aadhaar Front is required.");
        if(!dropzoneAadhaarBack.getAcceptedFiles().length) err.push("Aadhaar Back is required.");
        if(!dropzonePancard.getAcceptedFiles().length) err.push("PAN Card document is required.");
        // Optional documents: Cancel Cheque/Passbook and Astrology Certificate
        if(err.length){ toastr.error(err[0]); return false; }
        return true;
      }

      function submitRegister(){
        var terms = $("#terms_and_conditions").is(":checked");
        if(!terms){ toastr.error("Please accept the Terms and Conditions."); return; }
        if(!validateStep4Documents()) return;
        $("#btn-submit-documents").prop("disabled", true).text("Submitting...");
        var formData = new FormData();
        var profileFile = dropzoneProfile.getAcceptedFiles()[0];
        if(profileFile) formData.append("profile_image", profileFile);
        formData.append("first_name", $("#first_name").val());
        formData.append("last_name", $("#last_name").val());
        formData.append("email", $("#email").val());
        formData.append("phone_code", $("#phone_code").val());
        formData.append("phone", $("#phone").val());
        formData.append("gender", $("#gender").val());
        formData.append("dob", $("#dob").val());
        formData.append("qualification", $("#qualification").val());
        formData.append("working_with_other_app", $("input[name='working_with_other_app']:checked").val());
        formData.append("job_start_year", $("#job_start_year").val());
        formData.append("address", $("#address").val());
        formData.append("area", $("#area").val());
        formData.append("landmark", $("#landmark").val());
        formData.append("country_id", $("#country_id").val());
        formData.append("state_id", $("#state_id").val());
        formData.append("city_id", $("#city_id").val());
        formData.append("pincode", $("#pincode").val());
        formData.append("instagram", $("#instagram").val());
        formData.append("facebook", $("#facebook").val());
        formData.append("youtube", $("#youtube").val());
        formData.append("any_healing", $("input[name='any_healing']:checked").val());
        formData.append("about", $("#about").val());
        formData.append("bank_account_number", $("#bank_account_number").val());
        formData.append("bank_account_number_confirm", $("#bank_account_number_confirm").val());
        formData.append("bank_account_name", $("#bank_account_name").val());
        formData.append("bank_name", $("#bank_name").val());
        formData.append("bank_ifsc_code", $("#bank_ifsc_code").val());
        formData.append("bank_city", $("#bank_city").val());
        formData.append("bank_state", $("#bank_state").val());
        formData.append("aadhaar_number", $("#aadhaar_number").val());
        formData.append("pan_card", $("#pan_card").val().toUpperCase());
        if(dropzoneAadhaarFront.getAcceptedFiles()[0]) formData.append("aadhaar_front", dropzoneAadhaarFront.getAcceptedFiles()[0]);
        if(dropzoneAadhaarBack.getAcceptedFiles()[0]) formData.append("aadhaar_back", dropzoneAadhaarBack.getAcceptedFiles()[0]);
        if(dropzonePancard.getAcceptedFiles()[0]) formData.append("pancard", dropzonePancard.getAcceptedFiles()[0]);
        if(dropzoneCancelCheque.getAcceptedFiles()[0]) formData.append("cancel_cheque", dropzoneCancelCheque.getAcceptedFiles()[0]);
        if(dropzoneAstrologyCert.getAcceptedFiles()[0]) formData.append("astrology_certificate", dropzoneAstrologyCert.getAcceptedFiles()[0]);
        formData.append("referral_code", $("#referral_code").val());
        formData.append("terms_and_conditions", terms ? "1" : "0");
        formData.append("has_website", "0");
        formData.append("website_url", "");
        formData.append("associate_with_temple", "0");
        formData.append("temple_name", "");
        languagesIdArray.forEach(function(id){ formData.append("languages[]", id); });
        servicesIdArray.forEach(function(id){ formData.append("services[]", id); });

        $.ajax({
          type: "POST",
          url: "{{ route('astrologer.register') }}",
          data: formData,
          processData: false,
          contentType: false,
          headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          success: function(response) {
            toastr.success(response.message || 'Registered successfully.');
            $("#get-otp-btn, #btn-submit-documents").text("Submitted").prop("disabled", true);
            if(response.redirectTo) setTimeout(function(){ window.location.href = response.redirectTo; }, 1500);
          },
          error: function(xhr){
            $("#btn-submit-documents").prop("disabled", false).text("Submit Application");
            var errors = xhr.responseJSON && xhr.responseJSON.errors;
            if(errors) for(var k in errors) toastr.error(errors[k][0]);
            else toastr.error(xhr.responseJSON && xhr.responseJSON.message || "Something went wrong.");
          }
        });
      }
    </script>
  @else
    <script>
      var resendOtpInterval;
      function bindOtpBoxes(){
        const otpBoxes = document.querySelectorAll('.otp-digit-box');
        if(!otpBoxes || !otpBoxes.length) return;

        otpBoxes.forEach(function(box, idx){
          box.addEventListener('input', function(){
            this.value = (this.value || '').replace(/\D/g, '').slice(0, 1);
            if(this.value && idx < otpBoxes.length - 1){
              otpBoxes[idx + 1].focus();
            }
          });

          box.addEventListener('keydown', function(e){
            if(e.key === 'Backspace' && !this.value && idx > 0){
              otpBoxes[idx - 1].focus();
            }
            if(e.key === 'Enter'){
              e.preventDefault();
              verifyOTP();
            }
          });

          box.addEventListener('paste', function(e){
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            if(!pasted) return;
            e.preventDefault();
            pasted.split('').forEach(function(ch, i){
              if(otpBoxes[i]) otpBoxes[i].value = ch;
            });
            const next = Math.min(pasted.length, otpBoxes.length - 1);
            otpBoxes[next].focus();
          });
        });
      }

      function clearOtpBoxes(){
        const otpBoxes = document.querySelectorAll('.otp-digit-box');
        otpBoxes.forEach(function(box){ box.value = ''; });
      }

      function getOtpFromBoxes(){
        const otpBoxes = document.querySelectorAll('.otp-digit-box');
        let otp = '';
        otpBoxes.forEach(function(box){ otp += (box.value || '').trim(); });
        return otp;
      }

      bindOtpBoxes();
      function getOTP(){
        $("#get-otp-btn").prop("disabled", true);
        let phone = $('#phone').val();
        $.ajax({
            type: "POST",
            url: @json(route('verifyusergetphoneotp')),
            data:{
              name:$('#name').val(),
              email:$('#email').val(),
              phone_code:$('#phone_code').val(),
              phone:phone,
            },
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response) {
              toastr.success(response.message);
              $('#phone-div').addClass('d-none');
              $('#get-otp-btn').addClass('d-none');
              $('#otp-phone-number-div').removeClass('d-none');
              $('#otp-phone-number').text(phone);
              $('#otp-div').removeClass('d-none');
              $('#submit-otp-btn').removeClass('d-none');
              clearOtpBoxes();
              const firstOtpBox = document.querySelector('.otp-digit-box');
              if(firstOtpBox) firstOtpBox.focus();
              resendOtpIn = 120;
              resendOtpInterval = setInterval(function (){
                if(resendOtpIn == 0){
                  $("#get-otp-btn").prop("disabled", false).removeClass('d-none');
                  $('#resend-otp-count-down').addClass('d-none');
                  clearInterval(resendOtpInterval);
                }else{
                  resendOtpIn--;
                  $('#otp-count-down').text(resendOtpIn);
                }
              }, 1000);
              $('#resend-otp-count-down').removeClass('d-none');
            },
            error: function(error){
              $("#get-otp-btn").prop("disabled", false);
              let errors = error.responseJSON.errors;
              if(errors){
                for(var key in errors){
                  toastr.error(errors[key][0]);
                }
              }
              console.log(error)
            }
        });
      }

      function verifyOTP(){
        const otpValue = getOtpFromBoxes();
        if(otpValue.length !== 6){
          toastr.error('Please enter 6 digit OTP.');
          return;
        }
        $.ajax({
            type: "POST",
            url: @json(route('verifyuserphoneotp')),
            data:{
              phone:$('#phone').val(),
              otp: otpValue,
            },
            headers: {
                'X-CSRF-TOKEN': @json(csrf_token())
            },
            success: function(response) {
              toastr.success(response.message);
              setTimeout(function(){
                window.location.href = response.redirectTo;
              }, 2000);
            },
            error: function(error){
              let errors = error.responseJSON.errors;
              if(errors){
                for(var key in errors){
                  toastr.error(errors[key][0]);
                }
              }
              console.log(error)
            }
        });
      }
    </script>
  @endif
@endpush
