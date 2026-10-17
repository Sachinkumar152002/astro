@extends(themeView().'astrologers.account.explore.layout')

@section('title', 'IIAG | Assignment Question')
@section('keywords', '')
@section('description','')

@section('content')

<section class="mt-3 mb-5">
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <div class="card sticky-sidebar">
        <div class="card-header border-bottom border-dark float-shadow-2 py-3" id="headingOne">
          <h3 class="fs-6 text-white mb-0 fw-bold">{{$module->name}}</h3>
        </div>
        <div class="card-body">
          {!! $module->details !!}
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card container bg-light py-1 pb-2">
        <div class=" bg-white task_box  mt-4 pt-2">
          <div class="d-flex align-items-center justify-content-between border-bottom border-warning  border-2 pb-2 px-2 radius">
            <div class="border-end border-warning border-2">
              <h6 class="pe-3 pb-0 mb-0 fs-7"> Total Questions: <strong>{{$module->assignmentQuestions->count()}}</strong> </h6>
            </div>
            <div class="border-end border-warning border-2">
              <h6 class="pe-3 pb-0 mb-0 fs-7">Marks: <strong> {{$module->minus_marks}}/Each</strong></h6>
            </div>
            <div class="border-end border-warning border-2">
              @php
                $toalMarks = $module->minus_marks*$module->assignmentQuestions->count();
                $passingMarks = ceil(($toalMarks/100)*40);
              @endphp
              <h6 class="pe-3 pb-0 mb-0 fs-7"> Min. Marks: <strong> {{$passingMarks}}</strong></h6>
            </div>
            <div class="">
              <h6 class="pe-3 pb-0 mb-0 fs-7"> Total Marks: <strong>{{$toalMarks}}</strong> </h6>
            </div>
          </div>
          <div class="bg-light p-4">
            <form method="POST" action="{{route('astrologer.course.assignment.submit', [$course->id, 'assignment', $module->id])}}" onsubmit="return submitAssignmentQuestion(this)">
            <input type="hidden" name="question_id" id="question_id" value="">
            @php
              $allQuestionsId = [];
            @endphp
            @foreach($module->content as $contentKey => $content )
              @foreach($content->assignmentQuestions as $questionKey => $question )
              @php
                array_push($allQuestionsId, $question->id);
              @endphp
              @endforeach
            @endforeach

            <div class="q-step" data-tab="">
              <div class="question-box">
                <div class="question">
                  <p class="fw-bold" id="question"></p>
                </div>
                <ul class="options">
                  <li>
                    <input type="radio" class="question-option" id="option_1" name="choosed_option" value="1">
                    <label class="question-option" for="option_1"></label>
                  </li>
                  <li>
                    <input type="radio" class="question-option" id="option_2" name="choosed_option" value="2">
                    <label class="question-option" for="option_2"></label>
                  </li>
                  <li>
                    <input type="radio" class="question-option" id="option_3" name="choosed_option" value="3">
                    <label class="question-option" for="option_3"></label>
                  </li>
                  <li>
                    <input type="radio" class="question-option" id="option_4" name="choosed_option" value="4">
                    <label class="question-option" for="option_4"></label>
                  </li>
                </ul>
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between">
              <button type="button" class="btn-secondary btn-h px-5 fs-6 rounded-1 py-1" id="prev_btn" style="display:none;">Pevious</button>
              <button type="submit" class="btn-secondary btn-h px-5 fs-6 rounded-1 py-1" id="next_btn">Next</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

