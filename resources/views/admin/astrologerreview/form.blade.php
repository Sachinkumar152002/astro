@extends('layouts.admin')

@section('content')

<form id="reviewForm" method="post">
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Review</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
</div>


    @csrf
    <div class="row mt-3">
    <input type="hidden" name="id" value="{{ $review->id }}">
      <div class="col-md-3">
        <label for="name">{{ $astrologerType }}</label>
        <input type="text" class="form-control" name="astrologer_id" value="{{$review && $review->astrologer ? $review->astrologer->display_name : ''}}" readonly>
      </div>
      <div class="col-md-3">
        <label for="title">User</label>
        <input type="text" class="form-control" name="givenby" value="{{$review->user ? $review->user->name : ''}}" readonly>
      </div>
        <div class="col-md-3">
          <label for="rating">Ratings</label>
          <input type="text" class="form-control" name="" value="{{$review->rating}}" readonly>
        </div>
      <div class="col-md-3">
        <label for="status">Status</label>
        <select class="form-control" name="status">
          <option value="">Select</option>
          <option value="0" {{$review->status == 0 ? 'selected' : ''}}>Inactive</option>
          <option value="1" {{$review->status == 1 ? 'selected' : ''}}>Active</option>
        </select>
      </div>
      <div class="col-md-12 mt-3">
        <label for="comment">Comments</label>
        <textarea class="form-control" name="comments" id="comments" rows="4" cols="100" required>{{$review->comment ? $review->comment : ''}}</textarea>
      </div>
    </div>

</form>

@endsection
@push('scripts')
<script>
$(document).ready(function() {
      $('#reviewForm').on('submit', function(e) {
          e.preventDefault(); // Prevent the default form submission
          $.ajax({
              url: "{{route('admin.astrologer.review.submit', [request()->type])}}",
              type: 'POST',
              data: $(this).serialize(),
              success: function(response) {
                  toastr.success('Review Submitted Successfully!');
                  setTimeout(function() {
                      window.location.href = "{{ route('admin.astrologer.review', [request()->type]) }}";
                  }, 3000);
              },
              error: function(xhr) {
                  toastr.error('Error submitting review. Please try again.');
              }
          });
      });
  });
</script>
@endpush
