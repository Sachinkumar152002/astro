@extends('layouts.app')
@push('styles')
 <!-- Event snippet for Lead conversion page -->
 <script>
  gtag('event', 'conversion', {'send_to': 'AW-822003219/GwDMCJfehHsQk4z7hwM'});
</script>
@endpush
@section('content')
<section class="middle-section bg-grey">
    <div class="container">
    <div class="row">
    <div class="col-md-6 col-md-offset-3 white-bg">
    <div class="col-md-12 text-center">
    <h1 class="success-icon"><i class="fa fa-check-circle text-success"></i></h1></h1>
    </div>
    <div class="col-md-12 text-center">
    <h1>Thank you !</h1>
    <h1 class="text-success">Payment Success</h1>
    <p>your Order id is <b>{{$order->invoice_id}}</b></p>
    <p>you received a order confirmation shortly on your email address with invoice.</p>
    <p>your course will be active within 24 hours</p>

    </div>
    </div>
    </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
window.dataLayer = window.dataLayer || []
dataLayer.push({
  'ecommerce': {
    'purchase': {
      'actionField': {
        'id': '{{$order->invoice_id}}',                         // Transaction ID. Required for purchases and refunds.
        'affiliation': 'Online Course',
        'revenue': '{{$order->total}}',                     // Total transaction value (incl. tax and shipping)
        'tax':'{{intval($order->total) ? $order->gst_amount: 0.00}}',
        'shipping': '0.00',
        'coupon': '{{$order->coupon_code}}'
      },
      'products': [{                            // List of productFieldObjects.
    @if($order->order_for=='employer' && $item = $order->items->first()->getDetailAttribute())
    'id': '{{$item->id}}',
    'sku': '{{str_slug($item->name)}}',
    'name': '{{$item->name}}',
    'category': 'Plan',
    'price': {{$item->price}},
    'brand': 'Smart Skill Tech',
    'quantity': 1
    @elseif($item = $order->items->first()->getDetailAttribute())
    'id': '{{$item->id}}',
    'sku': '{{$item->slug}}',
    'name': '{{$item->name}}',
    'category': 'Course',
    'price': {{$item->purchase_price}},
    'brand': 'Smart Skill Tech',
    'quantity': 1
    @endif
       },
     ]
    }
  }
});
</script>
@endpush