@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
<a class="btn btn-primary float-right" href="{{route('admin.course.video.add',[$course_id])}}">Add New</a>
<h2 class="text-primary float-left">{{$course->name}} Videos</h2>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="course-list" data-url="{{route('admin.course.video.all', $course_id)}}">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          {{-- <th>Time</th>
          <th>No. of Question</th> --}}
          <th>Files</th>
          {{-- <th>Attempts</th> --}}
          {{-- <th>Result</th> --}}
          <th>Caption</th>
          <th>Created At</th>
          {{-- <th>Updated By</th> --}}
          <th>Updated At</th>
          <th>Satus</th>
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
@endsection

@push('scripts')

<script type="text/javascript">
     var deleteUrl = '{{route('admin.course.video.delete', [$course_id])}}';
     var confirmDeleteModal = $('#modal-delete');
     var courseList = function(){
       var tblObj = $("#course-list");

       $.ajax({
         url: tblObj.data('url'),
         type : 'GET',
         data : {sync:1},
         dataType: 'json'
       })
       .done(function(response) {
           tblObj.find('tbody').html(response.rows);
           tblObj.find('tfoot tr td').html(response.pagination);
           reinitPagination();
       })
       .fail(function(response, code) {
           toastr.error('Error in listing rows');
       });
     };

     courseList();

     var reinitPagination = function(){
       $('.pagination a').on('click', function(e){
         e.preventDefault();
         $("#course-list").data('url', $(this).attr('href'));
           courseList();
       });
     }


      var moduleId = 0;

     var deleteCategory = function(module_id){
          moduleId  = module_id;
          confirmDeleteModal.modal('show');
     }

     var confirmDelete = function(){
        $.ajax({
           url: deleteUrl,
           type : 'DELETE',
           data : {module_id:moduleId, _token:window.Laravel.csrfToken},
           dataType: 'json'
         })
         .done(function(response) {
           toastr.success(response.message);
             courseList();
             moduleId= 0;
             confirmDeleteModal.modal('hide');
         })
         .fail(function(response, code) {
             toastr.error(response.message);
         });
     }

</script>
@endpush
