@extends('layouts.app')
@section('content')
    @include('main.partials.breadcrumb')
    <!--banner start-->
    <div class="cotact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3511.089507428733!2d77.30417881459778!3d28.356143403288122!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cdb7c7139989d%3A0x6e9661641a8cd6cf!2sSmart+Skill+Tech+Pvt.+Ltd.!5e0!3m2!1sen!2sin!4v1552725053264"
                width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>

    <div class="container">
        <div class="contact-us">
            <div class="row">
                <div class="col-md-4 prod-cont">
                    <h5>Contact Information</h5>
                    <p>Please let us know if you have a question, want to leave a comment, or would like further
                        information
                        about Smart Skill Tech.</p>
                    <hr/>
                    <h4>Contact Information</h4>
                    <div class="contact-page-add">
                        <ul>

                            <li class="email"><a href="mailto:info@smartskilltech.com">info@smartskilltech.com</a></li>
                            <li class="pho">(+91)7290-003-096</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 prod-cont">
                    <h5>Get In Touch</h5>
                    <div class="contact-form-se">
                        <form role="form" action="{{route('contact.submit')}}" method="post" id="contact-form"
                              novalidate="novalidate">
                            {{csrf_field()}}
                            <div class="contactform-left">
                                <div class="form-group">
                                    <input class="form-control" id="contact_name" name="name" placeholder="Name"
                                           required="" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="contact_email" name="email" placeholder="Email here"
                                           required="" type="email">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="contact_mobile" name="phone" placeholder="Phone"
                                           required="" type="text">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="contact_for" name="for" required="">
                                        <option value="">Select Course</option>
                                        <option value="Software Developer Program">Software Developer Program</option>
                                        <option value="Data Entry Operator">Data Entry Operator</option>
                                        <option value="Japanese Work Culture">Japanese Work Culture</option>
                                        <option value="Process Coordinator Program">Process Coordinator Program</option>
                                        <option value="Smart Employment Enhancement Program">Smart Employment
                                            Enhancement Program
                                        </option>
                                        <option value="Video Resume Training">Video Resume Training</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="contactform-right">
                                <div class="form-group">
                                    <textarea class="form-control" type="textarea" id="contact_message" name="message"
                                              placeholder="Query" rows="3" required=""></textarea>
                                </div>

                                <input class="btn btn-primary subt" name="Send" style="margin-top:20px;"
                                       type="submit" id="contact-btn">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<section id="page-header">
        <img src="images/inside-banner/contact.jpg" style="width:100%">
    </section>

    <section class="about">
        <div id="suc_msg" style="text-align: center; display: none;">
            <br><b></b></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3  wow slideInLeft animated" data-wow-delay=".5s"
                         style="visibility: visible; animation-delay: 0.5s; animation-name: slideInLeft;">
                        <div class="contact-detail text-center">
                            <i class="fa fa-map-marker"></i>
                            <h5></h5>
                            <p>
                                <b> Registered Office: </b> Plot - 137, Sector 24, Faridabad, Haryana 121005</p>
                        </div>
                        <div class="contact-detail text-center">
                            <i class="fa fa-phone"></i>
                            <h5></h5>
                            <p>
                                <b>Mob:</b> +91 8929 501 502</p>
                        </div>
                        <div class="contact-detail text-center">
                            <i class="fa fa-globe"></i>
                            <h5></h5>
                            <p>
                                <b>Email:</b> info@smartskilltech.com</p>
                        </div>
                    </div>
                    <div class="col-md-4  wow flipInX animated" data-wow-delay=".5s"
                         style="visibility: visible; animation-delay: 0.5s; animation-name: flipInX;">
                        <h3 class="title-1 text-uppercase">Contact Form</h3>
                        <div class="form-area">

                        </div>
                    </div>
                    <div class="col-md-5  wow fadeInRight animated" data-wow-delay=".5s"
                         style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInRight;">
                        <h3 class="title-1 text-uppercase">Our Location</h3>
                        <div class="map">

                            <div style="width: 100%">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14044.35865193012!2d77.3063675!3d28.3561387!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6e9661641a8cd6cf!2sSmart+Skill+Tech+Pvt.+Ltd.!5e0!3m2!1sen!2sin!4v1531889425676"
                                        style="width:100%" height="450" frameborder="0" style="border:0"></iframe>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
@endsection

@push('scripts')
    <script type="text/javascript">
        $("#contact-form").validate({
            submitHandler: function (form) {
                $('#contact-btn').addClass('disable').attr('disabled', 'disabled');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        toastr.success('Thankyou for contacting us');
                        $('#contact-submit').removeClass('disable').removeAttr('disabled');
                        sendDataToGoogleForm($('#contact_name').val(), $('#contact_email').val(), $('#contact_mobile').val(), $('#contact_for').val(), $('#contact_message').val());
                        setTimeout(function () {
                            window.location.reload();
                        }, 5000);
                    })
                    .catch(function (error) {
                        var errors = error.response.data;
                        // for (i in errors) {
                        //     toastr.error(errors[i]);
                        // }
                        toastr.error("Please refresh page and try again");
                        $('#contact-submit').removeClass('disable').removeAttr('disabled');
                    });
                return false;
            }
        });

        var url = 'https://script.google.com/macros/s/AKfycbxNZQrFHgFz2hlpH-pcnHhgyuH5zFDLedSsKRpNg42UZlFmkQwm/exec';
        var sendDataToGoogleForm = function (name, email, phone, _for, message) {

            var dataSend = {
                'Name': name,
                'E-mail ID': email,
                'Contact No.': phone,
                'Certificate & Course': message,
                'Qualification': '',
                'Experience': '',
                'Remark': 'Source - Website Contact Page' + _for,
                'Form Edit URL': 'Web Admin',
                'Template 1 - Send Status': '',
                'Location': 'Website Contact page',
                'Timestamp': '<?php echo date('d/m/Y H:i:s');?>',
                'page': 'LP',
                'Education': ''
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