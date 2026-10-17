@extends('layouts.app')
@section('content')
    @include('main.partials.breadcrumb')
    <section class="about ">
        <div class="container">
            <div class="upper-content">
                <div class="title-4 text-center"><h1><span>Skill Development Centre In Faridabad</span></h1></div>
                <h3 class="text-center">Centre Details</h3>
                <p class="text-center">Smart Skill Tech has two working Skill Development centres in Faridabad in which
                    we provide Skill Development Programs under PMKVY in various skill sectors. Skill Training in
                    Faridabad has been set up to facilitate the development and upgrading the skills of student and the
                    growing workforce through our Skill Development Programs.</p>
            </div>
            <div class="inner-pages">
                <div class="row">

                    <div class="col-md-12 sep-div">
                        <h3 class="title-3 titletxt" style="margin-top:0">Skill Development Centre :-</h3>

                        <div class="col-md-4 col-xs-12 fl-left">
                            <img alt="Sewing-Machine-Training" class="img-responsive"
                                 src="{{asset('images/Sewing-Machine-Training.jpg')}}">
                        </div>
                        <div class="col-md-8 col-xs-12 fl-right">

                            <h4>Plot No. 137, Sec.24</h4>
                            <p>Here skill development experts train the aspirants in the vocational course of Sewing
                                Machine Training (Tailoring), it one of the job oriented development course. The best
                                thing about this course is that it suits female candidates very well.</p>
                            <p>This Skill Development Program trains students in areas like- cutting, sewing, trade
                                theory and practical. 8th passed students are eligible to pursue this Skill Development
                                Course. At the end of Skill Development Training successful candidates are awarded with
                                PMKVY certifications related to this trade. The main aim of this Skill Development
                                Program is to provide Skill Development Training to candidates and turn them into
                                skilled professionals who can work with garments (measure, cut, stitch, operated
                                relevant machinery) and make different types of dresses.</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 sep-div">

                        <div class="col-md-4 col-xs-12 fl-right">
                            <img alt="Painting-and-Decorating-Courseware" class="img-responsive"
                                 src="{{asset('images/Painting-and-Decorating-Courseware.jpg')}}">
                        </div>
                        <div class="col-md-8 col-xs-12 fl-left">
                            <h4>D-541, Sec.15A</h4>
                            <p>Here skill development trainers strive to ensure that the young aspirants are trained on
                                corporate readiness. It helps in bridging the gap between academia and industry
                                demands. </p>
                            <h4>Painting and Decorating Courseware </h4>
                            <ul class="ul-listing">
                                <li>How to apply paint or other substance for protective, decorative or technical
                                    purposes, including colour matching.
                                </li>
                                <li>How to apply texture coatings.</li>
                                <li>How to apply wall papers.</li>
                                <li>How to prepare surfaces for application of paint or other protective, decorative or
                                    technical materials.
                                </li>
                                <li>How to apply cement render to no more than 8mm thickness.</li>
                                <ul>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
    </section>

@endsection