@extends('layouts.app')

@section('content')
    @include('main.partials.breadcrumb')

    <section id="partner" class="about bg-grey">
        <div class="container">
            <div class="about-us-page-tab">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#home">Smart Skill Tech</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu1">C. DASS GROUP</a>
                    </li>
                </ul>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="home" class="container tab-pane active"><br>
                    <h3>Our Vision</h3>
                    <p>One stop Training and Placement Solution Company aligning with Prime Ministers dream of "Skilled
                        India","Kaushal Bharat Kushal Bharat"</p>

                    <h3>Our Mission</h3>
                    <p>Aligning ourselves with Skill India Mission to empower youth by online facilitating Skill
                        Development
                        Programs certified through recognized organizations and thereby providing placement assistance
                        as
                        per the job role selected.</p>
                    <p>Enhance, Support and Upgrade skills to international standards through significant industrial
                        involvement and to contribute significantly to the overall target of skilling & up-skilling 100
                        million people in India by 2025.</p>


                    <h3>Company Profile</h3>
                    <p>Smart Skill Tech started its skill development center in Wardha with an aim to provide best soft
                        skill training and placement to students.Youth today lack in KEY JOB Skills and therefore there
                        is a
                        need for soft skill development course and new technology to be integrated in the education
                        system.</p>
                    <p>Thus, Smart Skill Tech deals in Online Skill Development programs to bridge this gap. The group
                        has
                        dedicated itself to facilitate Skill Development Programs online and to join the Skill India
                        Mission
                        in various sectors to add value with our placement assistance services.</p>
                    <p>Smart Skill Tech is India's growing skill development platform where people develop their skills,
                        learn, improve and utilize their talents across the globe. We believe in the overall development
                        of
                        people so as to utilize full potential and offer best placements.</p>


                    <h3>Skill Development Center</h3>
                    <img src="{{asset('/rewamp/images/offices-all-india.jpg')}}">
                    {{--<div class="listing">
                        <ul>
                            <li>Faridabad Centre-1, Plot No.-137, sector-24, faridabad</li>
                            <li>Faridabad Centre-2, D-541, Sec-15A, Faridabad</li>
                            <li>Faridabad Centre-3, A32, Sector 11, near Shani Mandir, Faridabad</li>
                            <li>Gurugram Centre Village Harchandpur-District-Sohna, Gurugram</li>
                            <li>Nagpur Centre-Shri Ganesh Vighnaharta Apartment, 1st Floor, above Axis Bank, Mate
                                Square,
                                South Ambazari Road, Nagpur-44 022.
                            </li>
                            <li>Jaipur Craft Private Limited.</li>
                            <li>Bharatpur Center, Raja Khemkaran School, 3thok Jagheena, Bharatpur</li>
                            <li>Lohagarh Center, Shastri Nagar Colony, Bharatpur</li>
                            <li>Wardha Centre-Plot No.H-1, MIDC Area, Sevagram-Wardha Road, Wardha-442 006.</li>
                        </ul>
                    </div>--}}

                </div>


                <div id="menu1" class="container tab-pane fade">
                    <h3>Our Vision</h3>
                    <p>To be Recognized as the most Diversifying and People Oriented Group, serving the Society with
                        Quality
                        services, Winning Customer Satisfaction.</p>

                    <h3>Our Mission</h3>

                    <p>Our Mission is to provide the Best Quality and Innovative Products by adopting latest
                        Technologies
                        and Processes.</p>
                    <p>We shall achieve this through the Responsive, Cohesive, Talented and Dedicated team, ensuring the
                        success of our Group Companies by constantly and consistently satisfying our Customers,
                        Shareholders
                        and Employees.</p>


                    <h3>Our Branches</h3>
                    <h6>CDass Group has regional offices in the following cities in India.</h6>
                    <div class="listing">
                        <ul>
                            <li>New Delhi</li>
                            <li>Faridabad</li>
                            <li>Hosur</li>
                            <li>Prithla</li>
                            <li>Ludhiana</li>
                            <li>Pune</li>
                            <li>Noida</li>
                            <li>Bangalore</li>
                            <li>Aurangabad</li>
                        </ul>

                    </div>

                    <div class="row timeline">
                        <div class="col-md-12 col-xs-12">
                            <ul class="timeline">
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/c-dass1.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">Started Trading Thinners and
                                            Solvents</p>
                                        <p class="Date">1976</p>
                                        <p>The group started with a Trading Business of Thinners and Solvents.</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/c-dass.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">M/s C.Dass Chemicals Pvt.
                                            Ltd.</p>
                                        <p class="Date">1980</p>
                                        <p>In a short span of 2 Years in 1980 the trading business was expanded to a
                                            Chemical Manufacturing Unit under the name and style of M/s C Dass Chemicals
                                            Pvt. Ltd. and over the years has earned the reorganization as a manufacturer
                                            of
                                            quality paints and Allied products conforming to International Quality
                                            Standards.</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/maharani-paints.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">M/s. Maharani Paints Pvt.
                                            Ltd.</p>
                                        <p class="Date">1986</p>
                                        <p>MAHARANI PAINTS PVT. LTD. It was Year 1986 M/s Maharani Paints Pvt. Ltd. was
                                            incorporated to start and take over the market business in the vertical of
                                            Automotive/Industrial Paints and Powder Coating Manufacturing. Ever since
                                            its
                                            incorporation, the Company has been providing its customers the best quality
                                            products and services with a reasonable pricing policy.</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/vamani.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">M/s Vamani Overseas Pvt. Ltd.</p>
                                        <p class="Date">1999</p>
                                        <p>In 1999 the group diversified their business into the vertical of Hard Wood
                                            Furniture and Manufacturing of Garments under the name and style of M/s
                                            Vamani
                                            Overseas Pvt. Ltd. primarily exporting to multiple countries in the World
                                            including Australia and USA (i.e.100% EOU).</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/vamani.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">Vamani Started Garments
                                            Manufacturing</p>
                                        <p class="Date">2006</p>
                                        <p>Vamani Overseas - started 100% EOU Manufacturing Garments &amp; exporting to
                                            Europe</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/wardha.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">Wardha IT Park Pvt. Ltd.</p>
                                        <p class="Date">2011</p>
                                        <p>Initiated Project WARDHA IT PARK With the experience of over 35 years in
                                            multiple
                                            industries and countries in Year 2012 a team of Professionals further
                                            initiated
                                            to diversify the business into the Vertical of Real Estate. Itss is only a
                                            first
                                            step of the company with its most prestigious project in Maharashtra known
                                            as
                                            WARDHA IT PARK. The company plans to expand its Real Estate business by
                                            investing more into the other Metro markets, such as Delhi NCR.</p></div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/maharani-innovatives.png')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">Maharani Innovative Paints
                                            Pvt.Ltd.</p>
                                        <p class="Date">2014</p>
                                        <p>Subsidiary Company of Maharani Paints Private Limited has entered into a
                                            technical collaboration agreement with internationally acclaimed automotive
                                            paints manufacturing company M/s. Dai Nippon Toryo Company Ltd. (DNT)
                                            Japan</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="col-md-2 text-center float-left"><img class="responsive imgs"
                                                                                      src="{{asset('rewamp/images/about-us/smart-skill.jpg')}}">
                                    </div>
                                    <div class="col-md-9 float-right"><p class="title">Smart Skill Tech Pvt Ltd.</p>
                                        <p class="Date">2016</p>
                                        <p>Started its operation as a unit under Wardha IT Park Pvt Ltd.with the aim of
                                            providing training and placement of students.</p></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{--<section id="page-header">
        <img src="{{asset('images/inside-banner/about.jpg')}}" alt="skill-development-centers" style="width:100%">
    </section>

    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <ul class="nav nav-tabs nav-justified" id="about-tab">
                        <li class="active"><a href="#1" data-toggle="tab">SMART SKILL TECH</a></li>
                        <li><a href="#2" data-toggle="tab">C. DASS GROUP</a></li>
                    </ul>
                    <div class="tab-content" id="about-content">
                        <div id="1" class="tab-pane fade in active">
                            <h3 class="title-3 text-uppercase">Our vision</h3>
                            <p>One stop Training and Placement Solution Company aligning with Prime Ministers dream of
                                "Skilled India",<span style="color:blue;">"Kaushal Bharat Kushal Bharat".</span></p>
                            <h3 class="title-3 text-uppercase">Our mission</h3>
                            <p>Aligning ourselves with Skill India Mission to empower youth by online facilitating Skill
                                Development Programs certified through recognized organizations and thereby providing
                                placement assistance as per the job role selected.</p>

                            <p>Enhance, Support and Upgrade skills to international standards through significant
                                industrial involvement and to contribute significantly to the overall target of skilling
                                &amp; up-skilling 100 million people in India by 2025.</p>

                            <h3 class="title-3 text-uppercase">Company Profile</h3>
                            <p>Smart Skill Tech started its skill development center in Wardha with an aim to provide
                                best soft skill training and placement to students.Youth today lack in KEY JOB Skills
                                and therefore there is a need for soft skill development course and new technology to be
                                integrated in the education system.</p>
                            <p>Thus, Smart Skill Tech deals in Online Skill Development programs to bridge this gap.
                                The group has dedicated itself to facilitate Skill Development Programs online and to
                                join the Skill India Mission in various sectors to add value with our placement
                                assistance services. </p>
                            <p>Smart Skill Tech is India's growing skill development platform where people develop their
                                skills, learn, improve and utilize their talents across the globe.
                                We believe in the overall development of people so as to utilize full potential and
                                offer best placements.
                            </p>


                            <div class="row direction-map text-center">

                                <div class="col-md-5 col-sm-6 col-xs-12">

                                    <ul class="nav nav-tabs tabs-left sideways">
                                        <li class="active"><i class="fa fa-star" aria-hidden="true" style="color:#ffd800;margin-right:10px;"></i> <a href="{{asset('contact')}}" data-toggle="tab">Faridabad Centre-1, Plot No.-137, sector-24, faridabad</a></li>
                                        <li><i class="fa fa-star" aria-hidden="true" style="color:#ffd800;margin-right:10px;"></i>  <a href="{{asset('contact')}}" data-toggle="tab">Faridabad Centre-2, Plot No.-349, Sector-24, faridabad</a></li>
                                        <li><i class="fa fa-star" aria-hidden="true" style="color:#ffd800;margin-right:10px;"></i><a href="{{asset('contact')}}" data-toggle="tab">Gurugram Centre Village Harchandpur-District-Sohna, Gurugram </a></li>
                                        <li><i class="fa fa-star" aria-hidden="true" style="color:#ffd800;margin-right:10px;"></i> <a href="{{asset('skill-development-centre-nagpur')}}" data-toggle="">Nagpur Centre-Shri Ganesh Vighnaharta Apartment, 1st Floor, above Axis Bank, Mate Square, South Ambazari Road, Nagpur-44 022.</a></li>
                                        <li><i class="fa fa-star" aria-hidden="true" style="color:#ffd800;margin-right:10px;"></i> <a href="{{asset('skill-development-centre-wardha')}}" data-toggle="">Wardha Centre-Plot No.H-1, MIDC Area, Sevagram-Wardha Road, Wardha-442 006.</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="tab-content">
                                        <div class="tab-pane" id="add-1"> </div>
                                        <div class="tab-pane" id="add-2"> </div>
                                         <div class="tab-pane" id="add-3"> </div>
                                    </div>
                                </div>

                                <img src="{{asset('images/map.jpg')}}" alt="alt" />
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <h1 class="title-3 text-uppercase text-left">Skill Development Centres </h1>
                                    <ul class="sideways">

                                        <li class="active"><i class="fa fa-star"></i> <span>Faridabad Centre-1, Plot No.-137, sector-24, faridabad</span>
                                        </li>
                                        <li><i class="fa fa-star"></i> <span>Faridabad Centre-2, D-541, Sec-15A, Faridabad</span>
                                        </li>
                                        <li><i class="fa fa-star"></i><span>Gurugram Centre Village Harchandpur-District-Sohna, Gurugram </span>
                                        </li>
                                        <li><i class="fa fa-star"></i> <span>Nagpur Centre-Shri Ganesh Vighnaharta Apartment, 1st Floor, above Axis Bank, Mate Square, South Ambazari Road, Nagpur-44 022.</span>
                                        </li>
                                        <li><i class="fa fa-star"></i> <span>Wardha Centre-Plot No.H-1, MIDC Area, Sevagram-Wardha Road, Wardha-442 006.</span>
                                        </li>
                                        <li><i class="fa fa-star"></i> <span>Jaipur Craft Private Limited.</span></li>
                                    </ul>
                                </div>
                                <div id="map" class="col-md-5" style="height:350px;"></div>
                            </div>
                        </div>
                        <div id="2" class="tab-pane fade">
                            <h3 class="title-3 text-uppercase">Our vision</h3>
                            <p>To be Recognized as the most Diversifying and People Oriented Group, serving the Society
                                with Quality services, Winning Customer Satisfaction.</p>
                            <h3 class="title-3 text-uppercase">Our mission</h3>
                            <p>Our Mission is to provide the Best Quality and Innovative Products by adopting latest
                                Technologies and Processes. </p>
                            <p>We shall achieve this through the Responsive, Cohesive, Talented and Dedicated team,
                                ensuring the success of our Group Companies by constantly and consistently satisfying
                                our Customers, Shareholders and Employees.</p>
                            <h3 class="title-3 text-uppercase">Our Branches</h3>
                            <p>CDass Group has regional offices in the following cities in India.</p>
                            <ul class="list-1">
                                <li><b>New Delhi</b></li>
                                <li><b> Faridabad</b></li>
                                <li><b>Hosur</b></li>
                                <li><b>Prithla</b></li>
                                <li><b>Ludhiana</b></li>
                                <li><b>Pune</b></li>
                                <li><b>Noida</b></li>
                                <li><b>Bangalore</b></li>
                                <li><b>Aurangabad</b></li>
                            </ul>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="timeline">
                                        <div class="container1 left">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/c-dass1.png')}}"> 1976 </h2>
                                                <h3> Started Trading Thinners and Solvents</h3>
                                                <p>The group started with a Trading Business of Thinners and
                                                    Solvents.</p>
                                            </div>
                                        </div>
                                        <div class="container1 right">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/c-dass.png')}}"> 1980 </h2>
                                                <h3> M/s C.Dass Chemicals Pvt. Ltd.</h3>
                                                <p>In a short span of 2 Years in 1980 the trading business was expanded
                                                    to a Chemical Manufacturing Unit under the name and style of M/s C
                                                    Dass Chemicals Pvt. Ltd. and over the years has earned the
                                                    reorganization as a manufacturer of quality paints and Allied
                                                    products conforming to International Quality Standards.</p>
                                            </div>
                                        </div>
                                        <div class="container1 left">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/maharani-paints.png')}}"> 1986 </h2>
                                                <h3> M/s. Maharani Paints Pvt. Ltd.</h3>
                                                <p>MAHARANI PAINTS PVT. LTD. It was Year 1986 M/s Maharani Paints Pvt.
                                                    Ltd. was incorporated to start and take over the market business in
                                                    the vertical of Automotive/Industrial Paints and Powder Coating
                                                    Manufacturing. Ever since its incorporation, the Company has been
                                                    providing its customers the best quality products and services with
                                                    a reasonable pricing policy.</p>
                                            </div>
                                        </div>
                                        <div class="container1 right">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/vamani.png')}}"> 1999 </h2>
                                                <h3> M/s Vamani Overseas Pvt. Ltd.</h3>
                                                <p>n 1999 the group diversified their business into the vertical of Hard
                                                    Wood Furniture and Manufacturing of Garments under the name and
                                                    style of M/s Vamani Overseas Pvt. Ltd. primarily exporting to
                                                    multiple countries in the World including Australia and USA
                                                    (i.e.100% EOU). </p>
                                            </div>
                                        </div>
                                        <div class="container1 left">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/vamani.png')}}"> 2006</h2>
                                                <h3>Vamani Started Garments Manufacturing</h3>
                                                <p>Vamani Overseas - started 100% EOU Manufacturing Garments & exporting
                                                    to Europe </p>
                                            </div>
                                        </div>
                                        <div class="container1 right">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/wardha.png')}}">2011 </h2>
                                                <h3> Wardha IT Park Pvt. Ltd.</h3>
                                                <p>Initiated Project WARDHA IT PARK With the experience of over 35 years
                                                    in multiple industries and countries in Year 2012 a team of
                                                    Professionals further initiated to diversify the business into the
                                                    Vertical of Real Estate. Itss is only a first step of the company
                                                    with its most prestigious project in Maharashtra known as WARDHA IT
                                                    PARK. The company plans to expand its Real Estate business by
                                                    investing more into the other Metro markets, such as Delhi NCR.</p>
                                            </div>
                                        </div>
                                        <div class="container1 left">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/maharani-innovatives.png')}}"> 2014
                                                </h2>
                                                <h3>Maharani Innovative Paints Pvt.Ltd.</h3>
                                                <p>subsidiary Company of Maharani Paints Private Limited has entered
                                                    into a technical collaboration agreement with internationally
                                                    acclaimed automotive paints manufacturing company M/s. Dai Nippon
                                                    Toryo Company Ltd. (DNT) Japan </p>
                                            </div>
                                        </div>
                                        <div class="container1 right">
                                            <div class="content">
                                                <h2><img src="{{asset('images/logos/smart-skill.png')}}">2015 </h2>
                                                <h3>Smart Skill Tech Pvt Ltd.</h3>
                                                <p>started its operation as a unit under Wardha IT Park Pvt Ltd.with the
                                                    aim of providing training and placement of students.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- </div>-->
                </div>
            </div>
        </div>
    </section>--}}

@endsection

@push('scripts')
    <script>
        function initMap() {
            var locations = [

                ['Faridabad Centre-1, Plot No.-137, sector-24, faridabad', 28.3557609, 77.3044367, 1],
                ['Faridabad Centre-2, D-541, Sec-15A, Faridabad', 28.3954232, 77.3146217, 2],
                ['Jaipur Craft Private Limited.', 26.8851417, 75.6504706, 3],
                ['Nagpur Centre-Shri Ganesh Vighnaharta Apartment, 1st Floor, above Axis Bank, Mate Square, South Ambazari Road, Nagpur-44 022.', 21.1234685, 79.0555566, 4],
                ['Gurugram Centre Village Harchandpur-District-Sohna, Gurugram ', 28.2660792, 77.134704, 5],
                ['Wardha Centre-Plot No.H-1, MIDC Area, Sevagram-Wardha Road, Wardha-442 006.', 20.7409188, 78.6275849, 6]

            ];

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: new google.maps.LatLng(22.7239117, 75.7237622),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }

    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFaIK3Wh9JbO_JkQXlYvW7luJX0B0dO7s&libraries=places&callback=initMap">
    </script>
@endpush