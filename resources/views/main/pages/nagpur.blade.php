@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
@endpush
@section('content')
<section class="about ">
    <div class="container">
        <div class="upper-content">
            <div class="title-4 text-center">
                <h1><span>Skill Development Centre In Nagpur</span></h1>
            </div>
            <h3 class="text-center">Centre Details</h3>
            <p class="text-center">We are one of India’s prestigious organizations that articulates the interest of Industries, Entrepreneurs and academic Institutions for up skilling of youth.We play an important role in creating and sustaining an environment in which employment
                and entrepreneurship flourish.Through the facilitation of partnerships and with an extensive network of influence corporate, individual members and entrepreneurship opportunities. Smart Skill Tech Pvt. Ltd., Nagpur signed MOU with Rashtrasant
                Tukdoji Maharaj Nagpur University, Nagpur for providing the skill development program to the UG/PG students of the University. </p>
        </div>
        <div class="inner-pages">
            <div class="row">

                <div class="col-md-12 sep-div">
                    <h3 class="title-3 titletxt" style="margin-top:0">Training Programs:- </h3>

                    <div class="col-md-3 col-xs-12 fl-left">
                        <img alt="Training-Program-In-Nagpur" class="img-responsive" src="{{asset('images/nagpur-training.jpg')}}">
                    </div>
                    <div class="col-md-9 col-xs-12 fl-right">

                        <h4>Training Program In Nagpur</h4>
                        <p> SEEP-IT(Smart Employment Enhancement Program in Information Technology)</p>

                        <p>SEEP-ITeS (Smart Employment Enhancement Program in Information Technology enable services)</p>

                        <p>Soft Skill Enhancement Training Program </p>

                        <p>Campus Recruitment Training Program </p>

                        <p>Training Program in Information Technology</p>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 sep-div">

                    <div class="col-md-3 col-xs-12 fl-right">
                        <img alt="Campus-Recruitment-Training-Program" class="img-responsive" src="{{asset('images/nagpur-training-2.jpg')}}">
                    </div>
                    <div class="col-md-9 col-xs-12 fl-left">
                        <h4>Campus Recruitment Training Program</h4>
                        <p>This Skill Development Program is offering occupational studies for vocational work that will be relevant regardless of what changes in the economy over the years. The students here are taught to plan, coordinate, budget and supervise
                            construction projects from start to finish.</p>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 sep-div">
                    <h3 class="title-3 titletxt" style="margin-top:0">Address</h3>

                    <div class="col-md-5 col-xs-12 fl-left">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5263.306633553492!2d79.05469764040946!3d21.123733881356895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd4c078f7593c4d%3A0x376507c68d63c106!2sMate+Chowk%2C+Pratap+Nagar%2C+Nagpur%2C+Maharashtra!5e0!3m2!1sen!2sin!4v1525082133802" style="width:100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    <div class="col-md-7 col-xs-12 fl-right">

                        <p>Smart Skill Technologies Pvt Ltd. 1st Floor, Shree Ganesh Vighnaharta Apartment, Above Axis Bank, Near Mate square, Shardhanand Peth, Nagpur- 440022. </p>
                        <p> Contact: +91 7030920275</p>
                        <p>Landline-0712-2239310</p>
                        <div class="row">
                            <div class="col-md-10 col-xs-12">
                                <p class=""><i class="fa fa-globe"></i> <a href="#" target="_blank"> info@smartskilltech.com</a> </p>
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Campus-Drive Nagpur/Nagpur-1-1.jpg')}}" class="img-responsive">
                                            <div class="caption">
                                                <p>GD Round for BPO </p>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Campus-Drive Nagpur/Nagpur-11.jpg')}}" class="img-responsive">
                                            <div class="caption">
                                                <p>Technical Interview </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Campus-Drive Nagpur/Nagpur-3.png')}}" class="img-responsive">
                                            <div class="caption">
                                                <p>Written test in</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h4>Key Resource Person</h4>

                                        <p>Mr. Sandeep Pillewar- Manager</p>

                                        <p>Ms. Labhshree Bhimte- Centre Coordinator</p>

                                        <p>Ms. Nuzhat Reyazuddin Bhat- Trainer cum HR</p>

                                        <p>Ms. Sneha Bais- Counselor</p>

                                        <p>Ms. Priyanka Pandey-Tele-caller</p>

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