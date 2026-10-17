@extends('layouts.landing')
@section('content')

    <div id="jwc-landing-page">
        <section id="sdp-enroll">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 jwc-head">
                        <h6>Learn <span>Japanese Work Culutre</span> Course from Home or any where</h6>
                        <img src="{{asset('rewamp/images/jwc/banner-jap.png')}}" alt="banner" title=""/>
                        <h6 class="rat-jwc">Price :<span>6,999/-</span><br/><img src="{{asset('rewamp/images/cart.jpg')}}" alt="cart" title=""> <img src="{{asset('rewamp/images/jwc/AOTS.jpg')}}"/></h6>
                    </div>
                    <div class="col-sm-4">
                        <div class="sdp-left-form prod-cont">
                            <h5>REGISTER WITH US</h5>
                            <form onsubmit="return validateSubmit(this);" role="form" action="{{route('contact.submit')}}"
                                  method="post" name="form1">
                                {{csrf_field()}}
                                <input type="hidden" name="source" value="2">
                                <input type="hidden" name="for" value="Japanese Work Culture">
                                <input type="hidden" name="message" value="Japanese Work Culture">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input  type="text" class="form-control" name="name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="text" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Phone No</label>
                                    <input type="text" class="form-control" name="phone" placeholder="phone Number" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <div class="contct-no-pro">
                                <div class="produc-no-left">
                                    <h6>Contact Us</h6>
                                    <p> {{Config('sst.contact.phone')}}</p>
                                    <p>
                                </div>
                                <div class="product-no-right">
                                    <i class="fa fa-phone">&nbsp;</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="sample">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sample-inner prod-cont">
                            <h5>Sample Course of Japanese Work Culture</h5>

                            <p>Japanese Work Culture by far one of the most unique cultures in the world. It has unusual working hours that Japanese work culture is rooted in the seniority system. The more you work the more chances you will get to grow in the company. Sometimes, the main focus is on quantity rather than quality. The culture also embraces the group of mentality.</p>
                            <a href="" class="getregistration">Join Now</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section id="struggling">
            <div class="container">
                <div class="row prod-cont">
                    <h5>Are you struggling with any of these career challenges?</h5>
                    <div class="col-sm-6 listing">
                        <ul>
                            <li>Are You facing problems in your official line ?</li>
                            <li>Do you have problems in balancing the time and workload?</li>
                            <li>Are you not able to balance your personal life and professional life?</li>
                            <li>Are you worried about your way of doing things?</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 listing">
                        <ul>
                            <li>Are you unable to find jobs according to your education?</li>
                            <li>Are you worried about the way your employees work?</li>
                            <li>Are you troubled by the turnover of your company?</li>
                            <li>Are you still not able to think what you have to do in life?</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="upskill" class="upskill2">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 upskll-inner">
                        <h6>It is time to upskill yourself.</h6>
                        <a href="#" class="getregistration">Join Now</a>
                    </div>
                    <div class="col-sm-8 upskill-right">
                        <h6>Ideal Solutions for you</h6>
                        <div class="listing">
                            <ul>
                                <li>It will helps to get a college degree without having to commute to a campus.</li>
                                <li>Government will recognize you and give certification to add value to your resume</li>
                                <li>Get yourself recognized and approved by the Government of India itself.</li>
                                <li>You increase your chances of Placement by 82% because the course is based on the practical knowledge.</li>
                                <li>This course saves your time and promotes skills.</li>
                            </ul>
                            <ul>
                                <li>It is time to get a well-paid job through this course.</li>
                                <li>This course is for both college graduate as well as those who are pursuing the degree.</li>
                                <li>This course will help you to upgrade your skills with the recent market and industry trends.</li>
                                <li>100% online training programme for those who hate travel.</li>

                            </ul></div>

                    </div>
                </div>
            </div>
        </section>
        <section id="upskill">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 upskll-inner">

                        <h6>We Have a<b> Ideal Solutions for you </b></h6>
                    </div>
                    <div class="col-sm-8 upskill-right">
                        <div class="listing">
                            <ul>
                                <li>Smart Skills Tech is the first company doing Japanese Work Culture courses and certifying AOTS certificates</li>
                                <li>Japanese Work Culture has been made to solve the problems of both employers and employees</li>
                                <li>This course will tell you what you are doing wrong, due to which you have to struggle.</li>

                            </ul>
                            <ul>
                                <li>By this course you will know how you are spoiling your valuable time; this course will tell you the value of time.</li>
                                <li>Government Certification add value to your resume and employability.</li>
                                <li>Get yourself recognized and approved by the Government of Japan itself.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="client">
            <div class="container">
                <div class="row sdp-client">
                    <div class="testmi"><h5>Our Recruitment Partners</h5></div>
                    <div class="col-md-12">
                        <ul>
                            <li><img alt="JBM" src="{{asset('rewamp/images/jbm.jpg')}}"></li>
                            <li><img alt="Ace" src="{{asset('rewamp/images/ace.jpg')}}"></li>
                            <li><img alt="Ntpc" src="{{asset('rewamp/images/ntpc.jpg')}}"></li>
                            <li><img alt="Google" src="{{asset('rewamp/images/google.jpg')}}"></li>
                            <li><img alt="Microsoft" src="{{asset('rewamp/images/microsoft.jpg')}}"></li>
                            <li><img alt="BMW" src="{{asset('rewamp/images/bmw.jpg')}}"></li>
                            <li><img alt="Mahindra" src="{{asset('rewamp/images/mahindra.jpg')}}"></li>
                            <li><img alt="Ashok Layland" src="{{asset('rewamp/images/ashok-leyland.jpg')}}"></li>
                            <li><img alt="Bajaj" src="{{asset('rewamp/images/bajaj.jpg')}}"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="enroll-now">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 enroll-left">
                        <img src="{{asset('rewamp/images/sdp/laptop.png')}}"/>
                    </div>
                    <div class="col-sm-4 enroll-right">
                        <h4>If you can relate to any of the above than Smart Skill Tech is your one stop solution.</h4>
                        <button class="getregistration">REGISTER NOW</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="struggling">
            <div class="container prod-cont">
                <h5>Benefits</h5>
                <div class="row ">
                    <div class="col-sm-6 listing">
                        <ul>
                            <li>Increase your Chances to Recruitment</li>
                            <li>Make the CV more Attractive</li>
                            <li>You have a 76% higher chance of getting place with 2 or more certificates</li>
                            <li>You can get large variety of course to choose from.</li>
                            <li>The schedule of the classes are very flexible.</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 listing">
                        <ul>
                            <li>It will boost or enhance your career advancement opportunities.</li>
                            <li>You are liable to take part in the classroom subject discussions.</li>
                            <li>High interaction and greater understanding to concentrate.</li>
                            <li>You can lots of technical skills along with personality development.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>
        <form action="{{route('thankyou')}}" method="post" id="thankyou-page">{{csrf_field()}}</form>
    </div>

