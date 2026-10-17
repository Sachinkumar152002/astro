@extends(themeView().'layout')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12">
  <div class="row my-2 dashbord">
    <div class="col-lg-3 mb-2 mb-md-0">
      <a href="">
        <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="fs-2 bi bi-journals text-white"></i>
          <p class="text-white fw-bold">Course Quizzes</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 mb-2 mb-md-0">
      <a href="">
        <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="fs-2 bi bi-clock-history text-white"></i>
          <p class="text-white fw-bold">Pendind Review</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 mb-2 mb-md-0">
      <a href="">
        <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="fs-2 bi bi-pass text-white"></i>
          <p class="text-white fw-bold">Passed</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 mb-2 mb-md-0">
      <a href="">
        <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="fs-2 bi bi-backspace text-white"></i>
          <p class="text-white fw-bold">Faild</p>
        </div>
      </a>
    </div>
  </div>
  <!-- filter-start -->

  <div class="card bg-light p-3 my-4">
      <h4 class="mb-4">Quizzes</h4>
    <table class="table table-bordered radius">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th> Quizzes </th>
          <th>Question</th>
          <th>Posted</th>
          <th>Updated</th>
          <th>Result</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Mordern Science of Vastu Shastra</td>
          <td>2</td>
          <td>2</td>
          <td>5 Months , 4 Week ago</td>
          <td>3 Week ago</td>
          <td>Pending</td>
          <td>
            <a href="#">
              <button type="button" name="button" class="btn btn-secondary btn-h">View</button>
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

      @endsection
