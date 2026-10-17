<style media="screen">
  .shipping-body td{
    padding:0.75rem;
  }
.shipping-body th{
    padding:0.75rem;
  }
  .shipping-body td input{
    width:120px;
    height: 30px;
  }
</style>


@extends('layouts.admin')
@section('content')
<form class="" action="{{route('admin.shippingweightprice.submit')}}" method="post" onsubmit="return validateAndSubmit(this)">
  @csrf
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Shipping Weight Prices</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
<!--
      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>
<div class="clearfix"></div>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row table-responsive">
      <table id="list-table" class="w-100">
        <thead class="shipping-body">
          <tr class="bg-primary text-white">
            <th>Weight From</th>
            <th>Weight To</th>
            <th>Local Price</th>
            <th>Domestic Price</th>
            <th>National Price</th>
            <th>Action</th>
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
          @forelse($shipping_weight_prices as $index => $value)
            <tr>
              <td><input type="number" {{$can_update ? '': 'readonly'}} name="weight_from[]" value="{{$value->weight_from}}"></td>
              <td><input type="number" {{$can_update ? '': 'readonly'}} name="weight_to[]" value="{{$value->weight_to}}"></td>
              <td><input type="number" {{$can_update ? '': 'readonly'}} name="local_price[]" value="{{$value->local_price}}"></td>
              <td><input type="number" {{$can_update ? '': 'readonly'}} name="domestic_price[]" value="{{$value->domestic_price}}"></td>
              <td><input type="number" {{$can_update ? '': 'readonly'}} name="national_price[]" value="{{$value->national_price}}"></td>
              <!-- <td>{{ $value->weight_from }}</td>
              <td>{{ $value->weight_to }}</td>
              <td>{{ $value->local_price }}</td>
              <td>{{ $value->domestic_price }}</td>
              <td>{{ $value->national_price }}</td> -->
              @if(!$index)
              <td>
                @if($can_add)
                <button type="button"  class="btn btn-primary p-2" onclick="addShipping(this)">
                  <i class="bi bi-plus text-white p-1" ></i></button>

                @endif
              </td>
              @else
              <td>
                @if($can_add)
                <button type="button"  class="btn-primary p-2" onclick="addShipping(this)">
                  <i class="bi bi-plus text-white p-1"></i></button>
                @endif
                @if($can_delete)
                |
                <button type="button" class="btn btn-danger p-2" onclick="removeShipping(this)">
                  <i class="bi bi-trash text-white p-1"></i></button>
                @endif
              </td>
              @endif
            </tr>
          @empty
            <tr>
              <td><input type="number" name="weight_from[]" value=""></td>
              <td><input type="number" name="weight_to[]" value=""></td>
              <td><input type="number" name="local_price[]" value=""></td>
              <td><input type="number" name="domestic_price[]" value=""></td>
              <td><input type="number" name="national_price[]" value=""></td>
              <td>
                @if($can_add)
                  <i class="bi bi-plus-lg text-primary" onclick="addShipping(this)"></i>
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
    function addShipping(that){
      let tr = document.createElement('tr');
      let appendData = `
      <td><input type="number" name="weight_from[]" value=""></td>
      <td><input type="number" name="weight_to[]" value=""></td>
      <td><input type="number" name="local_price[]" value=""></td>
      <td><input type="number" name="domestic_price[]" value=""></td>
      <td><input type="number" name="national_price[]" value=""></td>
      <td><i class="bi bi-plus-lg text-primary" onclick="addShipping(this)"></i> | <i class="bi bi-trash text-danger" onclick="removeShipping(this)"></i></td>
      `;
      tr.innerHTML = appendData;
      // let tbody = $('#shipping_prices');
      $(that).parent().parent().after(tr)
    }
    function removeShipping(that){
      $(that).parent().parent().remove();
    }
</script>
@endpush
