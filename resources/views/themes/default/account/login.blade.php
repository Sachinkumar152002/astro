
<style>
input::-webkit-outer-spin-button,
     input::-webkit-inner-spin-button {
         -webkit-appearance: none;
         margin: 0;
     }

     /* Hide spin buttons in Firefox */
     input[type="number"] {
         -moz-appearance: textfield;
     }
     .register-btn{
       padding: 5px 30px 5px 30px !important;
     }
     .login-submit{
       cursor: pointer;
     }
</style>
<div class="modal fade user-login-m" id="loginmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" id="guest-login">
    <div class="modal-content">
        <div class="modal-body p-0">
            <div class="container">
              <div class="row no-gutters ">
                  <div class="col-lg-6 col-12 lg_bg">
                  <div class="login_logo d-flex flex-column align-items-center justify-content-center p-2 px-5 text-center">
                      <img src="{{asset('assets/images/iiag-logo10.png')}}" alt="Logo" class="img-fluid">
                      <h2 class="heading">Welcome To<span style="color: #ffffff;">IIAG JYOTISH SANSTHAN</span></h2>
                  </div>
                  <div class="text-left desktop">
                      <h3 class="title">Why Sign Up?</h3>
                      <ul>
                          <li>Receive customized details</li>
                          <li>Store astrological charts (kundli) on the cloud.</li>
                          <li>Record your notes and remarks.</li>
                          <li>Access from anywhere: via mobile and web.</li>
                          <li>Open worksheet for analysis.</li>

                      </ul>
                  </div>
              </div>
                  <div class="col-lg-6 col-12 phonelogin d-flex justify-content-center align-items-center flex-column pb-3 pb-md-0" v-if="!otpScreen && !signupScreen">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      <div class="login_detail d-flex justify-content-center align-items-center text-left flex-column">
                      <h2 class="my-2 fs-1 text">LOGIN</h2>
                      <!-- <span class="notification">You will receive a 6 digit code for verification</span> -->
                  </div>
                      <div class="p-4 d-flex flex-wrap w-100 justify-content-between gap-3">
                              <div class="form-group w-100">
                                  <input type="text" id="mobile" required class="form-control" placeholder="Mobile" v-model="mobile" name="mobile" @input="validateMobileInput" maxlength="10">
                              </div>
                              <div class="text-danger fs-8" v-if="mobile_error!=''">@{{mobile_error}}</div>
                              <div class="form-group w-100 text-center">
                                  <button  type="button" class="btn btn-secondary rounded-pill w-50 py-2 login-submit mb-2" v-on:click="getOtp" >Submit</button>
                              </div>
                      </div>
                      {{--
                      <span class="notification mb-2">Login with</span>
                      --}}
                      <div class="w-50 login_icon">
                        {{--
                          <ul class="d-flex justify-content-between gap-2 p-0">
                              <li class="list-group-item">
                                  <a href="#"><img src="{{asset('assets/images/ggl.webp')}}" width="40" height="40" class="img-fluid" alt="Login with google"></a>
                              </li>
                              <li class="list-group-item">
                                  <a href="#"><img src="{{asset('assets/images/fcbok.png')}}" width="40" height="40" class="img-fluid" alt="login with facebook"></a>
                              </li>
                              <li class="list-group-item">
                                  <a href="#"><img src="{{asset('assets/images/apple.png')}}" width="40" height="40" class="img-fluid" alt="login with apple"></a>
                              </li>
                          </ul>
                          --}}
                            <a class="btn btn-secondary rounded-pill w-100 py-2" href="javascript:void(0)" v-on:click="signupScreen = true">Sign up</a>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-12 otp-verify d-flex align-items-center" v-if="otpScreen">
                      <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                      <div class="login_detail p-4 d-flex justify-content-center align-items-center text-left flex-column">
                          <h2 class="my-2 fs-1 text">Verify OTP</h2>

                          <p class="text-center">Entered the wrong number <span
                              class="userphone text-gold"><strong>@{{mobile}}</strong></span>? <a class="text-gold" href="javascript:void(0)" v-on:click="otpScreen=false">Click here </a>to change your phone number.</p>

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
                                  <p>Did not received Otp? <a href="javascript:void(0);" v-if="timer<=0" v-on:click="getOtp" class="text-gold">Resend Otp</a> <span v-else> Resend Otp in @{{timer}} seconds </span></p>
                              </div>
                              <div class="form-group">
                                  <button type="button" class="btn btn-warning btn-rounded w-100" id="login_after_otp" v-on:click="validatLogin" ref="login_btn">Login</button>
                                  <small class="text-danger" v-if="otp_error!=''">@{{otp_error}}</small>
                              </div>
                      </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-12 signup-form d-flex align-items-center" v-if="signupScreen">
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    <div class="login_detail p-4 w-100 mb-5 mb-md-0">
                        <h2 class="my-2 fs-1 text">Register</h2>
                        <hr>
                        <div class="form-group mb-2">
                            <label for="name" class="fw-semibold mb-1">Name<span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" id="name" placeholder="Full Name" v-model="name" name="name" @blur="validateName">
                            <small class="text-danger" v-if="!isValidName && nameTouched">This field is required</small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="email" class="fw-semibold mb-1">Email<span class="text-danger">*</span></label>
                            <input type="email" id="email" required class="form-control" placeholder="Email" v-model="email" name="email" >
                            <small class="text-danger" v-if="emailTouched">This field is required </small>
                            <small class="text-danger" v-if="!isValidEmail">Invalid Email </small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="mobile" class="fw-semibold mb-1">Mobile<span class="text-danger">*</span></label>
                            <input type="text" id="mobile" required class="form-control" placeholder="Mobile" v-model="mobile" name="mobile" @input="validateMobileInput" maxlength="10">
                            <small class="text-danger" v-if="!isValidMobile && mobileTouched">Invalid Mobile (Must be 10 digits)</small>
                        </div>
                        {{--
                        <p class="notification mb-2 mt-3 text-center">Login with</p>
                        --}}
                      <div class="d-flex align-items-center justify-content-center">
                        <div class="w-50 login_icon">
                          {{--
                            <ul class="d-flex justify-content-between gap-2 p-0">
                                <li class="list-group-item">
                                    <a href="#"><img src="{{asset('assets/images/ggl.webp')}}" width="40" height="40" class="img-fluid" alt="Login with google"></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#"><img src="{{asset('assets/images/fcbok.png')}}" width="40" height="40" class="img-fluid" alt="Login with facebook"></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#"><img src="{{asset('assets/images/apple.png')}}" width="40" height="40" class="img-fluid" alt="Login with apple"></a>
                                </li>
                            </ul>
                            --}}
                              <button type="button" class="btn btn-secondary rounded-pill w-100 py-2" v-on:click="register()">Sign up</button>
                              <button type="button" class="btn btn-secondary rounded-pill w-100 py-2 mt-2" v-on:click="signupScreen = false">Sign In</button>
                        </div>
                      </div>
                        <div class="d-flex justify-content-between">


                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    var openLoginModal = function(){
        $('#loginmodal').modal('show');
    }

    var guestLogin = new Vue({
        el: '#guest-login',
        data: {
            otpScreen: false,
            signupScreen: false,
            mobile: '',
            mobile_error: '',
            otp_error: '',
            otp: ['', '', '', '', '', ''],
            name: '',
            email: '',
            nameTouched: false,
            emailTouched: false,
            mobileTouched: false,
            register_error: '',
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
                // Clear previous error messages
                this.register_error = '';

                // Validate fields
                this.nameTouched = true;
                if(!this.email){
                  this.emailTouched = true;
                }
                this.mobileTouched = true;

                if (!this.isValidName || !this.isValidEmail || !this.isValidMobile) {
                    return; // Prevent submission if validation fails
                }

                // Proceed with registration if validation passes
                axios.post('{{route('register')}}', {
                    name: this.name,
                    email: this.email,
                    phone: this.mobile
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
                    if (errors.response && errors.response.data.errors) {
                        const err = errors.response.data.errors;
                        this.register_error = Object.values(err).map(e => e[0]).join('<br>');
                    } else {
                        this.register_error = errors.response.data.message || 'An error occurred. Please try again.';
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
                  if (index === 5 && this.otp[index]) {
                      // this.$refs[`otp_${index}`].blur(); // Removes focus from the last input
                  }
              },
            getOtp() {
                let that = this;
                that.mobile_error = '';
                if (!that.mobile) {
                    that.mobile_error = 'mobile number is required';
                    return false;
                }
                axios.post('{{ route('check.phone') }}', {
                    mobile: that.mobile
                })
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Otp Sent Successfully.',
                        confirmButtonText: 'Okay'
                    });
                    that.otpScreen = true;
                    that.timer = 120;
                })
                .catch(function(errors) {
                    that.mobile_error = errors.response.data.message;
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
                    window.location.reload();
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
