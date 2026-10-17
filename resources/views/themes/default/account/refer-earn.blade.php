@extends(themeView().'layout')

@section("title", "Refer & Earn | ".config("app.name"))

@push('style')
<style>
.refdash-wrap .share-card .code {
  font-size: 26px;
  font-weight: 800;
  letter-spacing: 2px;
}
.refdash-wrap .share-actions .btn {
  margin-right: 8px;
  margin-bottom: 8px;
}
.refdash-wrap .dash-card {
  border: 0;
  border-radius: 14px;

}
.refdash-wrap .dash-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(0,0,0,0.12);
}
.refdash-wrap .dash-card .label {
  font-size: 13px;
  color: #6c757d;
  font-weight: 600;
}
.refdash-wrap .dash-card .value {
  font-size: 22px;
  font-weight: 800;
  margin-top: 4px;
}
.refdash-wrap .dash-card .meta {
  font-size: 12px;
  color: #6c757d;
  margin-top: 6px;
}
.refdash-wrap .dash-card .icon {
  height: 42px;
  width: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  /* darker background so icons are clearly visible */
  background: #0d6efd !important;
  color: #ffffff !important;
  font-size: 20px;
}
</style>
@endpush

@section('content')
<section class="account-page-ui dashboad_section refdash-wrap">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeView().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">

        <!-- Referral code + link + sharing in one card -->
        <div class="card dash-card border-n share-card mb-4">
          <div class="card-body">
            <div class="row align-items-center">
            <div class="mb-3 col-12">
          <h3 class="mb-1"><i class="bi bi-gift" style="color:#0d6efd;"></i> Refer & Earn</h3>
          <p class="text-muted mb-0">Track your referral earnings and open detailed reports.</p>
        </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <div class="label">Your Referral Code</div>
                <div class="code" id="referralCode">{{ $user->referral_code ?? 'N/A' }}</div>
                <div class="mt-2">
                  <button class="btn btn-sm btn-primary" onclick="copyReferralCode()"><i class="bi bi-copy"></i> Copy Code</button>
                </div>
              </div>

              <div class="col-md-8">
                <div class="label mb-2">Your Referral Link</div>
                <div class="input-group mb-2">
                  <input type="text" class="form-control" id="referralLinkInput" readonly value="{{ $referralLink ?? '' }}">
                  <button class="btn btn-outline-primary" type="button" onclick="copyReferralLink()"><i class="bi bi-copy"></i> Copy Link</button>
                </div>

                <div class="label mb-2">Share</div>
                <div class="share-actions">
                  <a href="#" class="btn btn-sm btn-success" onclick="shareOnWhatsApp(event)"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                  <a href="#" class="btn btn-sm btn-primary" onclick="shareOnFacebook(event)"><i class="bi bi-facebook"></i> Facebook</a>
                  <a href="#" class="btn btn-sm btn-info text-white" onclick="shareOnTwitter(event)"><i class="bi bi-twitter"></i> Twitter</a>
                  <a href="#" class="btn btn-sm btn-danger" onclick="shareViaEmail(event)"><i class="bi bi-envelope"></i> Email</a>
                </div>

                <div class="meta mt-2">Your friends can use this link to sign up. Rewards are credited based on program rules.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dashboard cards linking to detail screens -->
        <div class="card dash-card share-card border-n mb-4">
        <div class="card-body">
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <a href="{{ route('user.earnings') }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Earnings</div>
                    <div class="value text-success">₹{{ number_format((float)($totalEarnings ?? 0), 2) }}</div>
                    <div class="meta">Lifetime: referral + commission</div>
                  </div>
                  <div class="icon"><i class="bi bi-graph-up"></i></div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('user.earnings', ['wallet_type' => 'referral']) }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Referral Wallet</div>
                    <div class="value text-success">₹{{ number_format((float)($referralBalance ?? 0), 2) }}</div>
                    <div class="meta">Lifetime: ₹{{ number_format((float)($referralLifetime ?? 0), 2) }}</div>
                  </div>
                  <div class="icon"><i class="bi bi-gift"></i></div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('user.earnings', ['wallet_type' => 'commission']) }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Commission Wallet</div>
                    <div class="value text-success">₹{{ number_format((float)($commissionBalance ?? 0), 2) }}</div>
                    <div class="meta">Lifetime: ₹{{ number_format((float)($commissionLifetime ?? 0), 2) }}</div>
                  </div>
                  <div class="icon"><i class="bi bi-wallet2"></i></div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('user.referrals.app') }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Active Referrals</div>
                    <div class="value">{{ (int)($appReferrals['credited_count'] ?? 0) }} credited</div>
                    <div class="meta">Earned: ₹{{ number_format((float)($appReferrals['credited_amount'] ?? 0), 2) }} · Pending: {{ (int)($appReferrals['pending_count'] ?? 0) }}</div>
                  </div>
                  <div class="icon" style="background: rgba(25,135,84,.12); color:#198754;"><i class="bi bi-person-plus"></i></div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('user.commissions') }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Product Commissions</div>
                    <div class="value">₹{{ number_format((float)($productCommissions['credited_amount'] ?? 0), 2) }}</div>
                    <div class="meta">Orders: {{ (int)($productCommissions['orders'] ?? 0) }} · Pending: ₹{{ number_format((float)($productCommissions['pending_amount'] ?? 0), 2) }}</div>
                  </div>
                  <div class="icon" style="background: rgba(13,202,240,.12); color:#0dcaf0;"><i class="bi bi-cash-coin"></i></div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('user.referral.rules') }}" class="text-decoration-none">
              <div class="card dash-card">
                <div class="card-body d-flex justify-content-between">
                  <div>
                    <div class="label">Referral Rules</div>
                    <div class="value">Read</div>
                    <div class="meta">Eligibility, limits & expiry</div>
                  </div>
                  <div class="icon"><i class="bi bi-info-circle"></i></div>
                </div>
              </div>
            </a>
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
<script>
function copyReferralCode() {
  const code = document.getElementById('referralCode').textContent;
  navigator.clipboard.writeText(code).then(() => {
    alert('Referral code copied to clipboard!');
  });
}

function copyReferralLink() {
  const link = document.getElementById('referralLinkInput').value;
  navigator.clipboard.writeText(link).then(() => {
    alert('Referral link copied to clipboard!');
  });
}

function shareOnWhatsApp(e) {
  e.preventDefault();
  const text = 'Join using my referral code: ' + document.getElementById('referralCode').textContent;
  const link = document.getElementById('referralLinkInput').value;
  window.open('https://wa.me/?text=' + encodeURIComponent(text + ' ' + link), '_blank');
}

function shareOnFacebook(e) {
  e.preventDefault();
  const link = document.getElementById('referralLinkInput').value;
  window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link), '_blank');
}

function shareOnTwitter(e) {
  e.preventDefault();
  const text = 'Join using my referral code: ' + document.getElementById('referralCode').textContent;
  const link = document.getElementById('referralLinkInput').value;
  window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(link), '_blank');
}

function shareViaEmail(e) {
  e.preventDefault();
  const subject = 'Join using my referral code';
  const body = 'Hi,\n\nJoin using my referral code: ' + document.getElementById('referralCode').textContent + '\n\nLink: ' + document.getElementById('referralLinkInput').value;
  window.location.href = 'mailto:?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
}
</script>
@endpush
