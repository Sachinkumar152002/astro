@extends('layouts.app')
@section('content')
    @include('main.partials.breadcrumb')
    <section class="about ">
        <div class="container">
            <div class="upper-content">
                <div class="title-4 text-center">
                    <h1><span>Skill Development Centre In Wardha</span></h1>
                </div>
                <h3 class="text-center">Centre Details</h3>
                <p class="text-center">Smart Skill Tech started its operation under Wardha IT Park Pvt. Ltd. With an aim
                    to provide Skill Development Programs and placement to students.We have observed lack of skills in
                    youth and therefore there is a huge need for technology to
                    be integrated in the education system.To bridge this gap, Smart Skill Tech deals in Skill
                    Development Programs</p>
            </div>
            <div class="inner-pages">
                <div class="row">

                    <div class="col-md-12 sep-div">
                        <h3 class="title-3 titletxt" style="margin-top:0">Skill Development Program's Offered </h3>

                        <div class="col-md-3 col-xs-12 fl-left">
                            <img class="img-responsive" src="{{asset('images/employment-enhancemente-programme.jpg')}}">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-right">

                            <h4>Employment Enhancement Programme</h4>
                            <p>Joblessness is universally viewed as a noteworthy social and monetary issue. The course
                                aims to develop professionals who are committed to excellence in their personal and
                                professional endeavours.</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 sep-div">

                        <div class="col-md-3 col-xs-12 fl-right">
                            <img class="img-responsive"
                                 src="{{asset('images/building-construction-management-&-supervision.jpg')}}">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-left">
                            <h4>Building Construction Management & Supervision</h4>
                            <p>This Skill Development Program is offering occupational studies for vocational work that
                                will be relevant regardless of what changes in the economy over the years. The students
                                here are taught to plan, coordinate, budget and supervise
                                construction projects from start to finish.</p>
                        </div>

                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 sep-div">
                        <h3 class="title-3 titletxt" style="margin-top:0">Address</h3>

                        <div class="col-md-5 col-xs-12 fl-left">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3731.336863567203!2d78.64512301467688!3d20.737136186157027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd47f97a942aa65%3A0xf9a6eb14db3e24e4!2sWARDHA+IT+PARK!5e0!3m2!1sen!2sin!4v1525082046685"
                                    style="width:100%" height="300" frameborder="0" style="border:0"
                                    allowfullscreen></iframe>
                        </div>
                        <div class="col-md-7 col-xs-12 fl-right">

                            <p>
                                Wardha Center: Plot No. H-1, MIDC Wardha, Wardha Sewagram Road, Wardha- 442006.
                            <p> Contact: +91 7030920274</p>

                            <div class="row">
                                <div class="col-md-10 col-xs-12">
                                    <p class=""><i class="fa fa-globe"></i> <a href="#" target="_blank">
                                            info@smartskilltech.com</a></p>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="thumbnail">
                                                <img src="{{asset('images/Wardha-Training/Wardha-Training-3.jpg')}}"
                                                     class="img-responsive">
                                                <div class="caption">
                                                    <p>Wardha Training</p>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="thumbnail">
                                                <img src="{{asset('images/Wardha-Training/Wardha-Training-4.jpg')}}"
                                                     class="img-responsive">
                                                <div class="caption">
                                                    <p>Wardha Training</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="thumbnail">
                                                <img src="{{asset('images/Wardha-Training/Wardha-Training-5.jpg')}}"
                                                     class="img-responsive">
                                                <div class="caption">
                                                    <p>Wardha Training</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h4>Key Resource Person</h4>
                                            <p>Mr. Sandeep Pillewar (Manager)</p>
                                            <p>Ms. Afreen Shaikh (Centre Coordinator)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection