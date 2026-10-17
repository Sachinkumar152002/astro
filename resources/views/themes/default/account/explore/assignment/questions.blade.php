@extends(themeView().'account.explore.layout')

@section('title', 'IIAG | Assignment Question')
@section('keywords', '')
@section('description','')

@section('content')

<section class="mt-3 mb-5">
<div class="container">
  <div class="row">
    <div class="col-md-3 d-md-block d-none">
      <div class="card sticky-sidebar">
        <div class="card-header border-bottom border-dark float-shadow-2 py-3" id="headingOne">
          <h3 class="fs-6 text-white mb-0 fw-bold">{{$module->name}}</h3>
        </div>
        <div class="card-body">
        <div class="side-scrollbar max-scroll">
            {!! $module->details !!}
        </div>
        </div>
      </div>
    </div>
    <!-- phone-sidebar-start -->
    <div class="col-md-3 d-md-none d-block pt-3 mt-md-0 mb-0 pb-0">

      <div class="offcanvas offcanvas-start p-canvas" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

        <div class="offcanvas-body">
          <div class="p-2">
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="btn-bg p-3 radius">
            <h5 class="h5 ms-2 mb-0">{{$module->name}}</h5>
          </div>
          <div class="media border-bottom p-2 blog-list">

            <div class="side-scrollbar max-scroll">

            <div class="hh">
              {!! $module->details !!}
            </div>

        </div>
          </div>
        </div>


        </div>
      </div>

  <!-- phone-sidebar-end -->
    <div class="col-md-9 mt-0 mt-md-0">
      <a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  <div class="d-md-none d-block v-btn-box">
      <button type="button" name="button text-white" class="arrow-b btn verticalButton">Chapter</button>
  </div>
    </a>
      <div class="card container bg-light py-1 pb-2">
        <div class=" bg-white task_box  mt-4 pt-2">
          <div class="d-flex align-items-center justify-content-between border-bottom border-warning  border-2 pb-2 px-2 radius">
            <div class="border-end border-warning border-2 Chapter-h">
              <h6 class="pe-1 pe-md-3 pb-0 mb-0 fs-7"> Total Questions: <strong>{{$module->assignmentQuestions->count()}}</strong> </h6>
            </div>
            <div class="border-end border-warning border-2 Chapter-h ms-2">
              <h6 class="pe-1 pe-md-3 pb-0 mb-0 fs-7">Marks: <strong> {{$module->minus_marks}}/Each</strong></h6>
            </div>
            <div class="border-end border-warning border-2 Chapter-h ms-2">
              @php
                $toalMarks = $module->minus_marks*$module->assignmentQuestions->count();
                $passingMarks = ceil(($toalMarks/100)*40);
              @endphp
              <h6 class="pe-1 pe-md-3 pb-0 mb-0 fs-7"> Min. Marks: <strong> {{$passingMarks}}</strong></h6>
            </div>
            <div class="Chapter-h ms-2">
              <h6 class="pe-1 pe-md-3 pb-0 mb-0 fs-7"> Total Marks: <strong>{{$toalMarks}}</strong> </h6>
            </div>
          </div>
          <div class="bg-light p-4">
            <form method="POST" action="{{route('user.course.assignment.submit', [$course->id, 'assignment', $module->id])}}" onsubmit="return submitAssignmentQuestion(this)">
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
              <button type="button" class="btn-secondary btn-h px-3 px-md-5 fs-6 rounded-1 py-1" id="prev_btn" style="display:none;">Previous</button>
              <button type="submit" class="btn-secondary btn-h px-3 px-md-5 ms-2 ms-md-0 fs-6 rounded-1 py-1" id="next_btn">Next</button>
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
    let url = @json(asset('/account'));
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
          console.log(xhr);
          toastr.error('Error in fetching question.');
          setTimeout(function(){
            window.location.href = @json(route('user.course.assignment', [$course->slug, 'assignment']));
          }, 2000);
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
        success: function(response){
          let questionId = parseInt($('#question_id').val());
          let questionIdIndex = allQuestionsId.indexOf(questionId);
          if(questionIdIndex == -1){
            return false;
          }
          if(allQuestionsId.length-2 == questionIdIndex){ //second last question
            $('#next_btn').text('Submit');
            fetchAssignmentQuestion((questionIdIndex+1));
          }else if(allQuestionsId.length-1 == questionIdIndex){ //last question
            toastr.success('You have successfully completed the assignment. Please wait for result.');
            setTimeout(function(){
              window.location.href = @json(route('user.course.assignment', [$course->slug, 'quizz']));
            }, 2000);
          }
        },
        error: function(xhr) {
          console.log(xhr);
          $('button[type=submit]').removeAttr('disabled');
          let error = xhr.responseJSON;
          if(!error.success && error.msg == 'answer required'){
            toastr.warning('Please select one option');
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
