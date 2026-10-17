@extends('layouts.app')

@section('content')
    <section class="middle-section">
        <div class="container">
            <div class="row">
                @include('main.account.partials.sidebar')
                <div class="col-md-9">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 box-white">
                                    <h4>{{$course->name}}</h4>
                                </div>
                                <div class="col-sm-12 col-md-12 box-white">
                                    <div class="bg-white">

                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-sm">

                                                    <tbody>
                                                    <tr class="tb-border">
                                                        <th>Name</th>
                                                        <td>{{$course->name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Image</th>
                                                        <td><img class="img-responsive img-table1"
                                                                 src="{{$course->image ? $course->image->image(150, 150) : ''}}"
                                                                 alt="{{$course->name}}"></td>
                                                    </tr>

                                                    <tr>
                                                        <th>Certified by</th>
                                                        <td>{{$course->association->short_name}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Access</th>
                                                        <td>{{$course->access}} days</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Start Date</th>
                                                        <td>{{date('d-m-Y', strtotime($studentCourse->start_date))}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Days Left</th>
                                                        <td><span class="red">{{ $studentCourse->daysLeft }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Score</th>
                                                        <td>{{$studentCourse->finalScore}}%</td>
                                                    </tr>
                                                    <tr>
                                                        <th>View Certificate</th>
                                                        <td>{!! $studentCourse->certificateLink !!}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>Modules</h3>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Name</th>
                                                        <th>Files</th>
                                                        <th>Attempt<br>Allowed</th>
                                                        <th>Attemped</th>
                                                        <th>Score</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    @if($course->modules)
                                                        @foreach($course->modules as $i=>$module)
                                                            <tr>
                                                                <td> {{$i+1}}</td>
                                                                <td><a href="javascript:void(0)"
                                                                       onclick="loadModuleFiles({{$module->id}})">{{$module->name}}</a>
                                                                </td>
                                                                <td> {{$module->files->count()}}</td>
                                                                <td> {{$module->attempt_allow}}</td>
                                                                <td> {{$studentCourse->totalAttemped($course->id, $module->id, auth()->user()->id)}}</td>
                                                                <td> {{$studentCourse->totalScored($course->id, $module->id, auth()->user()->id)}}</td>
                                                                <td class="text-right">
                                                                    <a href="javascript:void(0)"
                                                                       onclick="loadModuleFiles({{$module->id}})"
                                                                       class="btn btn-success">Read</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="javascript:void(0)" class="btn btn-primary"
                                                                       onclick="window.open('{{route('account.assessment.attempt.try',[$studentCourse->totalAttemped($course->id, $module->id, auth()->user()->id)+1, $course->id, $module->id])}}', 'Assessment', 'width=1000,height=600,location=0,menubar=0,top=100,toolbar=0,left=100')">Attempt</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade bd-example-modal-lg" id="module-files" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="loginmodal-container module-file-popup">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Module Files</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        var loadModuleFiles = function (module_id) {
            axios.post('{{route('account.course.files')}}', {module: module_id})
                .then(function (response) {
                    $('#module-files .modal-body').html(response.data);
                    $('#module-files').modal('show');
                })
                .catch(function (error) {
                });
        }
    </script>
@endpush