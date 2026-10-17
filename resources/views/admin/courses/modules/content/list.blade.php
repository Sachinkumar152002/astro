@extends('layouts.admin_two')
@section('content')
<div class="row justify-content-end align-items-center py-2">
  <a class="btn btn-primary" href="{{route('admin.course.module.content.add',[$course_id, $module_id])}}">Add New</a>
  {{--
<div class="col-lg-12 add-new-button float">
<h2 class="text-primary float-left">{{$course->name}} - {{$module->name}} Content</h2>
</div>
--}}
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.course.module.content.all', [$course_id, $module_id])}}">
      <thead class="">
          <tr class="bg-primary text-white">
          <th>#</th>
          <th class="sortable selected" data-sort="title">Title <i class="icon"></th>
          <th class="sortable" data-sort="order">Order <i class="icon"></th>
          <th class="sortable" data-sort="status">Status <i class="icon"></th>
          <th>Assignment</th>
          <th>Quizz</th>
          <th class="sortable" data-sort="updated_at">Updated At <i class="icon"></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row" class="d-none">
          <th></th>
          <th><input type="text" class="form-control" name="title" placeholder="Search Title"></th>
          <th></th>
          <th>
            <select class="form-control" name="status">
              <option value="">Select Status</option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @empty
              <option value="0">Inactive</option>
              @endforelse
            </select>
          </th>
          <th></th>
          <th></th>
          <th>
            <div class="">
              <input type="date" class="form-control" name="updated_at_from">
              <input type="date" class="form-control" name="updated_at_to">
            </div>
          </th>
          <th>
            <button class="btn btn-success text-dark" onclick="filterResults('{{route('admin.course.module.content.all', [$course_id, $module_id])}}')">Filter</button>
          </th>
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
     var deleteUrl = '{{route('admin.course.module.content.delete', [$course_id, $module_id])}}';
     var confirmDeleteModal = $('#modal-delete');
     // var courseList = function(){
     //   var tblObj = $("#course-list");
     //
     //   $.ajax({
     //     url: tblObj.data('url'),
     //     type : 'GET',
     //     data : {sync:1},
     //     dataType: 'json'
     //   })
     //   .done(function(response) {
     //       tblObj.find('tbody').html(response.rows);
     //       tblObj.find('tfoot tr td').html(response.pagination);
     //       reinitPagination();
     //   })
     //   .fail(function(response, code) {
     //       toastr.error('Error in listing rows');
     //   });
     // };
     //
     // courseList();
     //
     // var reinitPagination = function(){
     //   $('.pagination a').on('click', function(e){
     //     e.preventDefault();
     //     $("#course-list").data('url', $(this).attr('href'));
     //       courseList();
     //   });
     // }
     //
     //
      var deleteId = 0;
     //
     var deleteCategory = function(content_id){
       console.log(content_id)
          deleteId  = content_id;
          confirmDeleteModal.modal('show');
     }
     //
     // var confirmDelete = function(){
     //   // console.log(contentId)
     //    $.ajax({
     //       url: deleteUrl,
     //       type : 'DELETE',
     //       data : {content_id:contentId, _token:window.Laravel.csrfToken},
     //       dataType: 'json',
     //     })
     //     .done(function(response) {
     //       toastr.success(response.message);
     //         courseList();
     //         contentId= 0;
     //         confirmDeleteModal.modal('hide');
     //     })
     //     .fail(function(response, code) {
     //         toastr.error(response.message);
     //     });
     // }

</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