@endsection

@push('scripts')

    <script src="{{asset('js/jquery.3.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script>
        $(".getregistration").click(function() {
            $("html, body").animate({ scrollTop: 0 }, 2000);
            return false;
        });
        $(document).ready(function () {
            setInterval(function time() {
                var d = new Date();
                var hours = 24 - d.getHours();
                var min = 60 - d.getMinutes();
                if ((min + '').length == 1) {
                    min = '0' + min;
                }
                var sec = 60 - d.getSeconds();
                if ((sec + '').length == 1) {
                    sec = '0' + sec;
                }
                jQuery('#countdown #hour').html(hours);
                jQuery('#countdown #min').html(min);
                jQuery('#countdown #sec').html(sec);
            }, 1000);
        });

        var validateSubmit = function (form) {
            var form = $(form);
            form.find('button').addClass('disable').attr('disabled', 'disabled').text('submitting...');

            axios.post(form.attr('action'), form.serialize())
                .then(function (response) {
                    //sendDataToGoogleForm(form.find('input[name="name"]').val(), form.find('input[name="email"]').val(), form.find('input[name="phone"]').val());
                    toastr.success("Submit Successfully");
                    setTimeout(function () {
                        $('#thankyou-page').submit();
                    }, 3000);

                })
                .catch(function (error) {
                    var errors = error.response.data;
                    toastr.error("Please refresh page and try again");
                    form.find('button').removeClass('disable').removeAttr('disabled').text('Submit');
                });
            return false;

        };


        var url = 'https://script.google.com/macros/s/AKfycbxNZQrFHgFz2hlpH-pcnHhgyuH5zFDLedSsKRpNg42UZlFmkQwm/exec';
        var sendDataToGoogleForm = function (name, email, phone) {

            var dataSend = {
                'Name': name,
                'E-mail ID': email,
                'Contact No.': phone,
                'Certificate & Course': 'Japanese Work Culture',
                'Qualification': '',
                'Experience': '',
                'Remark': 'Source - Website LP JWC',
                'Form Edit URL': 'Web Admin',
                'Template 1 - Send Status': '',
                'Location': 'Website',
                'Timestamp': '<?php echo date('d/m/Y H:i:s');?>',
                'page': 'LP'
            };
            var jqxhr = $.ajax({
                url: url,
                method: "GET",
                dataType: "jsonP",
                crossDomain: true,
                data: dataSend
            }).done(function (res) {
                // console.log(res);
            });
        }
    </script>
@endpush
