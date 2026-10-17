@extends('layouts.assesment')

@section('content')

<section class="middle-section">
    <div class="container">

        <div class="row">
            <h1 class="text-primary text-center">{{$module->name}} Assessment Scores</h1>
            <div class="col-md-6 col-md-offset-3">
            <table class="table table-striped text-center">
                    <tr> <th>Attempt</th><td>:</td><td>{{$attemptCount}}</td> </tr>
                    <tr> <th>Total Questions</th><td>:</td><td>{{$totalQuestion}}</td> </tr>
                    <tr> <th>Attempt</th><td>:</td><td>{{$totalAttempt}}</td> </tr>
                    <tr> <th>Skipped</th><td>:</td><td>{{$totalSkipped}}</td> </tr>
                    <tr> <th>Not Answered</th><td>:</td><td>{{$totalNotAnswer}}</td> </tr>
                    <tr> <th>Correct</th><td>:</td><td>{{$totalScore}}</td> </tr>
                    <tr> <th>Percentage</th><td>:</td><td>{{sprintf('%.2f',$scorePerCentage)}}%</td> </tr>
            </table>
            </div>
            <div class="clearfix"></div>
            <div class="text-center">
                   <button class="btn btn-primary" onclick="window.opener.location.reload(true); window.close();">Close This Window</button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')

@endpush