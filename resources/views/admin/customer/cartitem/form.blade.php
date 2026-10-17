@extends('layouts.admin')


@section('content')
<form>
  <div class="">
    <div class="row">
      <div class="col-md-6">
        <h1 class="text-primary">Checkout Details</h1>
      </div>
      <div class="col-md-6 text-right">
         <!-- <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button> -->
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <ul class="nav nav-tabs">
    <li class="active nav-item">
      <a data-toggle="tab" class="active nav-link" href="#information-tab">Information</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="information-tab" class="tab-pane fade in active show px-0">
      <div class="container px-0 mx-0">
        <div class="row">
          <div class="col-3">
            <div class="invoice-box px-2 py-1">
              <p class="mb-0">
                Name:
                <a href="{{route('admin.customer.edit', [$user->id])}}" class="text-primary" target="_blank">
                  {{ $user->name }}
                </a>
              </p>
              <p class="mb-0">Phone: {{$user->phone}}</p>
              <p class="mb-0">Email: {{$user->email}}</p>
            </div>
          </div>
          <div class="col-3 mt-3">
            <button type="button" onclick="copyCheckoutUrl(this)" class="btn btn-primary text-white" data-checkout-url="{{ route('guest.redirecttodirectcheckout', ['user_id' => encrypt($user->id)]) }}"><i class="far fa-copy"></i> Checkout Url</button>
          </div>
        </div>
        <table class="table table-bordered mt-3">
          <tr>
            <th>Sr No.</th>
            <th>Product</th>
            <th class="text-right">Base Price</th>
            <th class="text-right">Sale Price</th>
            <th>Quantity</th>
            <th class="text-right">Total</th>
          </tr>
          @php
            $todayDate = date("Y-m-d");
            $subTotal=0;
            $total=0;
            $tax=0;
          @endphp
          @foreach($cartItems as $key => $item)
            @php
              $product=$item->product;
            @endphp
            @if($product)
              @php
                $item->unit_price=$product->base_price;
                $item->price=$product->base_price;
                if($product->sale_price_start_date && ($product->sale_price_start_date <= $todayDate) && ($product->sale_price_end_date >= $todayDate)){
                  $item->price=$product->sale_price;
                }
                $item->save();
                $gstPercentage=0;
                if($hnsCode=$product->hnsCode){
                  $gstPercentage=$hnsCode->gst_percentage;
                }
                $subTotal+=($item->unit_price*$item->quantity);
                $total+=($item->price*$item->quantity);
                $tax+=($item->price*$item->quantity)*($gstPercentage/100);
              @endphp
              <tr>
                <td>{{ $key+1 }}</td>
                @php
                  $title = $product->title;
                  $productAttributes = $product->attributes;
                  $productAttributeArray = [];
                  foreach($productAttributes as $key => $valueTwo){
                    if($attribute = $valueTwo->attribute){
                      $productAttributeArray[] = "$attribute->name: $valueTwo->attribute_value";
                    }
                  }
                @endphp
                <td>{{ $title }}</td>
                <td class="text-right">₹ {{$item->unit_price}}</td>
                <td class="text-right">₹ {{$item->price}}</td>
                <td>{{$item->quantity}}</td>
                <td class="text-right">₹ {{$item->price*$item->quantity}}</td>
              </tr>
            @endif
          @endforeach
          <tr>
            <th class="text-right" colspan="5">Sub Total</th>
            <td class="text-right">₹ {{sprintf('%.2f', $subTotal)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="5">Discount</th>
            <td class="text-right">- ₹ {{sprintf('%.2f', $subTotal-$total)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="5">Gst & Taxes </th>
            <td class="text-right">+ ₹ {{sprintf('%.2f', $tax)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="5">	Total</th>
            <td class="text-right">₹ {{sprintf('%.2f', ($total+$tax))}}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</form>
@endsection
@push("scripts")
<script>
  function copyCheckoutUrl(that){
    navigator.clipboard.writeText($(that).attr("data-checkout-url"));
    toastr.success("copied");
  }
</script>
@endpush
