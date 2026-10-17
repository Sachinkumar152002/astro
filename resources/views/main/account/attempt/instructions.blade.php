@extends('layouts.assesment')

@section('content')

<section class="middle-section">
    <div class="container">

        <div class="row">
            <h1 class="text-primary text-center">{{$module->name}} Assessment</h1>
            <div class="col-md-6 col-md-offset-3">
            <table class="table table-striped text-center">
                    <tr>
                        <th>Total questions</th><td>:</td><td>{{$module->questions}}</td>
                    </tr>
                    <tr>
                        <th>Passing marks Required</th><td>:</td><td>{{$module->passing_marks}}</td>
                    </tr>
                    <tr>
                        <th>Negative mark per question</th><td>:</td><td>{{$module->minus_marks}}</td>
                    </tr>
            </table>
            </div>
            <div class="clearfix"></div>
            <h3 class="text-center">General Instructions</h3>
            <h4 class="text-center">Please read the following instructions carefully</h4>
            <ul class="list-group">
              <li class="list-group-item">The candidate must 'mouse-click' the alternative he/she thinks is correct. The said alternative will be highlighted and shall be treated as the answer given by the candidate for the question. In case the candidate does not wish to attempt the question then he/she should not click on ANY OPTION for that question.</li>
              <li class="list-group-item">The questions may be answered in any order within the given time.</li>
              <li class="list-group-item">The question number of attempted and un-attempted questions is shown on the right hand side of the screen (navigation panel). You can click on any of the question numbers to view the question, in order to attempt/ revise your answer to the question.
                </li>
                <li class="list-group-item">To move back and forth between questions, candidates can use the "Next"/ "Previous" buttons or can click on the question number in the navigation panel on the left.</li>
                <li class="list-group-item">The answers are saved whenever the candidate navigates, i.e. by clicking on 'Next' or "Previous'' or by clicking a question number in the navigation panel. The candidate can change the response any time before he/she submits the question paper or before the exam time is over.
</li>
                <li class="list-group-item">After the expiry of time, the candidate will not be able to attempt any question. The answer of the candidate will be saved automatically by the online examination system even if he/she has not clicked the "Submit" button.
</li>

            </ul>

            <div class="text-center">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Start Your Assessment</button>
            </div>
        </div>
    </div>
</section>
<div  class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title text-success ">Are your sure want to continue?</h1>
      </div>
      <div class="modal-footer text-center">
        <a href="{{route('account.assessment.attempt.start',[$attempt, $course_id, $module_id])}}" class="btn btn-success">Yes Sure!</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')

@endpush