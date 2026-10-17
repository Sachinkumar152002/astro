@extends(themeView().'layout')
@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container mt-3 mb-3">
<div class="row">
@include(themeView().'astrologers._partials.sidebar')
<div class="col-lg-9 col-12 p-0">
<div class="card bg-light p-3 mb-4 mx-0">
  <h4 class="mb-4">Financial</h4>
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
    <tbody><tr>
      <td>1</td>
      <td>#674475</td>
      <td>Mordern Science of Vastu Shastra</td>
      <td>Course</td>
      <td>100</td>
      <td>15/12/2024</td>
      <td><i class="bi bi-printer text-blue fs-5 pointer"></i></td>

    </tr>

  </tbody></table>
</div>
</div>


  @endsection
