@extends('layouts.app')
@section('content')
<!--Banner Part Start Here-->
@include('main.employer.partials.banner')
<!--Banner Part End Here-->

<!--Steps Part Start Here-->
@include('main.employer.partials.steps')
<!--Steps Part End Here-->
<!--Categories Part Start Here-->
@include('main.employer.partials.skills')
<!--Categories Part End Here-->
<!--Certification Part End Here-->
@include('main.employer.partials.certificates')
<!--Certification Part End Here-->
<section class="greybg">
<div class="container">
<div class="row">
  <div class="col-md-12">
    @include('main.employer.partials.hothiring')
    @include('main.employer.partials.recentjobs')
  </div>
</div>
</div>
</section>

@include('main.employer.partials.mentors')

<section class="trained-candidates">
<div class="container">
<div class="row">
  @include('main.employer.partials.trained')
  @include('main.employer.partials.walkins')
</div>
</div>
</section>

<section class="trained-candidates bg-greydd">
<div class="container">
<div class="row">
  @include('main.employer.partials.videosresume')
  @include('main.employer.partials.events')
</div>
</div>
</section>

@include('main.home.partners')

@include('main.employer.partials.testimonials')

@endsection