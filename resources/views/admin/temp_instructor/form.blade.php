
@extends('layouts.admin')
@section('content')
<div id="profile-app">
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
{{ Form::hidden('astrologer_id', old('astrologer_id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Temp Instructor Detail</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#profile_tab">Profile</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#address_tab">Address</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#area_tab">Area of Expertise</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#bank_tab">Bank Details</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#document_tab">Documents</a></li>
</ul>
<div class="tab-content">
  <div id="profile_tab" class="tab-pane fade in active show">
<div class="row">
    <div class="form-group col-md-3 {{$astrologer->firstname!=$item->firstname?'blink-text':''}}" >
      {!! Form::label('firstname','First name', ['class'=>'required']) !!} <em><small>{{$astrologer->firstname?:'na'}}</small></em>
      {!! Form::text('firstname', old('firstname'),['class'=>'form-control', 'id'=>'firstname', 'onchange'=>'slugify()']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->lastname!=$item->lastname?'blink-text':''}}">
      {!! Form::label('lastname','Last name',['class'=>'required']) !!} <em><small>{{$astrologer->lastname?:'na'}}</small></em>
      {!! Form::text('lastname', old('lastname'),['class'=>'form-control', 'id'=>'lastname', 'onchange'=>'slugify()']) !!}
    </div>
    <div class="form-group col-md-3 d-none {{$astrologer->display_name!=$item->display_name?'blink-text':''}}">
      {!! Form::label('display_name','Display Name',['class'=>'required']) !!} <em><small>{{$astrologer->display_name?:'na'}}</small></em>
      {!! Form::text('display_name', old('display_name'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->email!=$item->email?'blink-text':''}}">
      {!! Form::label('email','Email',['class'=>'required']) !!} <em><small>{{$astrologer->email}}</small></em>
      {!! Form::email('email', old('email'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->phone!=$item->phone?'blink-text':''}}">
      {!! Form::label('phone','Phone',['class'=>'required']) !!} <em><small>{{$astrologer->phone?:'na'}}</small></em>
      {!! Form::number('phone', old('phone'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->gender!=$item->gender?'blink-text':''}}">
      {!! Form::label('gender', 'Gender') !!} <em><small>{{genders($astrologer->gender)}}</small></em>
      {!! Form::select('gender',genders(), old('gender'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->day!=$item->day?'blink-text':''}}">
      {!! Form::label('dob','Date of birth') !!}  <em><small>{{$astrologer->day?:'na'}}</small></em>
      {!! Form::date('dob', $item ? $item->day : '',['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->birth_time!=$item->birth_time?'blink-text':''}}">
      {!! Form::label('birth_time','Time of birth') !!}  <em><small>{{$astrologer->birth_time?:'na'}}</small></em>
      {!! Form::time('birth_time', $item ? $item->birth_time : '',['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-sm-3 {{$astrologer->birth_place!=$item->birth_place?'blink-text':''}}">
        {!! Form::label('birth_place','Birth Place') !!}  <em><small>{{$astrologer->birth_place?:'na'}}</small></em>
        {!! Form::text('birth_place', old('birth_place'),['class'=>'form-control']) !!}
    </div>

    <div class="form-group col-md-12 {{$astrologer->about!=$item->about?'blink-text':''}}">
      {!! Form::label('about', 'About') !!}  <em><small>{!! $astrologer->about?:'na' !!}</small></em>
      {!! Form::textarea('about', old('about'), ['class'=>'form-control']) !!}
    </div>

      <div class="form-group col-md-4 d-flex align-items-center {{$astrologer->image_id!=$item->image_id?'blink-text':''}}">
        @if($astrologer && $astrologer->image)
        <div>
          <img src="{{$astrologer->image->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->image->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('image')">Browse Photograph</button>
         <input type="hidden" name="image_id" value="{{$item?$item->image_id:0}}" id="image_id">
        <div class="image_img_div w-2 ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
          @if($item && $item->image)
            <img src="{{$item->image->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
            <p><a href="{{$item->image->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="image_src" alt="image" class="img-responsive">
          @endif
        </div>
      </div>

</div>
  </div>
  <div id="address_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-sm-12 {{$astrologer->address!=$item->address?'blink-text':''}}">
          {!! Form::label('address','Address') !!} <em><small>{{$astrologer->address?:'na'}}</small></em>
          {!! Form::text('address', old('address'),['class'=>'form-control mceNoEditor']) !!}
      </div>
      <div class="form-group col-sm-3 {{$astrologer->country_id!=$item->country_id?'blink-text':''}}">
          <label>Country</label>  <em><small>{{countryByID($astrologer->country_id)}}</small></em>
          <select name="country_id" v-model="country_id" class="form-control nosearch"  v-on:change="loadMaster('states')">
              <option value="0">Select Country</option>
              @foreach($countries as $country)
                <option value="{{$country->id}}">{{$country->name}}</option>
              @endforeach
          </select>
      </div>
      <div class="form-group col-sm-3 {{$astrologer->state_id!=$item->state_id?'blink-text':''}}">
          <label>State</label>  <em><small>{{stateByID($astrologer->state_id)}}</small></em>
          <select name="state_id" v-model="state_id" class="form-control nosearch" v-on:change="loadMaster('cities')">
              <option v-for="(item, id) in master.states" :key="id" :value="id">@{{item}}</option>
          </select>
      </div>
      <div class="form-group col-sm-3 {{$astrologer->city_id!=$item->city_id?'blink-text':''}}">
          <label>City</label> <em><small>{{cityByID($astrologer->city_id)}}</small></em>
          <select name="city_id" v-model="city_id" class="form-control nosearch" >
              <option v-for="(item, id) in master.cities" :key="id" :value="id">@{{item}}</option>
          </select>
      </div>

      <div class="form-group col-sm-3 {{$astrologer->pincode!=$item->pincode?'blink-text':''}}">
          {!! Form::label('pincode','Pincode') !!} <em><small>{{$astrologer->pincode?:'na'}}</small></em>
          {!! Form::text('pincode', $item ? $item->zip_code : '',['class'=>'form-control']) !!}
      </div>
    </div>
  </div>
  <div id="area_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-md-3 {{$astrologer->job_start_year!=$item->job_start_year?'blink-text':''}}">
        {!! Form::label('job_start_year','Astrology Start Year') !!} <em><small>{{$astrologer->job_start_year?:'na'}}</small></em>
        <select name="job_start_year"  class="form-control">
          <option value="0">select</option>
            @foreach (range(date('Y', strtotime('-100 years')), date('Y')) as $yyyy=>$year)
                <option value="{{$year}}" {{$year==$item->job_start_year?'selected':''}}>{{$year}}</option>
            @endforeach
        </select>
      </div>
      <div class="clearfix w-100">

      </div>
    <div class="form-group col-md-3 {{$astrologer->price!=$item->price?'blink-text':''}}">
      {!! Form::label('price','Video Meeting Price',['class'=>'required']) !!} <em><small>{{$astrologer->price?:'na'}}</small></em>
      {!! Form::text('price', old('price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->special_price!=$item->special_price?'blink-text':''}}">
      {!! Form::label('special_price','Video Meeting Special price') !!}  <em><small>{{$astrologer->special_price?:'na'}}</small></em>
      {!! Form::text('special_price', old('special_price'),['class'=>'form-control']) !!}
    </div>
    <div class="clearfix w-100">

    </div>
    {{-- chat price --}}
    <div class="form-group col-md-3 {{$astrologer->chat_price!=$item->chat_price?'blink-text':''}}">
      {!! Form::label('chat_price','Chat  Price',['class'=>'required']) !!}  <em><small>{{$astrologer->chat_price?:'na'}}</small></em>
      {!! Form::text('chat_price', old('chat_price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->chat_special_price!=$item->chat_special_price?'blink-text':''}}">
      {!! Form::label('chat_special_price','Chat  Special price') !!}  <em><small>{{$astrologer->chat_special_price?:'na'}}</small></em>
      {!! Form::text('chat_special_price', old('chat_special_price'),['class'=>'form-control']) !!}
    </div>
    <div class="clearfix w-100">

    </div>
    {{-- call price --}}
    <div class="form-group col-md-3 {{$astrologer->call_price!=$item->call_price?'blink-text':''}}">
      {!! Form::label('call_price','Call Price',['class'=>'required']) !!}  <em><small>{{$astrologer->call_price?:'na'}}</small></em>
      {!! Form::text('call_price', old('call_price'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->call_special_price!=$item->call_special_price?'blink-text':''}}">
      {!! Form::label('call_special_price',' Call Special price') !!}  <em><small>{{$astrologer->call_special_price?:'na'}}</small></em>
      {!! Form::text('call_special_price', old('call_special_price'),['class'=>'form-control']) !!}
    </div>
    <div class="clearfix w-100">

    </div>
    {{-- call end --}}
    <div class="form-group col-md-3 {{$astrologer->sp_from_date!=$item->sp_from_date?'blink-text':''}}">
      {!! Form::label('sp_from_date','Special Price From Date') !!}  <em><small>{{$astrologer->sp_from_date?:'na'}}</small></em>
      {!! Form::date('sp_from_date', old('sp_from_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3 {{$astrologer->sp_to_date!=$item->sp_to_date?'blink-text':''}}">
      {!! Form::label('sp_to_date','Special Price To Date') !!}  <em><small>{{$astrologer->sp_to_date?:'na'}}</small></em>
      {!! Form::date('sp_to_date', old('sp_to_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="clearfix w-100">

    </div>
    <div class="clearfix w-100"></div>
    <div class="form-group col-md-4 {{$astrologer->languages->count()!=count($item->languages)?'blink-text':''}}">
      <div class="card-body">
            <h5 class="text-orange required">Language</h5>
          <ul class="list-group list-group-flush">
              @foreach($languages as $lang)
              <li class="list-group-item pl-0 bg-light">
                  <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="language-{{$lang->id}}" value="{{$lang->id}}" :checked="languages.length ? languages.indexOf('{{$lang->id}}') > -1 : false"  v-on:change="toggleExpertise('languages', {{$lang->id}})" name="languages[]">
                  <label class="custom-control-label" for="language-{{$lang->id}}">{{$lang->name}}</label>
                  </div>
              </li>
              @endforeach
          </ul>
      </div>
    </div>
    <div class="form-group col-md-4 {{$astrologer->services->count()!=count($item->experties?:[])?'blink-text':''}}">
      <div class="card-body">
          <h5 class="text-orange required">Services</h5>
          <ul class="list-group list-group-flush">
              @foreach($services as $service)
              <li class="list-group-item pl-0 bg-light">
                  <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="service-{{$service->id}}" value="{{$service->id}}" :checked="services.length ? services.indexOf({{$service->id}})>-1 : false" v-on:change="toggleExpertise('services', {{$service->id}})" name="services[]">
                  <label class="custom-control-label" for="service-{{$service->id}}">{{$service->name}}</label>
                  </div>
              </li>
              @endforeach
          </ul>
      </div>
  </div>
  @if(count($timeTable))
  <div class="form-group col-md-4">
    <div class="card-body">
        <h5 class="text-orange">Time Availability</h5>
        <textarea name="timings" id="timings" style="display:none" class="d-none mceNoEditor" >@{{timings}}</textarea>
        <ul class="list-group list-group-flush">
            @foreach(weekDays() as $wday=>$wdayName)
            <li class="list-group-item ">
                <div class="custom-control custom-switch mb-2">
                    <input type="checkbox" class="custom-control-input" id="day-{{$wday}}" value="{{$wday}}" :checked="true"  v-on:change="toggleExpertise('days', {{$wday}})" name="days[]">
                    <label class="custom-control-label" for="day-{{$wday}}"><strong>{{$wdayName}}</strong></label>
                    {{--
                    <button class="btn btn-light float-right" v-on:click="addTime({{$wday}})" type="button">
                      <i class="fa fa-plus"></i>
                    </button>
                    --}}
                </div>
                <div class="clearfix"></div>
                {{--
                <div class="row" v-if="days.indexOf({{$wday}})>-1" v-for="(item, index) in timings[{{$wday}}]">
                --}}
                <div class="row">
                    <div class="form-group col">
                        From
                        <input type="time" class="form-control" v-model="timings[{{$wday}}].from_time">
                    </div>
                    <div class="form-group col">
                        To
                        <input type="time" class="form-control" v-model="timings[{{$wday}}].to_time">
                    </div>
                    {{--
                    <div class="form-group col">
                        <button class="btn btn-danger btn-sm mt-4" type="button" v-if="index!=0" v-on:click="removeTime({{$wday}}, index)"><i class="fa fa-times" ></i></button>
                    </div>
                    --}}
                </div>
            </li>
            @endforeach
        </ul>
    </div>
  </div>
  @endif
  </div>
  </div>

  <div id="bank_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-md-4 {{$astrologer->bank_name!=$item->bank_name?'blink-text':''}}">
        {!! Form::label('bank_name','Bank Name') !!} <em><small>{{$astrologer->bank_name?:'na'}}</small></em>
        {!! Form::text('bank_name', old('bank_name'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->bank_account_name!=$item->bank_account_name?'blink-text':''}}">
        {!! Form::label('bank_account_name','Account Name') !!} <em><small>{{$astrologer->bank_account_name?:'na'}}</small></em>
        {!! Form::text('bank_account_name', old('bank_account_name'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->bank_account_number!=$item->bank_account_number?'blink-text':''}}">
        {!! Form::label('bank_account_number','Account Number') !!} <em><small>{{$astrologer->bank_account_number?:'na'}}</small></em>
        {!! Form::text('bank_account_number', old('bank_account_number'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->bank_account_number_verify!=$item->bank_account_number_verify?'blink-text':''}}">
        {!! Form::label('bank_account_number_verify','Verify Account Number') !!} <em><small>{{$astrologer->bank_account_number_verify?:'na'}}</small></em>
        {!! Form::text('bank_account_number_verify', old('bank_account_number_verify'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->bank_ifsc_code!=$item->bank_ifsc_code?'blink-text':''}}">
        {!! Form::label('bank_ifsc_code','IFSC Code') !!} <em><small>{{$astrologer->bank_ifsc_code?:'na'}}</small></em>
        {!! Form::text('bank_ifsc_code', old('bank_ifsc_code'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->bank_branch!=$item->bank_branch?'blink-text':''}}">
        {!! Form::label('bank_branch','Branch') !!} <em><small>{{$astrologer->bank_branch?:'na'}}</small></em>
        {!! Form::text('bank_branch', old('bank_branch'),['class'=>'form-control']) !!}
      </div>
      {{--
      <div class="form-group col-md-4 {{$astrologer->commission_type!=$item->commission_type?'blink-text':''}}">
        {!! Form::label('commission_type','Commission Type') !!} <em><small>{{$astrologer->commission_type?:'na'}}</small></em>
        {!! Form::select('commission_type',[0=>'Percentage', 1=>'Fixed'], old('commission_type'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-4 {{$astrologer->commission!=$item->commission?'blink-text':''}}">
        {!! Form::label('commission','Commission') !!} <em><small>{{$astrologer->commission?:'na'}}</small></em>
        {!! Form::text('commission', old('commission'),['class'=>'form-control']) !!}
      </div>
      --}}
      <div class="form-group col-md-4 {{$astrologer->pan_card!=$item->pan_card?'blink-text':''}}">
        {!! Form::label('pan_card','Pan Card') !!} <em><small>{{$astrologer->pan_card?:'na'}}</small></em>
        {!! Form::text('pan_card', old('pan_card'),['class'=>'form-control']) !!}
      </div>

      <div class="form-group col-md-4 d-flex align-items-center {{$astrologer->pan_id!=$item->pan_id?'blink-text':''}}">
        @if($astrologer && $astrologer->panId)
        <div>
          <img src="{{$astrologer->panId->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->panId->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('pan_id')">Browse Pan Card</button>
         <input type="hidden" name="pan_id" value="{{$item?$item->pan_id:0}}" id="pan_id_id">
        <div class="pan_id_img_div" data-mediaid="{{$item ? $item->pan_id : 0}}">
          @if($item && $item->panId)
            <img src="{{$item->panId->image(50,50)}}" id="pan_id_src" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'pan_id')"></i>
            <p><a href="{{$item->panId->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="pan_id_src" alt="image" class="img-responsive">
          @endif
        </div>
      </div>
      <div class="form-group col-md-4 d-flex align-items-center {{$astrologer->passbook_id!=$item->passbook_id?'blink-text':''}}">
        @if($astrologer && $astrologer->passbookId)
        <div>
          <img src="{{$astrologer->passbookId->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->passbookId->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('passbook_id')">Browse Passbook</button>
         <input type="hidden" name="passbook_id" value="{{$item?$item->passbook_id:0}}" id="passbook_id_id">
        <div class="passbook_id_img_div w-25" data-mediaid="{{$item ? $item->passbook_id : 0}}">
          @if($item && $item->passbookId)
            <img src="{{$item->passbookId->image(50,50)}}" id="passbook_id_src" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'passbook_id')"></i>
              <p><a href="{{$item->passbookId->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="passbook_id_src" alt="image" class="img-responsive">
          @endif
        </div>
      </div>
   </div>
  </div>
  <div id="document_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-md-4 d-flex align-items-center {{$astrologer->id_proof!=$item->id_proof?'blink-text':''}}">
        @if($astrologer && $astrologer->idProof)
        <div>
          <img src="{{$astrologer->idProof->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->idProof->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('id_proof')">Browse Id Proof</button>
         <input type="hidden" name="id_proof" value="{{$item?$item->id_proof:0}}" id="id_proof_id">
        <div class="id_proof_img_div w-25 ml-3" data-mediaid="{{$item ? $item->id_proof : 0}}">
          @if($item && $item->idProof)
            <img src="{{$item->idProof->image(50,50)}}" id="id_proof_src" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'id_proof')"></i>
              <p><a href="{{$item->idProof->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="id_proof_src" alt="image" class="img-responsive">
          @endif
        </div>
      </div>
      <div class="form-group col-md-4 d-flex align-items-center {{$astrologer->address_proof_id!=$item->address_proof?'blink-text':''}}">
        @if($astrologer && $astrologer->addressProof)
        <div>
          <img src="{{$astrologer->addressProof->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->addressProof->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('address_proof')">Browse Address Proof</button>
         <input type="hidden" name="address_proof" value="{{$item?$item->address_proof:0}}" id="address_proof_id">
        <div class="address_proof_img_div w-25 ml-3" data-mediaid="{{$item ? $item->address_proof : 0}}">
          @if($item && $item->addressProof)
            <img src="{{$item->addressProof->image(50,50)}}" id="address_proof_src" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'address_proof')"></i>
              <p><a href="{{$item->addressProof->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="address_proof_src" alt="image" class="img-responsive">
          @endif
        </div>
      </div>
      <div class="form-group col-md-4 {{$astrologer->signature_id!=$item->signature?'blink-text':''}}">
        @if($astrologer && $astrologer->signatureId)
        <div>
          <img src="{{$astrologer->signatureId->image(50,50)}}" id="image_src" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
          <p><a href="{{$astrologer->signatureId->original_path}}" target="_blank"> View</a></p>
        </div>
        @endif
         <button type="button" class="btn float-left" onclick="openMediaManager('signature')">Browse Signature</button>
         <input type="hidden" name="signature" value="{{$item?$item->signature:0}}" id="signature_id">
        <div class="signature_img_div w-25" data-mediaid="{{$item ? $item->signature : 0}}">
          @if($item && $item->signatureImg)
            <img src="{{$item->signatureImg->image(100,100)}}" id="signature_src" alt="admin image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this, 'signature')"></i>
              <p><a href="{{$item->signatureImg->original_path}}" target="_blank"> View</a></p>
          @else
            <img src="{{no_image()}}" width="100" id="address_proof_src" alt="admin image" class="img-responsive">
          @endif
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
  function slugify(text = null) {
    if(!text){
      text = $('#firstname').val()+' '+$('#lastname').val();
    }
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
  $('document').ready(function (){
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
  });

  var removeImage = function(elm, imagesett){
    console.log(imagesett)
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
    console.log(imageset)
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
          country_id: '{{$item->country}}',
          state_id: '{{$item->state}}',
          city_id: '{{$item->city}}',
          image_id: '{{$item->image_id}}',
          id_proof: '{{$item->id_proof}}', //aadharcard
          address_proof: '{{$item->address_proof}}',
          pan_card: '{{$item->pan_card}}',
          pan_id: '{{$item->pan_id}}',
          passbook_id: '{{$item->passbook_id}}',
          image_id_src: '{{$item->image ? $item->image->image(200) : no_image()}}',
          id_proof_src: '{{$item->idProof ? $item->idProof->image(200) : no_image()}}',
          address_proof_src: '{{$item->addressProof ? $item->addressProof->image(200) : no_image()}}',
          pan_id_src: '{{$item->panId ? $item->panId->image(200) : no_image()}}',
          passbook_id_src: '{{$item->passbookId ? $item->passbookId->image(200) : no_image()}}',
          signature: '{{$item->signature}}',
          signature_src: '{{$item->signatureImg ? $item->signatureImg->image(200) : no_image()}}',
          languages : @json($item->languages) ? @json($item->languages) : [],
          services : @json($item->experties) ? @json($item->experties) : [],
          // days : {!! json_encode(array_values($item->timetable?$item->timetable->pluck('week_day')->unique()->toArray():[])) !!},
          days : [1,2,3,4,5,6,0],
          timings : @json($timeTable),
          master: {
              states: [],
              cities: []
          },
          errors: {},
          upload_type : '',
          successmessage:'',
          errormessage:'',
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
@endpush
