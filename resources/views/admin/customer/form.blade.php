@extends('layouts.admin')
@section('content')
<div id="profile-app">
  {!! Form::model($item, ['route' => 'admin.customer.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
  @if($item)
  {{ Form::hidden('id', old('id')) }}
  @endif
  <div class="row">
    <div class="col-md-6">
      <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
    </div>
    <div class="col-md-6 text-right mt-2">
       <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

    </div>
  </div>
  <div class="clearfix"></div>
  <ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#profile_tab">Profile</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#birth_details">Birth Details</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#address_details">Address Details</a></li>
    @if($item)
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#shipping_addresses">Shipping Details</a></li>
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#rechare_history">Recharge</a></li>
      {{--<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#education">Education</a></li>--}}
      {{--<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#course_purchased">Courses</a></li>--}}
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#consultation_history">Consultation</a></li>
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#orders">Orders</a></li>
      <li class="nav-item" id="bank_detail_tab_li" style="display: none;"><a data-toggle="tab" class="nav-link" href="#bank_detail_tab">Bank Detail</a></li>
    @endif
  </ul>
  <div class="tab-content">
    <div id="profile_tab" class="tab-pane fade in active show">
      <div class="row">
        <div class="form-group col-md-3">
          {!! Form::label('name','Name', ['class'=>'required']) !!}
          {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('email','Email',['class'=>'required']) !!}
          {!! Form::email('email', old('email'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('phone','Phone',['class'=>'required']) !!}
          {!! Form::text('phone', old('phone'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('wallet','Wallet',['class'=>'required']) !!}
          {!! Form::number('wallet', old('wallet'),['class'=>'form-control', 'min'=>'0']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('bonus','Bonus/Gift',['class'=>'required']) !!}
          {!! Form::number('bonus', 0,['class'=>'form-control', 'min'=>'0']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('gender', 'Gender') !!}
          {!! Form::select('gender',genders(), old('gender'), ['class'=>'form-control nosearch']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('language_id', 'Preferred Language') !!}
          {!! Form::select('language_id',$languages, old('language_id'), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('status', 'User Status') !!}
          {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control nosearch']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('is_verified', 'Account Verified') !!}
          {!! Form::select('is_verified',yesNoArray(), old('is_verified'), ['class'=>'form-control nosearch']) !!}
        </div>
        <div class="col-md-3">
          {{ Form::label('image_id', 'Image', ['class' => 'control-label']) }}
           <div class="form-group">
           <div class="d-flex align-items-center">
             <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
            {{ Form::hidden('image_id', old('image_id')) }}
            {{ Form::hidden('image_src', old('image_src',  $item && $item->image ? $item->image->image(200,200) : ''),['id'=>'image_src']) }}
            @if(old('image', $item ? $item->image_id : ''))
            <div class="profile_image ml-2 align-item-center" data-mediaid="{{old('image', $item ? $item->image_id : '')}}">
              <img src="{{old('image_src', $item && $item->image ? $item->image->image(100,100) : '')}}" alt="image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="form-group col-md-3">
          {!! Form::label('is_influencer', 'Make as Influencer', ['class' => 'control-label']) !!}
          <div class="d-flex align-items-center mt-2">
            <input type="checkbox" class="toggle-check" id="is_influencer" value="1" name="is_influencer" {{old('is_influencer', $item && $item->is_influencer ? $item->is_influencer : 0) == 1 ? 'checked' : ''}} onchange="toggleInfluencerBankTab()">
            <label class="mx-2 t-lable" for="is_influencer"></label>
          </div>
        </div>
      </div>
    </div>
    <div id="birth_details" class="tab-pane fade">
      <div class="row">
        <div class="form-group col-md-3">
          {!! Form::label('birth_date','Date of birth') !!}
          <div class="row">
            {!! Form::date('birth_date', old('birth_date'), ['class'=>'form-control']) !!}
            {{--
            <div class="col-12 col-sm-4">
              <!-- <label>Day</label> -->
              {!! Form::select('dob_dd',monthDays(), old('dob_dd'), ['class'=>'form-control']) !!}
            </div>
            <div class="col-12 col-sm-4">
              <!-- <label>Month</label> -->
              {!! Form::select('dob_mm',astroMonths(), old('dob_mm'), ['class'=>'form-control']) !!}

            </div>
            <div class="col-12 col-sm-4">
              <!-- <label>Year</label> -->
              <select name="dob_yyyy" class="form-control">
                @foreach (range(date('Y', strtotime('-100 years')), date('Y', strtotime('-18 years'))) as $yyyy=>$year)
                <option value="{{$year}}" {{$year==$item->dob_yyyy?'selected':''}}>{{$year}}</option>
                @endforeach
              </select>
            </div>
            --}}
          </div>
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('birth_time','Birth time') !!}
          {!! Form::time('birth_time', old('birth_time'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('birth_place','Birth Place') !!}
          {!! Form::text('birth_place', old('birth_place'),['class'=>'form-control']) !!}
        </div>
      </div>
    </div>
    <div id="address_details" class="tab-pane fade">
      <div class="row">
        <div class="form-group col-md-6">
          {!! Form::label('address','Address') !!}
          {!! Form::text('address', old('address'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-sm-3">
          <label>Country</label>
          <select name="country_id" v-model="country_id" class="form-control nosearch"
            v-on:change="loadMaster('states')">
            <option value="0">Select Country</option>
            @foreach($countries as $country)
            <option value="{{$country->id}}">{{$country->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-sm-3">
          <label>State</label>
          <select name="state_id" v-model="state_id" class="form-control nosearch" v-on:change="loadMaster('cities')">
            <option v-for="(item, id) in master.states" :key="id" :value="id">@{{item}}</option>
          </select>
        </div>
        <div class="form-group col-sm-3">
          <label>City</label>
          <select name="city_id" v-model="city_id" class="form-control nosearch">
            <option v-for="(item, id) in master.cities" :key="id" :value="id">@{{item}}</option>
          </select>
        </div>

        <div class="form-group col-sm-3">
          {!! Form::label('pincode','Pincode') !!}
          {!! Form::text('pincode', old('pincode'),['class'=>'form-control']) !!}
        </div>
      </div>
    </div>
    <div id="shipping_addresses" class="tab-pane fade">
      <div class="row">
        <div class="col-10"></div>
        <div class="col-2">
          <button type="button" class="btn btn-primary m-1 float-right">
            <i class="bi bi-plus" onclick="handleShippingAddress(this,'add',0,'','','',0,0,0,'','')"></i>
          </button>
        </div>
        <div class="table-responsive px-4">
          <table class="table">
          <thead>
            <tr class="bg-primary text-white">
              <th># <i class="icon"></i></th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($shippingAddresses as $index=>$itemm)
              <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $itemm->name }}</td>
                <td>{{ $itemm->email }}</td>
                <td>{{ $itemm->phone }}</td>
                <td>{{ $itemm->country }}</td>
                <td>{{ $itemm->state }}</td>
                <td>{{ $itemm->city }}</td>
                <td>
                  <i class="fa fa-edit text-success pointer" onclick="handleShippingAddress(this,'edit','{{ $itemm->id }}','{{ $itemm->name }}','{{ $itemm->email }}','{{ $itemm->phone }}','{{ $itemm->country_id }}','{{ $itemm->state_id }}','{{ $itemm->city_id }}','{{ $itemm->address }}','{{ $itemm->zipcode }}')"></i>
                  |
                  <i class="fa fa-trash text-danger pointer" onclick="handleDeleteShippingAddress(this,'{{ $itemm->id }}')"></i>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
              <tr>
                  <td colspan="10" class="pagination"></td>
              </tr>
          </tfoot>
        </table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="education">
        <div class="row">
          <div class="form-group col-md-6">
            {!! Form::label('education[qualification]','Qualification') !!}
            {!! Form::text('education[qualification]', old('education[qualification]'),['class'=>'form-control']) !!}
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('education[institution]','Institute') !!}
            {!! Form::text('education[institution]', old('education[institution]'),['class'=>'form-control']) !!}
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('education[passing_year]','Passing Year') !!}
            {!! Form::number('education[passing_year]', old('education[passing_year]'),['class'=>'form-control', 'max'=>date('Y'), 'min'=>date('Y', strtotime('-50 years'))]) !!}
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('education[division_grade]','Grade') !!}
            {!! Form::text('education[division_grade]', old('education[division_grade]'),['class'=>'form-control']) !!}
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('education[marks]','Marks') !!}
            {!! Form::text('education[marks]', old('education[marks]'),['class'=>'form-control']) !!}
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('education[experience]','Astrology Experience (if any)') !!}
            {!! Form::text('education[experience]', old('education[experience]'),['class'=>'form-control']) !!}
          </div>
          <div class="form-group col-md-6">
              <p>Image</p>
               <img src="{{$item && $item->additionalInformation && $item->additionalInformation->image ? $item->additionalInformation->image->image(100, 100): no_image()}}" alt="file" class="img-responsive border border-1">
               <p><a href="{{$item && $item->additionalInformation && $item->additionalInformation->image ? $item->additionalInformation->image->image(100, 100): no_image()}}" target="_blank">View</a></p>
          </div>
        </div>
    </div>
    <div id="course_purchased" class="tab-pane fade">
      <div class="row">
        <div class="col-md-12">
              <table class="table table-dashed">
                  <thead>
                    <tr class="bg-primary text-white">
                      <th>Sr.</th>
                      <th>Course</th>
                      <th>Certificate</th>
                      <th>Percentage</th>
                      <th>Expire Date</th>
                      <th>Amount</th>
                      <th>Purchased Date</th>
                      <th>Notify</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($item->allcourses as $n=>$course)
                    @php
                      $percentage = $course->percentage;
                    @endphp
                      <tr>
                        <td>{{++$n}}</td>
                        <td><a href="{{route('admin.order.edit', $course->order_id)}}" class="text-primary" target="_blank">{{$course->course_name}}</a></td>
                        <th>
                            @if($percentage>=100)
                              @php
                                $certificate = downloadCertificate($item, $course->user_type, $course->course);
                              @endphp
                              @if($certificate)
                                <a href="{{$certificate}}" target="_blank" class="text-success"><i class="fa fa-check-circle fa-2x"></i></a>
                              @endif
                            @else
                             -
                            @endif
                        </th>
                        <td>{{$percentage}}</td>
                        <td>{{date('d-m-Y', strtotime($course->expire_date))}}</td>
                        <td>{{$course->amount}}</td>
                        <td>{{date('d-m-Y', strtotime($course->created_at))}}</td>
                        <td> <a href="#">Reminder</a> </td>

                      </tr>
                    @endforeach
                  </tbody>
              </table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade"  id="rechare_history">
      <div class="row">
        <div class="col-md-12">
              <table class="table table-dashed">
                  <thead>
                    <tr class="bg-primary text-white">
                      <th>Sr.</th>
                      <th>Recharge Number</th>
                      <th>Invoice Number</th>
                      <th>Amount</th>
                      <th>Payment Status</th>
                      <th>Transaction Date</th>
                      <th>Notify</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($item->rechargeHistory as $n=>$rechargeHistory)

                      <tr>
                        <td>{{++$n}}</td>
                        <td>
                          @if(adminCan('user-recharge','update'))
                          <a href="{{route('admin.user_recharge.edit', [$rechargeHistory->id])}}" class="text-primary" target="_blank">
                            {{$rechargeHistory->my_txn_id}}
                          </a>
                          @else
                            {{$rechargeHistory->my_txn_id}}
                          @endif
                        </td>
                        <td>
                          @if($rechargeHistory->invoice)
                            <a href="{{route('admin.invoice.download', [$rechargeHistory->invoice->id])}}" class="text-primary" target="_blank">
                              {{$rechargeHistory->invoice->prefix.$rechargeHistory->invoice->number}}
                            </a>
                          @else
                              -
                          @endif
                        </td>
                        <td>{{$rechargeHistory->amount}}</td>
                        <td>{{ucfirst($rechargeHistory->payment_status)}}</td>
                        <td>{{$rechargeHistory->payment_date_time ? date('d-m-Y', strtotime($rechargeHistory->payment_date_time)) : '-'}}</td>
                        <td>
                          @if(adminCan('user-recharge','update'))
                          <a href="{{route('admin.user_recharge.edit', [$rechargeHistory->id])}}" class="text-primary" target="_blank">
                            <i class="fa fa-edit"></i>
                          </a>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
              </table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade"  id="consultation_history">
      <div class="row">
        <div class="col-md-12">
              <table class="table table-dashed">
                  <thead>
                    <tr class="bg-primary text-white">
                      <th>Sr.</th>
                      <th>Astrologer</th>
                      <th>CNS Mode</th>
                      <th>Schedule For</th>
                      <th>Time Slot</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Cancel</th>
                      <th>Reschedule</th>
                      <th>Updated At</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $userType = 'user';
                    @endphp
                    @foreach($item->appointments->sortByDesc('created_at') as $n=>$appointment)
                    @php
                      $mode = [1=>'video', '2'=>'call', '3'=>'chat'];
                    @endphp
                      <tr>
                        <td>{{++$n}}</td>
                        <td>
                          @if(adminCan(strtolower($userType).'-'.$mode[$appointment->package].'-consultation', 'update'))
                              <a href="{{route('admin.consultation.'.$mode[$appointment->package].'.view', [$userType, $appointment->id])}}" target="_blank">
                                {{$appointment->astrologer ? $appointment->astrologer->display_name : ''}}
                              </a>
                          @else
                            {{$appointment->astrologer ? $appointment->astrologer->display_name : ''}}
                          @endif
                        </td>
                        <td>{{ucfirst($mode[$appointment->package])}}</td>

                        <td>{{ $appointment->scheduled_for?'Later':'Now'}}</td>
                        <td>{{ $appointment->timeslot}} Min</td>
                        <td>₹ {{ $appointment->total_price}}</td>
                        <td>{{$appointment->reschedule?'rescheduled':($appointment->status?:'pending')}}</td>
                        <td>
                          <div>
                            @if(count(consultationRequests($appointment->id, 'cancel', 'astrologer')))
                              @if(strtolower($userType)=='astrologer')
                              A:
                              @else
                              I:
                              @endif
                              @foreach(consultationRequests($appointment->id, 'cancel', 'astrologer') as $key => $item)
                                @if($item->status == 1)
                                <i class="far fa-window-close text-success"></i>
                                @elseif($item->status == 2)
                                <i class="far fa-window-close text-danger"></i>
                                @else
                                <i class="far fa-window-close"></i>
                                @endif
                              @endforeach
                            @endif
                          </div>
                            <div>
                              @if(count(consultationRequests($appointment->id, 'cancel', 'user')))
                              U:
                                @foreach(consultationRequests($appointment->id, 'cancel', 'user') as $key => $item)
                                  @if($item->status == 1)
                                  <i class="far fa-window-close text-success"></i>
                                  @elseif($item->status == 2)
                                  <i class="far fa-window-close text-danger"></i>
                                  @else
                                  <i class="far fa-window-close"></i>
                                  @endif
                                @endforeach
                              @endif
                            </div>
                        </td>
                        <td>
                          <div>
                            @if(count(consultationRequests($appointment->id, 'reschedule', 'astrologer')))
                            A:
                              @foreach(consultationRequests($appointment->id, 'reschedule', 'astrologer') as $key => $item)
                                @if($item->status == 1)
                                <i class="far fa-window-close text-success"></i>
                                @elseif($item->status == 2)
                                <i class="far fa-window-close text-danger"></i>
                                @else
                                <i class="far fa-window-close"></i>
                                @endif
                              @endforeach
                            @endif
                          </div>
                            <div>
                              @if(count(consultationRequests($appointment->id, 'reschedule', 'user')))
                              U:
                                @foreach(consultationRequests($appointment->id, 'reschedule', 'user') as $key => $item)
                                  @if($item->status == 1)
                                  <i class="far fa-window-close text-success"></i>
                                  @elseif($item->status == 2)
                                  <i class="far fa-window-close text-danger"></i>
                                  @else
                                  <i class="far fa-window-close"></i>
                                  @endif
                                @endforeach
                              @endif
                            </div>
                        </td>
                        <td>{{ISTDatetimeFormat($appointment->updated_at)}}</td>
                        <td class="text-right">
                          @if(adminCan(strtolower($userType).'-'.$mode[$appointment->package].'-consultation', 'update'))
                          <a href="{{route('admin.consultation.'.$mode[$appointment->package].'.view', [$userType, $appointment->id])}}" class="text-success" target="_blank">
                            <i class="fa fa-edit"></i>
                          </a>
                          @endif
                        </td>

                      </tr>
                    @endforeach
                  </tbody>
              </table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="orders">
      <div class="row">
        <div class="col-1">
          <a href="{{ route('admin.order.create',['user_id'=>($item?$item->id:0)]) }}" class="btn btn-primary text-white" target="_blank">Create</a>
        </div>
        <div class="col-md-12">
              <table class="table table-dashed">
                  <thead>
                    <tr class="bg-primary text-white">
                      <th>Sr.</th>
                      <th>Order #</th>
                      <th>Invoice #</th>
                      <th>Status</th>
                      <th>Amount</th>
                      <th>Purchased Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($item->orders as $n=>$order)
                      <tr>
                        <td>{{++$n}}</td>
                        <td><a href="{{route('admin.order.edit', $order->id)}}" class="text-primary" target="_blank">{{$order->order_number}}</a></td>
                        <td>
                          @if($order->invoice)
                            <a href="{{route('admin.invoice.download', [$order->invoice->id])}}" class="text-primary" target="_blank">
                              {{$order->invoice->prefix.$order->invoice->number}}
                            </a>
                          @else
                          -
                          @endif
                        </td>
                        <td>{{$order->order_status}}</td>
                        <td>{{$order->total_price}}</td>
                        <td>{{date('d-m-Y', strtotime($order->created_at))}}</td>
                      </tr>
                    @endforeach
                  </tbody>
              </table>
        </div>
      </div>
    </div>
    <div id="bank_detail_tab" class="tab-pane fade">
      <div class="row">
        <div class="form-group col-md-4">
          {!! Form::label('account_number','Account Number') !!}
          {!! Form::text('account_number', old('account_number', $item ? $item->account_number : ''),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('ifsc_code','IFSC Code') !!}
          {!! Form::text('ifsc_code', old('ifsc_code', $item ? $item->ifsc_code : ''),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('upi_id','UPI ID') !!}
          {!! Form::text('upi_id', old('upi_id', $item ? $item->upi_id : ''),['class'=>'form-control']) !!}
        </div>
        <div class="col-md-4">
          {{ Form::label('passbook_image_id', 'Passbook Photo', ['class' => 'control-label']) }}
           <div class="form-group">
           <div class="d-flex align-items-center">
             <button type="button" class="btn float-left" id="select-passbook-image" onclick="openPassbookMediaManager()">Upload Passbook</button>
            {{ Form::hidden('passbook_image_id', old('passbook_image_id', $item ? $item->passbook_image_id : ''),['id'=>'passbook_image_id']) }}
            {{ Form::hidden('passbook_image_src', old('passbook_image_src', $item && $item->passbookImage ? $item->passbookImage->image(200,200) : ''),['id'=>'passbook_image_src']) }}
            @if(old('passbook_image_id', $item ? $item->passbook_image_id : ''))
            <div class="profile_image ml-2 align-item-center passbook_image" data-mediaid="{{old('passbook_image_id', $item ? $item->passbook_image_id : '')}}">
              <img src="{{old('passbook_image_src', $item && $item->passbookImage ? $item->passbookImage->image(100,100) : '')}}" alt="passbook" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removePassbookImage(this)"></i>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="text-center mt-4">
  </div>
  {!! Form::close() !!}

  <!-- shipping address modal start -->
  <div class="modal fade" id="shippingAddressModal" tabindex="-1" role="dialog" aria-labelledby="shippingAddressModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shippingAddressModalLabelled">Shipping Address</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="shipping_id" id="shipping_id" class="form-control">
            <div class="form-group col-4">
              <label>Shipping Name</label>
              <input type="text" name="shipping_name" id="shipping_name" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Shipping Email</label>
              <input type="text" name="shipping_email" id="shipping_email" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Shipping Phone</label>
              <input type="text" name="shipping_phone" id="shipping_phone" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Shipping Country</label>
              <select name="shipping[country_id]" v-model="shipping.country_id" class="form-control nosearch" v-on:change="loadShippingMaster('states')">
                <option value="0">Select Country</option>
                @foreach($countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-4">
              <label>Shipping State</label>
              <select name="shipping[state_id]" v-model="shipping.state_id" class="form-control nosearch" v-on:change="loadShippingMaster('cities')">
                <option v-for="(item, id) in mastershipping.states" :key="id" :value="id">@{{item}}</option>
              </select>
            </div>
            <div class="form-group col-4">
              <label>Shipping City</label>
              <select name="shipping[city_id]" v-model="shipping.city_id" class="form-control nosearch">
                <option v-for="(item, id) in mastershipping.cities" :key="id" :value="id">@{{item}}</option>
              </select>
            </div>
            <div class="form-group col-8">
              <label>Shipping Address</label>
              <input type="text" name="shipping_address" id="shipping_address" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Shipping Pincode</label>
              <input type="text" name="shipping_pincode" id="shipping_pincode" class="form-control">
            </div>
          </div>
          <button type="button" class="btn btn-primary m-1" onclick="saveShippingAddress(this)">
            submit
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- shipping address modal end -->

</div>
@include('admin.media.manager')
@endsection

@push('scripts')
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
      master: {
        states: [],
        cities: []
      },
      mastershipping: {
        states: [],
        cities: []
      },
      billing:{
        country_id: '{{$item->country_id}}',
        state_id: '{{$item->state_id}}',
        city_id: '{{$item->city_id}}'
      },
      shipping:{
        country_id: '{{$shipping?$shipping->country_id:''}}',
        state_id: '{{$shipping?$shipping->state_id:''}}',
        city_id: '{{$shipping?$shipping->city_id:''}}'
      },
      errors: {},
      upload_type: '',
      successmessage: '',
      errormessage: '',
    },
    methods: {
      loadMaster(options) {
        let that = this;
        let url = '{{route('locations')}}';
        if (options == 'states') {
          url += '/master_states/?country_id=' + that.country_id
        } else if (options == 'cities') {
          url += '/master_cities/?state_id=' + that.state_id
        }
        axios.post(url)
          .then(function (response) {
            that.master[options] = response.data;
          })
          .catch(function (response) {
          })
      },
      loadShippingMaster(options) {
        let that = this;
        let url = '{{route('locations')}}';
        if (options == 'states') {
          url += '/master_states/?country_id=' + that.shipping.country_id
        } else if (options == 'cities') {
          url += '/master_cities/?state_id=' + that.shipping.state_id
        }
        axios.post(url)
          .then(function (response) {
            that.mastershipping[options] = response.data;
          })
          .catch(function (response) {
          })
      }
    },
    mounted() {
      @if ($item && $item -> id)
        this.loadMaster('states');
        this.loadMaster('cities');
        this.loadShippingMaster('states');
        this.loadShippingMaster('cities');
      @endif
    }
  });


  // Toggle Bank Detail Tab based on Influencer checkbox
  function toggleInfluencerBankTab() {
    var isChecked = $('#is_influencer').is(':checked');
    if(isChecked) {
      $('#bank_detail_tab_li').show();
    } else {
      $('#bank_detail_tab_li').hide();
      // Switch to profile tab if bank detail tab is active
      if($('#bank_detail_tab').hasClass('active')) {
        $('a[href="#profile_tab"]').tab('show');
      }
    }
  }

  // Initialize on page load
  $(document).ready(function() {
    toggleInfluencerBankTab();
  });

  var media_image_type = 'users';
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
    if(imageset==='featured'){
      $('#image_id').val(mediaId);
      $('#image_src').val(imgSrc);
      $('.profile_image').not('.passbook_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
      $('#image_id').after(newImg)
    }
    else if(imageset==='passbook'){
      $('#passbook_image_id').val(mediaId);
      $('#passbook_image_src').val(imgSrc);
      $('.passbook_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image passbook_image float-right ml-2');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removePassbookImage(this)"></i>');
      $('#passbook_image_id').after(newImg)
    }
    // else if(imageset==='slide'){
    //     myVue.updateSideImage(mediaId, imgSrc.replace('200-200/', ''));
    // }
    else if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'featured';
    }
    $('#media-img-manager').modal('hide');
  });
 var removeImage = function(elm){
      $(elm).parents('.profile_image').remove();
      $('#image_id').val('');
      $('#image_src').val('');
  }
  $('#title').on('keyup blur', function(){
      var value = $(this).val();
      $('#slug').val(value);
  });

    var addImageToSlide = function() {
      imageset = 'slide';
      $('#media-img-manager').modal('show');
    }
    $("#media-img-manager").on('hidden.bs.modal', function (e) {
      imageset = 'featured';
    });
    var openMediaManager = function() {
      imageset = 'featured';
      $('#media-img-manager').modal('show');
    }
    var openPassbookMediaManager = function() {
      imageset = 'passbook';
      $('#media-img-manager').modal('show');
    }
    var removePassbookImage = function(elm){
      $(elm).parents('.passbook_image').remove();
      $('#passbook_image_id').val('');
      $('#passbook_image_src').val('');
    }
    function openShippingModal(){
      $('#shippingAddressModal').modal('show');
    }
    function closeShippingModal(){
      $('#shippingAddressModal').modal('hide');
    }
    function handleShippingAddress(that,action,id,name,email,phone,country_id,state_id,city_id,address,pincode){
      $("#shipping_id").val(id?id:"");
      $("#shipping_name").val(name?name:"");
      $("#shipping_email").val(email?email:"");
      $("#shipping_phone").val(phone?phone:"");
      profileapp._data.shipping.country_id=country_id;
      if(country_id){
        profileapp.loadShippingMaster("states");
      }
      profileapp._data.shipping.state_id=state_id;
      if(state_id){
        profileapp.loadShippingMaster("cities");
      }
      profileapp._data.shipping.city_id=city_id;
      $("#shipping_address").val(address?address:"");
      $("#shipping_pincode").val(pincode?pincode:"");
      console.log(profileapp)
      console.log(profileapp._data)
      openShippingModal();
    }
    function saveShippingAddress(that){
      $.ajax({
         url: "{{ route('admin.customer.saveshippingaddress') }}",
         type : 'GET',
         data : {
           user_id:'{{ $item?$item->id:0 }}',
           shipping_id:$("#shipping_id").val(),
           shipping_name:$("#shipping_name").val(),
           shipping_email:$("#shipping_email").val(),
           shipping_phone:$("#shipping_phone").val(),
           shipping_country_id:profileapp._data.shipping.country_id,
           shipping_state_id:profileapp._data.shipping.state_id,
           shipping_city_id:profileapp._data.shipping.city_id,
           shipping_address:$("#shipping_address").val(),
           shipping_pincode:$("#shipping_pincode").val(),
         },
         success:function(response){
           toastr.success(response.message);
           setTimeout(function(){
             window.location.reload();
           },1500);
         },
         error:function(error){
           toastr.error("Error occured");
         }
       });
    }
    function handleDeleteShippingAddress(that,id){
      $.ajax({
         url: "{{ route('admin.customer.deleteshippingaddress') }}",
         type : 'GET',
         data : {id},
         success:function(response){
           toastr.success(response.message);
           setTimeout(function(){
             window.location.reload();
           },1500);
         },
         error:function(error){
           toastr.error("Error occured");
         }
       });
    }
</script>
@endpush
