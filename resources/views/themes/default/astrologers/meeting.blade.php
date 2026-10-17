@extends(themeView().'layout')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12">
    <div class="row mb-2 dashbord">
      <div class="col-lg-3 mb-2 mb-md-0">
        <a href="">
          <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-person text-white"></i>
            <p class="text-white fw-bold">Open meetings</p>
          </div>
        </a>
      </div>
      <div class="col-lg-3 mb-2 mb-md-0">
        <a href="">
          <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-clock-history text-white"></i>
            <p class="text-white fw-bold">Total meetings</p>
          </div>
        </a>
      </div>
      <div class="col-lg-3 mb-2 mb-md-0">
        <a href="">
          <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-pass text-white"></i>
            <p class="text-white fw-bold">Active hours</p>
          </div>
        </a>
      </div>
      <div class="col-lg-3 mb-2 mb-md-0">
        <a href="">
          <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-clock-history text-white"></i>
            <p class="text-white fw-bold">Active hours</p>
          </div>
        </a>
      </div>
    </div>
  <!-- <div class="card bg-light mt-5">
      <div class="card-body">
        <div class="d-flex align justify-content-between my-3">
          <h4 class="mb-4">Filter Meetings</h4>
          <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold" style="height: 100%;"> Show Result </button>
        </div>
        <div class="row mb-3">
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label> Form <em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control">
            </div>
          </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>To <em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control">
            </div>
          </div>
          <div class="col-sm-2 col-12">
            <div class="form-group">
              <label>Day <em class="text-danger">*</em>
              </label>
              <select class="form-select" aria-label="Default select example">
                <option selected="">All Day</option>
                <option value="1">Sunday</option>
                <option value="2">Monday</option>
                <option value="3">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="3">Thursday</option>
                <option value="3">Saturday</option>
              </select>
            </div>
          </div>
          <div class="col-sm-2 col-12">
            <div class="form-group">
              <label>Instructor <em class="text-danger">*</em>
              </label>
              <select class="form-select" aria-label="Default select example">
                <option selected="">All Instructor</option>
              </select>
            </div>
          </div>
          <div class="col-sm-2 col-12">
            <div class="form-group">
              <label>Status <em class="text-danger">*</em>
              </label>
              <select class="form-select" aria-label="Default select example">
                <option selected="">All</option>
                <option value="1">Pending</option>
                <option value="2">Passed</option>
                <option value="4">Not Passed</option>
                <option value="5">Not Submited</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="card bg-light p-3 my-4">
        <h4 class="mb-4">Meetings</h4>
      <table class="table table-bordered radius">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Platform</th>
            <th>Instructor</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Rupesh</td>
            <td>20/11/2024</td>
            <td>02:30 PM - 03:30 PM</td>
            <td>Google Meet</td>
            <td>Sonu Sharma</td>
            <td>Hold</td>
            <td>
              <a href="/account/course/question">
                <button type="button" name="button" class="btn btn-secondary btn-h">Join</button>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

        @endsection