@endsection
@push('scripts')
<script type="text/javascript">
  // var currentTab = 0;
  // var showNextQuestion = false;
  // function showTab(n) {
  //     $(".q-step").hide();
  //     $(".q-step").eq(n).show();
  //     if (n === 0) {
  //         $("#prev_btn").hide();
  //     } else {
  //         $("#prev_btn").show();
  //     }
  //     if (n === $(".q-step").length - 1) {
  //         $("#next_btn").text("Submit");
  //         $("#next_btn").attr('type', 'submit');
  //     } else {
  //         $("#next_btn").text("Next");
  //         $("#next_btn").attr('type', 'button');
  //     }
  // }
  // showTab(currentTab);
  // $("#next_btn").click(function(){
  //   if (currentTab < $(".q-step").length - 1){
  //     if(!showNextQuestion){
  //       Swal.fire({
  //           icon: 'error',
  //           title: "Please select one option.",
  //           confirmButtonText: 'Okay'
  //       });
  //       return 0;
  //     }
  //     currentTab++;
  //     showTab(currentTab);
  //     showNextQuestion = false;
  //   }else{
  //       alert("Form Submitted!");
  //   }
  // });
  // $("#prev_btn").click(function(){
  //     if(currentTab > 0){
  //       currentTab--;
  //       showTab(currentTab);
  //       showNextQuestion = true;
  //     }
  // });
  // $(".question-option").click(function(){
  //   showNextQuestion = true;
  // });
</script>
<script type="text/javascript">
var course_id = @json($course->id);
var module_id = @json($module->id);
var allQuestionsId = @json($allQuestionsId);
  function fetchAssignmentQuestion(index){
    if(index>0){
      $('#prev_btn').show();
      $('#prev_btn').data('index', index-1);
    }else{
      $('#prev_btn').hide();
      $('#prev_btn').removeAttr('data-index');
    }
    let question_id = allQuestionsId[index]
    let url = @json(asset('/astrologer'));
    url += `/course/${course_id}/assignment/${module_id}/question/${question_id}`;
    $.ajax({
        url: url,
        method: "POST",
        data: {},
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          let question = response.question;
          $('#question').html(`${allQuestionsId.indexOf(question_id)+1}. ${question.question}`)
          $('label[for=option_1]').html(question.option_a)
          $('label[for=option_2]').html(question.option_b)
          $('label[for=option_3]').html(question.option_c)
          $('label[for=option_4]').html(question.option_d)
          $('#option_1').prop('checked', false);
          $('#option_2').prop('checked', false);
          $('#option_3').prop('checked', false);
          $('#option_4').prop('checked', false);
          $('#question_id').val(question_id);
          if(response.answer){
            $('#option_'+response.answer).prop('checked', true);
          }
          $('button[type=submit]').removeAttr('disabled');
        },
        error: function(xhr) {
          console.log(xhr)
        }
    });
  }
  $(document).ready(function (){
    fetchAssignmentQuestion(0);
  })
  function submitAssignmentQuestion(form){
    var _f = $(form);
    let url = _f.attr('action');
    let button = _f.find('button[type=submit]');
    button.attr('disabled', 'disabled');
    $.ajax({
        url: url,
        method: "POST",
        data: _f.serialize(),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          console.log(response);
          let questionId = parseInt($('#question_id').val());
          let questionIdIndex = allQuestionsId.indexOf(questionId);
          console.log(questionIdIndex)
          if(questionIdIndex == -1){
            return false;
          }
          if(allQuestionsId.length-2 == questionIdIndex){ //second last question
            $('#next_btn').text('Submit');
          }else if(allQuestionsId.length-1 == questionIdIndex){ //last question
            Swal.fire({
                icon: 'success',
                title: "Complete. wait for result.",
                confirmButtonText: 'Okay'
            }).then((result) => {
              window.location.href = @json(route('astrologer.course.assignment', [$course->slug, 'assignment']))
            });
          }
          fetchAssignmentQuestion((questionIdIndex+1));
        },
        error: function(xhr) {
          $('button[type=submit]').removeAttr('disabled');
          let error = xhr.responseJSON;
          console.log(error)
          if(!error.success && error.msg == 'answer required'){
            Swal.fire({
                icon: 'error',
                title: "Please select one option.",
                confirmButtonText: 'Okay'
            });
          }
        }
    });
    return false;
  }

  $("#prev_btn").click(function(){
    let previousQuestionIdIndex = ($(this).data('index'));
    fetchAssignmentQuestion(previousQuestionIdIndex);
  });
</script>
@endpush
