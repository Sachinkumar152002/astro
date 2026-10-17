@extends('layouts.admin')
@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">

       <div class="" id="filter_row">
    <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-4">
         <label for="" class="fs-13">Invoice Number</label>
          <input type="text" class="form-control" name="invoice_number" placeholder="Invoice Number">
      </div>

     <div class="mt-2">
       <label for="" class="fs-13">Status</label>
       <select class="form-control" name="relation_type">
         <option value="">Select</option>
         <option value="order">Order</option>
         <option value="recharge">Recharge</option>
       </select>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Bill To</label>
    <input type="text" class="form-control" name="billing_full_name" placeholder="Bill To">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Ship To</label>
       <input type="text" class="form-control" name="name" placeholder="Ship To">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Date</label>
       <div class="d-flex">
        <div class="">
        <input type="date" class="form-control" name="updated_at_from">
        </div>
        <div class="ml-2">
      <input type="date" class="form-control" name="updated_at_to">
     </div>
       </div>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Amount</label>
       <div class="d-flex">
        <div class="">
        <input type="number" class="form-control" name="total_amount_from">

        </div>
        <div class="ml-2">
        <input type="number" class="form-control" name="total_amount_to">
        </div>
       </div>
     </div>
     <button class="btn btn-success text-white mt-3" onclick="filterResults('{{ route('admin.invoice.all') }}')">Apply</button>
      </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="text-primary float-left">Invoices</h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end">

            <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.course.all') }}')">Filter</button>
            <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
            Filter  <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.invoice.all') }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>#</th>
                    <th class="sortable" data-sort="invoice_number">Invoice Number <i class="icon"></th>
                      <th class="sortable" data-sort="relation_type">Type <i class="icon"></th>
                    <th class="sortable selected" data-sort="bill_to">Bill To <i class="icon"></th>
                    <th class="sortable selected" data-sort="ship_to">Ship To <i class="icon"></th>
                    <th class="sortable" data-sort="total_amount">Amount <i class="icon"></th>
                    <th class="sortable" data-sort="updated_at">Updated At <i class="icon"></th>
                    <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
        var deleteUrl = '{{ route('admin.invoice.delete') }}';
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
