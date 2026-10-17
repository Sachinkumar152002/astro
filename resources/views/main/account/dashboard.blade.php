@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.account.partials.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">

                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>
                                    My Details <a href="{{route('account.profile')}}"><i class="fa fa-edit pull-right"></i></a></h4>

                                <div class="bg-white text-left">
                                    <div class="col-md-6">
                                        <h3>Personal Details</h3>
                                        <div class="full-details">
                                            <div class="row sec-sep">
                                                <div class="col-md-5 col-xs-6">
                                                    <i class="fa fa-user"></i> <b>Name</b>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{auth()->user()->name}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5 col-xs-6">
                                                    <i class="fa fa-envelope"></i> <b>Email Id</b>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{auth()->user()->email}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5 col-xs-6">
                                                    <i class="fa fa-phone"></i> <b>Contact Number</b>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    +91 {{auth()->user()->details ? auth()->user()->details->phone : ''}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5 col-xs-6">
                                                    <i class="fa fa-user"></i> <b>Gender</b>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{auth()->user()->details ? auth()->user()->details->gender_text : ''}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Contact Details</h3>
                                        <div class="full-details">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row sec-sep">
                                                        <div class="col-md-2 col-xs-6">
                                                            <b>Address</b>
                                                        </div>
                                                        <div class="col-md-10 col-xs-6">
                                                           {{auth()->user()->details ? auth()->user()->details->address : ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row sec-sep">
                                                        <div class="col-md-2 col-xs-6">
                                                            <b>City</b>
                                                        </div>
                                                        <div class="col-md-10 col-xs-6">
                                                            {{auth()->user()->details ? auth()->user()->details->city : ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row sec-sep">
                                                        <div class="col-md-2 col-xs-6">
                                                            <b>State</b>
                                                        </div>
                                                        <div class="col-md-10 col-xs-6">
                                                            {{auth()->user()->details ? auth()->user()->details->state_text : ''}}
                                                        </div>
                                                    </div>

                                                    <div class="row sec-sep">
                                                        <div class="col-md-2 col-xs-6">
                                                            <b>Pincode</b>
                                                        </div>
                                                        <div class="col-md-10 col-xs-6">
                                                            {{auth()->user()->details ? auth()->user()->details->pincode : ''}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Split button -->
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->courses)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Registered Course</h4>
                                <div class="bg-white">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Course Image</th>
                                                        <th>Course Name</th>
                                                        <th class="text-center">Access (Days)</th>
                                                        <th class="text-center">Left (Days)</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(auth()->user()->courses as $course)
                                                    <tr>
                                                        <td ><img class="img-responsive img-table" src="{{$course->course->image ? $course->course->image->image(50, 50) : ''}}" alt="{{$course->course->name}}"></td>
                                                        <td>
                                                            <a href="{{route('account.course.detail', [$course->course->slug])}}">
                                                            {{$course->course->name}}
                                                            </a>
                                                        </td>
                                                        <td class="text-center">{{$course->course->access}}</td>
                                                        <td class="text-center"><span class="red">{{ $course->daysLeft }}</span></td>
                                                        <td>
                                                            <a href="{{route('account.course.detail', [$course->course->slug])}}" class="btn btn-success">
                                                            Start
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="clearfix"></div>
                         @include('main.courses.studentdashboard')
            </div>
        </div>
    </div>
</section>
@endsection
