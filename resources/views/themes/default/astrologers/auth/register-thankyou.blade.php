@extends(themeView() . 'layout')

@section('title', 'Thank You | Astrologer Registration | '.config("app.name"))
@section('keywords', '')
@section('description', '')

@push('style')
<style>
  .thankyou-card {
    max-width: 520px;
    margin: 60px auto;
    padding: 40px 30px;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    text-align: center;
  }
  .thankyou-card h2 { color: #0e4677; margin-bottom: 12px; font-size: 1.5rem; }
  .thankyou-card .application-id {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0e4677;
    letter-spacing: 0.5px;
    margin: 16px 0 24px;
    padding: 12px 20px;
    background: #f0f6fa;
    border-radius: 8px;
    display: inline-block;
  }
  .thankyou-card p { color: #555; margin-bottom: 24px; line-height: 1.6; }
  .thankyou-card .btn-login { background: #0e4677; color: #fff; padding: 10px 28px; border-radius: 8px; font-weight: 600; }
  .thankyou-card .btn-login:hover { background: #0a3559; color: #fff; }
</style>
@endpush

@section('content')
<div class="container py-5">
  <div class="thankyou-card">
    <h2>Thank You!</h2>
    <p>Your astrologer registration has been submitted successfully. Our team will review your application and get in touch with you.</p>
    <p class="mb-1">Your Application ID</p>
    <div class="application-id">{{ $applicationId ?? '—' }}</div>
    <p class="small text-muted mb-0">Please save this ID for future reference.</p>
    <div class="mt-4">
      <a href="{{ route('astrologer.login') }}" class="btn btn-login">Go to Login</a>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Back to Home</a>
    </div>
  </div>
</div>
@endsection
