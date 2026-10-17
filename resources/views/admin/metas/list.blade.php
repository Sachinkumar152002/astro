@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
  <a class="btn btn-primary float-right" href="{{route('admin.meta.add')}}">Add New</a>
<h1 class="text-primary float-left">Meta Templates</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.meta.list')}}">
      <thead>
        <tr class="bg-primary text-white">

          <th># <i class="icon"></i></th>
          <th class="sortable selected" data-sort="meta_title">Meta template <i class="icon"></i></th>
          <th class="sortable" data-sort="meta_keywords">Meta Keywords <i class="icon"></i></th>
          <th class="sortable" data-sort="meta_description">Meta Description <i class="icon"></i></th>
          <th>Overwrite</th>
          <th class="sortable" data-sort="status">Status<i class="icon"></i></th>
          <th class="sortable" data-sort="updated_at">Updated At<i class="icon"></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row">
          <th></th>
          <th><input type="text" class="form-control" name="meta_title"></th>
          <th><input type="text" class="form-control" name="meta_keywords"></th>
          <th><input type="text" class="form-control" name="meta_description"></th>
          <th></th>
          <th><select class="form-control" name="status">
            <option value=""></option>
            @forelse (statusArray() as $k=>$v)
              <option value="{{$k}}">{{$v}}</option>
            @empty
            <option value="0">Inactive</option>
            @endforelse
        </select></th>
          <th><input type="date" class="form-control" name="updated_at"></th>
          <th>
            <button class="btn btn-success" onclick="listTable()">Filter</button>
          </th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
          <tr>
              <td colspan="8" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->
@endsection

@push('scripts')
<script type="text/javascript">
     var deleteUrl = '{{route('admin.meta.delete')}}';
</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
