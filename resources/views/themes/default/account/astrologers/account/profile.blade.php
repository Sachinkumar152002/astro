@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-sm-9" id="profile-app">
            <div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 style="display: none;" v-show="successmessage!=''"
                                class="alert alert-success text-center" v-html="successmessage"></h3>
                            <p style="display: none;" v-show="errormessage!=''" class="alert alert-danger"
                                v-html="errormessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs " id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active text-dark" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">My Profile</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-dark" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Area of Expertise</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-dark" id="bank-tab" data-bs-toggle="tab" href="#bank" role="tab"
                        aria-controls="bank" aria-selected="true">Bank Details</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane mb-5 fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form class="mt-4">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group mb-2">
                                    <label>About Me <em class="text-danger">*</em></label>
                                    <textarea class="form-control" rows="4" placeholder="About Me" name="profile[about]"
                                        v-model="profile.about"></textarea>
                                </div>
                                <h4>Profile Info</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>First Name <em class="text-danger">*</em></label>
                                        <input type="text" class="form-control" placeholder="First Name"
                                            name="profile[firstname]" v-model="profile.firstname"
                                            @if($user->is_verified) readonly @endif>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>Last Name <em class="text-danger">*</em></label>
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            name="profile[lastname]" v-model="profile.lastname" @if($user->is_verified)
                                        readonly @endif>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>Display Name <em class="text-danger">*</em></label>
                                        <input type="text" class="form-control" placeholder="Display Name"
                                            name="profile[display_name]" v-model="profile.display_name"
                                            @if($user->is_verified) readonly @endif>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>Phone <em class="text-danger">*</em></label>
                                        <input type="text" class="form-control" placeholder="Phone"
                                            name="profile[phone]" v-model="profile.phone" disabled>
                                    </div>
                                    <div class="form-group col-sm-12 mb-3">
                                        <label>Email Address <em class="text-danger">*</em></label>
                                        <input type="email" class="form-control" placeholder="Email"
                                            name="profile[email]" v-model="profile.email" readonly>
                                    </div>
                                    <div class="form-group col-sm-12 mb-3">
                                        <label>Date of birth <em class="text-danger">*</em></label>
                                        <div class="row">
                                            <div class="col-12 col-sm-4 mb-3">
                                                <label>Day</label>
                                                <select name="profile[dob_dd]" v-model="profile.dob_dd"
                                                    class="form-control">
                                                    @foreach (monthDays() as $dd=>$day)
                                                    <option value="{{$dd}}">{{$day}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-4 mb-3">
                                                <label>Month</label>
                                                <select name="profile[dob_mm]" v-model="profile.dob_mm"
                                                    class="form-control">
                                                    @foreach (astroMonths() as $mm=>$month)
                                                    <option value="{{$mm}}">{{$month}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-4 mb-3">
                                                <label>Year</label>
                                                <select name="profile[dob_yyyy]" v-model="profile.dob_yyyy"
                                                    class="form-control">
                                                    @foreach (range(date('Y', strtotime('-100 years')), date('Y',
                                                    strtotime('-18 years'))) as $yyyy=>$year)
                                                    <option value="{{$year}}">{{$year}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>Gender<em class="text-danger">*</em></label>
                                        <select name="gender" class="form-control" name="profile[gender]"
                                            v-model="profile.gender">
                                            @foreach (genders() as $gid=>$gname)
                                            <option value="{{$gid}}">{{$gname}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-12 mb-3">
                                        <label>Address</label>
                                        <input type="text" class="form-control" placeholder="Address"
                                            name="profile[address]" v-model="profile.address">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>County</label>
                                        <select name="profile[country_id]" v-model="profile.country_id"
                                            class="form-select custom-select" v-on:change="loadMaster('states')">
                                            <option value="0">Select Country</option>
                                            @foreach($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>State</label>
                                        <select name="profile[state_id]" v-model="profile.state_id"
                                            class="form-select custom-select" v-on:change="loadMaster('cities')">

                                            <option v-for="(item, id) in master.states" :key="id" :value="id">@{{item}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label>City</label>
                                        <select name="profile[city_id]" v-model="profile.city_id"
                                            class="form-select custom-select">
                                            <option v-for="(item, id) in master.cities" :key="id" :value="id">@{{item}}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-6 mb-3">
                                        <label>Zip Code</label>
                                        <input type="text" class="form-control" placeholder="Zip Code"
                                            name="profile[pincode]" v-model="profile.pincode">
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="button" v-on:click="saveData('profile')"
                                            class="btn btn-warning">Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card bg-light mb-3">
                                    <div class="card-body" v-on:click="openModalUpload('image_id')">
                                        <div class="media">
                                            <input type="hidden" name="profile[image_id]" v-model="profile.image_id">
                                            <img :src="profile.image_id_src" class="mr-3 w-25 border rounded-circle"
                                                alt="profile_image">
                                            <div class="media-body pt-3">
                                                <p><strong>My Photo</strong> </p>
                                                <button type="button" class="btn btn-warning btn-sm float-right">Upload
                                                    Photo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-light mb-3">
                                    <div class="card-body" v-on:click="openModalUpload('id_proof')">
                                        <div class="media">
                                            <div class="media-body">
                                                <input type="hidden" name="profile[id_proof]"
                                                    v-model="profile.id_proof">
                                                <p><strong>My Id Prof</strong> <span class="float-right"> <a
                                                            v-if="profile.id_proof_src!=''"
                                                            :href="profile.id_proof_src"><i
                                                                class="fa fa-eye"></i></a></span></p>
                                                <button type="button" class="btn btn-warning btn-sm float-right">Upload
                                                    ID Proof</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-light mb-3">
                                    <div class="card-body" v-on:click="openModalUpload('address_proof')">
                                        <div class="media">
                                            <div class="media-body">
                                                <input type="hidden" name="profile[address_proof]"
                                                    v-model="profile.address_proof">
                                                <p><strong>My Address proof</strong> <span class="float-right"> <a
                                                            v-if="profile.address_proof_src!=''"
                                                            :href="profile.address_proof_src"><i
                                                                class="fa fa-eye"></i></a></span></p>
                                                <button type="button" class="btn btn-warning btn-sm float-right">Upload
                                                    Address Proof</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane mb-5 fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="mt-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-orange">Career Start Date <em class="text-danger">*</em></h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-light">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select name="expertise[job_start_year]" class="form-control"
                                                            v-model="expertise.job_start_year">
                                                            <option value="0">select</option>
                                                            @foreach (range(date('Y', strtotime('-100 years')),
                                                            date('Y')) as $yyyy=>$year)
                                                            <option value="{{$year}}">{{$year}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-orange">Call/Chat Charges</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-light">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Price per Minute <em class="text-danger">*</em></label>
                                                        <input type="number" class="form-control" min="0.1"
                                                            name="expertise[price]" v-model="expertise.price"
                                                            v-on:focusout="expertise.price = parseFloat(expertise.price).toFixed(2)">
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <label>Special Price per Minute</label>
                                                        <input type="number" class="form-control" min="0"
                                                            :max="parseInt(expertise.price)"
                                                            name="expertise[special_price]"
                                                            v-model="expertise.special_price" v-on:keyup="checkandfixSP"
                                                            v-on:change="checkandfixSP"
                                                            v-on:focusout="expertise.special_price = parseFloat(expertise.special_price).toFixed(2)">
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label>Special Price From</label>
                                                        <input type="date" class="form-control" min="{{date('Y-m-d')}}"
                                                            :max="expertise.sp_to_date?expertise.sp_to_date:''"
                                                            name="expertise[sp_from_date]"
                                                            v-model="expertise.sp_from_date">
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Special Price To</label>
                                                        <input type="date" class="form-control"
                                                            :min="expertise.sp_from_date?expertise.sp_from_date:'{{date('Y-m-d')}}'"
                                                            name="expertise[sp_to_date]" v-model="expertise.sp_to_date">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="text-orange">Languages <em class="text-danger">*</em></h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach($languages as $lang)
                                            <li class="list-group-item pl-0 bg-light">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="language-{{$lang->id}}" value="{{$lang->id}}"
                                                        :checked="expertise.languages.indexOf({{$lang->id}})>-1"
                                                        v-on:change="toggleExpertise('languages', {{$lang->id}})">
                                                    <label class="custom-control-label"
                                                        for="language-{{$lang->id}}">{{$lang->name}}</label>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-warning" v-on:click="saveData('expertise')"
                                        type="button">Save</button>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-orange">Area of Expertise <em class="text-danger">*</em></h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach($services as $service)
                                            <li class="list-group-item pl-0 bg-light">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="service-{{$service->id}}" value="{{$service->id}}"
                                                        :checked="expertise.services.indexOf({{$service->id}})>-1"
                                                        v-on:change="toggleExpertise('services', {{$service->id}})">
                                                    <label class="custom-control-label"
                                                        for="service-{{$service->id}}">{{$service->name}}</label>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-orange">Time Availability</h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach(weekDays() as $wday=>$wdayName)
                                            <li class="list-group-item ">
                                                <div class="custom-control custom-switch mb-2">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="day-{{$wday}}" value="{{$wday}}"
                                                        :checked="expertise.days.indexOf({{$wday}})>-1"
                                                        v-on:change="toggleExpertise('days', {{$wday}})">
                                                    <label class="custom-control-label"
                                                        for="day-{{$wday}}"><strong>{{$wdayName}}</strong></label>
                                                    <button class="btn btn-light float-right"
                                                        v-on:click="addTime({{$wday}})" type="button"
                                                        v-if="expertise.timings[{{$wday}}].length<2">
                                                        <i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="row" v-if="expertise.days.indexOf({{$wday}})>-1"
                                                    v-for="(item, index) in expertise.timings[{{$wday}}]">
                                                    <div class="form-group col">
                                                        From
                                                        <input type="time" class="form-control"
                                                            v-model="item.from_time">
                                                    </div>
                                                    <div class="form-group col">
                                                        To
                                                        <input type="time" class="form-control" v-model="item.to_time">
                                                    </div>
                                                    <div class="form-group col">
                                                        <button class="btn btn-danger btn-sm mt-4" type="button"
                                                            v-if="index!=0" v-on:click="removeTime({{$wday}}, index)"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane mb-5 fade show" id="bank" role="tabpanel" aria-labelledby="profile-tab">
                    <form class="mt-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Bank Name <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="Bank Name"
                                                name="bank[bank_name]" v-model="bank.bank_name" pattern="[a-zA-Z]+"
                                                oninvalid="setCustomValidity('Please enter on alphabets only. ')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Account Name <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="Account Name"
                                                name="bank[bank_account_name]" v-model="bank.bank_account_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Account Number <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="Account Number"
                                                name="bank[bank_account_number]" v-model="bank.bank_account_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Verify Account Number <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="Verfiy Account Number"
                                                name="bank[bank_account_number_verify]"
                                                v-model="bank.bank_account_number_verify">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>IFSC Code <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="IFSC Code"
                                                name="bank[bank_ifsc_code]" v-model="bank.bank_ifsc_code">
                                            <small><a href="https://governmentschemes.org/ifsc-code/" target="_blank"><i
                                                        class="fa fa-lens"></i> Search ifsc code</a></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>UPI ID</label>
                                            <input type="text" class="form-control" placeholder="UPI ID"
                                                name="bank[bank_upi_id]" v-model="bank.bank_upi_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Bank Branch <em class="text-danger">*</em></label>
                                            <input type="text" class="form-control" placeholder="Branch Name"
                                                name="bank[bank_branch]" v-model="bank.bank_branch">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>PAN Number <em class="text-danger">*</em></label>
                                    <input type="text" class="form-control" placeholder="PAN Number"
                                        name="bank[pan_card]" v-model="bank.pan_card">
                                </div>
                                <div class="card bg-light mb-3">
                                    <div class="card-body" v-on:click="openModalUpload('pan_id')">
                                        <div class="media">
                                            <div class="media-body">
                                                <input type="hidden" name="bank[pan_id]" v-model="bank.pan_id">
                                                <p><strong>My PAN proof</strong> <span class="float-right"> <a
                                                            v-if="bank.pan_id_src!=''" :href="bank.pan_id_src"
                                                            target="_blank"><i class="fa fa-eye"></i></a></span></p>
                                                <button type="button" class="btn btn-warning btn-sm float-right">Upload
                                                    PAN Proof</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-warning" type="button"
                                        v-on:click="saveData('bank')">Save</button>
                                </div>
                            </div>

                        </div>
                    </form>


                </div>

            </div>
            <div id="media-img-manager" tabindex="-1" role="dialog" aria-labelledby="MediaImageManager"
                aria-hidden="true" class="modal fade text-center">
                <div role="document" class="modal-dialog modal-media">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="MediaImageManager" class="modal-title">Upload</h4>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="dropzone" id="file-upload"></div>
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <small class="text-danger">* Only .jpg, .jpeg, .png@{{upload_type!='image_id'?' ,.pdf, .dox,
                                .docx':''}} images are allowed</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{asset('js/dropzone.js')}}"></script>
<script>
    var profileapp = new Vue({
        el: '#profile-app',
        data: {
            profile: {
                about: `{{$user->about}}`,
                firstname: '{{$user->firstname}}',
                lastname: '{{$user->lastname}}',
                display_name: '{{$user->display_name}}',
                email: '{{$user->email}}',
                phone: '{{$user->phone}}',
                dob_dd: '{{$user->dob_dd}}',
                dob_mm: '{{$user->dob_mm}}',
                dob_yyyy: '{{$user->dob_yyyy}}',
                gender: '{{$user->gender}}',
                address: '{{$user->address}}',
                country_id: '{{$user->country_id?:101}}',
                state_id: '{{$user->state_id}}',
                city_id: '{{$user->city_id}}',
                pincode: '{{$user->pincode}}',
                image_id: '{{$user->image_id}}',
                id_proof: '{{$user->id_proof}}',
                address_proof: '{{$user->address_proof}}',
                image_id_src: '{{$user->image_id && $user->image ? $user->image->image(200) : no_image()}}',
                id_proof_src: '{{$user->id_proof ? asset('uploads/ astrologers / '.$user->id_proof) : ''}}',
            address_proof_src: '{{$user->address_proof ? asset('uploads/ astrologers / '.$user->address_proof) : ''}}',
    },
        bank: {
            bank_name: '{{$user->bank_name}}',
            bank_account_name: '{{$user->bank_account_name}}',
            bank_account_number: '{{$user->bank_account_number}}',
            bank_account_number_verify: '{{$user->bank_account_number_verify}}',
            bank_ifsc_code: '{{$user->bank_ifsc_code}}',
            bank_upi_id: '{{$user->bank_upi_id}}',
            bank_branch: '{{$user->bank_branch}}',
            pan_card: '{{$user->pan_card}}',
            pan_id: '{{$user->pan_id}}',
            pan_id_src: '{{$user->pan_id ? asset('uploads/ astrologers / '.$user->pan_id) : ''}}',
            },
    expertise: {
        price: '{{$user->price}}',
            special_price: '{{$user->special_price}}',
                sp_from_date: '{{$user->sp_from_date}}',
                    sp_to_date: '{{$user->sp_to_date}}',
                        job_start_year: '{{$user->job_start_year?:date('Y')}}',
                            languages : { { json_encode($user -> languages ? $user -> languages -> pluck('language_id') -> toArray() : []) } },
        services: { { json_encode($user -> services ? $user -> services -> pluck('service_id') -> toArray() : []) } },
        days: { { json_encode($user -> timetable ? array_values($user -> timetable -> pluck('week_day') -> unique() -> toArray()) : []) } },
        timings: { !!json_encode($timeTable)!! },
    },
    master: {
        states: [],
            cities: []
    },
    errors: { },
    upload_type: '',
        successmessage: '',
            errormessage: '',
        },
    methods: {
        loadMaster(options) {
            let that = this;
            let url = '{{route('locations')}}';
            if (options == 'states') {
                url += '/master_states/?country_id=' + that.profile.country_id
            } else if (options == 'cities') {
                url += '/master_cities/?state_id=' + that.profile.state_id
            }
            axios.post(url)
                .then(function (response) {
                    that.master[options] = response.data;
                })
                .catch(function (response) {
                })
        },
        saveData(dataOp) {
            let that = this;
            that.successmessage = '';
            that.errormessage = '';
            axios.post('{{route('astrologer.profile')}}', {
                data_type: dataOp,
                data: that[dataOp]
            }).then(function (response) {
                that.successmessage = 'Updated successfully';
                // window.location.reload();
                // setTimeout(function(){
                //     that.successmessage = '';
                // }, 3000);
                $('#modalMessage').modal('show');
            }).catch(function (response) {

                let errors = response.response.data;
                if (errors) {
                    that.errormessage += '<strong>' + errors.message + '</strong><br>';
                    for (i in errors.errors) {
                        that.errormessage += errors.errors[i][0] + '<br>';
                    }
                } else {
                    that.errormessage = 'Please fill all required fields';
                }
                $('#modalMessage').modal('show');
                // setTimeout(function(){
                //     that.errormessage = '';
                // }, 20000);
            });
        },
        toggleExpertise(expt, value){
            if (this.expertise[expt].indexOf(value) > -1) {
                this.expertise[expt].splice(this.expertise[expt].indexOf(value), 1);
            } else {
                this.expertise[expt].push(value);
            }
        },
        addTime(wday){
            if (!this.expertise.timings[wday] || this.expertise.timings[wday].length == 0) {
                this.expertise.timings.splice(wday, 0, []);
            }
            if (this.expertise.timings[wday].length < 2)
                this.expertise.timings[wday].push({ week_day: wday, from_time: '', to_time: '' });
        },
        removeTime(wday, tmindex){
            this.expertise.timings[wday].splice(tmindex, 1);
        },
        openModalUpload(type) {
            this.upload_type = type;
            $('#media-img-manager').modal('show')
        },
        checkandfixSP(){
            if (parseInt(this.expertise.special_price) >= parseInt(this.expertise.price)) {
                this.expertise.special_price = parseInt(this.expertise.price) - 0.01;
            }
        }
    },
    mounted() {
        @if ($user && $user -> id)
            this.loadMaster('states');
        this.loadMaster('cities');
        @endif
    }
    });
    var meida_upload_url = '{{route('astrologer.upload')}}';
    var myDropzone = new Dropzone("div#file-upload",
        {
            url: meida_upload_url,
            paramName: "file",
            maxFilesize: 128,
            uploadMultiple: false,
            acceptedFiles: (profileapp.upload_type == 'image_id' ? '.jpg,.jpeg,.png' : '.jpg,.jpeg,.png,application/pdf,.doc,.docx,.odt'),
            previewTemplate: '<i class="hidden preview-image"></i>',
            parallelUploads: 1,
            success: function (file, data) {
                $('#progress .progress-bar').css(
                    'width',
                    '0%'
                );
                profileapp.profile[profileapp.upload_type] = data['value'];
                profileapp.profile[profileapp.upload_type + '_src'] = data['path'];
                $('#media-img-manager').modal('hide');
            },
            uploadprogress: function (file, progress, bytesent) {
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            },
            sending: function (file, xhr, formData) {
                formData.append("_token", window.Laravel.csrfToken);
                formData.append("file_type", profileapp.upload_type);
            }
        }
    );
    Dropzone.autoDiscover = false;

</script>

@endpush
