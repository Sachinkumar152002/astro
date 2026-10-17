@extends('layouts.app')

@push('styles')
    <link href="{{asset('css/jquery.scrolling-tabs1.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/lightgallery.css')}}" rel="stylesheet" type="text/css">
    <style>
        .gallery-section .tab-content img {
            border-radius: 4px;
            width: 250px;
            padding: 2px;
            border: 1px solid #d0d0d0;
        }

        .gallery-section li.col-xs-6.col-sm-4.col-md-3 {
            display: inline-block;
            margin: 15px 0px;
        }

        .gallery-section .list-unstyled {
            text-align: center;
        }
    </style>
@endpush

@section('content')
    @include('main.partials.breadcrumb')
    <section id="partner" class="about bg-grey gallery-section">
        <div class="container">
            <div class="about-us-page-tab">
                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#tab2" data-toggle="pill"> Training Under
                            President House</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab3" data-toggle="tab"> Placement Drive</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab4" data-toggle="tab"> Training at CRPF </a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab5" data-toggle="tab"> Training at Raj Bhavan </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#tab10" data-toggle="tab">Training At Faridabad</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab2">
                    <div class="demo-gallery">
                        <ul id="lightgallery2" class="list-unstyled">
                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-2.jpg 375, images/President-House/President-House-2.jpg 480, images/President-House/President-House-2.jpg 800"
                                data-src="images/President-House/President-House-2.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-3.jpg 375, images/President-House/President-House-3.jpg 480, images/President-House/President-House-3.jpg 800"
                                data-src="images/President-House/President-House-3.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-4.jpg 375, images/President-House/President-House-4.jpg 480, images/President-House/President-House-4.jpg 800"
                                data-src="images/President-House/President-House-4.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-5.jpg 375, images/President-House/President-House-5.jpg 480, images/President-House/President-House-5.jpg 800"
                                data-src="images/President-House/President-House-5.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-6.jpg 375, images/President-House/President-House-6.jpg 480, images/President-House/President-House-6.jpg 800"
                                data-src="images/President-House/President-House-6.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-6.jpg">
                                </a>
                            </li>


                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-8.jpg 375, images/President-House/President-House-8.jpg 480, images/President-House/President-House-8.jpg 800"
                                data-src="images/President-House/President-House-8.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-8.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-9.jpg 375, images/President-House/President-House-9.jpg 480, images/President-House/President-House-9.jpg 800"
                                data-src="images/President-House/President-House-9.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-9.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-10.jpg 375,images/President-House/President-House-10.jpg 480, images/President-House/President-House-10.jpg 800"
                                data-src="images/President-House/President-House-10.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-10.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-11.jpg 375, images/President-House/President-House-11.jpg 480,images/President-House/President-House-11.jpg 800"
                                data-src="images/President-House/President-House-11.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-11.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-12.jpg 375, images/President-House/President-House-12.jpg 480, images/President-House/President-House-12.jpg 800"
                                data-src="images/President-House/President-House-12.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-12.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-13.jpg 375, images/President-House/President-House-13.jpg 480, images/President-House/President-House-13.jpg 800"
                                data-src="images/President-House/President-House-13.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-13.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-14.jpg 375, images/President-House/President-House-14.jpg 480, images/President-House/President-House-14.jpg 800"
                                data-src="images/President-House/President-House-14.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-14.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-15.jpg 375,images/President-House/President-House-15.jpg 480, images/President-House/President-House-15.jpg 800"
                                data-src="images/President-House/President-House-15.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-15.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/President-House/President-House-16.jpg 375, images/President-House/President-House-16.jpg 480, images/President-House/President-House-16.jpg 800"
                                data-src="images/President-House/President-House-16.jpg"
                                data-sub-html="<h4>Training Under President House</h4><p></p>">
                                <a href="">
                                    <img src="images/President-House/President-House-16.jpg">
                                </a>
                            </li>


                        </ul>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab3">
                    <div class="demo-gallery">
                        <ul id="lightgallery3" class="list-unstyled">
                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Placement-Drive/place-1.jpg 375, images/Placement-Drive/place-1.jpg 480, images/Placement-Drive/place-1.jpg 800"
                                data-src="images/Placement-Drive/place-1.jpg"
                                data-sub-html="<h4>Placement Drive</h4><p></p>">
                                <a href="">
                                    <img src="images/Placement-Drive/place-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Placement-Drive/place-2.jpg 375, images/Placement-Drive/place-2.jpg 480, images/Placement-Drive/place-2.jpg 800"
                                data-src="images/Placement-Drive/place-2.jpg"
                                data-sub-html="<h4>Placement Drive</h4><p></p>">
                                <a href="">
                                    <img src="images/Placement-Drive/place-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Placement-Drive/place-3.jpg 375, images/Placement-Drive/place-3.jpg 480, images/Placement-Drive/place-3.jpg 800"
                                data-src="images/Placement-Drive/place-3.jpg"
                                data-sub-html="<h4>Placement Drive</h4><p></p>">
                                <a href="">
                                    <img src="images/Placement-Drive/place-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Placement-Drive/place-4.jpg 375, images/Placement-Drive/place-4.jpg 480, images/Placement-Drive/place-4.jpg 800"
                                data-src="images/Placement-Drive/place-4.jpg"
                                data-sub-html="<h4>Placement Drive</h4><p></p>">
                                <a href="">
                                    <img src="images/Placement-Drive/place-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Placement-Drive/place-5.jpg 375, images/Placement-Drive/place-5.jpg 480, images/Placement-Drive/place-5.jpg 800"
                                data-src="images/Placement-Drive/place-5.jpg"
                                data-sub-html="<h4>Placement Drive</h4><p></p>">
                                <a href="">
                                    <img src="images/Placement-Drive/place-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-1.jpg 375, images/Wardha-Training/Wardha-Training-1.jpg 480,images/Wardha-Training/Wardha-Training-1.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-1.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-2.jpg 375, images/Wardha-Training/Wardha-Training-2.jpg 480,images/Wardha-Training/Wardha-Training-2.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-2.png"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-3.jpg 375, images/Wardha-Training/Wardha-Training-3.jpg 480,images/Wardha-Training/Wardha-Training-3.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-3.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-4.jpg 375, images/Wardha-Training/Wardha-Training-4.jpg 480,images/Wardha-Training/Wardha-Training-4.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-4.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-5.jpg 375, images/Wardha-Training/Wardha-Training-5.jpg 480,images/Wardha-Training/Wardha-Training-5.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-5.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-training-6.jpg 375, images/Wardha-Training/Wardha-training-6.jpg 480,images/Wardha-Training/Wardha-training-6.jpg 800"
                                data-src="images/Wardha-Training/Wardha-training-6.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-training-6.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-7.jpg 375, images/Wardha-Training/Wardha-Training-7.jpg 480,images/Wardha-Training/Wardha-Training-7.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-7.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-7.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-8.jpg 375, images/Wardha-Training/Wardha-Training-8.jpg 480,images/Wardha-Training/Wardha-Training-8.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-8.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-8.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-9.jpg 375, images/Wardha-Training/Wardha-Training-9.jpg 480,images/Wardha-Training/Wardha-Training-9.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-9.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-9.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Wardha-Training/Wardha-Training-10.jpg 375, images/Wardha-Training/Wardha-Training-10.jpg 480,images/Wardha-Training/Wardha-Training-10.jpg 800"
                                data-src="images/Wardha-Training/Wardha-Training-10.jpg"
                                data-sub-html="<h4>Wardha Training </h4><p></p>">
                                <a href="">
                                    <img src="images/Wardha-Training/Wardha-Training-10.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-1.jpg 375, images/Tops/Tops-1.jpg 480, images/Tops/Tops-1.jpg 800"
                                data-src="images/Tops/Tops-1.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-2.jpg 375, images/Tops/Tops-2.jpg 480, images/Tops/Tops-2.jpg 800"
                                data-src="images/Tops/Tops-2.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-3.jpg 375, images/Tops/Tops-1.jpg 480, images/Tops/Tops-3.jpg 800"
                                data-src="images/Tops/Tops-3.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-4.jpg 375, images/Tops/Tops-4.jpg 480, images/Tops/Tops-4.jpg 800"
                                data-src="images/Tops/Tops-4.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-5.jpg 375, images/Tops/Tops-5.png 480, images/Tops/Tops-5.jpg 800"
                                data-src="images/Tops/Tops-5.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-6.jpg 375, images/Tops/Tops-6.jpg 480, images/Tops/Tops-6.jpg 800"
                                data-src="images/Tops/Tops-6.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-6.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops7.jpg 375, images/Tops/Tops-7.jpg 480, images/Tops/Tops-7.jpg 800"
                                data-src="images/Tops/Tops-7.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-7.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Tops/Tops-8.jpg 375, images/Tops/Tops-8.jpg 480, images/Tops/Tops-8.jpg 800"
                                data-src="images/Tops/Tops-8.jpg" data-sub-html="<h4>TOPS Security Ltd.</h4><p></p>">
                                <a href="">
                                    <img src="images/Tops/Tops-8.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-1.jpg 375,images/Campus-Drive Nagpur/Nagpur-1.jpg 480, images/Campus-Drive Nagpur/Nagpur-1.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-1.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-2.jpg 375,images/Campus-Drive Nagpur/Nagpur-2.jpg 480, images/Campus-Drive Nagpur/Nagpur-2.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-2.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-3.jpg 375,images/Campus-Drive Nagpur/Nagpur-3.jpg 480, images/Campus-Drive Nagpur/Nagpur-3.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-3.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-4.jpg 375,images/Campus-Drive Nagpur/Nagpur-4.jpg 480, images/Campus-Drive Nagpur/Nagpur-4.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-4.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-5.jpg 375,images/Campus-Drive Nagpur/Nagpur-5.jpg 480, images/Campus-Drive Nagpur/Nagpur-5.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-5.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-6.jpg 375,images/Campus-Drive Nagpur/Nagpur-6.jpg 480, images/Campus-Drive Nagpur/Nagpur-6.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-6.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-6.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-7.jpg 375,images/Campus-Drive Nagpur/Nagpur-7.jpg 480, images/Campus-Drive Nagpur/Nagpur-7.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-7.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-7.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-8.jpg 375,images/Campus-Drive Nagpur/Nagpur-8.jpg 480, images/Campus-Drive Nagpur/Nagpur-8.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-8.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-8.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-9.jpg 375,images/Campus-Drive Nagpur/Nagpur-1.jpg 480, images/Campus-Drive Nagpur/Nagpur-9.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-9.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-9.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-10.jpg 375,images/Campus-Drive Nagpur/Nagpur-10.jpg 480, images/Campus-Drive Nagpur/Nagpur-10.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-10.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-10.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Campus-Drive Nagpur/Nagpur-11.jpg 375,images/Campus-Drive Nagpur/Nagpur-11.jpg 480, images/Campus-Drive Nagpur/Nagpur-11.jpg 800"
                                data-src="images/Campus-Drive Nagpur/Nagpur-11.jpg"
                                data-sub-html="<h4>Campus Drive At Nagpur</h4><p></p>">
                                <a href="">
                                    <img src="images/Campus-Drive Nagpur/Nagpur-11.jpg">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab4">

                    <div class="demo-gallery">
                        <ul id="lightgallery4" class="list-unstyled">
                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/CRPF/crpf-l-2-min.jpg 375, images/CRPF/crpf-l-2-min.jpg 480, images/CRPF/crpf-l-2-min.jpg 800"
                                data-src="images/CRPF/crpf-l-2-min.jpg"
                                data-sub-html="<h4>Training at CRPF</h4><p></p>">
                                <a href="">
                                    <img src="images/CRPF/crpf-l-2-min.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/CRPF/crpf-l-3-min.jpg 375,images/CRPF/crpf-l-3-min.jpg 480, images/CRPF/crpf-l-3-min.jpg 800"
                                data-src="images/CRPF/crpf-l-3-min.jpg"
                                data-sub-html="<h4>Training at CRPF</h4><p></p>">
                                <a href="">
                                    <img src="images/CRPF/crpf-l-3-min.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/CRPF/crpf-l-4-min.jpg 375, images/CRPF/crpf-l-4-min.jpg 480, images/CRPF/crpf-l-4-min.jpg 800"
                                data-src="images/CRPF/crpf-l-4-min.jpg"
                                data-sub-html="<h4>Training at CRPF</h4><p></p>">
                                <a href="">
                                    <img src="images/CRPF/crpf-l-4-min.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/CRPF/crpf-l-5-min.jpg 375, images/CRPF/crpf-l-5-min.jpg 480, images/CRPF/crpf-l-5-min.jpg 800"
                                data-src="images/CRPF/crpf-l-5-min.jpg"
                                data-sub-html="<h4>Training at CRPF</h4><p></p>">
                                <a href="">
                                    <img src="images/CRPF/crpf-l-5-min.jpg">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab5">

                    <div class="demo-gallery">
                        <ul id="lightgallery5" class="list-unstyled">
                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/RajBhavan/Rajbhavan-1.jpg 375, images/RajBhavan/Rajbhavan-1.jpg 480, images/RajBhavan/Rajbhavan-1.jpg 800"
                                data-src="images/RajBhavan/Rajbhavan-1.jpg"
                                data-sub-html="<h4>Training Rajbhavan</h4><p></p>">
                                <a href="">
                                    <img src="images/RajBhavan/Rajbhavan-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/RajBhavan/Rajbhavan-2.jpg 375, images/RajBhavan/Rajbhavan-2.jpg 480, images/RajBhavan/Rajbhavan-2.jpg 800"
                                data-src="images/RajBhavan/Rajbhavan-2.jpg"
                                data-sub-html="<h4>Training Rajbhavan</h4><p></p>">
                                <a href="">
                                    <img src="images/RajBhavan/Rajbhavan-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/RajBhavan/Rajbhavan-3.jpg 375, images/RajBhavan/Rajbhavan-3.jpg 480, images/RajBhavan/Rajbhavan-3.jpg 800"
                                data-src="images/RajBhavan/Rajbhavan-3.jpg"
                                data-sub-html="<h4>Training Rajbhavan</h4><p></p>">
                                <a href="">
                                    <img src="images/RajBhavan/Rajbhavan-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/RajBhavan/Rajbhavan-4.jpg 375, images/RajBhavan/Rajbhavan-4.jpg 480, images/RajBhavan/Rajbhavan-4.jpg 800"
                                data-src="images/RajBhavan/Rajbhavan-4.jpg"
                                data-sub-html="<h4>Training Rajbhavan</h4><p></p>">
                                <a href="">
                                    <img src="images/RajBhavan/Rajbhavan-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/RajBhavan/Rajbhavan-5.jpg 375, images/RajBhavan/Rajbhavan-5.jpg 480, images/RajBhavan/Rajbhavan-5.jpg 800"
                                data-src="images/RajBhavan/Rajbhavan-5.jpg"
                                data-sub-html="<h4>Training Rajbhavan</h4><p></p>">
                                <a href="">
                                    <img src="images/RajBhavan/Rajbhavan-1.jpg">
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="tab10">

                    <div class="demo-gallery">
                        <ul id="lightgallery10" class="list-unstyled">
                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Training-at-fdb/fdb-1.jpg 375, images/Training-at-fdb/fdb-1.jpg 480, images/Training-at-fdb/fdb-1.jpg 800"
                                data-src="images/Training-at-fdb/fdb-1.jpg"
                                data-sub-html="<h4>Training at Faridabad</h4><p></p>">
                                <a href="">
                                    <img src="images/Training-at-fdb/fdb-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Training-at-fdb/fdb-2.jpg 375, images/Training-at-fdb/fdb-2.jpg 480, images/Training-at-fdb/fdb-2.jpg 800"
                                data-src="images/Training-at-fdb/fdb-2.jpg"
                                data-sub-html="<h4>Training at Faridabad</h4><p></p>">
                                <a href="">
                                    <img src="images/Training-at-fdb/fdb-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Training-at-fdb/fdb-3.jpg 375, images/Training-at-fdb/fdb-3.jpg 480, images/Training-at-fdb/fdb-3.jpg 800"
                                data-src="images/Training-at-fdb/fdb-3.jpg"
                                data-sub-html="<h4>Training at Faridabad</h4><p></p>">
                                <a href="">
                                    <img src="images/Training-at-fdb/fdb-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Training-at-fdb/fdb-4.jpg 375, images/Training-at-fdb/fdb-4.jpg 480, images/Training-at-fdb/fdb-4.jpg 800"
                                data-src="images/Training-at-fdb/fdb-4.jpg"
                                data-sub-html="<h4>Training at Faridabad</h4><p></p>">
                                <a href="">
                                    <img src="images/Training-at-fdb/fdb-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Training-at-fdb/fdb-5.jpg 375, images/Training-at-fdb/fdb-5.jpg 480, images/Training-at-fdb/fdb-5.jpg 800"
                                data-src="images/Training-at-fdb/fdb-5.jpg"
                                data-sub-html="<h4>Training at Faridabad</h4><p></p>">
                                <a href="">
                                    <img src="images/Training-at-fdb/fdb-5.jpg">
                                </a>
                            </li>


                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-1.jpg 375,images/Apperal/Apperal-1.jpg 480, images/Apperal/Apperal-1.jpg 800"
                                data-src="images/Apperal/Apperal-1.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-1.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-2.jpg 375,images/Apperal/Apperal-2.jpg 480, images/Apperal/Apperal-2.jpg 800"
                                data-src="images/Apperal/Apperal-2.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-2.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-3.jpg 375,images/Apperal/Apperal-3.jpg 480, images/Apperal/Apperal-3.jpg 800"
                                data-src="images/Apperal/Apperal-3.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-3.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-4.jpg 375,images/Apperal/Apperal-4.jpg 480, images/Apperal/Apperal-4.jpg 800"
                                data-src="images/Apperal/Apperal-4.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-4.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-5.jpg 375,images/Apperal/Apperal-5.jpg 480, images/Apperal/Apperal-5.jpg 800"
                                data-src="images/Apperal/Apperal-5.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-5.jpg">
                                </a>
                            </li>

                            <li class="col-xs-6 col-sm-4 col-md-3"
                                data-responsive="images/Apperal/Apperal-6.jpg 375,images/Apperal/Apperal-6.jpg 480, images/Apperal/Apperal-6.jpg 800"
                                data-src="images/Apperal/Apperal-6.jpg" data-sub-html="<h4>Apparel</h4><p></p>">
                                <a href="">
                                    <img src="images/Apperal/Apperal-6.jpg">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
    <script src="{{asset('js/jquery.scrolling-tabs.js')}}"></script>
    <script type="text/javascript" defer>
        $(document).ready(function () {
            $('.nav-tabs').scrollingTabs();
            $('#lightgallery').lightGallery();
            $('#lightgallery2').lightGallery();
            $('#lightgallery3').lightGallery();
            $('#lightgallery4').lightGallery();
            $('#lightgallery5').lightGallery();
            $('#lightgallery6').lightGallery();
            $('#lightgallery7').lightGallery();
            $('#lightgallery8').lightGallery();
            $('#lightgallery9').lightGallery();
            $('#lightgallery10').lightGallery();
            $('#lightgallery11').lightGallery();
            $('#lightgallery12').lightGallery();
            $('#lightgallery13').lightGallery();
        });
    </script>

    <script src="{{asset('js/lightgallery.js')}}"></script>
    <script src="{{asset('js/lg-fullscreen.js')}}"></script>

@endpush