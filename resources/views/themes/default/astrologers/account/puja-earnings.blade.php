@extends(themeView().'layout')

@section('title', 'Puja Earnings | '.config("app.name"))

@section('content')
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeview().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
        <div class="tab-scroll-wrapper ps-2">
          <div class="card mb-3 bg-light border-n">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 dash-heading">Puja Earnings</h4>
                <a href="{{ route('astrologer.puja.earnings.pdf') }}" class="btn btn-primary" target="_blank">
                  <i class="bi bi-download"></i> Download Data
                </a>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="bg-primary text-white">
                    <tr>
                      <th>Sr.</th>
                      <th>Date</th>
                      <th>Puja Name</th>
                      <th>Puja Type</th>
                      <th>Order Number</th>
                      <th>Amount</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($pujaEarnings && count($pujaEarnings) > 0)
                      @foreach($pujaEarnings as $index => $earning)
                        @php
                          $assignment = $earning->pujaAssignment;
                          $product = $assignment ? $assignment->product : null;
                          $orderItem = $assignment ? $assignment->orderItem : null;
                          $order = $orderItem ? $orderItem->order : null;
                          $isOneToOne = $product && $product->is_one_to_one_puja == 1;
                        @endphp
                        <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>{{ $earning->created_at ? date('d-m-Y', strtotime($earning->created_at)) : '-' }}</td>
                          <td>{{ $product ? $product->title : 'N/A' }}</td>
                          <td>
                            @if($isOneToOne)
                              <span class="badge bg-info">One-to-One Puja</span>
                            @else
                              <span class="badge bg-success">Group Puja</span>
                            @endif
                          </td>
                          <td>{{ $order ? $order->order_number : 'N/A' }}</td>
                          <td>₹ {{ number_format($earning->amount, 2) }}</td>
                          <td>
                            @if($earning->status == 1)
                              <span class="badge bg-success">Success</span>
                            @else
                              <span class="badge bg-danger">Failed</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="7" class="text-center">No puja earnings found</td>
                      </tr>
                    @endif
                  </tbody>
                  @if($pujaEarnings && count($pujaEarnings) > 0)
                    <tfoot>
                      <tr class="bg-light">
                        <td colspan="5" class="text-end"><strong>Total Earnings:</strong></td>
                        <td colspan="2"><strong>₹ {{ number_format($pujaEarnings->sum('amount'), 2) }}</strong></td>
                      </tr>
                    </tfoot>
                  @endif
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
