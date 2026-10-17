@extends(themeView() . 'layout')

@section('title', 'Application Under Review | '.config("app.name"))
@section('keywords', '')
@section('description', '')

@push('style')
<style>
  .pending-card {
    max-width: 520px;
    margin: 60px auto;
    padding: 40px 30px;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    text-align: center;
  }
  .pending-card h2 { color: #0e4677; margin-bottom: 12px; font-size: 1.5rem; }
  .pending-card .application-id {
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
  .pending-card p { color: #555; margin-bottom: 24px; line-height: 1.6; }
  .pending-card .btn-dashboard { background: #0e4677; color: #fff; padding: 10px 28px; border-radius: 8px; font-weight: 600; }
  .pending-card .btn-dashboard:hover { background: #0a3559; color: #fff; }
  .pending-card .btn-logout { background: transparent; color: #6c757d; border: 1px solid #dee2e6; padding: 10px 28px; border-radius: 8px; font-weight: 600; }
  .pending-card .btn-logout:hover { background: #f8f9fa; color: #495057; }
</style>
@endpush

@section('content')
<div class="container py-5">
  <div class="pending-card">
    <h2>Application Under Review</h2>
    <p>Thank you for registering. Your application is being reviewed by our team. You will be able to access the dashboard once it is approved.</p>
    <p class="mb-1">Your Application ID</p>
    <div class="application-id">{{ $applicationId ?? '—' }}</div>
    <p class="small text-muted mb-0">Please save this ID for reference.</p>
    <div class="mt-4">
      <a href="{{ route('astrologer.logout') }}" class="btn btn-logout">Logout</a>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Back to Home</a>
    </div>
  </div>
</div>
@endsection
