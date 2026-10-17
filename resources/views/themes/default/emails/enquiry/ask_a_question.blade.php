@extends(themeView().'emails.layout')
@section('content')
  <div class="">


    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Name :</th>
            <td>{{$enquiry->name}}</td>
        </tr>
        <tr>
            <th>Phone :</th>
            <td>{{$enquiry->phone}}</td>
        </tr>
        <tr>
            <th>Email :</th>
            <td>{{$enquiry->email}}</td>
        </tr>
        <tr>
            <th>Country :</th>
            <td>{{$enquiry->country}}</td>
        </tr>
        <tr>
            <th>State :</th>
            <td>{{$enquiry->state}}</td>
        </tr>
        <tr>
            <th>City :</th>
            <td>{{$enquiry->city}}</td>
        </tr>
        <tr>
            <th>Category :</th>
            <td>{{$enquiry->case_category}}</td>
        </tr>
        <tr>
            <th>Question :</th>
            <td>{{$enquiry->question}}</td>
        </tr>
        @if($enquiry->description)
        <tr>
            <th>Description :</th>
            <td>{{$enquiry->description}}</td>
        </tr>
        @endif
    </table>
  </div>
@endsection
