@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-12 wow slideInRight">
    <div class="events-section">
    <h2>Events</h2>
    @if(count($events)>0)
    @foreach($events as $event)
    <div class="box">
    <div class="row">
    <div class="col-md-12">
    <div class="box1 row">
    <div class="mentor-image col-md-2"><img src="{{$event->image ? $event->image->image(100,100) : asset('images/payu.jpg')}}"></div>
    <div class="candidate-info col-md-10">
    <div class="row">
    <div class="title-box">
    <div class="col-md-9"><h3>{{$event->title}}</h3></div>
    <div class="col-md-3">
    <ul class="date-city">
    <li class="date">{{date('M d Y', strtotime($event->event_date))}}</li>
    <li class="city">{{$event->cityobj ? $event->cityobj->name : ''}}</li>
    </ul>
    </div>
    </div>
    <p>{{$event->description}}</p>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    @endforeach

    @else
    <p class="text-center">No Event</p>
    @endif
    </div>
    </div>
</div>
@endsection