@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
@include(themeView().'astrologers._partials.top')
<section class="mt-3 mb-5">
  <div class="container">
  <div class="row justify-content-between">
    @include(themeView().'astrologers._partials.sidebar')
    <div class="col-lg-9 col-12">
      <div class="row mb-3 recent">
        <div class="col-lg-12">
          <h4>
          Orders
          </h4>
        </div>
        <!-- new-design-start -->
        @if(isset($orders) && count($orders))
          @foreach($orders as $orderKey => $order)
            @foreach($order->orderItems as $itemKey => $orderItem)
              @if($orderItem->type == 'shop')
           <div class="order-box my-2">
             <div class="o-table-box bg-light px-4 py-3">
               <table class="w-100">
                 <tbody>
                   <tr>
                     <td>
                       <strong>Order Date:</strong> {{Illuminate\Support\Carbon::parse($orderItem->created_at)->format('d-m-Y')}}
                     </td>
                     <td>
                       <strong>Total Price:</strong> ₹{{ $orderItem->price*$orderItem->quantity }}
                     </td>
                     <td>
                       <strong>Order ID:</strong>  # {{ $order->order_number }}
                     </td>
                     <td>
                       <strong>Order Status:</strong> {{ ucfirst($order->status) }}
                     </td>
                   </tr>
                 </tbody>
               </table>
             </div>
               <div class="px-4 py-3">
                 <div class="row">
                   <div class="col-md-1">
                     <img src="{{ $orderItem->product && $orderItem->product->image ? $orderItem->product->image->image(250, 250) : 'assets/image/default.png' }}" class="img-fluid radius" alt="{{ $orderItem->product->title }}" height="100">
                   </div>
                   <div class="col-md-7">
                     <p class="mb-0">{{ $orderItem->product->title }}</p>
                     <div class="">
                       <del class="actual_price fs-7 text fw-bold" tabindex="0">₹1,500</del>
                       <span class="ms-2 sale_price text-left fs-7 text fw-bold text-ac-price" tabindex="0">₹{{ $orderItem->price*$orderItem->quantity }}</span>
                     </div>
                   </div>
                   <div class="col-md-4 text-end">
                     <a href="{{route('product.handle', [$orderItem->product->slug])}}">
                       <button class="btn btn-secondary py-0 px-4" style="height:35px;">View Detail</button>
                     </a>
                   </div>
                 </div>
               </div>
          </div>

        @endif
        @endforeach
      @endforeach
    @endif

        <!-- new-design-end -->
      </div>
    </div>
  </div>
  </div>
</section>
@endsection
