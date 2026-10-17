@extends(themeView().'astrologers.account.explore.layout')

@section('title', 'IIAG | Quizz')
@section('keywords', '')
@section('description','')

@section('content')

<section class="mt-3 mb-5">
<div class="container">
<div class="row justify-content-between">
    <div class="col-12">
      <div class="row my-2 dashbord">
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-journals text-white"></i> <p class="text-white fw-bold">Course Quizz</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-clock-history text-white"></i> <p class="text-white fw-bold">Pendind Review</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-pass text-white"></i> <p class="text-white fw-bold"><span id="passed-count">{{$passed}}</span> Passed</p>
            </div></a>
          </div>
          <div class="col-lg-3 mb-2 mb-md-0">
          <a href="">  <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-backspace text-white"></i> <p class="text-white fw-bold"><span id="failed-count">{{$failed}}</span> Faild</p>
            </div></a>
          </div>
        </div>
<div class="card bg-light p-3 my-4">
  <table class="table table-bordered radius">
  <thead>
    <tr>
      <th>#</th>
      <th>Topic</th>
      <th>Question</th>
      <th>Posted</th>
      <th>Updated</th>
      <th>Result</th>
      <th>Certificate</th>
      <th>Action</th>
    </tr>
      </thead>
      @php
        $hasQuizz = false;
      @endphp
      @foreach($course->modules as $key => $module)
      @php
        $questionsCount = $module->quizzQuestions->count();
      @endphp
        @if($questionsCount)
          @php
            $hasQuizz = true;
          @endphp
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$module->name}}</td>
            <td>{{$questionsCount}}</td>
            <td>{{date('d-m-Y', strtotime($module->created_at))}}</td>
            <td>{{$module->lastUpdatedQuizzQuestion ? date('d-m-Y', strtotime($module->lastUpdatedQuizzQuestion->updated_at)) : ''}}</td>
            <td>
              @if($module->quizzAnswer->count() == $questionsCount)
              <a href="{{route('astrologer.course.result', [$course->slug, 'quizz', $module->id])}}">
                result
              </a>
              @else
              <a href="javascript:void(0)">
                no attempted
              </a>
              @endif
            </td>
            <td>
              @if($module->quizzAnswer->count() == $questionsCount)
              <a href="{{route('astrologer.course.certificate', [$course->slug, $module->id])}}">
                download
              </a>
              @else
              <a href="javascript:void(0)">
                pending
              </a>
              @endif
            </td>
            <td>
              @if(($module->quizzAnswer->count() == $questionsCount))
                <button type="button" name="button" onclick="attempted()" class="btn btn-secondary btn-h">Done</button>
              @elseif(!$module->hasPassed)
                <button type="button" name="button" onclick="studyFirst()" class="btn btn-secondary btn-h">View</button>
              @else
                <a href="{{route('astrologer.course.assignment.questions', [$course->slug, 'quizz', $module->id])}}"> <button type="button" name="button" class="btn btn-secondary btn-h">View</button></a>
              @endif
            </td>
          </tr>
        @endif
      @endforeach
      @if(!$hasQuizz)
        <tr>
          <td colspan="7">No Quizz</td>
        </tr>
      @endif
  </table>
</div>
</div>
    </div>
</section>

@endsection
@push('scripts')
<script type="text/javascript">
function studyFirst(){
  Swal.fire({
      icon: 'error',
      title: "Watch all content first of this Chapter.",
      confirmButtonText: 'Okay'
  });
}
function attempted(){
  Swal.fire({
      icon: 'success',
      title: "You already attemped this quizz.",
      confirmButtonText: 'Okay'
  });
}
</script>
@endpush
