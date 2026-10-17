@extends('layouts.admin')


@section('content')
{!! Form::model($review, ['route' => 'admin.product.review.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($review)
{{ Form::hidden('id', old('id')) }}
@endif
<input type="hidden" name="productTypeName" value="{{ request()->get('productTypeName', 'Product') }}">
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Review</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-3">
        <label for="title">Product</label>
        <input type="text" class="form-control" name="product_id" value="{{$review->previews ? $review->previews->title : ''}}" readonly>
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
        <select class="form-control nosearch" name="status">
          <option value="">Select</option>
          <option value="0" {{$review->status==0 ? 'selected' : ''}}>Inactive</option>
          <option value="1" {{$review->status==1 ? 'selected' : ''}}>Active</option>
        </select>
      </div>
      <div class="col-md-12 mt-3">
        <label for="comments">Comments</label>
        <textarea class="form-control" name="comments" id="comments" rows="4" cols="100" required>{{$review->review ? $review->review : ''}}</textarea>
      </div>
    </div>
  </div>
</div>


  {!! Form::close() !!}
@endsection

@push('scripts')
  <script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
