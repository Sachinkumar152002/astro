
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astro IIAG Assignment Result</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #fffff;
        }
        .main-box{
          padding:0px 20px;
          /* border:2px solid black; */
        }
        ul li {
          list-style: none;
          font-size: 15px;
        }

      .answer-p p{
        margin: 0 0px;
        padding: 0;
        font-size: 15px;
      }
      .answer-p {
        padding: 0 0 10px 0px;
        margin-top: -10px;
      }
      .topic{
        margin: 0;
        padding: 0;
      }
  .dis{
    margin-bottom: 10px;


  }
  .question{
    font-size: 15px;
    font-weight: bold;

  }
.f-bold{
  font-weight: bold;
}
.marks-t{
  border-collapse: collapse;
}

.marks-t, .marks-t td, .marks-t th {
  border: 1px solid;
}

    </style>
</head>

<body>
<div class="">
  <div style="display:flex;">
    <img src="{{logo()}}" alt="{{config('app.name')}}" style=" width: 150px;">

  </div>
<div class="">
  <p style="font-weight:bold; margin-left:13px; font-size:25px;">Quizz Result</p>
</div>
</div>
<div class="main-box" >

  <table style="width:100%;padding-top:20px;">

<h4 class="topic">{{$module->name}}</h4>
          {!! $module->description !!}


          <table style="width:100%; margin-top:10px;" class="marks-t">
            <tr>
              @php
                $toalMarks = $module->minus_marks*$module->quizzQuestions->count();
                $passingMarks = ceil(($toalMarks/100)*40);
              @endphp
              <th style="text-align:left"> Max. Marks: {{$toalMarks}}</th>
              <th style="text-align:left"> Min. Marks: {{$passingMarks}}</th>
              <th style="text-align:left"> Obt. Marks: <span id="obtained_marks">00</span></th>
            </tr>

          </table>
        </td>
      </tr>
      @php
        $sn = 1;
        $correctAnswerCount = 0;
      @endphp
      @foreach($module->content as $contentKey => $content )
        @foreach($content->quizzQuestions as $questionKey => $question )
      <tr>
        <td class="question"> <p style="margin-top:20px;"><span>{{$sn}}. </span>{{$question->question}}</p> </td>
      </tr>
      <tr>
         <td >
           <ul >
            <li><span>A. </span>{{$question->option_a}}</li>
            <li><span>B. </span>{{$question->option_b}}</li>
            <li><span>C. </span>{{$question->option_c}}</li>
            <li><span>D. </span>{{$question->option_d}}</li>
          </ul>
          <div class="answer-p">
            @php
              $attemptedAnswer = assignmentAnswer($user->id, $question->id);
              if($attemptedAnswer && $attemptedAnswer->answer == $question->answer){
                $correctAnswerCount++;
              }
            @endphp
            <p class="f-bold">Selected Option: <span>{{correctOption($attemptedAnswer?$attemptedAnswer->answer:0)}}</span> | Correct Answer: <span>{{correctOption($question->answer)}}</span></p>
            <p></p>
          </div>
        </td>
      </tr>
      @php
        $sn++;
      @endphp
      @endforeach
    @endforeach
      @php
        $obtainedMarks = $module->minus_marks*$correctAnswerCount;
      @endphp
</table>

</div>
 </div>
 <script type="text/javascript">
  document.getElementById('obtained_marks').innerHTML = @json($obtainedMarks);
 </script>
</body>
</html>
