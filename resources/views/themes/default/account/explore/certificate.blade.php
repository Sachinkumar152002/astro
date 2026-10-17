
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
    text-align: center;
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
  @php
  $lmsCourse = $course->lms($user->id, $course->id);
  @endphp

<div style="width:780px; margin:auto">

    <div style="width:800px; height:550px; padding:10px; text-align:center; border: 10px solid #F7471B;background:#ffeecc;">
     <div style="width:750px; height:500px; padding:20px; text-align:center; border: 5px solid #F7471B">
       <div class="">
         @php
           $logoUrl = explode('.in', logo(100, 100));
         @endphp
         <img src=".{{$logoUrl[1]}}" alt="{{config('app.name')}}" style=" width:130px;">
         <!-- <p style="padding-bottom:0px;">(Registered in Haryana Govt. under Indian Trusts Act, 1882)</p> -->
       </div>
       <br>
       <span style="font-size:50px; font-weight:bold">Certificate of Participation</span>
       <br>
        <br>
       <p style="font-size:25px;margin:0;padding:0;"><i>This is to certify that</i> &nbsp;&nbsp;</p><br>
       <p class="name" style="font-size:30px;margin:0;padding:0">
          @if($user->gender == 1)
            <b>MR. {{isset($user) && $user ? strtoupper($user->name) : 'UNKNOWN'}}</b>
          @else
            <b>MISS. {{isset($user) && $user ? strtoupper($user->name) : 'UNKNOWN'}}</b>
          @endif
        </p>
        <br>
       <span style="font-size:25px;"><i>has participated in</i></span> <br/><br/>
       <span style="font-size:30px">{{strtoupper($course->name)}}</span> <br/><br/>
       <span style="font-size:20px">during the month of <b>{{date('d M Y', strtotime($lmsCourse->created_at))}} - {{date('d M Y', strtotime($lmsCourse->expire_date))}}</b></span> <br/><br/>
       {{--<span style="font-size:25px"><i>Awarded Date</i></span><br>
       <p style="font-size:20px;text-align:center;margin:0;padding:0;"><i>{{date('d M Y', strtotime($lmsCourse->expire_date))}}</i></p>--}}
         @php
         $instructorName = '';
         $instructorImg = ['', ''];
         if($course->instructor){
           $instructorName = $course->instructor->display_name;
           if($course->instructor->signature){
             $instructorImg = explode('.in', $course->instructor->signature(100, 100));
           }
         }
         @endphp
         <table style="margin-top:40px;float:right">
         @if($instructorImg[1])
             <tr>
               <td style="text-align:center"><span><b>{{$instructorName}}</b></td>
             </tr>
             <tr>
               <td style="width:200px;float:right;border:0;border-bottom:1px solid #000;"></td>
             </tr>
             <tr>
               <td style="text-align:center"><span><b>Instructor Sign.</b></td>
             </tr>
             <tr style="text-align:center;margin-top:2px;">
               <img src=".{{$instructorImg[1]}}" alt="Director Sign" style=" width:100%; height:50px;">
             </tr>
         @endif
       </table>
       @php
         $directorSign = explode('.in', directorSign(100, 100));
       @endphp
       <table style="margin-top:20px;float:right">
       @if($directorSign[1])
       <tr style="text-align:center;margin-top:2px;">
         <img src=".{{$directorSign[1]}}" alt="Director Sign" style=" width:100%; height:50px;">
       </tr>
           <tr>
             <td style="text-align:center"><span><b>{{director()}}</b></td>
          </tr>
          <tr>
            <td style="width:200px;float:left;border:0;border-bottom:1px solid #000;"></td>
          </tr>
          <tr>
            <td style="text-align:center"><span><b>Director Sign.</b></td>
          </tr>

      @endif
    </table>
</div>
</div>
</div>


</body>
</html>
