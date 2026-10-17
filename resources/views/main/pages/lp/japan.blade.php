<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Urgent Requirements In Japan - Smart Skill Tech</title>
    <meta name="copyright" content="Smart Skill Tech" />
    <meta name="description" content="Urgent Requirements in Japan, Interview Date - 27,28,29 July, 2018"
    />
    <meta name="keywords" content="Smart Skill Tech,smart, smart skills, smart technologies, Japan, interview"
    />
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="{{url()->current()}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/japan.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
     <!-- Scripts -->
    @include('main.partials.top-scripts')
</head>

<body>
    <header>
        <div class="container">
            <div class="header clearfix">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <img src="{{asset('images/logo.jpg')}}">
                    </div>
                    <div class="col-md-9">
                        <nav>
                            <ul class="pull-right right-nav">
                                <li>
                                    <a href="tel:918929501502">
                                        <i class="fa fa-phone"></i> <span>(+91) 8929 501 502</span></a>
                                </li>
                                <li>
                                    <a href="mailto:info@smartskilltech.com">
                                        <i class="fa fa-envelope"></i> <span>info@smartskilltech.com</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        <span class="extrabold red">Job Opportunity in Japan,</span>
                        <br/>Software Developer/Programmer jobs</h1>
                    {{--  <div class="interview-section">
                        <p class="date-interview">Interview Date : 18 Aug, 2018</p>
                        <div class="clearfix"></div>
                        <p class="int-venue">
                            <b>Interview Venue</b> :
                            <br/>A32, Sector 11. Main road, Near Shani Mandir, Faridabad.</p>
                    </div>  --}}



                    <div class="w100 mt-15" id="timer">
                        <div class="counter-section">
                            Registration open till : {{--date('d M, Y')--}} 18 Aug, 2018



                            <div id="countdown" class="mt-15">
                                <span id="days" class="timer">0</span>
                                <span class="timer1"></span>
                                <span id="hour" class="timer"></span>
                                <span class="timer1"></span>
                                <span id="min" class="timer"></span>
                                <span class="timer1"></span>
                                <span id="sec" class="timer"></span>
                            </div>



                        </div>
                    </div>
                    <div class="w100 mt-15">
                        <div class="hurry-up">Hurry Up
                            <span class="bold red">Register Now</span>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 col-md-offset-2">

                    <div class="form-area red-bg">
                       <form role="form" class="form" action="{{route('requirements-in-japan.submit')}}" method="post" name="form1" onSubmit="return validateSubmit(this);">
    {{csrf_field()}}

                            <h3>Register Now
                                <span class="pull-right">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </h3>
                            <p>Fill the form below to get instant access</p>
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Number" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                            </div>


                            <div class="form-group">
                                <button type="submit"  name="submit" class="btn bg-yellow form-control">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>




    <section class="ideal-solution bg-red video-section">

        <div class="container">

            <div class="row">
                <div class="col-md-7">
                    <div class="caption-sec">
                        <h3>About
                            <span>Job Opportunity</span>
                        </h3>
                        <div class="video-frrame">
                            <p>Japan has a huge requirement of IT professionals and India is known as a hub of skilled IT professionals.
                                We are looking for the candidates who are eager to make their career in the field of information
                                technology and ready to work in Japan. There is an urgent requirement in Japan for the skilled
                                IT professionals having 1-2 years of experience, in the area of
                                <b>Web application development</b> and
                                <b>System development of E-commerce.</b>
                            </p>
                            <h2>Benefits:</h2>
                            <p>
                                <b>Salary</b> : 18 -50 Lakhs PA</p>
                            <p>
                                <b>Employment status</b> : Regular</p>
                            <p>
                                <b>Bonus </b> : Twice in a year</p>
                            <p>
                                <b>Leaves Policy</b> : Saturday, Sunday, National Holiday, Summer, winter holiday, casual leave,
                                special leave( maternity leave, child leave, marriage leave, etc )</p>
                            <p>
                                <b>Other Benefits</b> : Social insurance, transportation expenses ,fixed pension system, welfare
                                pension fund, Visa support , accommodation support, Stock option, Study space given, Gym,
                                etc.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-area red-bg2">
                        <form role="form" class="form" action="{{route('requirements-in-japan.submit')}}" method="post" name="form1" onSubmit="return validateSubmit(this);">
    {{csrf_field()}}

                            <h3 class="mt-0 mb-0">Register Now
                                <span class="pull-right">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </h3>
                            <p>Its Time to get
                                <span class="bold">well paid Job</span>
                            </p>
                            <div class="form-group">
                                <input class="form-control" id="name" name="name" placeholder="Name" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="email" name="email" placeholder="Email" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="phone" name="phone" placeholder="Mobile Number" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="city" name="city" placeholder="City" required type="text">
                            </div>
                            <div class="form-group">
                                <button type="submit"  name="submit" class="btn bg-yellow w100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="how-it-works who-should-attend">
        <h3>Who is this program for?</h3>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="image">
                        <img src="{{asset('images/software-developer.png')}}">
                    </div>
                    <p>Software Developer
                    </p>
                </div>
                <div class="col-md-3">
                    <div class="image">
                        <img src="{{asset('images/software-programmer.png')}}">
                    </div>
                    <p>Software programmer</p>
                </div>
                <div class="col-md-3">
                    <div class="image">
                        <img src="{{asset('images/game-developer.png')}}">
                    </div>
                    <p>Game Developers</p>
                </div>
                <div class="col-md-3">
                    <div class="image">
                        <img src="{{asset('images/ios-android-developer.png')}}">
                    </div>
                    <p>IOS/Android developers</p>
                </div>
            </div>

        </div>
    </section>

    <section class="ideal-solution bg-red what-can">

        <div class="container">

            <div class="row">
                <div class="col-md-3">
                    <div class="caption-sec">
                        <h3>What they need?</h3>
                        <ul class="bullets-right">
                            <li>Software skills</li>
                            <li>experience 1-2 yrs</li>
                            <li>Developments Skills</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="caption-sec">
                        <h3>What we provide?</h3>
                        <ul class="bullets-right">
                            <li>Training on Japanese Language</li>
                            <li>Training on Japanese Work Culture</li>
                            <li>Trainer from Japan</li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-area red-bg2 form-area1">
                        <form role="form" class="form" action="{{route('requirements-in-japan.submit')}}" method="post" name="form1" onSubmit="return validateSubmit(this);">
    {{csrf_field()}}

                            <h3 class="mt-0 mb-0">Register
                                <span class="pull-right">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </h3>

                            <div class="form-group">
                                <input class="form-control" id="name" name="name" placeholder="Name" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="email" name="email" placeholder="Email" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="phone" name="phone" placeholder="Mobile Number" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="city" name="city" placeholder="City" required type="text">
                            </div>


                            <div class="form-group">
                                <button type="submit"  name="submit" class="btn bg-yellow">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="testimonial" class="testimonials">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="what-can">
                        <div class="caption-sec">
                            <h3>Joining Formalities</h3>
                            <ul class="bullets-right">
                                <li>Assessment Free</li>
                                <li>Registration Fees <i class="fa fa-inr"></i> 2,500</li>
                                <li>Training duration 30-45 days</li>
                                <li>Training batch only 60 seats</li>
                                <li>After selection job package - 18 lac to 50 lac pa</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-area red-bg2 form-area1">
                       <form role="form" class="form" action="{{route('requirements-in-japan.submit')}}" method="post" name="form1" onSubmit="return validateSubmit(this);">
    {{csrf_field()}}

                            <h3>Register Now
                                <span class="pull-right">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </h3>

                            <div class="form-group">
                                <input class="form-control" id="name" name="name" placeholder="Name" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="email" name="email" placeholder="Email" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="phone" name="phone" placeholder="Mobile Number" required type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="city" name="city" placeholder="City" required type="text">
                            </div>




                            <div class="form-group">
                                <button type="submit"  name="submit" class="btn btn-danger w100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="corporate-partners current-opening">
        <div class="container">
            <div class="caption-sec">
                <h3>Current Openings</h3>

            </div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="submit" ollapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" onClick="$('#collapseOne').toggleClass('in')">
                                <i class="more-less glyphicon glyphicon-plus"></i>
                                Web Application Development Engineer
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body tab-cnt">
                            <p>
                                <span>Job Requirements </span>
                                <span>EC site design development, portal site design development, etc, Regarding Project.
                                </span>
                            </p>
                            <p>
                                <span>Job Description </span>
                                <span>Business application development for financial, public business, medical, and telecommunication.

                                </span>
                            </p>
                            <p>
                                <span>Application requirements :skill</span>
                                <span>[Required] Experience working of WEB application development.
                                    <br/>[Much better] A person who shows initiative and resourcefulness.
                                </span>
                            </p>
                            <p>
                                <span>Work department</span>
                                <span>TOKYO</span>
                            </p>
                            <p>
                                <span>Career Plan</span>
                                <span>not need specially </span>
                            </p>
                            <p>
                                <span>Work location</span>
                                <span>MINATOKU, TOKYO,JAPAN</span>
                            </p>
                            <p>
                                <span>Traffic</span>
                                <span>Regarding Project place</span>
                            </p>
                            <p>
                                <span>Working hours</span>
                                <span>9AM-6PM ( 1 hour break )</span>
                            </p>
                            <p>
                                <span>Break Time</span>
                                <span>60min</span>
                            </p>
                            <p>
                                <span>overtime hours for extra work</span>
                                <span>20-30hours / month</span>
                            </p>
                            <p>
                                <span>Annual salary</span>
                                <span>3million to 6 million JPY</span>
                            </p>
                            <p>
                                <span>Overtime paid</span>
                                <span>None</span>
                            </p>
                            <p>
                                <span>Bonus</span>
                                <span>2 times in a year, +end of year bonus</span>
                            </p>
                            <p>
                                <span>Employment status</span>
                                <span>Regular employee</span>
                            </p>
                            <p>
                                <span>Pay rise</span>
                                <span>twice a year</span>
                            </p>
                            <p>
                                <span>Employment period</span>
                                <span>indefinites ( Training term is for 2 month)</span>
                            </p>
                            <p>
                                <span>Annual holiday</span>
                                <span>123 days in a year including Saturday Sunday, casual leave, Regarding own calendar</span>
                            </p>
                            <p>
                                <span>Benefits</span>
                                <span>Social insurance, transportation expenses (maximum 30,000 yen / month) fixed pension system,
                                    welfare pension fund, accommodation support, Group stock option, family leave, maternity
                                    leave</span>
                            </p>
                            <p>
                                <span>Social pension</span>
                                <span>yes</span>
                            </p>
                            <p>
                                <span>Main business activities</span>
                                <span>Business application, network, network database, software installation, system maintenance,
                                    operating</span>
                            </p>
                            <p>
                                <span>Japanese language skill</span>
                                <span>【must 】communication revel ( Up to N3)
                                </span>
                            </p>

                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" role="submit" ollapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo" onClick="$('#collapseTwo').toggleClass('in')">
                                <i class="more-less glyphicon glyphicon-plus"></i>
                                System Development Position Search

                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body tab-cnt">
                            <p><span>Job Requirements</span> <span>"To design, development and operation of global EC marketplace platform<br/>
