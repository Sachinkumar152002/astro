@if($courses)
<div class="col-sm-12 col-md-12 box-white student-other-course">
<h4>Recommended Courses </h4>
<div class="bg-white student-other-course">
   @include('main.courses.list-recom')
</div>
</div>
@endif