@extends('layouts.app')
@push('styles')
    <link href="{{asset('css/lightgallery.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    @include('main.partials.breadcrumb')

    <section id="partner" class="about bg-grey">
        <div class="container">
            <div class="about-us-page-tab">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#home">Government Affiliation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu1">Corporate Affiliation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu2">Universities Affiliation</a>
                    </li>
                </ul>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="home" class="container tab-pane active"><br>
                    <h3>Government Affiliation: The Secured Department</h3>
                    <p>Smart Skill Tech has tied up with various government bodies. These organisations offer secure jobs
                        and immense opportunities along with incentives for those who wish to plan their career in public
                        sector. As per the certification of the courses you undergo, we will help you for interviews and the
                        most appropriate profile.</p>

                    <h3>Apparel Made Ups And Home Furnishing Sector Skill Council</h3>
                    <p>AMHSSC has been authorized by NSDC for evolving assessing proficiencies of skills of trainees for the
                        apparel sector, made-ups and home furnishing for their respective subject areas.</p>
                    <a href="http://www.sscamh.com/" target="blank"><i class="fa fa-globe"></i> www.sscamh.com</a>

                    <h3>Retailer's Association Skill Council of India</h3>
                    <p>RASCI is a not-for-profit; independent public limited organisation funded by the Government of India
                        along with Equity participation from Retailers Association of India for the respected job roles.</p>
                    <a href="http://rasci.in" target="blank"><i class="fa fa-globe"></i> www.rasci.in</a>

                    <h3>The National Association of Software and Services</h3>
                    <p>Companies is a trade association of Indian Information Technology and Business Process Outsourcing
                        industry. Established in 1988, NASSCOM is a non-profit organisation.</p>
                    <a href="http://www.nasscom.in/" target="blank"><i class="fa fa-globe"></i> www.nasscom.in</a>

                    <h3>Pradhan Mantri Kaushal Vikas Yojana (PMKVY)</h3>
                    <p>The flagship scheme of the Ministry of Skill Development &amp; Entrepreneurship (MSDE).Under this
                        Scheme, Training and Assessment fees are completely paid by the Government.</p>
                    <a href="http://www.pmkvyofficial.org/" target="blank"><i class="fa fa-globe"></i> www.pmkvyofficial.org</a>
                </div>
                <div id="menu1" class="container tab-pane fade"><br>
                    <h3>Recruitments Affiliation:The Corporate Beginning</h3>
                    <p>The most prestigious of all are the corporates. Reason being that they are highly in demand but
                        vacancies are limited. The lateral job vacancies come up in less number and the competition is huge.
                        Thus, we ensure to bring good recruitments for you once you get the certification. The corporates
                        are mostly preferred as they offer high growth and good promotions. But at the same time you need to
                        work hard and achieve targets to get that position.</p>
                    <div class="row partner-sec">
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/ace.png')}}"></div>
                                    <div class="col-md-8"><h4>Action Construction Equipment Ltd</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/escorts.png')}}"></div>
                                    <div class="col-md-8"><h4>Escorts Agri Machinery</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/BMW-logo.png')}}"></div>
                                    <div class="col-md-8"><h4>Deutsche Motoren</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row partner-sec mt-30">
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/ifco.png')}}"></div>
                                    <div class="col-md-8"><h4>General Insurance Company Ltd</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/jbm.png')}}"></div>
                                    <div class="col-md-8"><h4>JBM Industries Ltd</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/ntpc.png')}}"></div>
                                    <div class="col-md-8"><h4>National Thermal Power Corporation Ltd</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row partner-sec mt-30">
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/nutec.png')}}"></div>
                                    <div class="col-md-8"><h4>Nutech Group</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/fedral-logo.png')}}"></div>
                                    <div class="col-md-8"><h4>Federal Mogul PowerTrain</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4"><img src="{{asset('rewamp/images/about-us/vamani.png')}}"></div>
                                    <div class="col-md-8"><h4>Vamani Overseas Private Limited</h4></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="menu2" class="container tab-pane fade"><br>
                    <h3>Universities Affiliation: Education has the utmost scope</h3>
                    <p>Universities are the education hub. They are preferred by ones who wish to learn throughout their
                        career because pedagogy in itself teaches a lot. There is no end to this and you can explore all
                        kinds of verticals as per your interest. Hence, we care for your interest and arrange reputed
                        university recruitments.</p>
                    <div class="row">
                        <div class="demo-gallery">
                            <ul id="lightgallery-about" class="list-unstyled">
                                <li style="display: inline-block;padding: 10px;" class="col-xs-12 col-sm-12 col-md-12"
                                    data-responsive="rewamp/images/nagpur-coll.jpg 375, rewamp/images/nagpur-coll.jpg 480, rewamp/images/nagpur-coll.jpg 800"
                                    data-src="rewamp/images/nagpur-coll.jpg"
                                    data-sub-html="<h4>Training Under President House</h4><p></p>">
                                    <a href="">
                                        <img src="{{asset('rewamp/images/nagpur-coll.jpg')}}" style="border-radius: unset;width: 100%;">
                                    </a>
                                </li>
                                <li style="display: inline-block;padding: 10px;" class="col-xs-6 col-sm-5 col-md-5"
                                    data-responsive="rewamp/images/about-us/nu1.jpg 375, rewamp/images/about-us/nu1.jpg 480, rewamp/images/about-us/nu1.jpg 800"
                                    data-src="rewamp/images/about-us/nu1.jpg"
                                    data-sub-html="<h4>Training Under President House</h4><p></p>">
                                    <a href="">
                                        <img src="{{asset('rewamp/images/about-us/nu1.jpg')}}" style="border-radius: unset;width: 100%;">
                                    </a>
                                </li>
                                <li style="display: inline-block;padding: 10px;" class="col-xs-6 col-sm-5 col-md-5"
                                    data-responsive="rewamp/images/about-us/nu2.jpg 375, rewamp/images/about-us/nu2.jpg 480, rewamp/images/about-us/nu2.jpg 800"
                                    data-src="rewamp/images/about-us/nu2.jpg"
                                    data-sub-html="<h4>Training Under President House</h4><p></p>">
                                    <a href="">
                                        <img src="{{asset('rewamp/images/about-us/nu2.jpg')}}" style="border-radius: unset;width: 100%;">
                                    </a>
                                </li>
                            </ul>
                        </div>
                       {{-- <div class="col-md-6 image">
                            <img class="img-fluid" src="{{asset('rewamp/images/about-us/nu1.jpg')}}">
                            <div class="overlay"><a href="#" class="icon" title="zoom"><i class="fa fa-search-plus"></i></a>
                            </div>
                        </div>
                        <div class="col-md-6 image"><img class="img-fluid" src="{{asset('rewamp/images/about-us/nu2.jpg')}}">
                            <div class="overlay"><a href="#" class="icon" title="zoom"><i class="fa fa-search-plus"></i></a>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--<section id="page-header">
        <img src="{{asset('images/inside-banner/partners.jpg')}}" style="width:100%">
    </section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-8 col-xs-9 bhoechie-tab-container">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bhoechie-tab-menu">
                    <div class="list-group">
                        <a href="#" class="list-group-item active text-center">Government Affiliation</a>
                        <a href="#" class="list-group-item text-center">Recruitments Affiliation</a>
                        <a href="#" class="list-group-item text-center">Universities Affiliation</a>

                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 bhoechie-tab">
                    <!-- flight section -->
                    <div class="bhoechie-tab-content active">
                        <div class="tab-pane active" id="Government-Affiliation">
                            <h3 class="title-1 text-uppercase">Government Affiliation: The Secured Department</h3>

                            <!--<h3 class="title-3" style="margin-top:0">The secured department</h3>-->
                            <p>Smart Skill Tech has tied up with various government bodies. These organisations offer
                                secure jobs and immense opportunities along with incentives for those who wish to plan
                                their career in public sector. As per the certification
                                of the courses you undergo, we will help you for interviews and the most appropriate
                                profile.</p>
                            <h3 class="title-3" style="margin-top:0">Apparel Made Ups And Home Furnishing Sector Skill
                                Council</h3>
                            <p>AMHSSC has been authorized by NSDC for evolving assessing proficiencies of skills of
                                trainees for the apparel sector, made-ups and home furnishing for their respective
                                subject areas.</p>
                            <p class="weblink"><i class="fa fa-globe"></i> <a href="http://www.sscamh.com/"
                                                                              target="_blank"> www.sscamh.com</a></p>
                            <h3 class="title-3">Retailer's Association Skill Council of India</h3>
                            <p>RASCI is a not-for-profit; independent public limited organisation funded by the
                                Government of India along with Equity participation from Retailers Association of India
                                for the respected job roles.</p>
                            <p class="weblink"><i class="fa fa-globe"></i> <a href="http://rasci.in/" target="_blank">
                                    www.rasci.in</a></p>
                            <h3 class="title-3">The National Association of Software and Services</h3>
                            <p>Companies is a trade association of Indian Information Technology and Business Process
                                Outsourcing industry. Established in 1988, NASSCOM is a non-profit organisation.</p>
                            <p class="weblink"><i class="fa fa-globe"></i> <a href="http://www.nasscom.in/"
                                                                              target="_blank"> www.nasscom.in</a></p>
                            <h3 class="title-3">Pradhan Mantri Kaushal Vikas Yojana (PMKVY)</h3>
                            <p>The flagship scheme of the Ministry of Skill Development &amp; Entrepreneurship
                                (MSDE).Under this Scheme, Training and Assessment fees are completely paid by the
                                Government.</p>
                            <p class="weblink"><i class="fa fa-globe"></i> <a href="http://www.pmkvyofficial.org/"
                                                                              target="_blank"> www.pmkvyofficial.org</a>
                            </p>

                        </div>
                    </div>
                    <!-- train section -->
                    <div class="bhoechie-tab-content">
                        <div class="tab-pane " id="Recruitments-Affiliation">
                            <h3 class="title-1 text-uppercase">Recruitments Affiliation:The Corporate Beginning</h3>

                            <!-- <h3 class="title-3" style="margin-top:0">The corporate beginning</h3> -->
                            <p>The most prestigious of all are the corporates. Reason being that they are highly in
                                demand but vacancies are limited. The lateral job vacancies come up in less number and
                                the competition is huge. Thus, we ensure to bring good
                                recruitments for you once you get the certification. The corporates are mostly preferred
                                as they offer high growth and good promotions. But at the same time you need to work
                                hard and achieve targets to get that position.</p>
                            <!-- <h3 class="title-3" style="margin-top:0">Apparel Made Ups And Home Furnishing Sector Skill Council</h3> -->
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/ace.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Action Construction Equipment Ltd</h4>
                                    <a href="http://ace-cranes.com/" target="_blank">http://ace-cranes.com/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/escorts.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Escorts Agri Machinery</h4>
                                    <a href="http://www.escortsgroup.com/" target="blank">
                                        http://www.escortsgroup.com/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/BMW-logo.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Deutsche Motoren</h4>
                                    <a href="http://www.bmw-deutschemotoren.in/home/" target="blank">
                                        http://www.bmw-deutschemotoren.in/home/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/ifco.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>General Insurance Company Ltd </h4>
                                    <a href="https://www.iffcotokio.co.in/"
                                       target="blank">https://www.iffcotokio.co.in/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/jbm.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>JBM Industries Ltd</h4>
                                    <a href="http://www.jbm-group.com/" target="blank">http://www.jbm-group.com/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/ntpc.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4> National Thermal Power Corporation Ltd</h4>
                                    <a href="http://www.ntpc.co.in/" target="blank"> http://www.ntpc.co.in/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/nutec.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Nutech Group</h4>
                                    <a href="http://nutechgroup.org" target="blank">http://nutechgroup.org</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/fedral-logo.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Federal Mogul PowerTrain</h4>
                                    <a href="http://www.federalmogul.com/" target="_blank">
                                        http://www.federalmogul.com/</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="well">
                                <div class="col-md-3">
                                    <img src="{{asset('images/corporate-partners/vamani.png')}}" class="keyimg">
                                </div>
                                <div class="col-md-9">
                                    <h4>Vamani Overseas Private Limited</h4>
                                    <a href="http://www.vamanioverseas.com" target="_blank">
                                        http://www.vamanioverseas.com</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <!-- hotel search -->
                    <div class="bhoechie-tab-content">
                        <div class="tab-pane" id="Universities-Affiliation">
                            <h3 class="title-1 text-uppercase">Universities Affiliation: Education has the utmost
                                scope</h3>

                            <p>Universities are the education hub. They are preferred by ones who wish to learn
                                throughout their career because pedagogy in itself teaches a lot. There is no end to
                                this and you can explore all kinds of verticals as per your interest.
                                Hence, we care for your interest and arrange reputed university recruitments.</p>

                            <div class="col-md-6">
                                <img src="{{asset('images/nu1.jpg')}}" class="img-responsive thumbnail">
                            </div>
                            <div class="col-md-6">
                                <img src="{{asset('images/nu2.jpg')}}" class="img-responsive thumbnail">
                            </div>
                        </div>
                    </div>
                    <div class="bhoechie-tab-content">
                        <center>
                            <h1 class="glyphicon glyphicon-cutlery" style="font-size:12em;color:#55518a"></h1>
                            <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                            <h3 style="margin-top: 0;color:#55518a">Restaurant Diirectory</h3>
                        </center>
                    </div>
                    <div class="bhoechie-tab-content">
                        <center>
                            <h1 class="glyphicon glyphicon-credit-card" style="font-size:12em;color:#55518a"></h1>
                            <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                            <h3 style="margin-top: 0;color:#55518a">Credit Card</h3>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

@endsection

@push('scripts')
    <script src="{{asset('js/jquery.scrolling-tabs.js')}}"></script>
    <script type="text/javascript" defer>
        $(document).ready(function () {
            $('#lightgallery-about').lightGallery();
        });
    </script>

    <script src="{{asset('js/lightgallery.js')}}"></script>
    <script src="{{asset('js/lg-fullscreen.js')}}"></script>

@endpush