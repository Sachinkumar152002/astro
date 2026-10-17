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
                                <h4>My Courses</h4>
                            </div>
                            <div class="col-sm-12 col-md-12 box-white">
                                <div class="bg-white">
                                @if(auth()->user()->courses)
                                @foreach(auth()->user()->courses as $course)
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-sm">

                                                <tbody>
                                                     <tr>

                                                        <td class="text-left" colspan="2"><img class="img-responsive img-table1" src="{{$course->course->image ? $course->course->image->image(150, 150) : ''}}" alt="{{$course->course->name}}" style="display: inline-block;"></td>
                                                    </tr>
                                                    <tr class="tb-border">
                                                        <th>Name</th>
                                                        <td><a href="{{route('account.course.handle', [$course->course->slug])}}">
                                                            {{$course->course->name}}


                                                            </a></td>
                                                    </tr>


                                                    <tr>
                                                        <th>Certified by</th>
                                                        <td>{{$course->course->association->short_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Module(s)</th>
                                                        <td><a href="#">{{$course->course->modules->count()}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Access</th>
                                                        <td>{{$course->course->access}} days</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Start Date</th>
                                                        <td>{{date('d-m-Y', strtotime($course->start_date))}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Days Left</th>
                                                        <td><span class="red">{{ $course->daysLeft }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Score</th>
                                                        <td>{{ $course->finalScore }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>View Certificate</th>
                                                        <td>{!! $course->certificateLink !!}</td>
                                                    </tr>
                                                    <tr>
                                                      <td class="" colspan="2">
                                                            <a href="{{route('account.course.handle', [$course->course->slug])}}" class="w100 btn btn-success">
                                                            Start Course
                                                            </a>
                                                        </td>
                                                        </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                     @endforeach
                                     @else
                                     <p>No Course</p>
                                 @endif
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
