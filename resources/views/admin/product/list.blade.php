@extends('layouts.admin')
@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
     <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <input type="hidden" class="form-control" name="minimum_quantity" value="{{ request()->has('minimum_quantity') ? request()->get('minimum_quantity') : 'hide' }}" placeholder="Search Title">
       <div class="mt-2">
         <label for="" class="fs-13">Name</label>
        <input type="text" class="form-control" name="title" placeholder="Search Title">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Category</label>
         <input type="text" class="form-control" name="category_id" placeholder="Search Category">
       </div>
      <div class="mt-2">
       <label for="" class="fs-13">Price Range</label>
       <div class="d-flex">
        <div class="">
            <input type="number" class="form-control" name="base_price_from" placeholder="From">
        </div>
        <div class="ml-2">
          <input type="number" class="form-control" name="base_price_to" placeholder="To">
          </div>
       </div>
      </div>
      <div class="mt-2">
        <label for="" class="fs-13">Status</label>
        <select class="form-control" name="status">
          <option value="">Select Status</option>
          @forelse (statusArray() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
          @empty
          <option value="0">Inactive</option>
          @endforelse
        </select>
      </div>
      <div class="mt-2">
       <label for="" class="fs-13">Date Range</label>
       <div class="d-flex">
        <div class="">
         <input type="date" class="form-control" name="updated_at_from">
       </div>
        <div class="ml-2">
          <input type="date" class="form-control" name="updated_at_to">
          </div>
       </div>
      </div>
      @if($p_id)
        <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.product.variation.all', [$p_id, $productTypeName]) }}')">Apply</button>
      @else
        <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.product.all', [$productTypeName]) }}')">Apply</button>
      @endif
      </div>
    </div>
    </div>
    </div>
 <!-- filter-design-new-end -->
     <div class="row">
        <div class="col-md-6">
          <h1 class="text-primary float-left">{{$product?$product->title.' Products' : $moduleName}} </h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end">
            @if(adminCan('product', 'add'))
              @if($p_id)
                <a class="btn btn-primary m-1">
                  <i class="bi bi-plus-lg text-white" onclick="cloneRecord('{{route('admin.product.copy', [$p_id, $productTypeName])}}', 'parent')"></i>
                </a>
              @else
                <a href="{{ route('admin.product.add', [$productTypeName]) }}" class="btn btn-primary m-1 text-white">
                  <i class="bi bi-plus-lg"></i>
                </a>
              @endif
            @endif
            @if($p_id)
              <button class="btn btn-success m-1 py-2 text-white" onclick="exportCsv(this, '{{ route('admin.product.variation.exportlist', [$p_id, $productTypeName]) }}')">Export</button>
            @else
              <button class="btn btn-success m-1 py-2 text-white" onclick="exportCsv(this, '{{ route('admin.product.exportlist', [$productTypeName]) }}')">Export</button>
            @endif
            <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
            Filter <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>
    <div class="table-responsive">
    @if($p_id)
      <table class="table" id="list-table" data-url="{{ route('admin.product.variation.all', [$p_id, $productTypeName]) }}">
    @else
      <table class="table" id="list-table" data-url="{{ route('admin.product.all', [$productTypeName]) }}">
    @endif
            <thead>
                <tr class="bg-primary text-white">
                    <th>All</th>
                    <th>#</th>
                    <th>Image</th>
                    <th class="sortable " data-sort="title">Title <i class="icon"></th>
                    <th class="sortable" data-sort="category_id">Category <i class="icon"></th>
                    <th class="sortable" data-sort="base_price">Price <i class="icon"></th>
                    <th class="sortable" data-sort="order">Order <i class="icon"></th>
                    <th class="sortable" data-sort="status">Status <i class="icon"></th>
                    @if($productTypeValue != 3)
                      <th class="sortable" data-sort="variations">Variation <i class="icon"></th>
                    @endif
                    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></th>
                    <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
                    <th></th>
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
  @if($p_id)
    var deleteUrl = '{{ route('admin.product.variation.delete', [$p_id]) }}';
  @else
  var deleteUrl = '{{ route('admin.product.delete') }}';
  @endif
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

function handleMouseOver(that){
  $(that).parent().find(".allcategoriesname").removeClass("d-none");
}
function handleMouseOut(that){
  $(that).parent().find(".allcategoriesname").addClass("d-none");
}
function handleEditOrder(that){
  $(that).parent().addClass("d-none");
  $(that).parent().parent().find(".editable").removeClass("d-none");
}
function handleChangeOrder(that,product_id){
  let orderInput=$(that).parent().find("input[name=order]");
  let order=orderInput.val()?orderInput.val():0;
  $.ajax({
     url: "{{ route('admin.product.handlechangeorder') }}",
     type : 'GET',
     data : {product_id,order},
     success:function(response){
       toastr.success(response.message);
       listTable();
     },
     error:function(error){
       toastr.error("Error occured in updating category");
     }
   })
}
</script>
@endpush
