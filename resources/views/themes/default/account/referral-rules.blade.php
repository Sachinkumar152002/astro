@extends(themeView().'layout')

@section("title", "Referral Rules | ".config("app.name"))

@push('style')
<style>
.rules-section {
    margin-bottom: 30px;
}
.rule-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.rule-icon {
    font-size: 2.5rem;
    color: #667eea;
    margin-bottom: 15px;
}
.faq-item {
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 0;
}
.faq-item:last-child {
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
            <h3 class="mb-0"><i class="bi bi-info-circle"></i> Referral Rules & Terms</h3>
            <p class="text-muted mb-0">Understand how our referral program works</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- App Referral Rules -->
        <div class="rules-section">
          <div class="card rule-card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-gift"></i> App Referral Rules</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Fixed Rewards:</strong> App referrals use fixed amount rewards only
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Reward Triggers:</strong> Rewards are credited on signup or first purchase (as configured)
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Self-Referral:</strong> Self-referral is strictly prohibited and will be blocked
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Limits:</strong> Maximum referrals per user and total earning limits may apply
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Expiry:</strong> Rewards may expire after a certain number of days if not credited
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Product Commission Rules -->
        <div class="rules-section">
          <div class="card rule-card">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Product Commission Rules</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Category-Based:</strong> Commissions are calculated based on product categories
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Commission Mode:</strong> Either Percentage or Fixed (one mode active globally)
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Calculation:</strong> Commission = Order Amount × (Rate/100) for Percentage, or Fixed Amount
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Max Cap:</strong> Maximum commission cap may apply per category
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Credit Time:</strong> Commissions credited instantly or after order completion
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Refund Policy:</strong> If order is refunded, commission will be cancelled
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Wallet Rules -->
        <div class="rules-section">
          <div class="card rule-card">
            <div class="card-header bg-info text-dark">
              <h5 class="mb-0"><i class="bi bi-wallet2"></i> Wallet Rules</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Two Wallets:</strong> Separate wallets for Referral Rewards and Product Commissions
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Referral Wallet:</strong> Contains earnings from app referrals
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Commission Wallet:</strong> Contains earnings from product commissions
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Withdrawal:</strong> Astrologers can withdraw from both wallets (subject to terms)
                </li>
                <li class="mb-3">
                  <i class="bi bi-check-circle text-success"></i>
                  <strong>Ledger:</strong> All transactions are recorded in the wallet ledger
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- FAQ Section -->
        <div class="rules-section">
          <div class="card rule-card">
            <div class="card-header bg-warning text-dark">
              <h5 class="mb-0"><i class="bi bi-question-circle"></i> Frequently Asked Questions</h5>
            </div>
            <div class="card-body">
              <div class="faq-item">
                <h6><strong>Q: How do I earn referral rewards?</strong></h6>
                <p class="text-muted">A: Share your referral code with friends. When they sign up or make their first purchase using your code, you earn rewards.</p>
              </div>
              <div class="faq-item">
                <h6><strong>Q: When are rewards credited?</strong></h6>
                <p class="text-muted">A: App referral rewards are credited based on the trigger (signup or first purchase). Product commissions are credited instantly or after order completion.</p>
              </div>
              <div class="faq-item">
                <h6><strong>Q: Can I refer myself?</strong></h6>
                <p class="text-muted">A: No, self-referral is strictly prohibited and will be automatically blocked by the system.</p>
              </div>
              <div class="faq-item">
                <h6><strong>Q: What happens if an order is refunded?</strong></h6>
                <p class="text-muted">A: If an order is refunded, the commission earned from that order will be cancelled and deducted from your commission wallet.</p>
              </div>
              <div class="faq-item">
                <h6><strong>Q: How are commissions calculated?</strong></h6>
                <p class="text-muted">A: Commissions are calculated based on the product category. The system uses either percentage or fixed mode (one active globally).</p>
              </div>
              <div class="faq-item">
                <h6><strong>Q: Can I withdraw my earnings?</strong></h6>
                <p class="text-muted">A: Withdrawal is available for astrologers. Users can use their wallet balance for purchases on the platform.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Support -->
        <div class="card">
          <div class="card-body text-center">
            <h5>Need More Help?</h5>
            <p class="text-muted">If you have any questions about the referral program, please contact our support team.</p>
            <a href="{{ route('user.support') }}" class="btn btn-primary">
              <i class="bi bi-headset"></i> Contact Support
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
@endpush
