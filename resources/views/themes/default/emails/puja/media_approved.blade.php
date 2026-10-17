@extends(themeView().'emails.layout')
@section('content')
<div class="">
  <p>Dear {{ $userName }},</p>
  <p>We are happy to inform you that your astrologer has successfully uploaded the photos and videos for your booked puja.</p>
  <p>You can now view and access the sacred moments of your puja in your <strong>Booked Puja</strong> section.</p>
  <p>If you need any assistance or have questions, please feel free to contact our support team.</p>
  <p>Wishing you happiness and prosperity.</p>
  <p>Warm regards,<br>{{ $platformName }} Support Team</p>
</div>
@endsection
