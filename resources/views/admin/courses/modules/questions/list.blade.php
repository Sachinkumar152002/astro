@extends('layouts.admin_two')

@section('content')
<div class="row">
<div class="col-lg-12 add-new-button">
<a class="btn btn-primary float-right" href="{{route('admin.course.module.content.question.add',[$course_id, $module_id, $content_id, $faq_type])}}">Add New</a>
{{--
<h2 class="text-primary float-left">{{$course->name}} - {{$module->name}}-{{$content->title}}  Questions</h2>
--}}
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.course.module.content.question.all', [$course_id, $module_id, $content_id, $faq_type])}}">
      <thead>
          <tr class="bg-primary text-white">
          <th>#</th>
          <th>Question</th>
          <!-- <th>Show In</th> -->
          <th>Order</th>
          <th>Satus</th>
          <th>Updated At</th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row" class="d-none">
          <th></th>
          <th><input type="text" class="form-control" name="question" placeholder="Search Question"></th>
          {{--
          <th>
            <select class="form-control" name="show_in">
              @foreach(courseQuestionShowIn() as $k=>$v)
                <option value="{{$k}}" {{$k=='assignment'?'selected':''}}>{{$v}}</option>
              @endforeach
          </select>
          </th>
          --}}
          <th></th>
          <th>
            <select class="form-control" name="status">
              <option value="">Select Status</option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}" {{$k==1 ? 'selected':''}}>{{$v}}</option>
              @empty
              <option value="0">Inactive</option>
              @endforelse
          </select>
        </th>
        <th>
          <div class="">
            <input type="date" class="form-control" name="updated_at_from">
            <input type="date" class="form-control" name="updated_at_to">
          </div>
        </th>
          <th>
            <button class="btn btn-success text-dark" onclick="filterResults('{{ route('admin.course.module.content.question.all', [$course_id, $module_id, $content_id, $faq_type]) }}')">Filter</button>
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
     var deleteUrl = '{{route('admin.course.module.content.question.delete', [$course_id, $module_id, $content_id, $faq_type])}}';
     // var confirmDeleteModal = $('#modal-delete');
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
     //  var questionId = 0;
     //
     // var deleteCategory = function(question){
     //      questionId  = question;
     //      confirmDeleteModal.modal('show');
     // }
     //
     // var confirmDelete = function(){
     //    $.ajax({
     //       url: deleteUrl,
     //       type : 'DELETE',
     //       data : {question_id:questionId, _token:window.Laravel.csrfToken},
     //       dataType: 'json'
     //     })
     //     .done(function(response) {
     //       toastr.success(response.message);
     //         courseList();
     //         moduleId= 0;
     //         confirmDeleteModal.modal('hide');
     //     })
     //     .fail(function(response, code) {
     //         toastr.error(response.message);
     //     });
     // }

</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
