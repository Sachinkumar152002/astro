@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">

           @include('main.account.partials.sidebar')
            <div class="col-md-9">
                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 box-white">
                                <h4>Order List</h4>
                            </div>
                            <div class="col-sm-12 col-md-12 box-white">
                                <div class="bg-white">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Date</th>
                                                        <th>Invoice ID</th>
                                                        <th>Status</th>
                                                        <th>Amount</th>
                                                        <th>Invoice</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($orders)>0)
                                                    @foreach($orders as $order)
                                                    <tr>
                                                        <th scope="row">{{$order->increment_id}}</th>
                                                        <td>{{$order->order_date}}</td>
                                                        <td>{{$order->invoice_id}}</td>
                                                        <td>{{ucfirst($order->payemnt_status?$order->payemnt_status:'Pending Payment')}}</td>
                                                        <td><i class="fa fa-inr"></i> {{number_format($order->total)}}</td>
                                                        <td class="ico-btn">
                                                            @if($order->invoice_id)
                                                            <a href="javascript:void(0)" onclick="viewInvoice('{{asset('order/invoice/'.$order->invoice_id.'.pdf')}}')"><i class="fa fa-eye"></i></a>
                                                            <a href="javascript:void(0)" onclick="printInvoice('{{asset('order/invoice/'.$order->invoice_id.'.pdf')}}')"><i class="fa fa-print"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endif

                                                </tbody>
                                            </table>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    var viewInvoice = function(pdfurl){
        window.open(pdfurl)
    }
     var printInvoice = function(pdfurl){
        var W = window.open(pdfurl);
        W.window.print()
    }
</script>
@endpush