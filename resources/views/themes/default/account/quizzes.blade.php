@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')

<section class="mt-3 mb-5">
<div class="container">
<div class="row justify-content-between">
     @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
      <div class="row my-2 dashbord">
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-journals text-white"></i> <p class="text-white fw-bold">Course Quizzes</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-clock-history text-white"></i> <p class="text-white fw-bold">Pendind Review</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-pass text-white"></i> <p class="text-white fw-bold">Passed</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
          <a href="">  <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-backspace text-white"></i> <p class="text-white fw-bold">Faild</p>
            </div></a>
          </div>
        </div>

        <!-- filter-start -->
<div class="card bg-light mt-5">
        <div class="card-body">
        <div class="d-flex align justify-content-between my-3">
        <h4 class="mb-4">Filter Quizzes</h4>

        <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold" style="height: 100%;">
         View Result
          </button>
      </div>
      <div class="row mb-3">
        <div class="col-sm-3 col-12">
            <div class="form-group">
              <label> Form<em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control">
            </div>
          </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>To<em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control">
            </div>
          </div>
          <div class="col-sm-2 col-12">
            <div class="form-group">
              <label>Course<em class="text-danger">*</em>
              </label>
              <select class="form-select" aria-label="Default select example">
              <option selected="">Select Course</option>
              <option value="1">Monthly</option>
             <option value="2">Quarterly</option>
             <option value="3">Half Yearly</option>
           <option value="3">Annually</option>
            </select>
            </div>
          </div>

          <div class="col-sm-2 col-12">
            <div class="form-group">
              <label>Status<em class="text-danger">*</em>
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
        <div class="col-sm-2">
          <div class="mt-3">
            <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold btn-h w-100" style="height: 100%;">
             Apply
              </button>
          </div>
        </div>
          </div>

     </div>

</div>
<div class="card bg-light p-3 my-4">
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
    <tr>
      <td>1</td>
      <td>Mordern Science of Vastu Shastra</td>
      <td>2</td>
      <td>2</td>
      <td>5 Months , 4 Week ago</td>
      <td>3 Week ago</td>
      <td>Pending</td>
      <td> <a href="/account/course/question"> <button type="button" name="button" class="btn btn-secondary btn-h">View</button></a> </td>

    </tr>

  </table>
</div>
</div>
    </div>

</section>

@endsection