System development of EC commerce for shopping costomer / Development of management systems for shops"</span></p>
<p><span>Job Description</span><span>Development of delivery service system , Startup of new services</span></p>
<p><span>Application requirements :skill</span><span>【Required】 Up to three years system development practical experience, those who are not resistant to the English language as the official language in comapany<br/>[more Better] Up to TOEIC 600 score, or equivalent English ability, those who can acquire TOEIC up to 800 score after joinning the company</span></p>
<p><span>Application requirements :mental</span><span>Self-starter type</span></p>
<p><span>Work department</span><span></span>Self-starter type</p>
<p><span>Work department</span><span>TOKYO</span></p>
<p><span>Career Plan</span><span>not need specially </span></p>
<p><span>Work location</span><span>Setagaya, TOKYO,JAPAN</span></p>
<p><span>Traffic</span><span>hutakotamagawa St,</span></p>
<p><span>Working hours</span><span>9AM-5:30PM ( Real working time is 7H30min)</span></p>
<p><span>Break Time</span><span>60min</span></p>
<p><span>overtime hours for extra work</span><span>around 40hours / month</span></p>
<p><span>Annual salary</span><span>4milliom to 10million  JPY</span></p>
<p><span>Overtime paid</span><span>None</span></p>
<p><span>Bonus</span><span>2 times in a year( June ,December)</span></p>
<p><span>Employment status</span><span>Regular employee</span></p>
<p><span>Pay rise</span><span>twice a year ( June ,December),Regarding result annual sales of company or personal effort</span></p>
<p><span>Employment period</span><span>indefinites</span></p>
<p><span>Annual holiday</span><span>Saturday, Sunday, National Holiday, Summer,winter holiday,casual leave, special leave( maternity leave, child leave, marriage leave etc )</span></p>
<p><span>Benefits </span><span>Social insurance, transportation expenses ,fixed pension system, welfare pension fund, Visa support . accommodation support, Stock option, Study space given, Gim, some party, etc</span></p>
<p><span>Social pension</span><span>Yes</span></p>
<p><span>Main business activities</span><span>EC business , auction business, Internet services such as golf, business, securities, credit card business , etc.</span></p>
<p><span>Japanese language skill</span><span>Not necessary Japanese skill however,resume should be written in Japanese </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- carousel slide -->
    </section>
    <footer class="bg-red">
        <p>Copyright 2018 smart skill tech. all right reserved</p>
    </footer>
    <form action="{{route('requirements-in-japan.thankyou')}}" method="post" id="thankyou-page">{{csrf_field()}}</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('js/jquery.3.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script>
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
            jQuery('#countdown #days').html('{{18 - date('d')}}' + 'd');
                jQuery('#countdown #hour').html(hours + 'h');
                jQuery('#countdown #min').html(min + 'm');
                jQuery('#countdown #sec').html(sec + 's');
            }, 1000);
        });

        var validateSubmit = function(form){
            var form = $(form);
            form.find('button').addClass('disable').attr('disabled', 'disabled').text('submitting...');
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                //sendDataToGoogleForm(form.find('input[name="name"]').val(), form.find('input[name="email"]').val(), form.find('input[name="phone"]').val(), form.find('input[name="city"]').val());
                toastr.success("Submit Successfully");
            setTimeout(function(){
                    $('#thankyou-page').submit();
            }, 3000);

            })
            .catch(function (error) {
                var errors = error.response.data;
                toastr.error("Please refresh page and try again");
                form.find('button').removeClass('disable').removeAttr('disabled').text('Submit');
            });
            return false;

        }
        var   url = 'https://script.google.com/macros/s/AKfycbxNZQrFHgFz2hlpH-pcnHhgyuH5zFDLedSsKRpNg42UZlFmkQwm/exec';
        var sendDataToGoogleForm = function(name, email, phone, city){

            var dataSend = {
                'Name': name,
                'E-mail ID': email,
                'Contact No.': phone,
                'Certificate & Course':'Japanese Inteview - 27,28,29 July, 2018',
                'Qualification':'Software Developer',
                'Experience':'1-2 yr',
                'Remark':'Source - Website LP Japan Interview',
                'Form Edit URL':'Web Admin',
                'Template 1 - Send Status':'',
                'Location': city,
                'Timestamp':'<?php echo date('d/m/Y H:i:s');?>',
                'page':'Japan LP'
            };
            var jqxhr = $.ajax({
                    url: url,
                    method: "GET",
                    dataType: "jsonP",
                    crossDomain: true,
                    data: dataSend
            }).done(function(res){});
        }
    </script>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5b2b7f80d0b5a54796820896/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
{{--
<script type="text/javascript">
_linkedin_partner_id = "271019";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="linkedin" src="https://dc.ads.linkedin.com/collect/?pid=271019&fmt=gif" />
</noscript>  --}}
</body>
</html>
