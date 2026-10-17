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
                                <h4>Notifications</h4>
                            </div>
                            <div class="col-sm-12 col-md-12 box-white">
                                <div class="bg-gray">
                                    <div class="col-md-12">
                                        <div class="notification-full-section">
                                            <div class="notification-section  ">
                                                <div class="Content-section-section">
                                                    Welcome to Smart Skill Tech
                                                </div>
                                            </div>
                                            @foreach($messages as $notifiation)
                                            <div class="notification-section  ">
                                                <div class="Content-section-section">
                                                    {{$notifiation->message}}
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
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
@endsection