@extends('layouts.app')

@section('content')
    @include('main.partials.breadcrumb')

    <!--Banner Part Start Here-->
    <div class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 wow slideInLeft lt-section col-md-offset-1">
                    <h1><strong>Hire Skilled People,</strong> Best of Them</h1>
                    <div class="search-section">
                        <form action="{{route('employer.search')}}" method="get">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6"><input type="text" title="" name="jobrole" id="jobrole"
                                                                 placeholder="select job role"></div>
                                    <div class="col-md-6 location-box">
                                        <div class="col-md-9 pd-0">
                                            <input type="text" name="location" id="location" placeholder="Location">
                                        </div>
                                        <div class="col-md-3 pd-0">
                                            <div class="home-btns">
                                                <button type="submit" name="Searchindex" class="btn btn-danger">Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--Banner Part End Here--><!--Steps Part Start Here-->
    <section class="steps">
        <div id="exTab1" class="container wow fadeInDown">
            <ul class="nav nav-pills emp-tab">
                <li class="active">
                    <a href="#1a" data-toggle="tab">Employer</a>
                </li>
                <li class="hidden"><a href="#2a" data-toggle="tab">Jobseeker</a>
                </li>
            </ul>

            <div class="tab-content clearfix">
                <div class="tab-pane active" id="1a">

                    <h3>How we work for <span>Employer?</span></h3>
                    <ul class="step-tabs">
                        <li><img src="{{asset('images/emp-register.png')}}"></li>
                        <li><img src="{{asset('images/emp-postjoob.png')}}"></li>
                        <li><img src="{{asset('images/emp-screenresume.png')}}"></li>
                        <li><img src="{{asset('images/emp-purchaseplan.png')}}"></li>
                        <li><img src="{{asset('images/emp-interview.png')}}"></li>
                    </ul>
                </div>
                <div class="tab-pane hidden" id="2a">
                    <h3>How we work for <span>Jobseeker?</span></h3>
                    <ul class="step-tabs">
                        <li><img src="{{asset('images/jobseeker1.png')}}"></li>
                        <li><img src="{{asset('images/jobseeker2.png')}}"></li>
                        <li><img src="{{asset('images/jobseeker3.png')}}"></li>
                        <li><img src="{{asset('images/jobseeker4.png')}}"></li>

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--Steps Part End Here-->
    <!--Categories Part Start Here-->
    <section class="skill-categories">
        <div class="container wow fadeInDown">
            <h3><span>Skills</span> we Provide</h3>
            <div class="categories-container">
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/sales1.png')}}">
                        <img class="img-top" src="{{asset('images/sales2.png')}}">
                    </div>
                    <h4>Sales & Marketing</h4>
                    <p><a href="#">Explore</a></p>
                </div>
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/accounts1.png')}}">
                        <img class="img-top" src="{{asset('images/accounts2.png')}}">
                    </div>
                    <h4>Finance & Accounting</h4>
                    <p><a href="#">Explore</a></p>
                </div>
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/office1.png')}}">
                        <img class="img-top" src="{{asset('images/office2.png')}}">
                    </div>
                    <h4>Office Administration</h4>
                    <p><a href="#">Explore</a></p>
                </div>
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/sales1.png')}}">
                        <img class="img-top" src="{{asset('images/sales2.png')}}">
                    </div>
                    <h4>Sales & Marketing</h4>
                    <p><a href="#">Explore</a></p>
                </div>
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/it1.png')}}">
                        <img class="img-top" src="{{asset('images/it2.png')}}">
                    </div>
                    <h4>Sales & Marketing</h4>
                    <p><a href="#">Explore</a></p>
                </div>
                <div class="cat-box">
                    <div class="card">
                        <img src="{{asset('images/accounts1.png')}}">
                        <img class="img-top" src="{{asset('images/accounts2.png')}}">
                    </div>
                    <h4>Finance & Accounting</h4>
                    <p><a href="#">Explore</a></p>
                </div>
            </div>
            <div class="explore-btn">
                <button>Explore all Skills</button>
            </div>
        </div>
    </section>
    <!--Categories Part End Here-->
    <!--Certification Part End Here-->
    <section class="certification-container wow fadeInDown">
        <div class="container">
            <div class="certification-box">
                <h3><span>Certification</span> Type</h3>

                <div class='col-md-12 certification-slider'>
                    <div class="carousel slide media-carousel" id="media">
                        <div class="carousel-inner col-md-12">
                            <div class="item  active">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="nasscom" src="{{asset('images/nasscom.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="rasci"
                                                                           src="{{asset('images/rasci.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="hida"
                                                                           src="{{asset('images/hida.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="apparel" src="{{asset('images/apparel.jpg')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="nasscom" src="{{asset('images/nasscom.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="rasci"
                                                                           src="{{asset('images/rasci.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="hida"
                                                                           src="{{asset('images/hida.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="apparel" src="{{asset('images/apparel.jpg')}}"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="nasscom" src="{{asset('images/nasscom.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="rasci"
                                                                           src="{{asset('images/rasci.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="hida"
                                                                           src="{{asset('images/hida.jpg')}}"></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="thumbnail" href="#"><img alt="apparel" src="{{asset('images/apparel.jpg')}}"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a data-slide="prev" href="#media" class="left carousel-control left-controler1"><b><</b></a>
                        <a data-slide="next" href="#media" class="right carousel-control right-controler1"><b>></b></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Certification Part End Here-->
    <section class="greybg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-6 hot-hiring wow slideInLeft">
                        <h3>Hot Hiring <span>Compnies</span></h3>
                        <div class="logo-container">


                            <div class="hot-job "> <!--hot-border start-->
                                <ul>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/ace.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p> ACE Pvt Ltd </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 36 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/BMW-logo.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p> BMW </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 36 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/escorts.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p> Escorts </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 36 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/fedral-logo.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p> Federal-mogul </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 36 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/ifco.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p>Iffco Tokio </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 63 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <img src="{{asset('images/corporate-partners/jbm.png')}}"
                                                     style="height:80px; width:126px;">
                                                <p> JBM </p>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="pot-emp-follow">
                                                    <span class="follow-tip"></span>
                                                    <div id="follow_company_widget_1818_887"><a title="Click to follow"
                                                                                                class="followbtn"
                                                                                                onclick="checkLoginForFollowWidget(&quot;http://static.timesjobs.com/images_cand/&quot;,&quot;1818&quot;,1,1818);">Hot
                                                            Hiring</a></div>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <span> <i class="fa fa-suitcase" aria-hidden="true"></i> </span>
                                                        <a href="#"> 36 jobs</a>


                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>   <!--hot-border end-->
                        </div>
                    </div>
                    <div class="col-md-6 hot-hiring  wow slideInRight">
                        <h3>Recent<span> Job Posted</span></h3>
                        <div class="logo-container b">
                            <div class="b-con">
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Technical Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Software Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Data Entry Operator</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Technical Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Software Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Data Entry Operator</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Technical Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Database Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Software Engineer</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-jobs">
                                    <div class="company-logo">
                                        <img src="{{asset('images/recent-job1.jpg')}}">
                                    </div>
                                    <div class="job-data">
                                        <h4>Data Entry Operator</h4>
                                        <div class="company-details">
                                            <p>Datebase Management Company</p>
                                            <p>Location : Faridabad</p>
                                            <p>Skill : Data Entry</p>
                                            <p>Salary : 10,000-12,000/Month</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="mentor">
        <div class="container wow fadeInDown">
            <div class="row">
                <h3>Mentors at <span>Smart Skill Tech</span></h3>


                <div class="mentor-section">
                    <div class="col-md-4">
                        <div class="mentor-box">
                            <div class="mentor-image"><img src="{{asset('adm/uploads_mntr/profile_img/Gary.jpg')}}">
                            </div>
                            <h4>Gary Vaynerchuk</h4>
                            <p>Entrepreneurship, social media, angel investing, wine education</p>

                        </div>
                    </div>


                </div>

                <div class="mentor-section">
                    <div class="col-md-4">
                        <div class="mentor-box">
                            <div class="mentor-image"><img src="{{asset('adm/uploads_mntr/profile_img/manmohan.jpg')}}">
                            </div>
                            <h4>ManMohan Singh</h4>
                            <p>President, AOTS Alumni Society, Delhi </br> Secretary General, Federation of AOTS </p>

                        </div>
                    </div>


                </div>

                <div class="mentor-section">
                    <div class="col-md-4">
                        <div class="mentor-box">
                            <div class="mentor-image"><img src="{{asset('adm/uploads_mntr/profile_img/nuz.jpg')}}">
                            </div>
                            <h4>Nuzhat Reyazuddin Bhat</h4>
                            <p>Exposure in different profiles of HR, mainly worked as HR Consultant, HR Generalist & HR
                                Operations.</p>

                        </div>
                    </div>


                </div>
            <!--
<div class="mentor-section">
<div class="col-md-4">
<div class="mentor-box">
<div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
<h4></h4>
<p></p>

</div>
</div>

</div>
<div class="mentor-section">
<div class="col-md-4">
<div class="mentor-box">
<div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
<h4></h4>
<p></p>

</div>
</div>


</div>-->
            </div>
        </div>
    </section>
    <section class="trained-candidates">
        <div class="container">
            <div class="row">
                <div class="col-md-8 wow slideInLeft">
                    <div class="box">
                        <h3><span>Trained Candidates</span></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box1">
                                    <div class="mentor-image"><img src="{{asset('images/mentor.jpg')}}"></div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>

                            <div class="ask-ques-btn text-center">
                                <button>View All</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 wow slideInRight">
                    <div class="box">
                        <h3><span>walk in opportunities</span></h3>
                        <div class="row b1">
                            <div class="b-con">
                                <div class="col-md-12">
                                    <div class="box1">
                                        <div class="company-logo">
                                            <img src="{{asset('images/recent-job1.jpg')}}">
                                        </div>
                                        <div class="candidate-info">
                                            <h3><span>Vamani</span></h3>
                                            <p>Opportunities : Data Entry Operator</p>
                                            <p>Location : Faridabad</p>
                                            <p>Date : 16/03/2017</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box1">
                                        <div class="company-logo">
                                            <img src="{{asset('images/recent-job1.jpg')}}">
                                        </div>
                                        <div class="candidate-info">
                                            <h3><span>Vamani</span></h3>
                                            <p>Opportunities : Data Entry Operator</p>
                                            <p>Location : Faridabad</p>
                                            <p>Date : 16/03/2017</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box1">
                                        <div class="company-logo">
                                            <img src="{{asset('images/recent-job1.jpg')}}">
                                        </div>
                                        <div class="candidate-info">
                                            <h3><span>Vamani</span></h3>
                                            <p>Opportunities : Data Entry Operator</p>
                                            <p>Location : Faridabad</p>
                                            <p>Date : 16/03/2017</p>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="ask-ques-btn text-center">
                                <button>View All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trained-candidates bg-greydd">
        <div class="container">
            <div class="row">
                <div class="col-md-4 wow slideInLeft">
                    <div class="box">
                        <h3>Video <span>Resume</span></h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box1">
                                    <div class="video-resume-img">
                                        <iframe width="100%" height="77" src="https://www.youtube.com/embed/llVZCWdFNMs"
                                                frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="box1">
                                    <div class="video-resume-img">
                                        <iframe width="100%" height="77" src="https://www.youtube.com/embed/llVZCWdFNMs"
                                                frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="box1">
                                    <div class="video-resume-img">
                                        <iframe width="100%" height="77" src="https://www.youtube.com/embed/llVZCWdFNMs"
                                                frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="box1">
                                    <div class="video-resume-img">
                                        <iframe width="100%" height="77" src="https://www.youtube.com/embed/llVZCWdFNMs"
                                                frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="candidate-info">
                                        <h3><span>Candidate Name</span></h3>
                                        <p>Trained in : Data Entry Operator</p>
                                        <p>Certtification Type : Nasscom</p>
                                        <p>Marks : 79%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ask-ques-btn text-center">
                                <button>View All</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 wow slideInRight">
                    <div class="events-section">
                        <div class="box">
                            <h3>Events at <span>smart skill tech</span></h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box1 row">
                                        <div class="mentor-image col-md-2"><img src="{{asset('images/payu.jpg')}}">
                                        </div>
                                        <div class="candidate-info col-md-10">
                                            <div class="row">
                                                <div class="title-box">
                                                    <div class="col-md-9"><h3>UpStart Delhi - Funding ka Funda</h3>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="date-city">
                                                            <li class="date">18 Mar 2017</li>
                                                            <li class="city">Delhi</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit
                                                    dignissim... tellus, non auctor risus lobortis ac. Donec sit ame.
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                                                    hendrerit dignissim... tellus, non auctor risus lobortis ac. Donec
                                                    sit ame.</p>
                                                <div class="viewall-btn-small">
                                                    <button>View All</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box1 row">
                                        <div class="mentor-image col-md-2"><img src="{{asset('images/payu.jpg')}}">
                                        </div>
                                        <div class="candidate-info col-md-10">
                                            <div class="row">
                                                <div class="title-box">
                                                    <div class="col-md-9"><h3>UpStart Delhi - Funding ka Funda</h3>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="date-city">
                                                            <li class="date">18 Mar 2017</li>
                                                            <li class="city">Delhi</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit
                                                    dignissim... tellus, non auctor risus lobortis ac. Donec sit ame.
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                                                    hendrerit dignissim... tellus, non auctor risus lobortis ac. Donec
                                                    sit ame.</p>
                                                <div class="viewall-btn-small">
                                                    <button>View All</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box1 row">
                                        <div class="mentor-image col-md-2"><img src="{{asset('images/payu.jpg')}}">
                                        </div>
                                        <div class="candidate-info col-md-10">
                                            <div class="row">
                                                <div class="title-box">
                                                    <div class="col-md-9"><h3>UpStart Delhi - Funding ka Funda</h3>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="date-city">
                                                            <li class="date">18 Mar 2017</li>
                                                            <li class="city">Delhi</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit
                                                    dignissim... tellus, non auctor risus lobortis ac. Donec sit ame.
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                                                    hendrerit dignissim... tellus, non auctor risus lobortis ac. Donec
                                                    sit ame.</p>
                                                <div class="viewall-btn-small">
                                                    <button>View All</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="ask-ques-btn text-center">
                                    <button>View All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('main.home.partners')

    <section class="testimonials">
        <div class="container wow fadeInDown">
            <h3 class="text-center"><span>Testimonials</span></h3>
            <div id="tcb-testimonial-carousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="row">
                            <div class="col-xs-12">
                                <figure class="clearfix">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <figcaption class="caption">
                                            <div class="image">
                                                <img src="{{asset('uploads_emp/tstmnls_img/maharani-paints.jpg')}}">
                                            </div>
                                            <p class="text-brand">R.K. Bhatia</p>
                                            <p class="text-brand1">Maharani Innovative Private Limited</p>
                                            <p class="text-pd"><i>I will definitely use Professional employment's
                                                    recruiting services again and have already recommended them to
                                                    others. They understood my urgency in filling the position. They
                                                    also took the time to make sure the selected person fits with the
                                                    culture of the company. This
                                                    approach is what sets them apart from other recruiting services. I
                                                    was very impressed with their responsiveness and that...</i></p>
                                            <p class="quote"><i class="fa fa-quote-right"></i></p>
                                        </figcaption>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="row">
                            <div class="col-xs-12">
                                <figure class="clearfix">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <figcaption class="caption">
                                            <div class="image">
                                                <img src="{{asset('uploads_emp/tstmnls_img/cdass.jpg')}}">
                                            </div>
                                            <p class="text-brand">M.M. Singh</p>
                                            <p class="text-brand1">Cdass Group</p>
                                            <p class="text-pd"><i>The placement services are very impressive.They are
                                                    offering great services.</i></p>
                                            <p class="quote"><i class="fa fa-quote-right"></i></p>
                                        </figcaption>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="row">
                            <div class="col-xs-12">
                                <figure class="clearfix">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <figcaption class="caption">
                                            <div class="image">
                                                <img src="{{asset('uploads_emp/tstmnls_img/vamani.png')}}">
                                            </div>
                                            <p class="text-brand">N. R. Bhat</p>
                                            <p class="text-brand1">Vamani Overseas</p>
                                            <p class="text-pd"><i>I will definitely use Professional employment's
                                                    recruiting services again and have already recommended them to
                                                    others. They understood my urgency in filling the position. They
                                                    also took the time to make sure the selected person fits with the
                                                    culture of the company. This
                                                    approach is what sets them apart from other recruiting services. I
                                                    was very impressed with their responsiveness and that...</i></p>
                                            <p class="quote"><i class="fa fa-quote-right"></i></p>
                                        </figcaption>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="row">
                            <div class="col-xs-12">
                                <figure class="clearfix">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <figcaption class="caption">
                                            <div class="image">
                                                <img src="{{asset('uploads_emp/tstmnls_img/vamani.png')}}">
                                            </div>
                                            <p class="text-brand">Freedom Writer Essay </p>
                                            <p class="text-brand1">Freedom Writer Essay </p>
                                            <p class="text-pd"><i>research paper <a href="http://researchpaper.store">write
                                                        research paper</a> college research paper
                                                    [url=http://researchpaper.store]research paper[/url] </i></p>
                                            <p class="quote"><i class="fa fa-quote-right"></i></p>
                                        </figcaption>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#tcb-testimonial-carousel" data-slide="prev"> <span
                            class="glyphicon glyphicon-chevron-left"></span> </a>
                <a class="right carousel-control" href="#tcb-testimonial-carousel" data-slide="next"> <span
                            class="glyphicon glyphicon-chevron-right"></span> </a>
            </div>
            <br/>


        </div>
        <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-danger">Create Testimonials</a>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content modal-content1">
                    <div class="modal-body">
                        <div class="form_main">
                            <h4 class="heading"><strong>Write </strong> Testimonial <span></span></h4>
                            <div class="form">
                                <form action="" method="post" enctype="multipart/form-data" id="form1">
                                    <div class="row">
                                        <div class="col-md-6"><input type="text" required="" placeholder="Name" value=""
                                                                     name="name" class="txt"></div>
                                        <div class="col-md-6"><input type="text" required="" placeholder="Company Name"
                                                                     value="" name="companyname" class="txt"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><input type="text" required="" placeholder="Email"
                                                                     value="" name="email" class="txt"></div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-testimonials">
                                                <input type="file" name="upload" class="file">
                                                <div class="input-group col-xs-12">
                                                    <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-picture"></i></span>
                                                    <input type="text" class="form-control" disabled
                                                           placeholder="Upload Image">
                                                    <span class="input-group-btn">
        <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
      </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <textarea placeholder="Your Message" name="message" type="text"
                                              class="txt_3"></textarea>
                                    <div class="captchaContainer"></div>
                                    <input type="submit" value="submit" name="submit" class="txt2">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection
