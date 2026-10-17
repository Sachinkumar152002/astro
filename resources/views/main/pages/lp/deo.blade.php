@extends('layouts.landing')
@section('content')

    <div id="data-entry-pro" class="data-entry-pro">
        <section id="sdp-enroll">
            <div class="row">
                <div class="col-sm-8">
                    <div class="sdp-inner">
                        <div class="text-sdp">
                            <h1>Enroll Now and kickstart your life of opportunities.</h1>
                            <p>It is time to move on with the fast changing technology.Hence Smart Skill Tech is the
                                perfect platform for you.</p>
                            <h5 class="price-cou">Data Entry Operator Online Course @ <b><i class="fa fa-inr">&nbsp;</i>
                                    5,999/- only</b></h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="sdp-left-form prod-cont">
                        <h5>REGISTER WITH US</h5>
                        <form action="{{route('contact.submit')}}" method="post" name="form1" class="radiobox"
                              id="form-skilltraining1">
                            {{csrf_field()}}
                            <input type="hidden" name="source" value="1">
                            <input type="hidden" name="for" value="Data Entry Operator">
                            <input type="hidden" name="message" value="Data Entry Operator">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" required placeholder="Full name" type="text"
                                        class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email address</label>
                                <input required name="email" placeholder="Email" type="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Phone No</label>
                                <input required name="phone" placeholder="phone" type="phone" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn-submit1">Submit</button>
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
        </section>

        <section id="sample">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sample-inner prod-cont">
                            <h5> Data Entry Operator</h5>

                            <p>Data Entry course is one of the most preferred courses in India. Whether for part-time, full-time and work from home it has availability for all. This online skill development program will give an individual a chance to teach typing, database management, Microsoft office, Personality Development and File Handling. It has everything that can help to get the data entry job in any part of the country.</p>
                            <a href="#" class="getregistration">Join Now</a>
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
                            <li>Are you Jobless, even after graduating?</li>
                            <li>Are you getting rejected from Interviews?</li>
                            <li>Has Campus Placements not benefited you?</li>
                            <li>Having a good degree from good college is not helping you?</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 listing">
                        <ul>
                            <li>Are you Jobless, even after graduating?</li>
                            <li>Are you getting rejected from Interviews?</li>
                            <li>Has Campus Placements not benefited you?</li>
                            <li>Having a good degree from good college is not helping you?</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="upskill">
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
                            <li><img alt="Microdoft" src="{{asset('rewamp/images/microsoft.jpg')}}"></li>
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
                        <img alt="ALptop" src="{{asset('rewamp/images/sdp/laptop.png')}}"/>
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
        <form action="{{route('cart.item.add',['data-entry-operator'])}}" method="get" id="thankyou-page"></form>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('js/jquery.3.1.1.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>

    <script type="text/javascript">
        $(".getregistration").click(function() {
            $("html, body").animate({ scrollTop: 0 }, 2000);
            return false;
        });

        $("#form-skilltraining1").validate({
            submitHandler: function (form) {
                $('#btn-submit1').addClass('disable').attr('disabled', 'disabled').text('Submitting...');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        sendDataToGoogleForm(form.find('input[name="name"]').val(), form.find('input[name="email"]').val(), form.find('input[name="phone"]').val());
                        toastr.success("Submit Successfully");
                        setTimeout(function () {
                            $('#thankyou-page').submit();
                        }, 3000);

                    })
                    .catch(function (error) {
                        var errors = error.response.data;
                        // for(i in errors){
                        // toastr.error(errors[i]);
                        // }
                        toastr.error("Please refresh page and try again");
                        $('#btn-submit1').removeClass('disable').removeAttr('disabled').text('Submit');
                    });
                return false;
            }
        });
        $("#form-skilltraining2").validate({
            submitHandler: function (form) {
                $('#btn-submit2').addClass('disable').attr('disabled', 'disabled').text('submitting...');
                var form = $(form);
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
                        // for(i in errors){
                        // toastr.error(errors[i]);
                        // }
                        toastr.error("Please refresh page and try again");
                        $('#btn-submit2').removeClass('disable').removeAttr('disabled').text('Submit');
                    });
                return false;
            }
        });
        var url = 'https://script.google.com/macros/s/AKfycbxNZQrFHgFz2hlpH-pcnHhgyuH5zFDLedSsKRpNg42UZlFmkQwm/exec';
        var sendDataToGoogleForm = function (name, email, phone) {

            var dataSend = {
                'Name': name,
                'E-mail ID': email,
                'Contact No.': phone,
                'Certificate & Course': 'Data Entry Operator (Certified By NSDC)',
                'Qualification': '',
                'Experience': '',
                'Remark': 'Source - Website LP DEO',
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