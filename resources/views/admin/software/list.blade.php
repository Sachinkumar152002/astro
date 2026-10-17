@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
  {{--
<a class="btn btn-primary float-right" href="{{route('admin.software.add')}}">Add New</a>
--}}
<h1 class="text-primary float-left">Pages</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.software.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Image</th>
          <th class="sortable" data-sort="title">Title <i class="icon"></i></th>
          <th class="sortable" data-sort="page">Page <i class="icon"></i></th>
          <th class="sortable" data-sort="packages">Packages <i class="icon"></i></th>
          <th class="sortable" data-sort="features">Features <i class="icon"></i></th>
          <th class="sortable" data-sort="procedures">Procedures <i class="icon"></i></th>
          <th class="sortable" data-sort="videos">Videos <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated<i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>
        <tr class="d-none" id="filter_row">
          <th></th>
          <th></th>
          <th></th>
          <th><input type="text" class="form-control" name="name" placeholder="Search Name"></th>
          <th></th>
          <th>
            <select class="form-control" name="status">
              <option value="">Select Status</option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @empty
              <option value="0">Inactive</option>
              @endforelse
          </select>
        </th>
          <th>
            <div class="">
              <input type="date" class="form-control" name="updated_at_from">
              <input type="date" class="form-control" name="updated_at_to">
          </div>
          </th>
          <th>
            <button class="btn btn-success text-dark" onclick="filterResults('{{route('admin.'.$nav.'.all')}}')">Filter</button>
          </th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="12" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->
    @include('admin.media.view')

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.software.delete')}}';
</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
