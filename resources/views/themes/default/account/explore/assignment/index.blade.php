@extends(themeView().'account.explore.layout')

@section('title', 'IIAG | Assignment')
@section('keywords', '')
@section('description','')

@section('content')

<section class="mt-3 mb-5">
<div class="container">
<div class="row justify-content-between">
    <div class="col-12">
      <div class="row my-2 dashbord">
        <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3">
            <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-journals text-white"></i> <p class="text-white fw-bold text-center min-hhh">Course Assignment</p>
            </div></a>
          </div>
           <div class="col-md-3 col-6 mb-2 mb-md-0 ps-1 ps-md-3">
              <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-clock-history text-white"></i> <p class="text-white fw-bold text-center min-hhh">Pending Review</p>
            </div></a>
          </div>
           <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3">
             <a href=""><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-pass text-white"></i> <p class="text-white fw-bold text-cente min-hhh"><span id="passed-count">{{$passed}}</span> Passed</p>
            </div></a>
          </div>
         <div class="col-md-3 col-6 mb-2 mb-md-0 ps-1 ps-md-3">          <a href="">  <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
            <i class="fs-2 bi bi-backspace text-white"></i> <p class="text-white fw-bold text-cente min-hhh"><span id="failed-count">{{$failed}}</span> Faild</p>
            </div></a>
          </div>
        </div>
<div class="card bg-light p-3 my-4 table-responsive">
  <table class="table table-bordered radius contable">
  <thead>
    <tr>
      <th>#</th>
      <th>Topic</th>
      <th>Question</th>
      <th>Posted</th>
      <th>Updated</th>
      <th>Result</th>
      <!-- <th>Certificate</th> -->
      <th>Action</th>
    </tr>
      </thead>
      @php
        $hasAssignment = false;
      @endphp
      @foreach($course->modules as $key => $module)
      @php
        $questionsCount = $module->assignmentQuestions->count();
      @endphp
        @if($questionsCount)
          @php
            $hasAssignment = true;
          @endphp
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$module->name}}</td>
            <td>{{$questionsCount}}</td>
            <td>{{date('d-m-Y', strtotime($module->created_at))}}</td>
            <td>{{$module->lastUpdatedAssignmentQuestion ? date('d-m-Y', strtotime($module->lastUpdatedAssignmentQuestion->updated_at)) : ''}}</td>
            <td class="pointer fw-bold">
              @if($module->assignmentAnswer->count() == $questionsCount)
              <a href="{{route('user.course.result', [$course->slug, 'assignment', $module->id])}}" class="text-blue" target="_blank">
                Result
              </a>
              @else
              <a href="javascript:void(0)">
                Not Attempted
              </a>
              @endif
            </td>
            {{--
            <td>
              @if($module->assignmentAnswer->count() == $questionsCount)
              <a href="{{route('user.course.certificate', [$course->slug, $module->id])}}">
                Download
              </a>
              @else
              <a href="javascript:void(0)">
                pending
              </a>
              @endif
            </td>
            --}}
            <td> <a href="{{route('user.course.assignment.questions', [$course->slug, 'assignment', $module->id])}}"> <button type="button" name="button" class="btn btn-secondary btn-h">View</button></a> </td>
          </tr>
        @endif
      @endforeach
      @if(!$hasAssignment)
        <tr>
          <td colspan="7" class="text-center">
            <img src="/assets/images/assig.webp" alt="No Assignment Found" class="nodata">
         <h5 class="mt-3 text-dark"> No Assignment Found!</h5>
         <p class="text-secondary"> Course assignments will be displayed here.</p>
          </td>
        </tr>
      @endif
  </table>
</div>
</div>
    </div>
</section>

@endsection
