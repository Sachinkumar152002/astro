@extends('layouts.admin')

@section('content')
<div class="px-3">
  <div class="row">
    <div class="col-md-6">
      <h1 class="text-primary">{{ $moduleName }}</h1>
      <p class="text-muted small">Select products to show in Astrologer Profile under "Top Selling". Product links will include astrologer referral code when viewed by astrologers.</p>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-end">
      <button type="button" class="btn btn-success" id="btn-save-products">
        <i class="bi bi-check-lg"></i> Save
      </button>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-bordered" id="list-table" data-url="{{ route('admin.products_for_astrologer.all') }}">
          <thead>
            <tr class="bg-primary text-white">
              <th>#</th>
              <th>Title</th>
              <th>Type</th>
              <th class="text-center">Show in Astrologer Profile</th>
              <th class="text-center" style="width:100px;">Sort Order</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6" class="pagination"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('theme-admin/js/listtable.js') }}"></script>
<script>
$(document).on('change', '#list-table input[name="show_in_astrologer_profile[]"]', function() {
  var label = $(this).siblings('.form-check-label');
  if (label.length) label.text($(this).prop('checked') ? 'On' : 'Off');
});
$(document).on('click', '#list-table .btn-copy-url', function(e) {
  e.preventDefault();
  var url = $(this).data('url');
  var btn = $(this);
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(url).then(function() {
      var t = btn.html(); btn.html('Copied!'); setTimeout(function() { btn.html(t); }, 1500);
    });
  } else {
    var ta = document.createElement('textarea'); ta.value = url; document.body.appendChild(ta); ta.select();
    document.execCommand('copy'); document.body.removeChild(ta);
    var t = btn.html(); btn.html('Copied!'); setTimeout(function() { btn.html(t); }, 1500);
  }
});

$('#btn-save-products').on('click', function() {
  var products = [];
  $('#list-table tbody tr[data-product-id]').each(function() {
    var row = $(this);
    var id = row.data('product-id');
    var show = row.find('input[name="show_in_astrologer_profile[]"]').prop('checked');
    var orderVal = row.find('input[name="astrologer_profile_sort_order[]"]').val();
    products.push({
      id: id,
      show_in_astrologer_profile: show ? 1 : 0,
      astrologer_profile_sort_order: orderVal ? parseInt(orderVal, 10) : null
    });
  });
  $.ajax({
    url: '{{ route("admin.products_for_astrologer.submit") }}',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ products: products, _token: '{{ csrf_token() }}' }),
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .done(function(data) {
    if (data.success) {
      toastr.success(data.message || 'Saved successfully.');
    } else {
      toastr.error(data.message || 'Failed to save.');
    }
  })
  .fail(function() {
    toastr.error('Request failed.');
  });
});
</script>
@endpush
