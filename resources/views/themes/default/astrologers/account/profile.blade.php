@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@push('style')
<style>
  .document-thumb-wrap { margin-top: 8px; }
  .document-thumb-wrap a { display: inline-block; text-decoration: none; }
  .document-thumb {
    width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6;
    background: #f8f9fa; display: flex; align-items: center; justify-content: center;
  }
  .document-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .document-thumb-pdf {
    background: #e9ecef url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='0 0 16 16'%3E%3Cpath d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/%3E%3C/svg%3E") center/55% no-repeat;
  }
  .document-thumb-pdf:hover { opacity: 0.9; }
  .document-thumb-label { font-size: 11px; color: #6c757d; margin-top: 2px; }
</style>
@endpush

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
          <div class="tab-scroll-wrapper ps-2">
            <!-- tab start -->
            <ul class="nav dashboard_tab nav-tabs dash-tab astro-account-tab" id="myTab" role="tablist">
              @php
                $redictToUrl=route('astrologer.profile', ['tab' => 'my-profile']);
                if($_token){
                  $redictToUrl=route('astrologer.profile', ['tab' => 'my-profile',"_token"=>$_token]);
                }
              @endphp
              <li class="nav-item" role="presentation">
                <a class="nav-link text-dark px-2 {{ $tab == 'my-profile' ? 'active' : '' }}" id="my-profile-tab" href="{{ $redictToUrl }}">
                  My Profile
                </a>
              </li>
              @php
                $redictToUrl=route('astrologer.profile', ['tab' => 'address-details']);
                if($_token){
                  $redictToUrl=route('astrologer.profile', ['tab' => 'address-details',"_token"=>$_token]);
                }
              @endphp
              <li class="nav-item" role="presentation">
                <a class="nav-link text-dark px-2 {{ $tab == 'address-details' ? 'active' : '' }}" id="address-details-tab" href="{{ $redictToUrl }}">
                  Address
                </a>
              </li>
              @php
                $redictToUrl=route('astrologer.profile', ['tab' => 'languages']);
                if($_token){
                  $redictToUrl=route('astrologer.profile', ['tab' => 'languages',"_token"=>$_token]);
                }
              @endphp
              <li class="nav-item" role="presentation">
                <a class="nav-link text-dark px-2 {{ $tab == 'languages' ? 'active' : '' }}" id="languages-tab" href="{{ $redictToUrl }}">
                  Languages
                </a>
              </li>
              @php
                $redictToUrl=route('astrologer.profile', ['tab' => 'skills']);
                if($_token){
                  $redictToUrl=route('astrologer.profile', ['tab' => 'skills',"_token"=>$_token]);
                }
              @endphp
              <li class="nav-item" role="presentation">
                <a class="nav-link text-dark px-2 {{ $tab == 'skills' ? 'active' : '' }}" id="skills-tab" href="{{ $redictToUrl }}">
                  Skills
                </a>
              </li>
              @php
                $redictToUrl=route('astrologer.profile', ['tab' => 'prices']);
                if($_token){
                  $redictToUrl=route('astrologer.profile', ['tab' => 'prices',"_token"=>$_token]);
                }
              @endphp
              @if($astrologer->is_verified==1)
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark px-2 {{ $tab == 'prices' ? 'active' : '' }}" id="prices-tab" href="{{ $redictToUrl }}">
                    Prices
                  </a>
                </li>
                @php
                  $redictToUrl=route('astrologer.profile', ['tab' => 'bank-details']);
                  if($_token){
                    $redictToUrl=route('astrologer.profile', ['tab' => 'bank-details',"_token"=>$_token]);
                  }
                @endphp
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark px-2 {{ $tab == 'bank-details' ? 'active' : '' }}" id="bank-details-tab" href="{{ $redictToUrl }}">
                    Bank
                  </a>
                </li>
                @php
                  $redictToUrl=route('astrologer.profile', ['tab' => 'documents']);
                  if($_token){
                    $redictToUrl=route('astrologer.profile', ['tab' => 'documents',"_token"=>$_token]);
                  }
                @endphp
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark px-2 {{ $tab == 'documents' ? 'active' : '' }}" id="documents-tab" href="{{ $redictToUrl }}">
                    Documents
                  </a>
                </li>
                @php
                  $redictToUrl=route('astrologer.profile', ['tab' => 'change-phone-number']);
                  if($_token){
                    $redictToUrl=route('astrologer.profile', ['tab' => 'change-phone-number',"_token"=>$_token]);
                  }
                @endphp
                {{--<li class="nav-item" role="presentation">
                  <a class="nav-link text-dark px-2 {{ $tab == 'change-phone-number' ? 'active' : '' }}" id="change-phone-number-tab" href="{{  $redictToUrl }}">
                    Phone Number
                  </a>
                </li>--}}
                @php
                  $redictToUrl=route('astrologer.profile', ['tab' => 'change-email-address']);
                  if($_token){
                    $redictToUrl=route('astrologer.profile', ['tab' => 'change-email-address',"_token"=>$_token]);
                  }
                @endphp
                {{--<li class="nav-item" role="presentation">
                  <a class="nav-link text-dark px-2 {{ $tab == 'change-email-address' ? 'active' : '' }}" id="change-email-address-tab" href="{{  $redictToUrl }}">
                    Email Address
                  </a>
                </li>--}}
              @endif
            </ul>
            <!-- tab ends -->
          </div>
          <div class="tab-content mt-4" id="myTabContent">
            <!-- my profile tab content start (aligned with registration) -->
            <div class="tab-pane {{ $tab == 'my-profile' ? 'active' : '' }}" id="my-profile">
              <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" enctype="multipart/form-data" onsubmit="return validateAndSubmit(this)">
                <input type="hidden" name="tab" value="my-profile">
                <input type="hidden" name="_token" value="{{ $_token }}">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card mb-3 bg-white">
                      <div class="card-body bg-white border-n radius">
                        <h4 class="mb-md-4 mb-2 dash-heading">My Profile</h4>
                        <div class="row mb-3">
                          @php $astrologerImage = $astrologer->image; @endphp
                          <div class="col-md-3 col-12 mb-3">
                            <label>Profile Photo</label>
                            <img id="image_src" src="{{ $astrologerImage ? $astrologerImage->image(100,100) : no_image() }}" width="100" height="100" onclick="handleChooseImage('image_id')" class="img-responsive border border-1 pointer" style="border-radius:50%;">
                            <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" onchange="showTempProfileImage('image_id', 'image_src')" class="form-control d-none" id="image_id" name="image">
                            <small class="text-muted">Max 5MB. JPG, PNG, WebP, GIF</small>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Primary Number <em class="text-danger">*</em></label>
                            <input type="text" class="form-control bg-light" value="{{ $astrologer->phone }}" readonly>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>First Name <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{ $astrologer->firstname }}" id="first_name">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Last Name <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ $astrologer->lastname }}" id="last_name">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Email <em class="text-danger">*</em></label>
                            <input type="email" class="form-control bg-light" value="{{ $astrologer->email }}" readonly>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Gender <em class="text-danger">*</em></label>
                            <select name="gender" class="form-select" id="gender">
                              @foreach (genders() as $gid=>$gname)
                                <option value="{{ $gid }}" {{ $astrologer->gender == $gid ? 'selected' : '' }}>{{ $gname }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Date of Birth <em class="text-danger">*</em></label>
                            <input type="date" max="{{ date('Y-m-d') }}" id="birth_date" class="form-control" name="birth_date" value="{{ $astrologer->dob && $astrologer->dob != '1970-01-01' ? $astrologer->dob : '' }}">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Qualification <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="e.g. MA, PhD" name="qualification" value="{{ $astrologer->qualification }}" id="qualification">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Experience <em class="text-danger">*</em></label>
                            <select name="job_start_year" class="form-select" id="job_start_year">
                              <option value="">Select Years</option>
                              @php
                                $currentYear = (int)date('Y');
                                $experienceYears = $astrologer->job_start_year ? ($currentYear - (int)$astrologer->job_start_year) : '';
                                if ($experienceYears < 0) $experienceYears = 0;
                                if ($experienceYears > 50) $experienceYears = 50;
                              @endphp
                              @for($i = 0; $i <= 50; $i++)
                                <option value="{{ $i }}" {{ (string)$experienceYears === (string)$i ? 'selected' : '' }}>{{ $i }} Year(s)</option>
                              @endfor
                            </select>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Working with any other app? <em class="text-danger">*</em></label>
                            <div class="d-flex gap-3 pt-2">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="working_with_other_app" id="other_app_yes" value="1" {{ $astrologer->working_with_other_app == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="other_app_yes">Yes</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="working_with_other_app" id="other_app_no" value="0" {{ $astrologer->working_with_other_app != 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="other_app_no">No</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Instagram <small class="text-muted">(Optional)</small></label>
                            <input type="url" class="form-control" placeholder="https://instagram.com/..." name="instagram" value="{{ $astrologer->instagram }}">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Facebook <small class="text-muted">(Optional)</small></label>
                            <input type="url" class="form-control" placeholder="https://facebook.com/..." name="facebook" value="{{ $astrologer->facebook }}">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <label>Youtube <small class="text-muted">(Optional)</small></label>
                            <input type="url" class="form-control" placeholder="https://youtube.com/..." name="youtube" value="{{ $astrologer->youtube }}">
                          </div>
                          <div class="col-12 mb-3">
                            <label>About yourself <em class="text-danger">*</em></label>
                            <textarea class="form-control" rows="4" id="about" placeholder="Write about yourself" name="about">{!! $astrologer->about !!}</textarea>
                          </div>
                          <div class="col-12 mt-3 text-center">
                            <button type="submit" class="main_button" style="height:100%;">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- my profile tab content end -->

            <!-- Address tab (aligned with registration) -->
            <div class="tab-pane {{ $tab == 'address-details' ? 'active' : '' }}" id="address-details">
              <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" onsubmit="return validateAndSubmit(this)">
                <input type="hidden" name="tab" value="address-details">
                <input type="hidden" name="_token" value="{{ $_token }}">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card mb-3 bg-light">
                      <div class="card-body border-n radius">
                        <h4 class="mb-4 dash-heading">Address</h4>
                        <div class="row mb-3">
                          <div class="col-md-6 col-12 mb-3">
                            <label>Address <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="{{ $astrologer->address }}">
                          </div>
                          <div class="col-md-6 col-12 mb-3">
                            <label>Area <small class="text-muted">(Optional)</small></label>
                            <input type="text" class="form-control" placeholder="Area" name="area" id="area" value="{{ $astrologer->area }}">
                          </div>
                          <div class="col-md-6 col-12 mb-3">
                            <label>Landmark <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Landmark" name="landmark" id="landmark" value="{{ $astrologer->landmark }}">
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <div class="form-group">
                              <label>Country <em class="text-danger">*</em></label>
                              <select name="country_id" class="form-select custom-select" onchange="fetchLocations('State', this, 'state_id')" id="country_id" >
                                <option value="0">Select Country</option>
                                @foreach($countries as $country)
                                  <option value="{{ $country->id }}" {{ $country->id == $astrologer->country_id ? "selected" : "" }}>
                                    {{$country->name}}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <div class="form-group">
                              <label>State <em class="text-danger">*</em></label>
                              <select name="state_id" class="form-select custom-select" onchange="fetchLocations('City', this, 'city_id')" id="state_id">
                                <option value="0">Select State</option>
                                @foreach($states as $state)
                                  <option value="{{ $state->id }}"  {{ $state->id == $astrologer->state_id ? "selected" : "" }}>{{$state->name}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <div class="form-group">
                              <label>City <em class="text-danger">*</em></label>
                              <select name="city_id" class="form-select custom-select" id="city_id">
                                <option value="0">Select City</option>
                                @foreach($cities as $city)
                                  <option value="{{ $city->id }}"  {{ $city->id == $astrologer->city_id ? "selected" : "" }}>{{$city->name}}</option>
                                @endforeach
                              </select>
                              </div>
                          </div>
                          <div class="col-md-4 col-12 mb-3">
                            <div class="form-group">
                              <label>Pincode <em class="text-danger">*</em></label>
                              <input type="number" class="form-control" placeholder="Pincode" name="pincode" id="pincode" value="{{ $astrologer->pincode }}" min="100000" max="999999">
                            </div>
                          </div>
                          <div class="col-12 mt-3 text-center">
                            <button type="submit" class="main_button" style="height:100%; ">Save </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- address details tab content end -->

            <!-- Languages tab (aligned with registration) -->
            <div class="tab-pane {{ $tab == 'languages' ? 'active' : '' }}" id="languages">
              <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" onsubmit="return validateAndSubmit(this)">
                <input type="hidden" name="tab" value="languages">
                <input type="hidden" name="_token" value="{{ $_token }}">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card mb-3 bg-light">
                      <div class="card-body border-n radius">
                        <h4 class="mb-4 dash-heading">Languages</h4>
                        <div class="row mb-3">
                          <div class="col-12">
                            <label class="d-block">Languages <em class="text-danger">*</em></label>
                            <div class="row g-2">
                              @foreach($languages as $lang)
                                <div class="col-6 col-md-4 col-lg-3">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" {{ in_array($lang->id, $astrologerLanguages) ? 'checked' : '' }} id="language_id-{{ $lang->id }}" value="{{ $lang->id }}" name="languages[]">
                                    <label class="form-check-label" for="language_id-{{ $lang->id }}">{{ $lang->name }}</label>
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                          <div class="col-12 mt-3 text-center">
                            <button type="submit" class="main_button" style="height:100%; ">Save </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- languages tab content end -->

            <!-- Skills tab (aligned with registration: skills + Any Healing) -->
            <div class="tab-pane {{ $tab == 'skills' ? 'active' : '' }}" id="skills">
              <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" onsubmit="return validateAndSubmit(this)">
                <input type="hidden" name="tab" value="skills">
                <input type="hidden" name="_token" value="{{ $_token }}">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card mb-3 bg-light">
                      <div class="card-body border-n radius">
                        <h4 class="mb-4 dash-heading">Skills</h4>
                        <div class="row mb-3">
                          <div class="col-12 mb-3">
                            <label class="d-block">Skills <em class="text-danger">*</em></label>
                            <div class="row g-2">
                              @foreach($services as $item)
                                <div class="col-6 col-md-4 col-lg-3">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" {{ in_array($item->id, $astrologerServices) ? 'checked' : '' }} id="service_id-{{ $item->id }}" value="{{ $item->id }}" name="services[]">
                                    <label class="form-check-label" for="service_id-{{ $item->id }}">{{ $item->name }}</label>
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                          <div class="col-12 mb-3">
                            <label class="d-block">Any Healing? <em class="text-danger">*</em></label>
                            <div class="d-flex gap-3 pt-2">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="any_healing" id="healing_yes" value="1" {{ $astrologer->any_healing == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="healing_yes">Yes</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="any_healing" id="healing_no" value="0" {{ $astrologer->any_healing != 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="healing_no">No</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 mt-3 text-center">
                            <button type="submit" class="main_button" style="height:100%;">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- languages tab content end -->

            @if($astrologer->is_verified==1)
              <!-- prices tab content start -->
              <div class="tab-pane {{ $tab == 'prices' ? 'active' : '' }}" id="prices">
                <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" onsubmit="return validateAndSubmit(this)">
                  <input type="hidden" name="tab" value="prices">
                  <input type="hidden" name="_token" value="{{ $_token }}">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card mb-3 bg-light">
                        <div class="card-body border-n radius">
                          <h4 class="mb-4 dash-heading">Price</h4>
                          <div class="row mb-3">
                            <div class="col-md-4 col-12 mb-3">
                              <label>Chat Price <em class="text-danger">*</em></label>
                              <input type="number" class="form-control" placeholder="Chat Price" name="chat_price" value="{{ $astrologer->chat_price }}" id="chat_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Chat Price Special</label>
                              <input type="number" class="form-control" placeholder="Chat Price Special" name="chat_special_price" value="{{ (isset($astrologer->chat_special_price) && (float)$astrologer->chat_special_price > 0) ? $astrologer->chat_special_price : '' }}" id="chat_special_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Call Price <em class="text-danger">*</em></label>
                              <input type="number" class="form-control" placeholder="Call Price" name="call_price" value="{{ $astrologer->call_price }}" id="call_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Call Price Special</label>
                              <input type="number" class="form-control" placeholder="Call Price Special" name="call_special_price" value="{{ (isset($astrologer->call_special_price) && (float)$astrologer->call_special_price > 0) ? $astrologer->call_special_price : '' }}" id="call_special_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Video Price <em class="text-danger">*</em></label>
                              <input type="number" class="form-control" placeholder="Video Price" name="video_price" value="{{ $astrologer->price }}" id="video_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Video Price Special</label>
                              <input type="number" class="form-control" placeholder="Video Price Special" name="video_special_price" value="{{ (isset($astrologer->special_price) && (float)$astrologer->special_price > 0) ? $astrologer->special_price : '' }}" id="video_special_price">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label >Special Price From Date</label>
                              <input type="date" max="{{ date('2099-m-d') }}" id="special_price_from_date" class="form-control" name="special_price_from_date" value="{{ $astrologer->sp_from_date && $astrologer->sp_from_date != '1970-01-01' ? $astrologer->sp_from_date : '' }}">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label >Special Price To Date</label>
                              <input type="date" max="{{ date('2099-m-d') }}" id="special_price_to_date" class="form-control" name="special_price_to_date" value="{{ $astrologer->sp_to_date && $astrologer->sp_to_date != '1970-01-01' ? $astrologer->sp_to_date : '' }}">
                            </div>
                            @if($tempAstrologer)
                            <div class="col-12 mt-3 text-center">
                              <button type="submit" class="main_button" disabled>Wait for approval </button>
                            </div>
                            @else
                              <div class="col-12 mt-3 text-center">
                                <button type="submit" class="main_button" style="height:100%; ">Save </button>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- prices tab content end -->

              <!-- Bank tab (aligned with registration: IFSC lookup, account details) -->
              <div class="tab-pane {{ $tab == 'bank-details' ? 'active' : '' }}" id="bank-details">
                <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" onsubmit="return validateAndSubmit(this)">
                  <input type="hidden" name="tab" value="bank-details">
                  <input type="hidden" name="_token" value="{{ $_token }}">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card mb-3 bg-light">
                        <div class="card-body border-n radius">
                          <h4 class="mb-4 dash-heading">Bank</h4>
                          <div class="row mb-3">
                            <div class="col-md-6 col-12 mb-3">
                              <label>Account Number <em class="text-danger">*</em></label>
                              <input type="text" class="form-control" placeholder="Account Number" name="account_number" value="{{ $astrologer->bank_account_number }}" id="account_number">
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                              <label>Confirm Account Number <em class="text-danger">*</em></label>
                              <input type="text" class="form-control" placeholder="Confirm Account Number" name="confirm_account_number" id="confirm_account_number" value="{{ $astrologer->bank_account_number }}">
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                              <label>Account Name <em class="text-danger">*</em></label>
                              <input type="text" class="form-control" placeholder="Account Holder Name" name="account_holder_name" value="{{ $astrologer->bank_account_name }}" id="account_holder_name">
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                              <label>IFSC Code <em class="text-danger">*</em></label>
                              <input type="text" class="form-control text-uppercase" placeholder="e.g. YESB0DNB002" name="ifsc_code" value="{{ $astrologer->bank_ifsc_code }}" id="ifsc_code" maxlength="11" autocomplete="off">
                              <small class="text-muted">Enter 11-character IFSC to auto-fill bank details</small>
                              <div id="ifsc-lookup-loading" class="small text-primary mt-1 d-none">Verifying IFSC...</div>
                              <div id="ifsc-lookup-error" class="small text-danger mt-1 d-none"></div>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                              <label>Bank Name <em class="text-danger">*</em></label>
                              <input type="text" class="form-control bg-light" name="bank_name" value="{{ $astrologer->bank_name }}" id="bank_name" readonly placeholder="Fetched from IFSC">
                            </div>
                            <div class="col-md-3 col-12 mb-3">
                              <label>City <small class="text-muted">(Optional)</small></label>
                              <input type="text" class="form-control bg-light" name="bank_city" value="{{ $astrologer->bank_city }}" id="bank_city" readonly placeholder="From IFSC">
                            </div>
                            <div class="col-md-3 col-12 mb-3">
                              <label>State <small class="text-muted">(Optional)</small></label>
                              <input type="text" class="form-control bg-light" name="bank_state" value="{{ $astrologer->bank_state }}" id="bank_state" readonly placeholder="From IFSC">
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>UPI ID <small class="text-muted">(Optional)</small></label>
                              <input type="text" class="form-control" placeholder="UPI ID" name="upi_id" value="{{ $astrologer->bank_upi_id }}" id="upi_id">
                            </div>
                            <div class="col-12 mt-3 text-center">
                              <button type="submit" class="main_button" style="height:100%;">Save</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- bank details tab content end -->

              <!-- Documents tab (aligned with registration) -->
              <div class="tab-pane {{ $tab == 'documents' ? 'active' : '' }}" id="documents">
                <form class="mt-4" method="post" action="{{route('astrologer.profile')}}" enctype="multipart/form-data" onsubmit="return validateAndSubmit(this)">
                  <input type="hidden" name="tab" value="documents">
                  <input type="hidden" name="_token" value="{{ $_token }}">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card mb-3 bg-light">
                        <div class="card-body border-n radius">
                          <h4 class="mb-4 dash-heading">Documents</h4>
                          <div class="row mb-3">
                            <div class="col-md-6 col-12 mb-3">
                              <label>Aadhaar Number <em class="text-danger">*</em></label>
                              <input type="text" class="form-control" placeholder="12 digit Aadhaar" name="aadhaar_number" value="{{ $astrologer->aadhaar_number }}" maxlength="12" pattern="[0-9]*">
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                              <label>PAN Card Number <em class="text-danger">*</em></label>
                              <input type="text" class="form-control text-uppercase" placeholder="e.g. ABCDE1234F" name="pan_card" value="{{ $astrologer->pan_card }}" maxlength="10">
                            </div>
                            @php
                              $aadhaarFront = $astrologer->aadhaarFront;
                              $aadhaarBack = $astrologer->aadhaarBack;
                              $astrologerPanId = $astrologer->panId;
                              $cancelCheque = $astrologer->cancelCheque;
                              $astrologyCert = $astrologer->astrologyCertificate;
                            @endphp
                            <div class="col-md-4 col-12 mb-3">
                              <label>Upload Aadhaar Front</label>
                              <input type="file" class="form-control" name="aadhaar_front" accept="image/*,application/pdf">
                              @if($aadhaarFront)
                                @php $ext = strtolower(pathinfo($aadhaarFront->file ?? '', PATHINFO_EXTENSION)); $isPdf = ($ext === 'pdf'); @endphp
                                <div class="document-thumb-wrap">
                                  <a href="{{ $aadhaarFront->image() }}" target="_blank" title="View">
                                    @if($isPdf)
                                      <div class="document-thumb document-thumb-pdf"></div>
                                      <span class="document-thumb-label">PDF · View</span>
                                    @else
                                      <div class="document-thumb"><img src="{{ $aadhaarFront->image(80,80) }}" alt="Aadhaar Front"></div>
                                      <span class="document-thumb-label">View</span>
                                    @endif
                                  </a>
                                </div>
                              @endif
                              <small class="d-block text-muted">Image or PDF, max 5MB</small>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Upload Aadhaar Back</label>
                              <input type="file" class="form-control" name="aadhaar_back" accept="image/*,application/pdf">
                              @if($aadhaarBack)
                                @php $ext = strtolower(pathinfo($aadhaarBack->file ?? '', PATHINFO_EXTENSION)); $isPdf = ($ext === 'pdf'); @endphp
                                <div class="document-thumb-wrap">
                                  <a href="{{ $aadhaarBack->image() }}" target="_blank" title="View">
                                    @if($isPdf)
                                      <div class="document-thumb document-thumb-pdf"></div>
                                      <span class="document-thumb-label">PDF · View</span>
                                    @else
                                      <div class="document-thumb"><img src="{{ $aadhaarBack->image(80,80) }}" alt="Aadhaar Back"></div>
                                      <span class="document-thumb-label">View</span>
                                    @endif
                                  </a>
                                </div>
                              @endif
                              <small class="d-block text-muted">Image or PDF, max 5MB</small>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Upload PAN Card</label>
                              <input type="file" class="form-control" name="pancard" accept="image/*,application/pdf">
                              @if($astrologerPanId)
                                @php $ext = strtolower(pathinfo($astrologerPanId->file ?? '', PATHINFO_EXTENSION)); $isPdf = ($ext === 'pdf'); @endphp
                                <div class="document-thumb-wrap">
                                  <a href="{{ $astrologerPanId->image() }}" target="_blank" title="View">
                                    @if($isPdf)
                                      <div class="document-thumb document-thumb-pdf"></div>
                                      <span class="document-thumb-label">PDF · View</span>
                                    @else
                                      <div class="document-thumb"><img src="{{ $astrologerPanId->image(80,80) }}" alt="PAN Card"></div>
                                      <span class="document-thumb-label">View</span>
                                    @endif
                                  </a>
                                </div>
                              @endif
                              <small class="d-block text-muted">Image or PDF, max 5MB</small>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Upload Cancel Cheque</label>
                              <input type="file" class="form-control" name="cancel_cheque" accept="image/*,application/pdf">
                              @if($cancelCheque)
                                @php $ext = strtolower(pathinfo($cancelCheque->file ?? '', PATHINFO_EXTENSION)); $isPdf = ($ext === 'pdf'); @endphp
                                <div class="document-thumb-wrap">
                                  <a href="{{ $cancelCheque->image() }}" target="_blank" title="View">
                                    @if($isPdf)
                                      <div class="document-thumb document-thumb-pdf"></div>
                                      <span class="document-thumb-label">PDF · View</span>
                                    @else
                                      <div class="document-thumb"><img src="{{ $cancelCheque->image(80,80) }}" alt="Cancel Cheque"></div>
                                      <span class="document-thumb-label">View</span>
                                    @endif
                                  </a>
                                </div>
                              @endif
                              <small class="d-block text-muted">Image or PDF, max 5MB</small>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                              <label>Upload Astrology Certificate</label>
                              <input type="file" class="form-control" name="astrology_certificate" accept="image/*,application/pdf">
                              @if($astrologyCert)
                                @php $ext = strtolower(pathinfo($astrologyCert->file ?? '', PATHINFO_EXTENSION)); $isPdf = ($ext === 'pdf'); @endphp
                                <div class="document-thumb-wrap">
                                  <a href="{{ $astrologyCert->image() }}" target="_blank" title="View">
                                    @if($isPdf)
                                      <div class="document-thumb document-thumb-pdf"></div>
                                      <span class="document-thumb-label">PDF · View</span>
                                    @else
                                      <div class="document-thumb"><img src="{{ $astrologyCert->image(80,80) }}" alt="Astrology Certificate"></div>
                                      <span class="document-thumb-label">View</span>
                                    @endif
                                  </a>
                                </div>
                              @endif
                              <small class="d-block text-muted">Image or PDF, max 5MB</small>
                            </div>
                            <div class="col-12 mt-3 text-center">
                              <button type="submit" class="main_button" style="height:100%;">Save</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- documents tab content end -->
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@include(themeView().'common.editor')
@push('scripts')
<script>
  function handleChooseImage(fileInputId){
    let value = $(`#${fileInputId}`).click();
  }
  function showTempProfileImage(file, src) {
    var fileInput = document.getElementById(file);
    var file = fileInput.files[0];
    if(file){
      var reader = new FileReader();
      reader.onload = function(e){
        var imageElement = document.getElementById(src);
        imageElement.src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
  }

  function validateAndSubmit(that){
    let _form = $(that);
    var formData = new FormData(that);
    _form.find("button[type=submit]").prop("disabled", true).text("Submitting");
    $.ajax({
        url: "{{ route('astrologer.profile') }}",
        enctype: 'multipart/form-data',
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response){
        _form.find("button[type=submit]").prop("disabled", true).text("Submitted");
          toastr.success(response.message);
          setTimeout(function(){
            window.location.href = response.redirectTo;
          }, 2000);
        },
        error: function(xhr){
          let errors = xhr.responseJSON.errors;
          if(errors){
            for(var key in errors){
              toastr.error(errors[key][0]);
            }
          }
          _form.find("button[type=submit]").prop("disabled", false).text("Save");
          console.log(xhr)
        }
    });
    return false;
  }

  function fetchLocations(table, that, elementId){
    let url = "{{ route('location') }}";
    let relationTableId = $(that).val();
    url += `/?table=${table}&relation_table_id=${relationTableId}`;
    let data = `<option value="">Select ${table}</option>`;
    $.ajax({
      type: "GET",
      url: url,
      success: function(response){
        for(let item in response){
          data += `<option value="${item}">${response[item]}</option>`
        }
        $('#'+elementId).html('');
        $('#'+elementId).append(data);
      },
      error: function(error){
        $('#'+elementId).html('');
        $('#'+elementId).append(data);
        console.log("fetch states by country:", error);
      }
    });
  }

  @if($tab == 'bank-details')
  (function(){
    var ifscInput = $('#ifsc_code');
    if (!ifscInput.length) return;
    ifscInput.on('blur', function(){
      var ifsc = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');
      if (ifsc.length !== 11) {
        $('#ifsc-lookup-error').addClass('d-none');
        $('#bank_name, #bank_city, #bank_state').val('');
        return;
      }
      $(this).val(ifsc);
      $('#ifsc-lookup-loading').removeClass('d-none');
      $('#ifsc-lookup-error').addClass('d-none').text('');
      $.ajax({
        url: "{{ url('astrologer/ifsc') }}/" + ifsc,
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(res){
          $('#ifsc-lookup-loading').addClass('d-none');
          $('#bank_name').val(res.BANK || '');
          $('#bank_city').val(res.CITY || '');
          $('#bank_state').val(res.STATE || '');
        },
        error: function(xhr){
          $('#ifsc-lookup-loading').addClass('d-none');
          $('#ifsc-lookup-error').removeClass('d-none').text(xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Invalid or not found IFSC.');
          $('#bank_name, #bank_city, #bank_state').val('');
        }
      });
    });
  })();
  @endif
</script>
@endpush
