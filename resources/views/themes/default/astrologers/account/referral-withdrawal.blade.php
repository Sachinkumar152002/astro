@extends(themeView().'layout')

@section("title", "Withdrawal | ".config("app.name"))

@push('style')
<style>
.balance-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.withdrawal-form-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.history-item {
    border-left: 4px solid #667eea;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}
</style>
@endpush

@section('content')
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-0 mtt-7 ast_dash">

        <div class="mb-4">
          <h3 class="mb-0"><i class="bi bi-cash-stack"></i> Withdrawal</h3>
          <p class="text-muted">Withdraw your referral and commission earnings</p>
        </div>

        <!-- Balance Cards -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="card balance-card border-n">
              <div class="card-body text-center">
                <h5 class="text-primary mb-3"><i class="bi bi-gift"></i> Referral Wallet</h5>
                <h2 class="text-primary mb-2">₹0.00</h2>
                <small class="text-muted">Available for withdrawal</small>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card balance-card border-n">
              <div class="card-body text-center">
                <h5 class="text-success mb-3"><i class="bi bi-cash-coin"></i> Commission Wallet</h5>
                <h2 class="text-success mb-2">₹0.00</h2>
                <small class="text-muted">Available for withdrawal</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="card withdrawal-form-card border-n">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-bank"></i> Request Withdrawal</h5>
          </div>
          <div class="card-body">
            <form id="withdrawalForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Select Wallet(s)</label>
                  <select class="form-select" id="walletType" required>
                    <option value="">Select Wallet</option>
                    <option value="referral">Referral Wallet Only</option>
                    <option value="commission">Commission Wallet Only</option>
                    <option value="both">Both Wallets</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Withdrawal Amount</label>
                  <input type="number" class="form-control" id="withdrawalAmount" step="0.01" min="0" placeholder="0.00" required>
                  <small class="text-muted">Minimum withdrawal: ₹100.00</small>
                </div>
              </div>

              <div class="row" id="walletBreakdown" style="display: none;">
                <div class="col-md-6 mb-3">
                  <label class="form-label">From Referral Wallet</label>
                  <input type="number" class="form-control" id="referralAmount" step="0.01" min="0" value="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">From Commission Wallet</label>
                  <input type="number" class="form-control" id="commissionAmount" step="0.01" min="0" value="0">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Bank Account Details</label>
                <div class="card bg-light">
                  <div class="card-body">
                    <p class="mb-1"><strong>Account Name:</strong> [Your Name]</p>
                    <p class="mb-1"><strong>Account Number:</strong> [Your Account Number]</p>
                    <p class="mb-0"><strong>IFSC Code:</strong> [Your IFSC Code]</p>
                    <a href="#" class="btn btn-sm btn-outline-primary mt-2">Update Bank Details</a>
                  </div>
                </div>
              </div>

              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Note:</strong> Withdrawal requests are processed within 3-5 business days. Minimum withdrawal amount is ₹100.00.
              </div>

              <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-send"></i> Submit Withdrawal Request
              </button>
            </form>
          </div>
        </div>

        <!-- Withdrawal History -->
        <div class="card mt-4 border-n">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Withdrawal History</h5>
          </div>
          <div class="card-body">

            <!-- Sample History Item -->
            <div class="history-item">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>Withdrawal #12345</strong>
                  <br>
                  <small class="text-muted">Referral: ₹500.00 | Commission: ₹300.00</small>
                  <br>
                  <small class="text-muted">15 Jan 2026, 10:30 AM</small>
                </div>
                <div class="text-end">
                  <strong class="text-primary">₹800.00</strong>
                  <br>
                  <span class="badge bg-success">Processed</span>
                  <br>
                  <small class="text-muted">Processed: 18 Jan 2026</small>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-5">
              <i class="bi bi-cash-stack" style="font-size: 4rem; color: #dee2e6;"></i>
              <p class="text-muted mt-3">No withdrawal requests yet</p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('walletType').addEventListener('change', function() {
  const walletType = this.value;
  const breakdownDiv = document.getElementById('walletBreakdown');

  if (walletType === 'both') {
    breakdownDiv.style.display = 'block';
  } else {
    breakdownDiv.style.display = 'none';
  }
});

document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
  e.preventDefault();
  // Handle form submission
  alert('Withdrawal request submitted successfully!');
});
</script>
@endpush
