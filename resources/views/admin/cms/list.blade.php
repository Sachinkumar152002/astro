@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Name</label>
    <input type="text" class="form-control" name="name">
     </div>

     <div class="mt-2">
      <label for="" class="fs-13">Slug</label>
  <input type="text" class="form-control" name="slug">
     </div>
     <div class="mt-2">
      <label for="" class="fs-13">Template</label>
      <select class="form-control" name="template">
          <option value=""></option>
          @forelse (pageTemplates() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
          @empty
          <option value="default">Default</option>
          @endforelse
      </select>
     </div>
     <div class="mt-2">
      <label for="" class="fs-13">Status</label>
      <select class="form-control" name="status">
          <option value=""></option>
          @forelse (statusArray() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
          @empty
          <option value="0">Inactive</option>
          @endforelse
      </select>
     </div>
     <button class="btn btn-success text-white mt-3" onclick="listTable()">Apply</button>

     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan('cms', 'add'))
    <button class="btn btn-primary m-1">
      <a href="{{route('admin.cms.add')}}" class="text-white">
        <i class="bi bi-plus"></i>
      </a>
    </button>

    @endif

    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.cms.list')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th >#<i class="icon"></i></th>
          <th class="sortable" data-sort="name">Name <i class="icon"></th>
          <th class="sortable" data-sort="slug">Slug <i class="icon"></i></th>
          <th class="sortable" data-sort="template">Template <i class="icon"></i></th>
          <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
          <th>In Apps</th>
          <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row" class="d-none">
          <th></th>
          <th><input type="text" class="form-control" name="name"></th>
          <th><input type="text" class="form-control" name="slug"></th>
          <th>
              <select class="form-control" name="template">
                  <option value=""></option>
                  @forelse (pageTemplates() as $k=>$v)
                    <option value="{{$k}}">{{$v}}</option>
                  @empty
                  <option value="default">Default</option>
                  @endforelse
              </select></th>
          <th><select class="form-control" name="status">
              <option value=""></option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @empty
              <option value="0">Inactive</option>
              @endforelse
          </select></th>
          <th></th>
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
     var deleteUrl = '{{route('admin.cms.delete')}}';
</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
<script type="text/javascript">
var search = $('.btn-search');
var close = $('.close-search');
var overlay = $('.filter-backdrop');
search.click(function() {
  filter_show();
});
close.click(function() {
  filter_hide();
});

function filter_show() {
  $('#filters').addClass('filtered');
  overlay.show(20);
}

function filter_hide() {
  $('#filters').removeClass('filtered');
  overlay.hide();
}
</script>
@endpush
