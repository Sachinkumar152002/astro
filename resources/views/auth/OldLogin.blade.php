@extends(themeView().'layout')

@section('title', "Login | ASTROVYRA")

@section('content')
<!-- banner-section-start -->
<a href="#loginformdiv" class="" id="redirect-btn" class="d-none"></a>
<!-- banner-section-end -->
<section class="mb-5 mt-5" id="loginformdiv">
  <div class="container container_register mt-4">
    <div class="row">



        <div class="login-boxx" id="guest-login-page">
          <div class="container sticky-sidebar-2 p-0">
            <div class="row no-gutters ">

                <div class="col-12 phonelogin d-flex   flex-column pb-3 pb-md-0" v-if="!otpScreen && !signupScreen">
                 <div class="card pb-4">
                      <div class=" p-3 radius_top mb-0 w-100">
                        <h4 class="h5 ms-0 mb-0 text-center text-danger fw-bold">Welcome to AstroKavach</h4>
                      </div>
                      <span class="notification text-center px-2 fs-7">Enter your mobile number to Login/Signup</span>

                      <div class="p-4 d-flex flex-wrap w-100 justify-content-between pb-0">
                        <div class="form-group w-100">
                            <label for="mobile" class="fw-semibold mb-1">Mobile<span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center border radius bg-white">
                            <div class="" style="width: 25%;">
                              <select class="form-control border-0" v-model="country_id">
                                @if($countries)
                                  @foreach($countries as $key => $item)
                                    <option value="{{$item->id}}">{{$item->name}} (+{{$item->phonecode}})</option>
                                  @endforeach
                                @endif
                              </select>

                            </div>

                              <div class=" p-0 m-0">
                                <input autofocus type="text" class="form-control fs-7 border-0 ps-0" onkeypress="javascript: if (event.key === 'Enter') { event.preventDefault(); document.getElementById('getopt-btn').click(); }" placeholder="Mobile Number" id="mobile" v-model="mobile">

                              </div>
                            </div>
                          <small class="text-danger" v-if="country_id_error!=''">@{{country_id_error}}</small>
                            <div class="form-group w-100"><small class="text-danger" v-if="mobile_error!=''">@{{mobile_error}}</small></div>
                        </div>
                          <p class="text-center mt-2 mb-0">You Will receive a 6 digit code  for verification</p>
                        <div class="form-group w-100 text-center mt-2">
                          <button type="submit" class="main_button login-submit" v-on:click="getOtp" id="getopt-btn" >Get OTP</button>
                        </div>
                      </div>


                      <div class="text-center pt-3">
                        <a class="w-100 py-2 text-center py-2" href="javascript:void(0)" v-on:click="signupScreen = true; mobile_error = ''; country_id_error = ''; ">Not Register Click Here to <span class="text-gold"> Register</span></a>
                        <!-- <p class="px-2 text-center mt-2 mb-0 f-14">By Signing up, you agree to our <br><a href="{{url('terms-conditions')}}" class="text-gold">Terms of Use</a> and <a href="{{url('privacy-policy')}}" class="text-gold">Privacy Policy</a></p> -->
                    </div>

                      </div>

                    </div>
                </div>
                <div class="col-lg-12 col-md-6 col-12 otp-verify d-flex align-items-center" v-if="otpScreen">
                  <div class="card">

                        <div class="login_detail p-4 d-flex justify-content-center align-items-center text-left flex-column">
                          <div class="p-3 radius mb-o w-100">
                            <h4 class="h5 ms-2 mb-0 text-center text-danger fw-bold">Welcome to AstroKavach</h4>
                            </div>
                        <p class="text-center">Entered the wrong number <span class="userphone text-gold"><strong>@{{mobile}}</strong></span>? <a class="text-gold" href="javascript:void(0)" v-on:click="otpScreen=false;timer=0">Click here to </a>change your phone number.</p>
                        <div class="form-group border-1 border-light">
                          <input type="number" required class="otp-input" maxlength="1" id="otp1" name="otp1" placeholder="0" v-model="otp[0]" ref="otp_0" v-on:keyup="validateNumber(0); moveFocus(0)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)" pattern="[0-9]" title="Please enter a single digit (0-9)">
                          <input type="number" required class="otp-input" maxlength="1" id="otp2" name="otp2" placeholder="0" v-model="otp[1]" ref="otp_1" v-on:keyup="validateNumber(1); moveFocus(1)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)" pattern="[0-9]" title="Please enter a single digit (0-9)">
                          <input type="number" required class="otp-input" maxlength="1" id="otp3" name="otp3" placeholder="0" v-model="otp[2]" ref="otp_2" v-on:keyup="validateNumber(2); moveFocus(2)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)" pattern="[0-9]" title="Please enter a single digit (0-9)">
                          <input type="number" required class="otp-input" maxlength="1" id="otp4" name="otp4" placeholder="0" v-model="otp[3]" ref="otp_3" v-on:keyup="validateNumber(3); moveFocus(3)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)" pattern="[0-9]" title="Please enter a single digit (0-9)">
                          <input type="number" required class="otp-input" maxlength="1" id="otp5" name="otp5" placeholder="0" v-model="otp[4]" ref="otp_4" v-on:keyup="validateNumber(4); moveFocus(4)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)" pattern="[0-9]" title="Please enter a single digit (0-9)">
                          <input type="number" required class="otp-input" maxlength="1" id="otp6" name="otp6" placeholder="0" v-model="otp[5]" ref="otp_5" v-on:keyup="validateNumber(5); moveFocus(5)" oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1)"
                          onkeypress="javascript: if (event.key === 'Enter') { event.preventDefault(); document.getElementById('login_after_otp').click(); }" pattern="[0-9]" title="Please enter a single digit (0-9)">
                        </div>
                        <div class="form-group">
                            <p class="text-center">Did not received OTP? <a href="javascript:void(0);" v-if="timer<=0" v-on:click="getOtp" class="text-gold">Resend OTP</a> <span v-else> Resend OTP in @{{timer}} seconds </span></p>
                        </div>
                        <div class="form-group">
                            <button type="button" class="main_button" id="login_after_otp" v-on:click="validatLogin" ref="login_btn">Login</button>
                            <small class="text-danger" v-if="otp_error!=''">@{{otp_error}}</small>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12 signup-form" v-if="signupScreen">
                  <div class="card">


                        <div class="p-3">
                          <div class="p-3 radius mb-0 w-100">
                            <h4 class="h5 ms-2 mb-0 text-center text-danger fw-bold">Welcome to AstroKavach</h4>
                            </div>
                          <div class="form-group mb-2">
                              <label for="name" class="fw-semibold mb-1">Name<span class="text-danger">*</span> </label>
                              <input type="text" required class="form-control fs-7" id="name" placeholder="Enter Name" v-model="name" name="name" @blur="validateName">
                              <small class="text-danger" v-if="name_error!=''" v-html="name_error"></small>
                          </div>
                          <div class="form-group mb-2">
                              <label for="email" class="fw-semibold mb-1">Email<span class="text-danger">*</span></label>
                              <input type="email" id="email" required class="form-control fs-7" placeholder="Enter Email" v-model="email" name="email" @blur="validateEmail">
                              <small class="text-danger" v-if="email_error!=''" v-html="email_error"></small>
                          </div>
                          <div class="form-group w-100">
                              <label for="mobile" class="fw-semibold mb-1">Mobile<span class="text-danger">*</span></label>
                              <div class="d-flex align-items-center border radius bg-white">
                              <div class="" style="width: 25%;">
                                <select class="form-control border-0" v-model="country_id">
                                  @if($countries)
                                    @foreach($countries as $key => $item)
                                      <option value="{{$item->id}}">{{$item->name}} (+{{$item->phonecode}})</option>
                                    @endforeach
                                  @endif
                                </select>
                                <div class="form-group w-100"><small class="text-danger" v-if="country_id_error!=''">@{{country_id_error}}</small></div>
                              </div>
                                <div class="col-7 p-0 m-0">
                                  <input autofocus type="text" class="form-control fs-7 border-0 ps-0" placeholder="Mobile Number" onkeypress="javascript: if (event.key === 'Enter') { event.preventDefault(); document.getElementById('register-btn').click(); }" id="mobile" v-model="mobile">

                                </div>
                              </div>
                              <div class="form-group w-100"><small class="text-danger" v-if="mobile_error!=''">@{{mobile_error}}</small></div>
                          </div>
                          <div class="text-center">
                            <small class="text-danger" v-if="register_error!=''" v-html="register_error"></small>
                          </div>
                          <div class="m-auto text-center">
                            <button type="button" class="main_button w-40 mt-3 py-2 fs-6" id="register-btn" v-on:click="register()">Sign up</button>
                            <!-- <button type="button" class="main_button w-40 py-2 mt-2 fs-6" v-on:click="signupScreen = false">Sign In</button> -->
                          </div>
                          <a href="#" v-on:click="signupScreen = false"><p class="text-center mt-2">If you are Already Registered, Click Here to <span class="text-gold">Login</span></p></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
