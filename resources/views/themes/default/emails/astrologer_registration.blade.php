@extends(themeView().'emails.layout')
@section('content')
  <div class="">
      <table style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <tr>
              <td>
                  <h2 style="text-align: center; color: #2c3e50;">Welcome to the {{config('app.name')}} Family!</h2>
                  <p style="font-size: 16px; line-height: 1.6; color: #555;">Hi {{$astrologer->display_name}},</p>
                  <p style="font-size: 16px; line-height: 1.6; color: #555;">We are thrilled to welcome you to the {{config('app.name')}} family! Your registration has been successfully completed.</p>
                  <h3 style="color: #2980b9;">Astrologer Details:</h3>
                  <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                      <tr>
                          <th>Mobile No.</th>
                          <td>{{$astrologer->phone}}</td>
                      </tr>
                      <tr>
                          <th>Email</th>
                          <td>{{$astrologer->email}}</td>
                      </tr>
                      <tr>
                          <th>Registration Date</th>
                          <td>{{date('d/m/Y', strtotime($astrologer->created_at))}}</td>
                      </tr>
                  </table>
                  <p style="font-size: 16px; line-height: 1.6; color: #555;">To get started, please log in to our portal using the credentials above.</p>
              </td>
          </tr>
      </table>
  </div>
@endsection
