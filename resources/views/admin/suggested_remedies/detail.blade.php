@extends('layouts.admin')

@section('content')
<style>
  .card {
    border-radius: 0 !important;
  }
  .card-header {
    background-color: transparent !important;
    border-radius: 0 !important;
  }
  .table tbody tr {
    background-color: transparent !important;
  }
  .table thead tr {
    background-color: transparent !important;
  }
  .table tbody tr:nth-child(even) {
    background-color: transparent !important;
  }
</style>
<div class="px-3">
  <div class="row">
    <div class="col-md-12">
      <h1 class="text-primary">Suggested Remedy Details</h1>
    </div>
  </div>

  @if(count($productDetails) > 0)
  <div class="card mt-3" style="border-radius: 0;">
    <div class="card-header" style="background-color: transparent; border-radius: 0;">
      <h5 class="mb-0">Products Suggested</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" style="border-radius: 0;">
          <thead>
            <tr style="background-color: transparent;">
              <th>#</th>
              <th>Product Name</th>
              <th class="text-right">Price (₹)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productDetails as $index => $product)
            <tr style="background-color: transparent;">
              <td>{{ $index + 1 }}</td>
              <td style="font-size: 12px;"><strong>{{ $product['title'] }}</strong></td>
              <td class="text-right">₹{{ number_format($product['price'], 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif

  @if(count($reportDetails) > 0)
  <div class="card mt-3" style="border-radius: 0;">
    <div class="card-header" style="background-color: transparent; border-radius: 0;">
      <h5 class="mb-0">Reports Suggested</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" style="border-radius: 0;">
          <thead>
            <tr style="background-color: transparent;">
              <th>#</th>
              <th>Report Name</th>
              <th class="text-right">Price (₹)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reportDetails as $index => $report)
            <tr style="background-color: transparent;">
              <td>{{ $index + 1 }}</td>
              <td style="font-size: 12px;"><strong>{{ $report['title'] }}</strong></td>
              <td class="text-right">₹{{ number_format($report['price'], 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif

  @if(count($pujaDetails ?? []) > 0)
  <div class="card mt-3" style="border-radius: 0;">
    <div class="card-header" style="background-color: transparent; border-radius: 0;">
      <h5 class="mb-0">Pujas Suggested</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" style="border-radius: 0;">
          <thead>
            <tr style="background-color: transparent;">
              <th>#</th>
              <th>Puja Name</th>
              <th class="text-right">Price (₹)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pujaDetails as $index => $puja)
            <tr style="background-color: transparent;">
              <td>{{ $index + 1 }}</td>
              <td style="font-size: 12px;"><strong>{{ $puja['title'] }}</strong></td>
              <td class="text-right">₹{{ number_format($puja['price'], 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif

  <div class="mt-3">
    <a href="{{ route('admin.suggested_remedies.index') }}" class="btn btn-primary">
      <i class="bi bi-arrow-left"></i> Back to List
    </a>
  </div>
</div>
@endsection
