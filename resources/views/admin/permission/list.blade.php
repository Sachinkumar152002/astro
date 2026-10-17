@extends('layouts.admin')

@section('content')<!-- BEGIN CONTENT-->
<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
        <div class="mt-4">
          <label for="" class="fs-13">Role</label>
          <select class="form-control" name="role_id">
            @foreach ($roles as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        <button class="btn btn-success text-white mt-3" onclick="filterResults('{{route('admin.permission.all')}}')">Apply</button>
      </div>
    </div>
  </div>
</div>
<section>
  <div class="section-body contain-lg">
    <div class="row">
      <div class="col-lg-6">
        <h1 class="text-primary">{{$moduleName}}</h1>
      </div>
      <div class="col-md-6">
    <div class="d-flex text-align-end justify-content-end">
          @if(adminCan($nav, 'add'))
            <button class="btn btn-primary text-white m-1">
              <a  href="{{route('admin.'.$nav.'.add')}}" class="text-white">
                <i class="bi bi-plus"></i>
              </a>
            </button>
    @endif

    <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.permission.all') }}')">Filter</button>
    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
    Filter <i class="bi bi-funnel text-white"></i>
    </button>
    </div>
      </div>
      <div class="col-lg-8">
        <article class="margin-bottom-xxl">
          <p class="lead">
          </p>
        </article>
      </div>
    </div>
    <div class="row">
    <div class="col-md-12">
    <div class="">
    <div class="card-body">
    <div class="table-responsive">
    <table class="table no-margin " data-url="{{route('admin.permission.all')}}" id="list-table">
    <thead>
                <tr class="bg-primary text-white">
    <th class="sortable selected" data-sort="id">#</th>
    <th class="selected" data-sort="role_id">Role</th>
                  <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
    </div><!--end .card-body -->
    </div><!--end .card -->
    </div><!--end .col -->
</div><!--end .row -->
<!-- END RESPONSIVE TABLE 1 -->


<!-- END RESPONSIVE TABLE 1 -->

</div>
</section>

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.permission.delete')}}'
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
