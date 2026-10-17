<footer id="main-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 prod-cont one">
                    <h5>MONEY BACK GUARANTEE</h5>
                    <!---<p>One stop Training and Placement Solution Company aligning with Prime Ministers dream of "Skilled India","Kaushal Bharat Kushal Bharat".</p>-->
                    <img src="{{asset('rewamp/images/money.png')}}" alt="money" title="money back"/>
                </div>

                <div class="col-sm-3 prod-cont ">
                    <h5> LINKS</h5>
                    <ul>
                        <li><a href="{{route('about')}}">About Us</a></li>
                        <li><a href="{{route('contact')}}">Contact Us</a></li>
                        <li><a href="{{route('faqs')}}">FAQ's</a></li>
                        <li><a href="{{route('blogs')}}">Our Blog</a></li>
                    </ul>

                </div>
                <div class="col-sm-3 prod-cont ">
                    <h5>OUR GROUP COMPANIES</h5>
                    <ul>
                        <li><a href="http://www.cdassgroup.com/" target="_blak">C.DASS Group</a></li>
                        <li><a href="http://www.maharanipaints.com/" target="_blank">Maharani Innovative Paints</a></li>
                        <li><a href="http://www.vamanioverseas.com/" target="_blank">Vamani Overseas</a></li>
                        <li><a href="http://www.wardhaitpark.com/" target="_blank">Wardha IT Park</a></li>
                    </ul>
                </div>
                <div class="col-sm-3 prod-cont">
                    <h5>GOVERNMENT AFFILATIONS</h5>
                    <img src="{{asset('rewamp/images/footer-logo-right.png')}}"/>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-social">
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <ul>
                        <li><a href="/sitemap.xml">Site Map </a></li>
                        <li><a href="{{route('terms')}}">Terms of Use </a></li>
                        <li><a href="{{route('policies')}}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-6 copy">
                    <p>© COPYRIGHT 2019 SMART SKILL TECH. ALL RIGHTS RESERVED.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

