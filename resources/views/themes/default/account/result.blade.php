@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')

<section class="mt-3 mb-5">
<div class="container">
<div class="table-responsive  bg-light card p-3 pt-4 pb-2">
  <h4>Assignment Result</h4>
  <!-- <h6>Topic</h6>
  <p>Which zodiac sign is known as "The Twins" Which zodiac sign is known as "The Twins"?</p> -->
  <table class="table table-bordered w-100">
    <thead>
      <tr>
        <th class="py-0 text-center">#</th>
        <th class="py-0 text-center">Question</th>
        <th class="py-0 text-center">Option A</th>
        <th class="py-0 text-center">Option B</th>
        <th class="py-0 text-center">Option C</th>
        <th class="py-0 text-center">Option D</th>
        <th class="py-0 text-center" colspan="3">Answer <i class="bi bi-printer fs-5 text-blue pointer"></i></th>
     </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td class="fw-bold">Which zodiac sign is known as "The Twins"?</td>
        <td class="bg-danger text-white">Which zodiac sign is known as "The Twins"?</td>
        <td> Which zodiac sign is known as "The Twins"?</td>
        <td class="text-white bg-success"> Which zodiac sign is known as "The Twins"?</td>
        <td>Which zodiac sign is known as "The Twins"?</td>
        <td class="fw-bold text-success" style="width:100px;">Option C</td>


      </tr>
    </tbody>
  </table>
</div>
</div>
</section>
@endsection
