@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$lawyer->display_name}},</p>
    <p>A new case has been submitted on Advolive in your practice area:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Case Title :</th>
            <td>{{$enquiry->case_title}}</td>
        </tr>
        @if($enquiry->case_type == 'old')
          <tr>
              <th>Case ID :</th>
              <td>{{$enquiry->case_id}}</td>
          </tr>
        @endif
        <tr>
            <th>Category :</th>
            <td>{{$enquiry->case_category}}</td>
        </tr>
        <tr>
            <th>Submitted By :</th>
            <td>{{$enquiry->name}}</td>
        </tr>
        <tr>
            <th>Location :</th>
            <td>{{$enquiry->city}} & {{$enquiry->state}}</td>
        </tr>
        <tr>
            <th>Description :</th>
            <td>{{$enquiry->case_description}}</td>
        </tr>
        @if($enquiry->document)
          <tr>
            <th>Document :</th>
            <td><a href="{{url($enquiry->document)}}">Document</a></td>
          </tr>
        @endif
        <tr>
            <th>Submitted On :</th>
            <td>{{date('d-m-Y h:i a', strtotime($enquiry->created_at))}}</td>
        </tr>
    </table>
    <a href="{{route('quatation.form', $enquiry->id)}}">Click here to review case details and submit your quote.</a>
    <p>Respond early to increase your chances of being hired.</p>
  </div>
@endsection
