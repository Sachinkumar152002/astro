@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="px-0 w-100" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
        <div class="mt-2">
          <label for="" class="fs-13">Date</label>
          <div class="d-flex">
           <div class="">
              <input type="date" class="form-control" name="date_from">
           </div>
           <div class="ml-2">
             <input type="date" class="form-control" name="date_to">
             </div>
          </div>
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">English</label>
          <input type="text" class="form-control" name="english">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Hindi</label>
          <input type="text" class="form-control" name="hindi">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Status</label>
          <select class="form-control" name="status">
            <option value="">Select</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Last Updated</label>
          <div class="d-flex">
           <div class="">
              <input type="date" class="form-control" name="updated_at_from">
           </div>
           <div class="ml-2">
             <input type="date" class="form-control" name="updated_at_to">
             </div>
          </div>
        </div>
          <button class="btn btn-success mt-3" onclick="listTable()">Apply</button>
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
    <button class="btn btn-dark text-black m-1">
      <a  href="{{asset('import/csv/sample/thoughtoftheday.csv')}}" class="text-white">
        <i class="bi bi-download"></i>
      </a>
    </button>
    <input type="file" name="file" accept=".csv" class="m-1" id="file">
    <button class="btn btn-primary text-black m-1" onclick="handleImportFile()">Import</button>
    @if(adminCan($nav, 'add'))
      <button class="btn btn-primary text-black m-1">
        <a  href="{{route('admin.'.$nav.'.add')}}" class="text-white">
          <i class="bi bi-plus"></i>
        </a>
    </button>
    {{-- <button class="btn btn-seconday text-black m-1">
      <i class="bi bi-copy"></i>
    </button> --}}
    @endif
    @if(adminCan($nav, 'delete'))
    {{-- <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button> --}}
    @endif
    <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
      <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Date</th>
          <th>English</th>
          <th>Hindi</th>
          <th>Status</th>
          <th>Last Updated</th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
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
<script type="text/javascript">
  function handleImportFile(){
    let file = $('#file')[0].files[0];
    console.log(file);
    if(file && file.type == 'text/csv'){
      var formData = new FormData();
      formData.append('file', file);
      formData.append('type', 'panchang');
      formData.append('module', 'panchange');
      $(this).prop('disabled', true);

      $.ajax({
        url: "{{route('admin.importfile.submit')}}",
        type: 'POST',
        data: formData,
        encType: "multipart/form-data",
        contentType: false,
        processData: false,
        headers:{
          'X-CSRF-TOKEN':"{{ csrf_token() }}"
        },
        success: function(response){
          toastr.success(response.message);
          setTimeout(function(){
            window.location.reload();
          }, 2000);
        },
        error: function(xhr){
          console.log(xhr);
          let error = xhr.responseJSON.errors;
          if(error){
            for (var key in error){
              toastr.error(error[key][0]);
            }
          }else{
            toastr.error('Error occurred while uploading the file.');
          }
        }
      });
      $(this).prop('disabled', true);
    }else{
      toastr.error('Please select a CSV file.');
    }

  }
</script>
@endpush
