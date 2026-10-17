@extends(themeView().'emails.layout')
@section('content')
<div class="">
  <p>Dear {{ $userName }},</p>
  <p>We are pleased to inform you that the mahurat for your booked puja has been successfully scheduled by your astrologer {{ $astrologerName }}.</p>
  <p>Here are the details of your puja:</p>
  <p><strong>Puja Name:</strong> {{ $pujaName }}</p>
  <p><strong>Mahurat Date:</strong> {{ $mahuratDate }}</p>
  <p><strong>Mahurat Time:</strong> {{ $mahuratTime }}</p>
  @if(!empty($videoCallUrl))
  <p>To attend the puja via video call, please use the link below at the scheduled time:</p>
  <p><strong>Video Call Link:</strong><br>
  <a href="{{ $videoCallUrl }}" target="_blank">{{ $videoCallUrl }}</a></p>
  <p>We recommend joining a few minutes early to ensure a smooth experience.</p>
  @endif
  <p>If you have any questions or need assistance, feel free to contact our support team.</p>
  <p>Warm regards,<br>{{ $platformName }} Support Team</p>
</div>
@endsection
