@extends(themeView().'astrologers.account.explore.layout')

@section('title', 'IIAG | Meeting')
@section('keywords', '')
@section('description','')

@section('content')
<section class="mt-3 mb-5">
<div class="container">
<div class="row justify-content-between">
    <div class="col-12">

        <div class="row my-2 dashbord">
            <div class="col-lg-3 mb-2 mb-md-0">
              <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
              <i class="fs-2 bi bi-book text-white"></i> <p class="text-white fw-bold">{{$course->name}}</p>
              </div></a>
            </div>
            <div class="col-lg-3 mb-2 mb-md-0">
              <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
              <i class="fs-2 bi bi bi-person text-white"></i> <p class="text-white fw-bold">{{$course->instructor ? $course->instructor->display_name : 'Instructor' }}</p>
              </div></a>
            </div>
            <div class="col-lg-3 mb-2 mb-md-0">
              <a href="">
                <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
                  <i class="fs-2 bi bi-clock text-white"></i>
                  <p class="text-white fw-bold">
                    @php
                      $durarion = abs(strtotime($course->meet_date.' '.$course->meet_start_time) - strtotime($course->meet_date.' '.$course->meet_end_time))/60;
                    @endphp
                    {{$durarion}} minutes
                  </p>
              </div>
            </a>
            </div>
            <div class="col-lg-3 mb-2 mb-md-0">
              <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
              <i class="fs-2 bi bi-clock-history text-white"></i> <p class="text-white fw-bold">{{$course->meet_status ? 'Active' : 'Inactive'}}</p>
              </div></a>
            </div>
          </div>
          <div class="card bg-light p-3 my-4">
            <table class="table table-bordered radius">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Time Slot</th>
                  <th>Platform</th>
                  <th>Instructor</th>
                  <!-- <th>Status</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($course->meet_link &&  ($course->meet_type == 'permanent' || time() < strtotime($course->meet_date.' '.$course->meet_end_time) ))
                <tr>
                  <td>1</td>
                  <td>{{$user->display_name}}</td>
                  <td>{{date('d-m-Y', strtotime($course->meet_date))}}</td>
                  <td>{{date('h:i A', strtotime($course->meet_date.' '.$course->meet_start_time))}} - {{date('h:i A', strtotime($course->meet_date.' '.$course->meet_end_time))}}</td>
                  <td>{{$course->meet_platform}}</td>
                  <td>{{$course->instructor ? $course->instructor->firstname.' '.$course->instructor->lastname : '-'}}</td>
                  <!-- <td>Hold</td> -->
                  @if($course->meet_status)
                    <td> <a href="{{$course->meet_link}}"> <button type="button" name="button" class="btn btn-secondary btn-h">Join</button></a> </td>
                  @else
                    <td><button type="button" name="button" class="btn btn-secondary btn-h">Join</button></td>
                  @endif
                </tr>
                @else
                  <tr>
                    <td colspan=8 class="text-center">
                      <img src="/assets/images/4393.webp" alt="no meeting" class="nodata">
                   <h5 class="mt-3 text-dark"> No Meeting Found!</h5>
                   <p class="text-secondary"> Meetings Links will be displayed here.</p>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
    </div>

</section>
@endsection
