@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.employer.account.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">

                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>
                                    My Details <a href="{{route('employer.account.profile')}}"><i class="fa fa-edit pull-right"></i></a></h4>

                                <div class="bg-white text-left">
                                    <div class="col-md-6">
                                        <h3>Personal Details</h3>
                                        <div class="full-details">
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-user"></i> <b>Name</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{auth()->guard('employer')->user()->name}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-envelope"></i> <b>Email Id</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{auth()->guard('employer')->user()->email}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-phone"></i> <b>Contact Number</b>
                                                </div>
                                                <div class="col-md-7">
                                                   {{auth()->guard('employer')->user()->mobile}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        @if($currentPlan = auth()->guard('employer')->user()->currentPlan(auth()->guard('employer')->user()->id))
                                        <h3>Current Package</h3>
                                        <div class="full-details">
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-info"></i> <b>Plan</b>
                                                </div>

                                                <div class="col-md-7">
                                                    {{$currentPlan->plan->name}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-calendar"></i> <b>Start Date</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{$currentPlan->start_date}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-calendar"></i> <b>End Date</b>
                                                </div>
                                                <div class="col-md-7">
                                                   {{$currentPlan->end_date}}
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-download"></i> <b>Downloads</b>
                                                </div>
                                                <div class="col-md-7">
                                                   {{$currentPlan->downloads}}
                                                </div>
                                            </div>

                                             <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-download"></i> <b>Total Downloaded</b>
                                                </div>
                                                <div class="col-md-7">
                                                   0
                                                </div>
                                            </div>
                                            <div class="row sec-sep">
                                                <div class="col-md-5">
                                                    <i class="fa fa-download"></i> <b>Total Clicked</b>
                                                </div>
                                                <div class="col-md-7">
                                                   0
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                    </div>


                                </div>

                                <!-- Split button -->

                            </div>
                        </div>

                    </div>

                    @if(auth()->guard('employer')->user()->company)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Companies</h4>
                                <div class="bg-white">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Logo</th>
                                                        <th>Name</th>
                                                        <th>Industry</th>
                                                        <th>Address</th>
                                                        <th>Contact To</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(auth()->guard('employer')->user()->company as $company)
                                                    <tr>
                                                        <td><img class="img-responsive img-table" src="{{$company->logoimage ? $company->logoimage->image(50, 50) : $company->no_image}}" alt="{{$company->name}}"></td>
                                                        <td>{{$company->name}}</td>
                                                        <td>{{$company->industry_type && $company->industry ? $company->industry->name : ''}}</td>
                                                        <td>{{$company->address}}</td>
                                                        <td>{{$company->contact_name}}</td>
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
            </div>
        </div>
    </div>
</section>
@endsection