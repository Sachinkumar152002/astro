@extends(themeView().'layout')

@section("title", "Wallet Ledger | ".config("app.name"))

@push('style')
<style>
.ledger-item {
    border-left: 4px solid #667eea;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    transition: transform 0.2s;
}
.ledger-item:hover {
    transform: translateX(5px);
}
.ledger-item.referral {
    border-left-color: #667eea;
}
.ledger-item.commission {
    border-left-color: #28a745;
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
            <h3 class="mb-0"><i class="bi bi-list-ul"></i> Wallet Ledger</h3>
            <p class="text-muted mb-0">Complete transaction history for both wallets</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- Wallet Balance Cards -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-body text-center">
                <h5 class="text-primary">Referral Wallet</h5>
                <h3 class="text-primary">₹0.00</h3>
                <small class="text-muted">Available Balance</small>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-body text-center">
                <h5 class="text-success">Commission Wallet</h5>
                <h3 class="text-success">₹0.00</h3>
                <small class="text-muted">Available Balance</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3 mb-2">
                <label class="form-label">Wallet Type</label>
                <select class="form-select">
                  <option value="">All Wallets</option>
                  <option value="referral">Referral Wallet</option>
                  <option value="commission">Commission Wallet</option>
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label">Transaction Type</label>
                <select class="form-select">
                  <option value="">All Types</option>
                  <option value="credit">Credit</option>
                  <option value="debit">Debit</option>
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label">Date From</label>
                <input type="date" class="form-control">
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label">Date To</label>
                <input type="date" class="form-control">
              </div>
              <div class="col-md-12">
                <button class="btn btn-primary">Apply Filters</button>
                <button class="btn btn-secondary">Reset</button>
                <button class="btn btn-success float-end">
                  <i class="bi bi-download"></i> Export CSV
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Ledger List -->
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="mb-0">Transaction History</h5>
          </div>
          <div class="card-body">

            <!-- Sample Ledger Item -->
            <div class="ledger-item referral">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>App Referral Reward</strong>
                  <br>
                  <small class="text-muted">User: John Doe | Order: #12345</small>
                  <br>
                  <small class="text-muted">15 Jan 2026, 10:30 AM</small>
                </div>
                <div class="text-end">
                  <strong class="text-success">+₹100.00</strong>
                  <br>
                  <span class="badge bg-primary">Referral Wallet</span>
                  <br>
                  <small class="text-muted">Balance: ₹100.00</small>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-5">
              <i class="bi bi-list-ul" style="font-size: 4rem; color: #dee2e6;"></i>
              <p class="text-muted mt-3">No transactions yet</p>
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