{{--<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 animated wow fadeInDown" data-wow-delay=".5s">
                <div class="footer-title ">
                    <h4>Company</h4>
                </div>
                <ul class="">
                    <li><a href="{{route('about')}}"><i class="fa fa-angle-right"></i> About us</a></li>
                    <li><a href="{{route('policies')}}"><i class="fa fa-angle-right"></i> Privacy &amp; Refund
                            Policy</a></li>
                    <li><a href="{{route('terms')}}"><i class="fa fa-angle-right"></i> Terms &amp; Conditions</a></li>
                    <li><a href="{{route('contact')}}"><i class="fa fa-angle-right"></i> Contact Us</a></li>
                    <li><a href="{{route('faqs')}}"><i class="fa fa-angle-right"></i> FAQs</a></li>
                    <li><a href="{{route('blog')}}"><i class="fa fa-angle-right"></i> Our Blog</a></li>
                </ul>


            </div>

            <div class="col-md-3 animated wow fadeInDown" data-wow-delay=".5s">
                <div class="footer-title">
                    <h4><a href="{{route('partners')}}">SKILL DEVELOPMENT CENTRES</a></h4>
                </div>
                <ul>
                    <li><a href="{{route('faridabad')}}"><i class="fa fa-angle-right"></i> FARIDABAD CENTRE</a></li>
                    <li><a href="{{route('wardha')}}"><i class="fa fa-angle-right"></i> WARDHA CENTRE </a></li>
                    <li><a href="{{route('nagpur')}}"><i class="fa fa-angle-right"></i> NAGPUR CENTRE</a></li>
                    <li><a href="{{route('gurugram')}}"><i class="fa fa-angle-right"></i> GURUGRAM CENTRE</a></li>
                    <li><a href="{{route('contact')}}"><i class="fa fa-angle-right"></i> PUNE CENTRE</a></li>
                </ul>
            </div>
            <div class="col-md-3 animated wow fadeInDown " data-wow-delay=".5s">
                <div class="footer-title">
                    <h4>Our Group Companies</h4>
                </div>
                <ul>
                    <li><a href="http://www.cdassgroup.com/" target="_blank"><i class="fa fa-angle-right"></i> C.DASS
                            Group</a></li>
                    <li><a href="http://www.maharanipaints.com/" target="_blank"><i class="fa fa-angle-right"></i>
                            Maharani Innovative Paints</a></li>
                    <li><a href="http://www.vamanioverseas.com/" target="_blank"><i class="fa fa-angle-right"></i>
                            Vamani Overseas</a></li>
                    <li><a href="http://www.wardhaitpark.com/" target="_blank"><i class="fa fa-angle-right"></i> Wardha
                            IT Park</a></li>
                </ul>
                --}}{{--
                <div class="footer-title-1">
                    <h4><a href="{{route('courses')}}">Skill Development Courses</a></h4>
                </div>
                    <ul>
                    @php
                    $coures = App\Model\Course::where('status', 1)
                            ->orderBy('order', 'asc')
                            ->get();
                    @endphp
                    @foreach($coures as $course)
                    <li><a href="{{route('course.detail', [$course->slug])}}"><i class="fa fa-angle-right"></i> {{$course->name}} </a></li>
                    @endforeach
                </ul>
                --}}{{--
            </div>
            --}}{{--
                        <div class="col-md-3 animated wow fadeInDown" data-wow-delay=".5s">
                            <div class="footer-title">
                                <h4><a href="{{route('partners')}}">Government Affiliations</a></h4>
                            </div>
            <ul>
                                <li><a href="{{route('pmkvy')}}"><i class="fa fa-angle-right"></i> PMKVY</a></li>
                                <li><a href="{{route('partners')}}"><i class="fa fa-angle-right"></i> SSCAMH </a></li>
                                <li><a href="{{route('partners')}}"><i class="fa fa-angle-right"></i> RASCI</a></li>
                                <li><a href="{{route('partners')}}"><i class="fa fa-angle-right"></i> NASSCOM</a></li>
                            </ul>
                            <div class="footer-title-1">
                                <h4>Support</h4>
                            </div>
            <ul class="">
                                <li><a href="{{route('faqs')}}"><i class="fa fa-angle-right"></i> FAQs</a></li>
                                <li><a href="{{route('blog')}}"><i class="fa fa-angle-right"></i> Our Blog</a></li>
                            </ul>
                        </div>
                        --}}{{--
            <div class="col-md-3 animated wow fadeInDown" data-wow-delay=".5s">
                <div class="footer-title">
                    <h4>Follow us</h4>
                </div>
                <ul class="list-inline" id="social">
                    <li><a href="https://www.linkedin.com/company/smart-skill-tech-pvt.-ltd." target="_blank"><i
                                    class="fa fa-2x fa-linkedin"></i></a></li>
                    <li><a href="https://www.facebook.com/smartskilltech" target="_blank"><i
                                    class="fa fa-2x fa-facebook"></i></a></li>
                    <li><a href="https://twitter.com/SmartSkillTech" target="_blank"><i class="fa fa-2x fa-twitter"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 animated wow fadeInDown" data-wow-delay=".5s">
                <div class="footer-title">
                    <h4>Newsletter</h4>
                </div>
                <div class="form-group newsletterbox">
                    <form action="{{route('subscribe')}}" method="post" id="subscriber-form">
                        <input type="email" class="form-control" placeholder="enter your email address" name="email"
                               required="">
                        <button class="btn btn-success" type="submit" id="subscriber-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>--}}


@push('scripts')
    <script type="text/javascript">
        $("#subscriber-form").validate({
            submitHandler: function (form) {
                $('#subscriber-btn').addClass('disable').attr('disabled', 'disabled');
                $("#subscriber-btn").html("<i class='fa fa-spinner fa-spin'></i>");
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        toastr.success('Thankyou for subscribing');
                        $("#subscriber-btn").html("<i class='fa fa-check'></i>");
                        $('#subscriber-submit').removeClass('disable').removeAttr('disabled');
                        setTimeout(function(){
                            window.location.reload(1);
                        }, 3000);
                    })
                    .catch(function (error) {
                        var errors = error.response.data;
                        for (i in errors) {
                            toastr.error(errors[i]);
                        }
                        $('#subscriber-submit').removeClass('disable').removeAttr('disabled');
                    });
                return false;
            }
        });
    </script>
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/5b2b7f80d0b5a54796820896/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endpush
