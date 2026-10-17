<style media="screen">
  .shipping-body td{
    padding:0.50rem;
  }
.shipping-body th{
    padding:0.50rem;
  }
  .shipping-body td input{
    padding: 5px;
    height: 30px;
  }
  </style>
@extends('layouts.admin')
@section('content')
<form class="" action="{{route('admin.localpincode.submit')}}" method="post" onsubmit="return validateAndSubmit(this)">
  @csrf
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Pincode Local</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <!-- <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>
<div class="clearfix"></div>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show table-responsive">
      <div class="row table-responsive">
    <table class="w-100">
      <thead class="shipping-body" >
      <tr class="bg-primary text-white">
        <!-- <th>S.No</th> -->
        <th>City</th>
        <th>Post Office</th>
        <th>Pincode</th>
        <th class="text-right">Action</th>
      </tr>
      </thead>
      @php
      $can_add = false;
      $can_update = false;
      $can_delete = false;
      if(adminCan($nav, 'add')){
        $can_add = true;
      }
      if(adminCan($nav, 'update')){
        $can_update = true;
      }
      if(adminCan($nav, 'delete')){
        $can_delete = true;
      }
      @endphp
      <tbody id="shipping_prices" class="shipping-body">
        @forelse($pincodes as $index => $value)
          <tr>
            {{--
            <td>{{$index+1}}</td>
            --}}
            <td><input type="text" {{$can_update ? '': 'readonly'}} name="city[]" value="{{$value->city}}"></td>
            <td><input type="text" {{$can_update ? '': 'readonly'}} name="post_office[]" value="{{$value->post_office}}"></td>
            <td><input type="number" {{$can_update ? '': 'readonly'}} name="pincode[]" value="{{$value->pincode}}"></td>
            @if(!$index)
            <td class="text-right">
              @if($can_add)
              <button type="button" name="button" class="btn btn-primary" onclick="add(this)">
                  <i class="bi bi-plus-lg text-white"></i></button>
              @endif
            </td>
            @else
            <td class="text-right">
              @if($can_add)
                <button type="button" name="button" class="btn btn-primary" onclick="add(this)">
                    <i class="bi bi-plus-lg text-white"></i></button>
              @endif
              @if($can_delete)
              |
              <button type="button" name="button" class="btn btn-danger" onclick="remove(this)">  <i class="bi bi-trash text-white"></i></button>
              @endif
            </td>
            @endif
          </tr>
        @empty
          <tr>
            <td><input type="text" name="city[]" ></td>
            <td><input type="text" name="post_office[]" ></td>
            <td><input type="number" name="pincode[]" ></td>
            <td class="text-right">
              @if($can_add)
                <button type="button" name="button" class="btn btn-primary" onclick="add(this)">  <i class="bi bi-plus-lg text-white"></i></button>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>
<div class="mb-3">
</div>
</form>
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
    function add(that){
      let tr = document.createElement('tr');
      let appendData = `
        <td><input type="text" name="city[]" ></td>
        <td><input type="text" name="post_office[]" ></td>
        <td><input type="number" name="pincode[]" ></td>
      <td class="text-right"><button type="button" name="button" class="btn btn-primary" onclick="add(this)"><i class="bi bi-plus-lg text-white"></i></button> | <button type="button" name="button" class="btn btn-danger" onclick="remove(this)"><i class="bi bi-trash text-white"></i></button></td>
      `;
      tr.innerHTML = appendData;
      $(that).parent().parent().after(tr)
    }
    function remove(that){
      $(that).parent().parent().remove();
    }
</script>
@endpush
