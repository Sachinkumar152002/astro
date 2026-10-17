@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi Admin,</p>
    <p>We hope this message finds you well. We wanted to bring to your attention that a new support ticket has been created in the web and app portal of <strong>{{config('app.name')}}</strong>.</p>
    <h3>Details:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Ticket Number</th>
            <td>{{$ticket->ticket_number}}</td>
        </tr>
        <tr>
            <th>Raised By</th>
            <td>{{$user_type == 'user' ? $user->name : $user->display_name}}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{date('d/m/Y', strtotime($ticket->created_at))}}</td>
        </tr>
        <tr>
            <th>Issue Description</th>
            <td>{{$ticket->subject}}</td>
        </tr>
    </table>

    <p>Please take the necessary actions to resolve the ticket at your earliest convenience. If you require any additional information, feel free to reach out.</p>
  </div>
@endsection
