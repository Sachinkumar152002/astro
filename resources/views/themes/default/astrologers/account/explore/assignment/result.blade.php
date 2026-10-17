
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

<div class="main-box">

  <table style="width:100%;">

      <tr>
          <td style="border-bottom:1px solid #f1f1f1;" colspan="3"><img src="{{logo()}}" alt="{{config('app.name')}}" style=" width: 350px;"></td>
          <td colspan="3" style="border-bottom:1px solid #f1f1f1; font-size:20px; font-weight:bold;">Assignment Result</td>
      </tr>

      <tr>
        <td colspan="6">
          <h2 class="topic">{{$module->name}}</h2>
          {!! $module->description !!}
        </td>
      </tr>
      <tr>
        <td>
          <table style="width:100%;" class="marks-t">
            <tr>
              @php
                $toalMarks = $module->minus_marks*$module->assignmentQuestions->count();
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
        @foreach($content->assignmentQuestions as $questionKey => $question )
      <tr>
        <td class="question"><span>{{$sn}}. </span>{{$question->question}}</td>
      </tr>
      <tr>
         <td>
           <ul>
            <li><span>A. </span>{{$question->option_a}}</li>
            <li><span>B. </span>{{$question->option_b}}</li>
            <li><span>C. </span>{{$question->option_c}}</li>
            <li><span>D. </span>{{$question->option_d}}</li>
          </ul>
          <div class="answer-p">
            @php
              if($question->userAssignmentAnswer->answer == $question->answer){
                $correctAnswerCount++;
              }
            @endphp
            <p class="f-bold">Selected Option: <span>{{correctOption($question->userAssignmentAnswer->answer)}}</span> | Correct Answer: <span>{{correctOption($question->answer)}}</span></p>
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
