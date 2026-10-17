@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th class="sortable " data-sort="from_number">From User <i class="icon"></i></th>
          <th class="sortable " data-sort="from_number">To  Astrologer <i class="icon"></i></th>
          <th class="sortable " data-sort="from_number">Start time <i class="icon"></i></th>
          <th class="sortable " data-sort="from_number">Emd time <i class="icon"></i></th>
          {{-- <th class="sortable " data-sort="to_number">To Number <i class="icon"></i></th> --}}
          <th class="sortable selected" data-sort="created_at">Meeting Date<i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>
        <tr id="filter_row" class="d-none">
          <th></th>
          <th><input type="text" class="form-control" name="from_number"></th>
          <th><input type="text" class="form-control" name="to_number"></th>
          <th><input type="date" class="form-control" name="created_at"></th>
          <th class="text-right">
            <button class="btn btn-success" onclick="listTable()">Filter</button>
          </th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="5"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
