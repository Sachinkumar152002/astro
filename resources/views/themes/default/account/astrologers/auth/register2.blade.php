@extends(themeView().'layout')
@section('title', 'Astrologers Register')
@section('keywords', '')
@section('description','')
@section('content')
<section class="astro-header">
    <div class="container asto-container">
        <div class="row">
            <div class="col-sm-8 col-12">
                <div id="carouselastro" class="carousel slide mt-5 pt-5" data-ride="carousel">
                    <div class="carousel-inner text-center text-uppercase text-white">
                        <div class="carousel-item active">
                            <h2>Provide consultations from anywhere at anytime</h2>
                        </div>
                        <div class="carousel-item">
                            <h2>Get your daily earning reports online</h2>
                        </div>
                        <div class="carousel-item">
                            <h2>Participate in special marketing activities to get new clients</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="mt-4" id="astrologer-register">
                    <div class="card">
                        <div class="card-body">
                            <div  v-show="!otpScreen">
                            <h4 class="text-dark">Astrologer Registration</h4>
                            <p>Get clients across the world</p>
                            <hr>
                            <div class="form-group">
                                <input type="text" required class="form-control" placeholder="Full Name" v-model="full_name" name="full_name">
                                <small class="text-danger" v-if="isValidName">This field is required</small>
                            </div>
                            <div class="form-group">
                                <input type="emal" required class="form-control" placeholder="Email" v-model="email" name="email">
                                <small class="text-danger" v-if="isValidEmail">Invalid Email</small>
                            </div>
                            <div class="form-group">
                                <input type="tel" required class="form-control" placeholder="Mobile" v-model="mobile" name="mobile">
                                <small class="text-danger" v-if="isValidMobile">Invalid Mobile</small>
                            </div>

                            <div class="form-group">
                                <small v-if="register_error!=''" class="text-danger" v-html="register_error"></small>
                                <button type="button" class="btn w-100 btn-warning" v-on:click="register">Submit <i class="fas fa-arrow-right"></i></button>
                            </div>
                            <div class="form-group">
                                <small v-if="register_error!=''" class="text-danger" v-html="register_error"></small>
                                <a href="{{ route('auth.google') }}"><button type="button" class="btn w-100 btn-success" >Login with Google <i class="fas fa-arrow-right"></i></button></a>
                            </div>
                            <div class="form-group">
                            <p class="text-center">Already Register <a href="{{route('astrologer.login')}}">Click Here to Login</a></p>
                            </div>
                            </div>
                            <div class="astro-otp0" v-show="otpScreen">
                                <div class="form-group">
                                    <p>Enter OTP</p>
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp1" name="otp1" v-model="otp[0]" ref="otp_0" v-on:keyup="validateNumber(0); otp[0].length>0 ? $refs.otp_1.focus():null">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp2" name="otp2" v-model="otp[1]" ref="otp_1" v-on:keyup="validateNumber(1); otp[1].length>0 ? $refs.otp_2.focus():$refs.otp_0.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp3" name="otp3" v-model="otp[2]" ref="otp_2" v-on:keyup="validateNumber(2); otp[2].length>0 ? $refs.otp_3.focus():$refs.otp_1.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp4" name="otp4" v-model="otp[3]" ref="otp_3" v-on:keyup="validateNumber(3); otp[3].length>0 ? $refs.otp_4.focus():$refs.otp_2.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp5" name="otp5" v-model="otp[4]" ref="otp_4" v-on:keyup="validateNumber(4); otp[4].length>0 ? $refs.otp_5.focus():$refs.otp_3.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp6" name="otp6" v-model="otp[5]" ref="otp_5" v-on:keyup="validateNumber(5); otp[5].length>0 ? $refs.login_btn.focus():$refs.otp_4.focus()">
                                </div>
                                <div class="form-group">
                                    <p>Did not received Otp? <a href="javascript:void(0);" v-on:click="getOtp">Resend Otp</a></p>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn w-100 btn-warning" v-on:click="validatLogin" ref="login_btn">Login <i class="fas fa-arrow-right"></i></button>
                                    <small class="text-danger" v-if="otp_error!=''">@{{otp_error}}</small>
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
<script>
    var astrolgerRegister = new Vue({
        el: '#astrologer-register',
        data: {
            full_name : '',
            email : '',
            mobile : '',

            register_error : '',
            otp_error : '',
            otp: ['','','','','',''],
            otpScreen : false,
        },
        methods: {
            register() {
                let that = this;
                if(that.isValidEmail && that.isValidMobile && that.isValidName ) {
                    alert('Please fill required and valid values');
                    return false;
                }
                that.register_error = '';
                    axios.post('{{route('astrologer.register')}}',{
                        full_name: that.full_name,
                        email: that.email,
                        mobile: that.mobile

                    },{
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        }
                    })
                    .then(function(response){
                        that.getOtp();
                    })
                    .catch(function(errors){
                        if(errors.response.data.errors){
                            var err  = errors.response.data.errors;
                            for(i in err) {
                                that.register_error += err[i][0]+'<br>';
                            }
                        }else {
                            that.register_error = errors.response.data.message;
                        }
                    });

            },
            getOtp(){
                let that = this;
                axios.post('{{route('astrologer.login')}}',{
                    mobile: that.mobile
                })
                .then(function(response){
                    that.otpScreen = true;
                })
                .catch(function(errors){
                    that.otp_error = errors.response.data.message;
                });
            },
            validatLogin(){
                let that = this;
                for(i in that.otp){
                    if(that.otp[i]=='' || isNaN(that.otp[i])) {
                        that.otp_error = 'Please enter valid otp';
                        return false;
                    }
                }
                that.otp_error = '';
                axios.post('{{route('astrologer.otp')}}', {
                    mobile: that.mobile,
                    otp : that.otp.join(''),
                })
                .then(function(response){
                    window.location.href = response.data.url;
                })
                .catch(function(errors){
                    that.otp_error = errors.response.data.message;
                });
            },
            validateNumber(n){
                this.otp[n] = isNaN(this.otp[n]) ? '': this.otp[n];
                this.otp[n] = this.otp[n].toLowerCase().replace(/ /g,"").replace(/[\D]/g, "");
                this.otp[n] = this.otp[n]>1 ? this.otp[n].substr(0,1) : this.otp[n];
            }
        },
        computed : {
            isValidEmail: function() {
                if(this.email.length<1) return false;
                this.email = this.email.replace(/ /g,"");
                var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                if(this.email.match(mailformat)){
                    return false;
                }
                return true;
            },
            isValidMobile: function() {
                if(this.mobile.length<5) return false;
                this.mobile = this.mobile.toLowerCase().replace(/ /g,"").replace(/[\D]/g, "");
                this.mobile = this.mobile.length>10 ? this.mobile.substr(0,10) : this.mobile;
                if(this.mobile.length==10 && !isNaN(this.mobile)) {
                    return false;
                }
                return true;
            },
            isValidName: function(){
                if(this.full_name.length>0 && this.full_name.trim()=='') {
                    return true;
                }
                return false;
            }
        }

    });
</script>
@endpush