@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
<a class="btn btn-primary float-right" href="{{route('admin.'.$nav.'.add')}}">Add New</a>
<a class="btn btn-secondary text-white float-right mr-4"><input type="checkbox" onchange="loadTrashedRecords(this)" id="only_trashed"> Trashed</a>
<a class="btn btn-danger text-white float-right mr-4"><input type="checkbox" onchange="unverifiedRecords(this)" id="unverified_data"> Unverified</a>
<a class="btn btn-warning text-white float-right mr-4"><input type="checkbox" onchange="inactiveRecords(this)" id="inactive_data"> InActive</a>
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Image</th>
          <th class="sortable " data-sort="display_name">Name <i class="icon"></i></th>
          <th class="sortable " data-sort="phone">Phone <i class="icon"></i></th>
          <th class="sortable " data-sort="email">Email <i class="icon"></i></th>

        </tr>
        <tr id="filter_row">
          <th><input type="hidden" name="trashed" value="0" id="trashed"></th>
          <th></th>
          <th><input type="text" class="form-control" name="display_name"></th>
          <th><input type="text" class="form-control" name="phone"></th>
          <th><input type="text" class="form-control" name="email"></th>
          <th><select class="form-control" name="status" id="status">
            <option value=""></option>
            @forelse (statusArray() as $k=>$v)
              <option value="{{$k}}">{{$v}}</option>
            @empty
            <option value="0">Inactive</option>
            @endforelse
        </select></th>
        <th><select class="form-control" name="is_verified" id="is_verified">
          <option value=""></option>
          @forelse (yesNoArray() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
          @empty
          <option value="0">Inactive</option>
          @endforelse
      </select></th>
          <th><input type="date" class="form-control" name="updated_at"></th>
          <th class="text-right">
            <button class="btn btn-success" onclick="listTable()">Filter</button>
          </th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="10" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.'.$nav.'.delete')}}';
  var loadTrashedRecords = function(){
    if($('#only_trashed').is(':checked')){
      $('#trashed').val(1);
      listTable();
    }else {
      resetFilter();
    }
  }
  var unverifiedRecords = function(){
    $('#is_verified').val('');
    if($('#unverified_data').is(':checked')){
      $('#is_verified').val(0);
    }
    listTable();
  }
  var inactiveRecords = function(){
    $('#status').val('');
    if($('#inactive_data').is(':checked')){
      $('#status').val(0);
    }
    listTable();
  }


</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
