@extends(themeView().'layout')

@section("title", "Commission Detail | ".config("app.name"))

@push('style')
<style>
.detail-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.info-row {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.info-row:last-child {
    border-bottom: none;
}
</style>
@endpush

@section('content')
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeView().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">

        <div class="mb-4 d-flex align-items-start justify-content-between gap-2 flex-wrap">
          <div>
            <h3 class="mb-0"><i class="bi bi-info-circle"></i> Commission Detail</h3>
            <p class="text-muted mb-0">View commission breakdown and order details</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- Commission Information -->
        <div class="card detail-card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Commission Information</h5>
          </div>
          <div class="card-body">
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Commission ID:</strong></div>
                <div class="col-md-8">#12345</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Status:</strong></div>
                <div class="col-md-8">
                  <span class="badge bg-success">Credited</span>
                </div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Commission Amount:</strong></div>
                <div class="col-md-8">
                  <h4 class="text-success mb-0">₹50.00</h4>
                </div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Credited At:</strong></div>
                <div class="col-md-8">15 Jan 2026, 10:30 AM</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Information -->
        <div class="card detail-card">
          <div class="card-header bg-info text-dark">
            <h5 class="mb-0">Order Information</h5>
          </div>
          <div class="card-body">
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Order ID:</strong></div>
                <div class="col-md-8">#ORD12345</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Product Name:</strong></div>
                <div class="col-md-8">Puja Service</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Category:</strong></div>
                <div class="col-md-8">Puja</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Order Amount:</strong></div>
                <div class="col-md-8">₹1,000.00</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Order Date:</strong></div>
                <div class="col-md-8">15 Jan 2026, 10:00 AM</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Calculation Breakdown -->
        <div class="card detail-card">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Calculation Breakdown</h5>
          </div>
          <div class="card-body">
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Commission Mode:</strong></div>
                <div class="col-md-8">
                  <span class="badge bg-primary">Percentage</span>
                </div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Commission Rate:</strong></div>
                <div class="col-md-8">5%</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Order Amount:</strong></div>
                <div class="col-md-8">₹1,000.00</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Calculated Commission:</strong></div>
                <div class="col-md-8">₹50.00 (5% of ₹1,000.00)</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Max Cap Applied:</strong></div>
                <div class="col-md-8">No</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Final Commission:</strong></div>
                <div class="col-md-8">
                  <h4 class="text-success mb-0">₹50.00</h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Credit Information -->
        <div class="card detail-card">
          <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Credit Information</h5>
          </div>
          <div class="card-body">
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Credit Time:</strong></div>
                <div class="col-md-8">Instant</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Credited To:</strong></div>
                <div class="col-md-8">Commission Wallet</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Balance Before:</strong></div>
                <div class="col-md-8">₹0.00</div>
              </div>
            </div>
            <div class="info-row">
              <div class="row">
                <div class="col-md-4"><strong>Balance After:</strong></div>
                <div class="col-md-8">₹50.00</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
@endpush
