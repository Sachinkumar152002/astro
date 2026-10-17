
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIAG | Certificate</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
    .signature, .title {
    float:left;
      border-top: 1px solid #000;
      width: 200px;
      text-align: center;
    }
    .name{
    border-bottom: 2px dotted;
    }
    .t-center{
      text-align:center;
    }
    .sec-table tr td{
      width:100%;
      text-align: center;
    }
    </style>
</head>

<body>
<!-- <div style="width:800px;margin:auto;text-align:center;background:#ffeecc;border: 10px solid #F7471B;background:#ffeecc;">
  <div class="" style="width:750px;border: 5px solid #F7471B;padding:10px; margin:10px;">
    <table style="width:100%;" class="sec-table">
      <tr>
        <td style="text-align:center; vertical-align:middle; padding:20px;">
          <img src="{{logo()}}" alt="{{config('app.name')}}" style="width: 350px;">
          <p style="padding-bottom:0px;">(Registered in Haryana Govt. under Indian Trusts Act, 1882)</p>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:50px; font-weight:bold;">Certificate of Completion</span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:25px;"><i>This is to certify that</i> &nbsp;&nbsp;</span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span class="name" style="font-size:30px;"><b>MR. RUPESH CHAUHAN</b></span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:25px;"><i>has participated in</i></span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:30px;">VEDIC ASTROLOGY COURSE</span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:20px;">during the month of <b>July 2024 - December 2024</b></span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <span style="font-size:25px;"><i>05-Jan-2025</i></span>
        </td>
      </tr>
      <tr>
        <td style="text-align:center; vertical-align:middle;">
          <table style="margin-top:40px; float:left; width:45%; text-align:center;">
            <tr>
              <td><span><b>Sonu Sharma</b></span></td>
            </tr>
            <tr>
              <td style="width:200px; border-bottom:1px solid #000;"></td>
            </tr>
            <tr>
              <td><span><b>Director Sign.</b></span></td>
            </tr>
          </table>
        </td>
        <td style="text-align:center; vertical-align:middle;">
          <table style="margin-top:40px; float:right; width:45%; text-align:center;">
            <tr>
              <td><span><b>Sonu Sharma</b></span></td>
            </tr>
            <tr>
              <td style="width:200px; border-bottom:1px solid #000;"></td>
            </tr>
            <tr>
              <td><span><b>Instructor Sign.</b></span></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
  </div> -->

<div style="width:780px; margin:auto">

    <div style="width:800px; height:550px; padding:10px; text-align:center; border: 10px solid #F7471B;background:#ffeecc;">
     <div style="width:750px; height:500px; padding:20px; text-align:center; border: 5px solid #F7471B">
       <div class="">
         <img src="{{logo()}}" alt="{{config('app.name')}}" style=" width: 350px;">
         <p style="padding-bottom:0px;">(Registered in Haryana Govt. under Indian Trusts Act, 1882)</p>
       </div>

       <span style="font-size:50px; font-weight:bold">Certificate of Participation</span>
       <br><br>
       <span style="font-size:25px"><i>This is to certify that</i> &nbsp;&nbsp;</span>
       <span class="name" style="font-size:30px">
          @if($user->gender == 1)
            <b>MR. {{isset($user) && $user ? strtoupper($user->display_name) : 'UNKNOWN'}}</b>
          @else
            <b>MISS. {{isset($user) && $user ? strtoupper($user->display_name) : 'UNKNOWN'}}</b>
          @endif
        </span>
       <br><br>
       <span style="font-size:25px"><i>has participated in</i></span> <br/><br/>
       <span style="font-size:30px">{{isset($course) && $course ? strtoupper($course->name) : 'COURSE NAME'}}</span> <br/><br/>
       <span style="font-size:20px">during the month of <b>{{date('M Y', strtotime($order->created_at)).' - '.date('M Y', strtotime($module->hasPassed->created_at))}}</b></span> <br/><br/>
       <span style="font-size:25px"><i>Awarded Date</i></span><br>
       <span style="font-size:25px"><i>{{date('d M Y', strtotime($module->hasPassed->created_at))}}</i></span>
    <table style="margin-top:40px;float:left">
    <tr>
   <td style="text-align:center"><span><b>Sonu Sharma</b></td>
   </tr>
  <tr>
 <td style="width:200px;float:left;border:0;border-bottom:1px solid #000;"></td>
</tr>
<tr>
<td style="text-align:center"><span><b>Director Sign.</b></td>
</tr>
</table>
<table style="margin-top:40px;float:right">
<tr>
<td style="text-align:center"><span><b>{{$course->instructor ? $course->instructor->firstname.' '.$course->instructor->lastname : '-'}}</b></td>
</tr>
<tr>
<td style="width:200px;float:right;border:0;border-bottom:1px solid #000;"></td>
</tr>
<tr>
<td style="text-align:center"><span><b>Instructor Sign.</b></td>
</tr>
</table>
</div>
</div>
</div>


</body>
</html>
