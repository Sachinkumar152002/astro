@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <h2 style="text-align: center; color: #333;">Order Cancellation Notice</h2>
    <p>Hi [Recipient's Name],</p>
    <p>We hope this message finds you well.</p>
    <p>We regret to inform you that your order placed through our Web & App Portal at {{config('app.name')}} has been cancelled due to <strong>[reason for cancellation, e.g., request by user, payment issues, product unavailability, etc.]</strong>.</p>
    <p>We value your business and are here to assist you with any questions or concerns you may have. You can also view your account and orders through the portal.</p>
  </div>
@endsection
