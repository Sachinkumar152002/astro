@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.employer.account.sidebar')
            <div class="col-md-9">
                <div class="row">

                    @if($jobs)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Jobs <a href="{{route('employer.post.job')}}" title="Add new" class="pull-right"><i class="fa fa-plus"></i></a> </h4>
                                <div class="bg-white">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Company</th>
                                                        <th>Industry</th>
                                                        <th>Vacanacy</th>
                                                        <th>Option</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jobs as $job)
                                                    <tr>
                                                        <td>{{$job->title}}</td>
                                                        <td>{{$job->company->name}}</td>
                                                        <td>{{$job->industry_id && $job->industry ? $job->industry->name : ''}}</td>
                                                        <td>{{$job->vacancy}}</td>
                                                        <td class="text-center"><a href="{{route('employer.job.edit',[$job->id])}}" title="Edit" ><i class="fa fa-edit"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection