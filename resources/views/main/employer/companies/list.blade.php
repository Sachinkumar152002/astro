@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.employer.account.sidebar')
            <div class="col-md-9">
                <div class="row">

                    @if(auth()->guard('employer')->user()->company)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Companies <a href="{{route('employer.company.add')}}" title="Add new" class="pull-right"><i class="fa fa-plus"></i></a> </h4>
                                <div class="bg-white">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Industry</th>
                                                        <th>Address</th>
                                                        <th>Contact</th>
                                                        <th>Option</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(auth()->guard('employer')->user()->company as $company)
                                                    <tr>
                                                        <td><img class="img-responsive img-table" src="{{$company->logoimage ? $company->logoimage->image(50, 50) : $company->no_image}}" alt="{{$company->name}}"></td>
                                                        <td>{{$company->name}}</td>
                                                        <td>{{$company->industry_type && $company->industry ? $company->industry->name : ''}}</td>
                                                        <td>{{$company->address}}</td>
                                                        <td>{{$company->contact_name}}, {{$company->mobile}}</td>
                                                        <td class="text-center"><a href="{{route('employer.company.edit',[$company->id])}}" title="Add new" ><i class="fa fa-edit"></i></a>
                                                            <a href="{{route('employer.company.delete',[$company->id])}}" title="Delete Company" class="text-danger" onclick="return deleteCompany(this)" ><i class="fa fa-trash"></i></a>
                                                        </td>
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

@push('scripts')
<script type="text/javascript">
    var deleteCompany = function(el){
        if(confirm("Are you want to delete this company? Please confirm no job post under this company other wise their jobs will delete.")) {
            var elm = $(el);
            axios.delete(elm.attr('href'), {})
            .then(function (response) {
                toastr.success("Company deleted successfully");
                setTimeout(function(){
                     window.location.reload();
                 }, 3000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                toastr.error(errors.message);
            });
        }
        return false;
    }

</script>
@endpush