window.location.href = '#loginformdiv';
    var guestLogin = new Vue({
        el: '#guest-login-page',
        data: {
            otpScreen: false,
            signupScreen: false,
            mobile: @json($temp_user && isset($temp_user->mobile) ? $temp_user->mobile : ''),
            country_id: 101,
            mobile_error: '',
            otp_error: '',
            otp: ['', '', '', '', '', ''],
            name: @json($temp_user && isset($temp_user->full_name) ? $temp_user->full_name : ''),
            email: @json($temp_user && isset($temp_user->email) ? $temp_user->email : ''),
            nameTouched: false,
            emailTouched: false,
            mobileTouched: false,
            register_error: '',
            name_error: '',
            email_error: '',
            mobile_error: '',
            country_id_error: '',
            timer: 0,
        },
        watch: {
              timer: {
                  handler(value) {
                      if (value > 0) {
                          setTimeout(() => {
                              this.timer--;
                          }, 1000);
                      }
                  },
              }
          },
        methods: {
            validateName() {
                this.nameTouched = true;
            },
            validateEmail() {
                this.emailTouched = true;
            },
            validateMobileInput() {
                this.mobileTouched = true;
                this.mobile = this.mobile.replace(/\D/g, '').slice(0, 10);
            },
            register() {
              this.name_error ='';
              this.mobile_error ='';
              this.email_error ='';
              this.register_error = '';
                axios.post('{{route('register')}}', {
                    name: this.name,
                    email: this.email,
                    phone: this.mobile,
                    country_id: this.country_id
                }, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    this.signupScreen = false;
                    this.getOtp();
                })
                .catch(errors => {
                  if(er = errors.response.data){
                    if(er.email){
                      this.email_error = er.email;
                    }
                    if(er.phone){
                      this.mobile_error = er.phone;
                    }
                  }
                  if (errors.response && errors.response.data.errors) {
                      const err = errors.response.data.errors;
                      if(err.name){
                        this.name_error =err.name[0];
                      }
                      if(err.phone){
                        this.mobile_error =err.phone[0];
                      }
                      if(err.email){
                        this.email_error =err.email[0];
                      }
                      if(err.country_id){
                        this.country_id_error = 'Code is required';
                      }
                  } else {
                      // this.register_error = errors.response.data.message || 'An error occurred. Please try again.';
                  }
                });
            },
            validateNumber(index) {
      // Ensure the input is a single numeric digit (0-9)
      let currentInput = this.otp[index];

      // Only allow digits, replace if not digit, and ensure length is 1
      if (!/^\d$/.test(currentInput)) {
          this.otp[index] = ''; // Clear input if it's not a valid digit
      }
  },
            moveFocus(index) {
                  // Move focus to the next or previous input based on the current value
                  if (this.otp[index] && index < 5) {
                      this.$refs[`otp_${index + 1}`].focus();
                  } else if (!this.otp[index] && index > 0) {
                      this.$refs[`otp_${index - 1}`].focus();
                  }
              },
            getOtp() {
                let that = this;
                that.mobile_error = '';
                that.country_id_error = '';
                that.timer = 0;
                axios.post('{{ route('check.phone') }}', {
                    mobile: that.mobile,
                    country_id: that.country_id
                })
                .then(function(response) {
                    toastr.success('Otp Sent Successfully');
                    that.otpScreen = true;
                    that.timer = 120;
                })
                .catch(function(errors) {
                    errors = errors.response.data.errors;
                    if(errors.mobile){
                      that.mobile_error = errors.mobile[0];
                    }
                    if(errors.country_id){
                      that.country_id_error = 'Code is required';
                    }
                });
            },
            validatLogin() {
                let that = this;
                for (let i = 0; i < that.otp.length; i++) {
                    if (that.otp[i] === '' || isNaN(that.otp[i])) {
                        that.otp_error = 'Please enter a valid OTP';
                        return false;
                    }
                }
                that.otp_error = '';
                axios.post('{{ route('verify.otp') }}', {
                    mobile: that.mobile,
                    otp: that.otp.join('')
                })
                .then(function(response) {
                  toastr.success('Login Successfully');
                  setTimeout(function(){
                    window.location.href = response.data.redirectTo;
                  }, 1500);
                })
                .catch(function(errors) {
                  that.otp_error = errors.response.data.message;
                });
            }
        },
        computed: {
            isValidMobile() {
                return this.mobile.length === 10;
            },
            isValidName() {
                return this.name.trim() !== '';
            },
            isValidEmail() {
                this.emailTouched = false;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(this.email){
                  return emailRegex.test(this.email);
                }
                return true;
            }
        }
    });
</script>
@endpush
