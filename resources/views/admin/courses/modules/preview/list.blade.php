@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
<a class="btn btn-primary float-right" href="{{route('admin.course.module.preview.add',[$course_id, $module_id])}}">Add New</a>
<h2 class="text-primary float-left">{{$course->name}} - {{$module->name}} Preview</h2>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="course-list" data-url="{{route('admin.course.module.preview.all', [$course_id, $module_id])}}">
      <thead>
        <tr>
          <th>#</th>
          <th>thumbnail</th>
          <th>Question</th>
          <th>Video Url</th>
          <th>Updated By</th>
          <th>Updated At</th>
          <th></th>
          <th></th>

          <th></th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="9" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->
@endsection

@push('scripts')

<script type="text/javascript">
     var deleteUrl = '{{route('admin.course.module.preview.delete', [$course_id, $module_id])}}';
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


      var questionId = 0;

     var deleteCategory = function(question){
          questionId  = question;
          confirmDeleteModal.modal('show');
     }

     var confirmDelete = function(){
        $.ajax({
           url: deleteUrl,
           type : 'DELETE',
           data : {question_id:questionId, _token:window.Laravel.csrfToken},
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
