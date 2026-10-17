
@extends('layouts.admin')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div id="profile-app">
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.'.$astrologerType.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
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
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#profile_tab">Profile</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link {{ $astrologerType=='AI'?'d-none':'' }}" href="#address_tab">Address</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link {{ $astrologerType=='AI'?'d-none':'' }}" href="#status_tab">CNS Status</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#about_tab">About</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#expertise_tab">Expertise</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#price_tab">Prices</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link {{ $astrologerType=='AI'?'d-none':'' }}" href="#bank_tab">Bank Details</a></li>
  {{-- <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#appointment_packages">Appointment Packages</a></li> --}}
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#attached_documents_tab">Attached Documents</a></li>
  <li class="nav-item puja-tab-item" style="display: {{($item && $item->is_pandit == 1) ? 'block' : 'none'}};"><a data-toggle="tab" class="nav-link" href="#puja_tab">Puja</a></li>
  @if($temp_astrologers->count() > 0)
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#published_requests_tab">Publised Requests</a></li>
  @endif
</ul>
<div class="tab-content">
  <div id="profile_tab" class="tab-pane fade in active show">
<div class="row">
    <div class="form-group col-md-3">
      {!! Form::label('firstname','First name', ['class'=>'required']) !!}
      {!! Form::text('firstname', old('firstname'),['class'=>'form-control', 'id'=>'firstname', 'onchange'=>'slugify()']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('lastname','Last name',['class'=>'required']) !!}
      {!! Form::text('lastname', old('lastname'),['class'=>'form-control', 'id'=>'lastname', 'onchange'=>'slugify()']) !!}
    </div>
    <div class="form-group col-md-3 d-none">
      {!! Form::label('display_name','Display Name',['class'=>'required']) !!}
      {!! Form::text('display_name', old('display_name'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('email','Email',['class'=>'required']) !!}
      {!! Form::email('email', old('email'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('phone','Phone',['class'=>'required']) !!}
      {!! Form::number('phone', old('phone'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('gender', 'Gender', ['class'=>'required']) !!}
      {!! Form::select('gender',genders(), old('gender'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('job_start_year','Start Year', ['class'=>'required']) !!}
      <select name="job_start_year"  class="form-control">
        <option value="">select</option>
          @foreach (range(date('Y', strtotime('-30 years')), date('Y')) as $yyyy=>$year)
              <option value="{{$year}}" {{$year==$item->job_start_year?'selected':''}}>{{$year}}</option>
          @endforeach
      </select>
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('dob','Birth Date') !!}
      {!! Form::date('dob', $item ? $item->day : '',['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    {{--<div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('birth_time','Birth Time') !!}
      {!! Form::time('birth_time', $item ? $item->birth_time : '',['class'=>'form-control', 'step'=>'1']) !!}
    </div>
    <div class="form-group col-sm-3 {{ $astrologerType=='AI'?'d-none':'' }}">
        {!! Form::label('birth_place','Birth Place') !!}
        {!! Form::text('birth_place', old('birth_place'),['class'=>'form-control']) !!}
    </div>--}}

    <div class="col-sm-3 mt-3 px-3">
      <div class="form-group col-md-4 d-flex align-items-start mt-3">
         <button type="button" class="btn float-left" onclick="openMediaManager('image')">Browse Photograph</button>
         <input type="hidden" name="image_id" value="{{$item?$item->image_id:0}}" id="image_id">
        <div class="image_img_div d-flex align-items-start mt-c ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
          @if($item && $item->image)
            <img src="{{$item->image->image(50,50)}}" id="image_src" alt="admin image" class="img-responsive">
            <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'image')"></i>
          @else
            <img src="{{no_image()}}" width="100" id="image_src" alt="admin image" class="img-responsive ml-2">
          @endif
        </div>
      </div>
    </div>
    <div class="form-group col-md-3 px-1">
        <span class="ml-2 mt-2"><strong>Green Tick</strong></span>
        <input type="checkbox" class="toggle-check" id="blue-tick" value="1" name="blue_tick" {{$item && $item->blue_tick==1?'checked':''}}>
        <label class="mx-2 t-lable mt-3" for="blue-tick">Green Tick</label>
    </div>
    <div class="form-group col-md-3 px-1">
        <span class="ml-2 mt-2"><strong>First Chat Free</strong></span>
        <input type="checkbox" class="toggle-check" id="first_chat_free" value="1" name="first_chat_free" {{$item && $item->first_chat_free == 1 ? 'checked' : ''}}>
        <label class="mx-2 t-lable mt-3" for="first_chat_free">First Chat Free</label>
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('rating','Rating') !!}
      {!! Form::text('rating', old('rating'),['class'=>'form-control' ,'placeholder'=>'0.0']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('can_go_live', 'Can Go Live') !!}
      {!! Form::select('can_go_live',yesNoArray(), old('can_go_live'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('status', 'Status') !!}
      {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('badge_id', 'Astrologer Badge') !!}
      {!! Form::select('badge_id', $badges, old('badge_id', $item ? $item->badge_id : ''), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('is_verified', 'Verified') !!}
      {!! Form::select('is_verified',yesNoArray(), old('is_verified'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3 px-1 {{ $astrologerType=='AI'?'d-none':'' }}">
        <span class="ml-2 mt-2"><strong>Make as Pandit</strong></span>
        <input type="checkbox" class="toggle-check" id="is_pandit" value="1" name="is_pandit" {{$item && $item->is_pandit == 1 ? 'checked' : ''}}>
        <label class="mx-2 t-lable mt-3" for="is_pandit">Make as Pandit</label>
    </div>
     <div class="form-group col-md-3 d-none">
      {!! Form::label('order', 'Order') !!}
      {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
    </div>


</div>
  </div>
    <div id="address_tab" class="tab-pane fade">
<div class="row">
    <div class="form-group col-sm-4">
        {!! Form::label('address','Address') !!}
        {!! Form::text('address', old('address'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-sm-2">
        <label>Country</label>
        <select name="country_id" v-model="country_id" class="form-control nosearch"  v-on:change="loadMaster('states')">
            <option value="0">Select Country</option>
            @foreach($countries as $country)
              <option value="{{$country->id}}">{{$country->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-2">
        <label>State</label>
        <select name="state_id" v-model="state_id" class="form-control nosearch" v-on:change="loadMaster('cities')">
            <option v-for="(item, id) in master.states" :key="id" :value="id">@{{item}}</option>
        </select>
    </div>
    <div class="form-group col-sm-2">
        <label>City</label>
        <select name="city_id" v-model="city_id" class="form-control nosearch" >
            <option v-for="(item, id) in master.cities" :key="id" :value="id">@{{item}}</option>
        </select>
    </div>

    <div class="form-group col-sm-2">
        {!! Form::label('pincode','Pincode') !!}
        {!! Form::text('pincode', old('pincode'),['class'=>'form-control']) !!}
    </div>
      <div class="row mt-3 px-3">
        <div class="form-group col-md-6 d-flex align-items-center">
           <button type="button" class="btn float-left" onclick="openMediaManager('id_proof')">Browse Id Proof</button>
           <input type="hidden" name="id_proof" value="{{$item?$item->id_proof:0}}" id="id_proof_id">
          <div class="id_proof_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->id_proof : 0}}">
            @if($item && $item->idProof)
              <img src="{{$item->idProof->image(50,50)}}" id="id_proof_src" alt="admin image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeImage(this, 'id_proof')"></i>
            @else
              <img src="{{no_image()}}" width="50" id="id_proof_src" alt="admin image" class="img-responsive ml-2">
            @endif
          </div>
        </div>
        <div class="form-group col-md-6 d-flex align-items-center">
           <button type="button" class="btn float-left" onclick="openMediaManager('address_proof')">Browse Address Proof</button>
           <input type="hidden" name="address_proof" value="{{$item?$item->address_proof:0}}" id="address_proof_id">
          <div class="address_proof_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->address_proof : 0}}">
            @if($item && $item->addressProof)
              <img src="{{$item->addressProof->image(50,50)}}" id="address_proof_src" alt="admin image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeImage(this, 'address_proof')"></i>
            @else
              <img src="{{no_image()}}" width="50" id="address_proof_src" alt="admin image" class="img-responsive ml-2">
            @endif
          </div>
        </div>
        @if($astrologerType == 'Instructor')
          <div class="form-group col-md-6 d-flex align-items-center">
           <button type="button" class="btn float-left" onclick="openMediaManager('signature')">Browse Signature</button>
           <input type="hidden" name="signature" value="{{$item?$item->signature:0}}" id="signature_id">
            <div class="signature_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->signature : 0}}">
              @if($item && $item->signatureId)
                <img src="{{$item->signatureId->image(50,50)}}" id="signature_src" alt="admin image" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeImage(this, 'signature')"></i>
              @else
                <img src="{{no_image()}}" width="50" id="signature_src" alt="admin image" class="img-responsive ml-2">
              @endif
            </div>
          </div>
        @endif
      </div>


</div>
  </div>
  <div id="status_tab" class="tab-pane fade">
<div class="row">
    <div class="form-group col-md-3">
      {!! Form::label('chat', 'Chat') !!}
      {!! Form::select('chat',[1 => 'Online',2 => 'Offline',3 => 'Busy',0 => 'Hide'], old('chat'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('call', 'Call') !!}
      {!! Form::select('call',[1 => 'Online',2 => 'Offline',3 => 'Busy',0 => 'Hide'], old('call'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('Video', 'Video') !!}
      {!! Form::select('video',[1 => 'Online',2 => 'Offline',3 => 'Busy',0 => 'Hide'], old('chat'), ['class'=>'form-control nosearch']) !!}
    </div>

</div>
  </div>
  <div id="about_tab" class="tab-pane fade">
<div class="row">
    {{--<div class="form-group col-md-12 {{ $astrologerType=='AI'?'d-none':'' }}">
      <h4 class="text-center" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>{!! Form::label('blog_category_id', 'Blog Category') !!}</b></h4>
      {!! Form::select('blog_category_id',$categories, old('blog_category_id'), ['class'=>'form-control']) !!}
    </div>--}}
    <div class="form-group col-md-12">
      <h4 class="text-center" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>{!! Form::label('about', 'About Info') !!}</b></h4>
      {!! Form::textarea('about', old('about'), ['class'=>'form-control']) !!}
    </div>
    {{--<div class="form-group col-md-12 {{ $astrologerType=='AI'?'d-none':'' }}">
      <h4 class="text-center" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>{!! Form::label('experience', 'Experience') !!}</b></h4>
      {!! Form::textarea('experience', old('experience'), ['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-12 {{ $astrologerType=='AI'?'d-none':'' }}">
      <h4 class="text-center" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>{!! Form::label('education', 'Educational Details') !!}</b></h4>
      {!! Form::textarea('education', old('education'), ['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-12 {{ $astrologerType=='AI'?'d-none':'' }}">
      <h4 class="text-center" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>{!! Form::label('expertise', 'Area of Expertise') !!}</b></h4>
      {!! Form::textarea('expertise', old('expertise'), ['class'=>'form-control']) !!}
    </div>--}}
</div>
  </div>
  <div id="expertise_tab" class="tab-pane fade">
    <div class="row">
    <div class="form-group col-md-12">
    <h4 class="text-center text-orange required" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>Language Known</b></h4>
      <div class="card-body d-flex justify-content-between px-0 pb-0">


             @foreach($languages as $lang)
             @php
         $languages = $item->languages?$item->languages->pluck('language_id')->toArray():[];
           @endphp
          <div class="form-group px-1">
              <span class="ml-2 mt-2"><strong>{{$lang->name}}</strong></span>
              <input type="checkbox" class="toggle-check" id="language-{{$lang->id}}" value="{{$lang->id}}"
                {{ in_array($lang->id, $languages) ? 'checked' : '' }} name="languages[]">
                <label class="mx-2 t-lable mt-2" for="language-{{$lang->id}}">{{$lang->name}}</label>
          </div>
          @endforeach
      </div>
    </div>

    <div class="form-group col-md-12">
      <h4 class="text-center text-orange required" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>Service Fields</b></h4>
    <div class="card-body d-flex justify-content-between px-0 pb-0">
        @foreach($services as $service)
        @php
          $services = $item->services?$item->services->pluck('service_id')->toArray():[];
        @endphp
        <div class="form-group px-1">
            <span class="ml-2 mt-2"><strong>{{$service->name}}</strong></span>
            <input type="checkbox" class="toggle-check" id="service-{{$service->id}}" value="{{$service->id}}"
              {{ in_array($service->id, $services) ? 'checked' : '' }} name="services[]">
              <label class="mx-2 t-lable mt-2" for="service-{{$service->id}}">{{$service->name}}</label>
        </div>
        @endforeach
    </div>
  </div>



<div class="form-group col-md-12 {{ $astrologerType=='AI'?'d-none':'d-none' }}">
      <h4 class="text-center text-orange" style="padding: 10px 16px; border-radius: 10px; font-size: 18px; line-height: 1.33; color: white; background-color: #7a70ba; text-align: justify;"><b>Working Days</b></h4>

  <div class="card-body d-flex justify-content-between px-0">

        @foreach(weekDays() as $wday=>$wdayName)
         @php
      $days=$item->timetable?$item->timetable->pluck('week_day')->unique()->toArray():[];

       @endphp
       <div class="form-group px-1">
          <span class="ml-2 mt-2"><strong>{{$wdayName}}</strong></span>
          <input type="checkbox" class="toggle-check" id="day-{{$wday}}" value="{{$wday}}" {{ in_array($lang->id, $days) ? 'checked' : '' }} :checked="days.indexOf({{$wday}})>-1"  v-on:change="toggleExpertise('days', {{$wday}})" name="days[]">
          <label class="mx-2 t-lable mt-2" for="day-{{$wday}}">{{$wdayName}}</label>

          <div class="row" v-if="days.indexOf({{$wday}})>-1" v-for="(item, index) in timings[{{$wday}}]">
              <div class="form-group col-12">
                  <input type="hidden" value="{{$wday}}" name="timings[{{$wday}}][week_day]">
                  From
                  <input type="time" class="form-control" v-model="item.from_time" name="timings[{{$wday}}][from_time]">
              </div>
              <div class="form-group col-12">
                  To
                  <input type="time" class="form-control" v-model="item.to_time" name="timings[{{$wday}}][to_time]">
              </div>
              <div class="form-group col">
                  <button class="btn btn-danger btn-sm mt-4" type="button" v-if="index!=0" v-on:click="removeTime({{$wday}}, index)"><i class="fa fa-times" ></i></button>
              </div>
          </div>

      </div>


      @endforeach
  </div>
</div>
  </div>
  </div>
  <div id="price_tab" class="tab-pane fade">
    <div class="row">
    {{-- chat price --}}
    <div class="form-group col-md-3">
      {!! Form::label('chat_price','Chat  Price',['class'=>'required']) !!}
      {!! Form::text('chat_price', old('chat_price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('chat_special_price','Chat  Special price') !!}
      {!! Form::text('chat_special_price', old('chat_special_price'),['class'=>'form-control']) !!}
    </div>
    {{-- call price --}}
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('call_price','Call Price',['class'=>'required']) !!}
      {!! Form::text('call_price', old('call_price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('call_special_price',' Call Special price') !!}
      {!! Form::text('call_special_price', old('call_special_price'),['class'=>'form-control']) !!}
    </div>
    {{-- video call price --}}
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('price','Video Call Price',['class'=>'required']) !!}
      {!! Form::text('price', old('price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('special_price','Video Call Special price') !!}
      {!! Form::text('special_price', old('special_price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('sp_from_date','Special Price From Date') !!}
      {!! Form::date('sp_from_date', old('sp_from_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('sp_to_date','Special Price To Date') !!}
      {!! Form::date('sp_to_date', old('sp_to_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3 {{ $astrologerType=='AI'?'d-none':'' }}">
      {!! Form::label('payout_package_id','Payout Packages', ['class'=>'required']) !!}
      {!! Form::select('payout_package_id', $payoutPackages, old('payout_package_id'), ['class'=>'form-control nosearch']) !!}
    </div>

  </div>
  </div>
  <div id="bank_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('bank_name','Bank Name') !!}
        {!! Form::text('bank_name', old('bank_name'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_account_name','Account Name') !!}
        {!! Form::text('bank_account_name', old('bank_account_name'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_account_number','Account Number') !!}
        {!! Form::text('bank_account_number', old('bank_account_number'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_account_number_verify','Verify Account Number') !!}
        {!! Form::text('bank_account_number_verify', old('bank_account_number_verify'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_ifsc_code','IFSC Code') !!}
        {!! Form::text('bank_ifsc_code', old('bank_ifsc_code'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_upi_id','UPI ID') !!}
        {!! Form::text('bank_upi_id', old('bank_upi_id'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('bank_branch','Branch') !!}
        {!! Form::text('bank_branch', old('bank_branch'),['class'=>'form-control']) !!}
      </div>
      {{--

      <div class="form-group col-md-4">
        {!! Form::label('commission_type','Commission Type') !!}
        {!! Form::select('commission_type',[0=>'Percentage', 1=>'Fixed'], old('commission_type'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('commission','Commission') !!}
        {!! Form::text('commission', old('commission'),['class'=>'form-control']) !!}
      </div>
      --}}
      <div class="form-group col-md-4">
        {!! Form::label('pan_card','Pan Card') !!}
        {!! Form::text('pan_card', old('pan_card'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 d-flex align-items-center">
         <button type="button" class="btn float-left" onclick="openMediaManager('pan_id')">Browse Pan Card</button>
         <input type="hidden" name="pan_id" value="{{$item?$item->pan_id:0}}" id="pan_id_id">
        <div class="pan_id_img_div ml-3" data-mediaid="{{$item ? $item->pan_id : 0}}">
          @if($item && $item->panId)
            <img src="{{$item->panId->image(50,50)}}" id="pan_id_src" alt="admin image" class="img-responsive">
            <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'pan_id')"></i>
          @else
            <img src="{{no_image()}}" width="100" id="pan_id_src" alt="admin image" class="img-responsive">
          @endif
        </div>
      </div>
      <div class="form-group col-md-4 d-flex align-items-center">
         <button type="button" class="btn float-left" onclick="openMediaManager('passbook_id')">Browse Passbook</button>
         <input type="hidden" name="passbook_id" value="{{$item?$item->passbook_id:0}}" id="passbook_id_id">
        <div class="passbook_id_img_div w-25 ml-3" data-mediaid="{{$item ? $item->passbook_id : 0}}">
          @if($item && $item->passbookId)
            <img src="{{$item->passbookId->image(50,50)}}" id="passbook_id_src" alt="admin image" class="img-responsive">
            <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'passbook_id')"></i>
          @else
            <img src="{{no_image()}}" width="100" id="passbook_id_src" alt="admin image" class="img-responsive">
          @endif
        </div>
      </div>
    </div>
   </div>
   <div id="seo_tab" class="tab-pane fade">
     <div class="row">
       <div class="col-md-6 col-12">
         <div class="form-group">
           {!! Form::label('slug', 'Url Key', ['class'=>'required']) !!}
           {!! Form::text('slug', old('slug'),['class'=>'form-control', 'id'=>'slug']) !!}
         </div>
       </div>
       <div class="col-md-6 col-12">
          <div class="form-group">
             {!! Form::label('meta_title','Meta Title') !!}
             {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
         </div>
       </div>
         <div class="col-md-12 col-12">
           <div class="form-group">
               {!! Form::label('meta_keywords','Meta Keywords') !!}
               {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
           </div>
         </div>
           <div class="col-md-12 col-12">
             <div class="form-group">
                 {!! Form::label('meta_description','Meta Description') !!}
                 {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
             </div>
           </div>
     </div>
   </div>
  <div id="attached_documents_tab" class="tab-pane fade">
    @php
      $attachedDocuments = [
        'Aadhaar Front' => $item ? $item->aadhaarFront : null,
        'Aadhaar Back' => $item ? $item->aadhaarBack : null,
        'PAN Card' => $item ? $item->panId : null,
        'Cancel Cheque' => $item ? $item->cancelCheque : null,
        'Astrology Certificate' => $item ? $item->astrologyCertificate : null,
      ];
    @endphp
    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th style="width: 30%;">Document Name</th>
            <th style="width: 50%;">Preview</th>
            <th style="width: 20%;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($attachedDocuments as $label => $media)
            @php
              $file = $media ? ($media->file ?? '') : '';
              $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              $isPdf = $ext === 'pdf';
              $src = $media ? $media->original_path : '';
            @endphp
            <tr>
              <td>{{ $label }}</td>
              <td>
                @if($media)
                  @if($isPdf)
                    <span class="text-muted">PDF Document</span>
                  @else
                    <img src="{{ $src }}" alt="{{ $label }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                  @endif
                @else
                  <span class="text-muted">Not uploaded</span>
                @endif
              </td>
              <td>
                @if($media)
                  <a href="{{ $src }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
   @php
     $pujaData = [];
     foreach($oneToOnePujas as $puja) {
       $pujaData[] = [
         'id' => $puja->id,
         'title' => $puja->title,
         'type' => 'one_to_one',
         'label' => $puja->title . ' (one to one puja)',
         'frontend_url' => route('one.to.one.puja.slug', ['slug' => $puja->slug]),
         'admin_url' => route('admin.one_to_one_puja.edit', ['id' => $puja->id]),
       ];
     }
     foreach($groupPujas as $puja) {
       $pujaData[] = [
         'id' => $puja->id,
         'title' => $puja->title,
         'type' => 'group',
         'label' => $puja->title . ' (group puja)',
         'frontend_url' => route('product.detailpuja', ['product_slug' => $puja->slug]),
         'admin_url' => route('admin.puja.edit', ['id' => $puja->id]),
       ];
     }
   @endphp
   <div id="puja_tab" class="tab-pane fade">
     <div class="row">
       {{-- Left column: Search and add puja --}}
       <div class="col-md-6">
         <div class="form-group">
           {!! Form::label('puja_search', 'Search Puja') !!}
           <input type="text" id="puja_search" class="form-control" placeholder="Search one-to-one or group puja by name">
         </div>
         <div id="puja-search-results" class="border p-3" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
           {{-- Filled by JS: initially all pujas, then filtered on search --}}
         </div>
       </div>

       {{-- Right column: Assigned pujas list --}}
       <div class="col-md-6">
         <div class="form-group">
           {!! Form::label('assigned_pujas', 'Assigned Pujas') !!}
           <div id="assigned-pujas-list" class="border p-3" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
             @if(count($selectedPujas))
               @foreach($selectedPujas as $pujaId)
                 @php
                   $puja = collect($pujaData)->firstWhere('id', $pujaId);
                 @endphp
                 @if($puja)
                   <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom puja-item" data-id="{{ $puja['id'] }}">
                     <span>{{ $puja['label'] }}</span>
                     <div class="btn-group btn-group-sm">
                       <a href="{{ $puja['frontend_url'] }}" target="_blank" class="btn btn-link p-0" title="View on site">
                         <i class="bi bi-eye text-primary"></i>
                       </a>
                       <a href="{{ $puja['admin_url'] }}" target="_blank" class="btn btn-link p-0 ml-2" title="Edit in admin">
                         <i class="bi bi-pencil-square text-secondary"></i>
                       </a>
                       <button type="button" class="btn btn-link p-0 ml-2 text-danger puja-remove-btn" title="Remove from astrologer">
                         <i class="bi bi-x-circle"></i>
                       </button>
                     </div>
                   </div>
                 @endif
               @endforeach
             @else
               <p class="text-muted mb-0"><small>No pujas assigned yet.</small></p>
             @endif
           </div>
         </div>
         {{-- Hidden original selects to keep existing backend logic --}}
         <select name="one_to_one_pujas[]" id="one_to_one_pujas" multiple="multiple" class="d-none">
           @foreach($oneToOnePujas as $puja)
             <option value="{{$puja->id}}" {{in_array($puja->id, $selectedPujas) ? 'selected' : ''}}>
               {{$puja->title}}
             </option>
           @endforeach
         </select>
         <select name="group_pujas[]" id="group_pujas" multiple="multiple" class="d-none">
           @foreach($groupPujas as $puja)
             <option value="{{$puja->id}}" {{in_array($puja->id, $selectedPujas) ? 'selected' : ''}}>
               {{$puja->title}}
             </option>
           @endforeach
         </select>
       </div>
     </div>
   </div>
   <div id="published_requests_tab" class="tab-pane fade">
     <div class="row">
       <div class="col-4"><strong>#</strong></div>
       <div class="col-4"><strong>Published At</strong></div>
       <div class="col-4"><strong>View</strong></div>
       @foreach($temp_astrologers as $index => $itm)
         <div class="col-4">{{$index+1}}</div>
         <div class="col-4">{{ISTDatetimeFormat($itm->updated_at)}}</div>
         <div class="col-4">
           <a href="{{route('admin.temp_astrologer.edit', [$itm->id])}}" target="_blank">
             <i class="fa fa fa-edit"></i>
           </a>
         </div>
       @endforeach
     </div>
   </div>

  </div>

  </div>

  {!! Form::close() !!}
</div>
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')

<script type="text/javascript">
  function slugify(){
    let text = $('#firstname').val()+' '+$('#lastname').val();
    let slug = text
    .toString()
    .toLowerCase()
    .replace(/\s+/g, "-")
    .replace(/[^\w\-]+/g, "")
    .replace(/\-\-+/g, "-")
    .replace(/^-+/, "")
    .replace(/-+$/, "");
    $('#slug').val(slug);
  }
</script>

<script type="text/javascript">
  var media_image_type = 'categories';
  var imageset = 'featured';
  $('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
        toastr.warning('Please select an image');
      return false;
    }
    var selectedImg = $('.img-box-choice.selected');
    var mediaId = selectedImg.data('mediaid');
    var imgSrc = $('.img-box-choice.selected img').attr('src')
    var imgSrcOriginal = $('.img-box-choice.selected img').data('original')
    var newImg  = selectedImg.clone()
    if (imageset === 'featured') {
         $('#image_id').val(mediaId);
         $('#image_src').val(imgSrc);
         $('.profile_image').remove(); // Remove the old image if it exists
         newImg.find('.action').remove();
         newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
         newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
         $('#image_id').after(newImg);
     } else if (imageset === 'editor') {
         tinyMCE.activeEditor.execCommand('mceInsertContent', true, '<img src="' + imgSrcOriginal.replace(window.location.origin, '') + '" alt="editor-insert-image"/>');
         imageset = 'featured';
     }else{
       $('#'+imageset+'_id').val(mediaId);
       $('#'+imageset+'_src').attr('src', imgSrc);
       $('.'+imageset+'_img_div').find('i').remove();
       $('.'+imageset+'_img_div').append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
     }
    $('#media-img-manager').modal('hide');
  });

  var removeImage = function(elm, imagesett){
    $('.'+imagesett+'_img_div').find('img').attr('src', @json(no_image()));
    $('.'+imagesett+'_img_div').find('i').remove();
    $('#'+imagesett+'_id').val(0);
  }

  var addImageToSlide = function() {
    imageset = 'slide';
    $('#media-img-manager').modal('show');
  }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });

  var openMediaManager = function(image = null) {
    imageset = image ? image : 'featured';
    $('#media-img-manager').modal('show');
  }
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script src="{{asset('js/vue.min.js')}}"></script>
<script src="{{asset('js/axios.min.js')}}"></script>
<script>
  var profileapp = new Vue({
      el: '#profile-app',
      data: {
          country_id: '{{$item->country_id}}',
          state_id: '{{$item->state_id}}',
          city_id: '{{$item->city_id}}',
          image_id: '{{$item->image_id}}',
          id_proof: '{{$item->id_proof}}',
          address_proof: '{{$item->address_proof}}',
          pan_id: '{{$item->address_proof}}',
          passbook_id: '{{$item->passbook_id}}',
          signature: '{{$item->signature}}',
          image_id_src: '{{$item->image ? $item->image->image(200) : no_image()}}',
          id_proof_src: '{{$item->idProof ? $item->idProof->image(200) : no_image()}}',
          address_proof_src: '{{$item->addressProof ? $item->addressProof->image(200) : no_image()}}',
          pan_id_src: '{{$item->panId ? $item->panId->image(200) : no_image()}}',
          passbook_id_src: '{{$item->passbookId ? $item->passbookId->image(200) : no_image()}}',
          signature_src: '{{$item->signatureId ? $item->signatureId->image(200) : no_image()}}',
          languages : {!! json_encode($item->languages?$item->languages->pluck('language_id')->toArray():[]) !!},
          services : {!!json_encode($item->services?$item->services->pluck('service_id')->toArray():[]) !!},
          days : {!! json_encode(array_values($item->timetable?$item->timetable->pluck('week_day')->unique()->toArray():[])) !!},
          timings : {!! json_encode($timeTable) !!},
          master: {
              states: [],
              cities: []
          },
          errors: {},
          upload_type : '',
          successmessage:'',
          errormessage:'',
          packages: {!! $item ? $item->packages : '[]' !!},
          name: '',
          price: '',
          minutes: '',
          status: '',
          selectedIndex: ''
      },
      methods: {
          loadMaster(options) {
              let that = this;
              let url = '{{route('locations')}}';
              if (options == 'states') {
                  url += '/master_states/?country_id='+ that.country_id
              } else if (options == 'cities') {
                  url += '/master_cities/?state_id='+ that.state_id
              }
              axios.get(url)
                  .then(function (response) {
                      that.master[options] = response.data;
                  })
                  .catch(function (response) {
                  })
          },
          toggleExpertise(expt, value){
              if(expt=='languages') {
                if(this.languages.indexOf(value)>-1) {
                  this.languages.splice(this.languages.indexOf(value), 1);
                }else {
                    this.languages.push(value);
                }
              }else if(expt=='services') {
                if(this.services.indexOf(value)>-1) {
                  this.services.splice(this.services.indexOf(value), 1);
                }else {
                    this.services.push(value);
                }
              }else if(expt=='days') {
                if(this.days.indexOf(value)>-1) {
                  this.days.splice(this.days.indexOf(value), 1);
                }else {
                    this.days.push(value);
                }
              }
          },
          addTime(wday){
              if(!this.timings[wday] || this.timings[wday].length==0) {
                  this.timings.splice( wday, 0, [] );
              }
              this.timings[wday].push({week_day:wday, from_time:'', to_time:''});
          },
          removeTime(wday, tmindex){
              this.timings[wday].splice(tmindex, 1);
          },
          openModalUpload(type) {
              this.upload_type = type;
              $('#media-img-manager').modal('show')
          },
          savePackage() {
           if(this.packages[this.selectedIndex]){
             this.packages[this.selectedIndex]['name'] = this.name;
             this.packages[this.selectedIndex]['price'] = this.price;
             this.packages[this.selectedIndex]['minutes'] = this.minutes;
             this.packages[this.selectedIndex]['status'] = this.status;
            }else {
              this.packages.push({name:this.name, price:this.price, minutes: this.minutes, status: this.status, id:''})
           }
           this.name = this.price = this.id = this.minutes = this.status = '';
           this.selectedIndex = '';
        },
        editPackage(item, index){
          this.name = item.name;
          this.price= item.price;
          this.minutes= item.minutes;
          this.status= item.status;
          this.selectedIndex = index;
        },
        deletePackage(index) {
          this.packages.splice(index,1);
        }
      },
      mounted() {
          @if($item && $item->id)
              this.loadMaster('states');
              this.loadMaster('cities');
          @endif
      }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    // Initialize select2 only where needed (excluding hidden puja selects)
    $('.select2').select2({
      width: '100%'
    });

    // Function to toggle Puja tab visibility
    function togglePujaTab() {
      if($('#is_pandit').is(':checked')) {
        $('.puja-tab-item').show();
      } else {
        $('.puja-tab-item').hide();
        // If Puja tab is active, switch to Profile tab
        if($('.puja-tab-item a').parent().hasClass('active')) {
          $('.nav-tabs a[href="#profile_tab"]').tab('show');
        }
      }
    }

    // Check on page load
    togglePujaTab();

    // Toggle on checkbox change
    $('#is_pandit').on('change', function() {
      togglePujaTab();
    });

    // Puja search & assignment logic
    initPujaAssignment();
  });

  function initPujaAssignment() {
    var allPujas = @json($pujaData);
    var selectedPujaIds = @json($selectedPujas);

    function renderAssignedPujas() {
      var container = $('#assigned-pujas-list');
      if(selectedPujaIds.length === 0) {
        container.html('<p class="text-muted mb-0"><small>No pujas assigned yet.</small></p>');
      } else {
        var html = '';
        selectedPujaIds.forEach(function(id) {
          var puja = allPujas.find(function(p) { return p.id == id; });
          if(!puja) return;
          html += '<div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom puja-item" data-id="'+puja.id+'">';
          html +=   '<span>'+puja.label+'</span>';
          html +=   '<div class="btn-group btn-group-sm">';
          html +=     '<a href="'+puja.frontend_url+'" target="_blank" class="btn btn-link p-0" title="View on site"><i class="bi bi-eye text-primary"></i></a>';
          html +=     '<a href="'+puja.admin_url+'" target="_blank" class="btn btn-link p-0 ml-2" title="Edit in admin"><i class="bi bi-pencil-square text-secondary"></i></a>';
          html +=     '<button type="button" class="btn btn-link p-0 ml-2 text-danger puja-remove-btn" title="Remove from astrologer"><i class="bi bi-x-circle"></i></button>';
          html +=   '</div>';
          html += '</div>';
        });
        container.html(html);
      }

      // Sync hidden selects for backend submit
      var oneToOneIds = [];
      var groupIds = [];
      selectedPujaIds.forEach(function(id) {
        var puja = allPujas.find(function(p) { return p.id == id; });
        if(!puja) return;
        if(puja.type === 'one_to_one') {
          oneToOneIds.push(String(puja.id));
        } else if(puja.type === 'group') {
          groupIds.push(String(puja.id));
        }
      });
      $('#one_to_one_pujas').val(oneToOneIds);
      $('#group_pujas').val(groupIds);
    }

    function renderSearchResults(query) {
      var resultsContainer = $('#puja-search-results');
      query = query.trim().toLowerCase();
      var filtered = allPujas.filter(function(p) {
        if(!query) return true;
        return p.title.toLowerCase().indexOf(query) !== -1;
      });
      if(filtered.length === 0) {
        resultsContainer.html('<p class="text-muted mb-0"><small>No pujas found for this search.</small></p>');
        return;
      }
      var html = '';
      filtered.forEach(function(puja) {
        var isSelected = selectedPujaIds.indexOf(puja.id) !== -1;
        html += '<div class="d-flex justify-content-between align-items-center mb-1 py-1 border-bottom puja-search-item" data-id="'+puja.id+'" style="cursor:pointer;">';
        html +=   '<span>'+puja.label+'</span>';
        html +=   '<span class="badge badge-'+(isSelected ? 'success' : 'primary')+'">'+(isSelected ? 'Added' : 'Add')+'</span>';
        html += '</div>';
      });
      resultsContainer.html(html);
    }

    $('#puja_search').on('keyup', function() {
      renderSearchResults($(this).val());
    });

    $('#puja-search-results').on('click', '.puja-search-item', function() {
      var id = parseInt($(this).data('id'));
      if(selectedPujaIds.indexOf(id) === -1) {
        selectedPujaIds.push(id);
        renderAssignedPujas();
        renderSearchResults($('#puja_search').val());
      }
    });

    // Remove puja from assigned list
    $('#assigned-pujas-list').on('click', '.puja-remove-btn', function(e) {
      e.preventDefault();
      var parent = $(this).closest('.puja-item');
      var id = parseInt(parent.data('id'));
      selectedPujaIds = selectedPujaIds.filter(function(pid) { return pid !== id; });
      renderAssignedPujas();
      renderSearchResults($('#puja_search').val());
    });

    // Initial render from existing selections and full puja list
    renderAssignedPujas();
    renderSearchResults('');
  }
</script>
@endpush
