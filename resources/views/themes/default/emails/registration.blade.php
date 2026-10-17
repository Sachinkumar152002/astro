@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{config('app.name')}}</p>
    <p style="font-size: 16px; line-height: 1.6; color: #555;">We are thrilled to welcome you to the {{config('app.name')}} family! Your registration has been successfully completed.</p>
    <h3 style="color: #2980b9;">Details:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Mobile No.</th>
            <td>{{$user->phone}}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{$user->email}}</td>
        </tr>
        <tr>
            <th>Registration Date</th>
            <td>{{date('d/m/Y', strtotime($user->created_at))}}</td>
        </tr>
    </table>
  </div>
@endsection
