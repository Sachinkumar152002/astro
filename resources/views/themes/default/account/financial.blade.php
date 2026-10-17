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


        <!-- filter-start -->
     <div class="card bg-light">
        <div class="card-body">
        <div class="d-flex align justify-content-between my-3">
        <h4 class="mb-4">Financial Filter</h4>

        <!-- <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold" style="height: 100%;">
         View Result
          </button> -->
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
        <th>Invoice</th>
      <th>Title </th>
      <th>Type</th>
      <th>Amount</th>
      <th>Date</th>
      <th>Action</th>

    </tr>
      </thead>
    <tr>
      <td>1</td>
      <td>#674475</td>
      <td>Mordern Science of Vastu Shastra</td>
      <td>Course</td>
      <td>100</td>
      <td>15/12/2024</td>
      <td><i class="bi bi-printer text-blue fs-5 pointer"></i></td>

    </tr>

  </table>
</div>
</div>
    </div>

</section>

@endsection
