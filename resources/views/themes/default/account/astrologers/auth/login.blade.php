@extends(themeView().'layout')

@section('title', 'Astrologers Login')
@section('keywords', '')
@section('description','')

@section('content')
<section class="astro-header">
    <div class="container asto-container">
        <div class="row">
            <div class="col-sm-8 col-12">
                <div id="carouselastro" class="carousel slide pt-5 mt-5" data-ride="carousel">

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
            <div class="col-sm-4 col-12" id="astrologer-login">
                <div class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="astro-mobile" v-show="!otpScreen">
                                <h4 class="text-gold text-center">Astrologer Login</h4>
                                <p class="text-center">Get clients across the world</p>
                                <hr>
                                <div class="form-group">
                                    <input type="text" required class="form-control" placeholder="Mobile" name="mobile" v-model="mobile" max-length="10" min-lenght="10" size="10">
                                    <small>Please enter your 10 digit mobile number</small>
                                </div>
                                <div class="form-group" v-if="isValidMobile">
                                    <button id="astrosubmit" class="btn w-100 btn-warning" v-on:click="getOtp">Submit <i class="fas fa-arrow-right"></i></button>
                                    <small v-if="mobile_error!=''" class="text-danger">@{{mobile_error}}</small>
                                </div>
                                <div class="text-center">Not Register <a class="text-gold" href="{{route('astrologer.register')}}">Click Here</a> to Register
                                </div>
                            </div>
                            <div class="astro-otp0" v-show="otpScreen">
                                <div class="form-group">
                                    <h4 class="text-center text-gold">Verify OTP</h4>
                                    <p class="lead fs-6 text">OTP has been sent to you on your mobile number - <span
                                            class="text-gold userphone"><strong>@{{mobile}}</strong></span> <a href="javascript:void(0)" v-on:click="otpScreen=false">Change
                                            phone</a></p>
                                    <hr>
                                    <p class="text-center">Enter OTP</p>
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp1" name="otp1" v-model="otp[0]" ref="otp_0" v-on:keyup="validateNumber(0); otp[0].length>0 ? $refs.otp_1.focus():null">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp2" name="otp2" v-model="otp[1]" ref="otp_1" v-on:keyup="validateNumber(1); otp[1].length>0 ? $refs.otp_2.focus():$refs.otp_0.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp3" name="otp3" v-model="otp[2]" ref="otp_2" v-on:keyup="validateNumber(2); otp[2].length>0 ? $refs.otp_3.focus():$refs.otp_1.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp4" name="otp4" v-model="otp[3]" ref="otp_3" v-on:keyup="validateNumber(3); otp[3].length>0 ? $refs.otp_4.focus():$refs.otp_2.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp5" name="otp5" v-model="otp[4]" ref="otp_4" v-on:keyup="validateNumber(4); otp[4].length>0 ? $refs.otp_5.focus():$refs.otp_3.focus()">
                                    <input type="text" required class="otp-input" size="1" maxlength="1" id="otp6" name="otp6" v-model="otp[5]" ref="otp_5" v-on:keyup="validateNumber(5); otp[5].length>0 ? $refs.login_btn.focus():$refs.otp_4.focus()">
                                </div>
                                <div class="form-group">
                                    <p class="fs-6 text">Did not received Otp? <a class="text-gold" href="javascript:void(0);" v-on:click="getOtp">Resend Otp</a></p>
                                </div>

                                <div class="form-group">
                                    <p class="fs-6 text">Wrong mobile entered? <a href="javascript:void(0)" v-on:click="otpScreen=false">Change Mobile Number</a></p>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn w-100 btn-warning" v-on:click="validatLogin" ref="login_btn"> Login <i class="fas fa-arrow-right"></i></button>
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
    var astrolgerLogin = new Vue({
        el: '#astrologer-login',
        data: {
            otpScreen : false,
            mobile:'',
            mobile_error : '',
            otp_error : '',
            otp: ['','','','','','']
        },
        methods:{
            getOtp(){
                let that = this;
                that.mobile_error = '';
                axios.post('{{route('astrologer.login')}}',{
                    mobile: that.mobile
                })
                .then(function(response){
                    that.otpScreen = true;
                })
                .catch(function(errors){
                    that.mobile_error = errors.response.data.message;
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
            isValidMobile: function() {
                this.mobile = this.mobile.toLowerCase().replace(/ /g,"").replace(/[\D]/g, "");
                this.mobile = this.mobile.length>10 ? this.mobile.substr(0,10) : this.mobile;
                if(this.mobile.length==10 && !isNaN(this.mobile)) {
                    return true;
                }
                return false;
            }
        },
    });
</script>
@endpush