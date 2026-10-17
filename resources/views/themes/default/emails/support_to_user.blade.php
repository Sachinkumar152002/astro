@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user_type == 'user' ? $user->name : $user->display_name}},</p>
    <p>Thank you for reaching out to us at <strong>{{config('app.name')}}</strong>. We appreciate your patience as we process your support request.</p>
    <p>We are pleased to inform you that your ticket has been successfully raised, and our team is currently reviewing the details. We aim to provide you with a comprehensive response as soon as possible. We have tracked the details related to your request.</p>
    <p><strong>Please find the tracking information below:</strong></p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Ticket Number</th>
            <td>{{$ticket->ticket_number}}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>Open</td>
        </tr>
        <tr>
            <th>Expected Resolution Time</th>
            <td>24 to 48 Hrs.</td>
        </tr>
    </table>
  </div>
@endsection
