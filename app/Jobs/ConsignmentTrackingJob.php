<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\ApiLog;
use App\Model\TrackConsignment;
use App\Model\OrderStatus;
use App\Order;
use App\Jobs\OrderStatusJob;
use App\Http\Controllers\Admin\InvoiceController;

class ConsignmentTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $consignment;
    public function __construct($consignment)
    {
        $this->consignment = $consignment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $api_token = config('vrdcms.one_delhivery_api_token');
      $consignment = $this->consignment;
      $order = Order::find($consignment->order_id);
      $waybill = $consignment->waybill;

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/api/v1/packages/json/?token=".$api_token."&waybill=".$waybill,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
      ]);
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      $api_log = new ApiLog();
      $api_log->table_name = 'consignments';
      $api_log->table_id = $consignment->id;
      $api_log->name = 'tracking status';
      $api_log->request = "token=".$api_token."&waybill=".$waybill;
      $api_log->response = $response;
      $api_log->save();


      if($err){
        $api_log->status = 'fail';
      }else{
        $api_log->status = 'success';
        $res = json_decode($response);
        $res_data = isset($res->ShipmentData) ? $res->ShipmentData:'';
        $all_scan = $res_data[0]->Shipment->Scans;
        foreach ($all_scan as $scan_data) {
          $datetime = explode('T',$scan_data->ScanDetail->StatusDateTime);
          $date = date('Y-m-d', strtotime($datetime[0]));
          $time = date('H:i:s', strtotime($datetime[1]));
          $comment = $scan_data->ScanDetail->ScannedLocation.' '.$scan_data->ScanDetail->Instructions;
          $track_consignment = TrackConsignment::where('order_id', $order->id)
          ->where('waybill', $waybill)
          ->where('consignment_id', $consignment->id)
          ->where('status', $scan_data->ScanDetail->Scan)
          ->where('status_code', $scan_data->ScanDetail->StatusCode)
          ->where('status_location', $scan_data->ScanDetail->ScannedLocation)
          ->where('date', $date)
          ->where('time', $time)
          ->where('instruction', $scan_data->ScanDetail->Instructions)
          ->first();
          if(!$track_consignment){
            $track_consignment = new TrackConsignment();
            $track_consignment->order_id = $order->id;
            $track_consignment->consignment_id = $consignment->id;
            $track_consignment->waybill = $waybill;
            $track_consignment->status = $scan_data->ScanDetail->Scan;
            $track_consignment->status_code = $scan_data->ScanDetail->StatusCode;
            $track_consignment->status_location = $scan_data->ScanDetail->ScannedLocation;
            $track_consignment->date = $date;
            $track_consignment->time = $time;
            $track_consignment->instruction = $scan_data->ScanDetail->Instructions;
            $track_consignment->comment = $comment;
            $track_consignment->save();
          }
        }

        $current_status = $res_data[0]->Shipment->Status;
        $status = $res_data[0]->Shipment->Status->Status;//Manifested, In Transit, Dispatched, Delivered
        if($status=='Manifested'){
          $status = 'shipped';
        }elseif($status=='In Transit'){
          $status = strtolower($status);
        }elseif($status=='Dispatched'){
          $status = strtolower($status);
        }elseif($status=='Delivered'){
          $status = strtolower($status);
        }elseif($status=='Not Picked'){
          $status = 'cancel';
        }else{
          $status = '';
        }
        if($status && $status!='cancel'){
          $order_status = OrderStatus::where('order_id', $order->id)->where('status', $status)->first();
          if(!$order_status){
            $order_status = new OrderStatus();
            $order_status->order_id = $order->id;
            $order_status->status = $status;
            $order_status->save();
            $order->order_status = $status;
            $order->save();
            if($status == 'shipped'){
              $invoice_controller = new InvoiceController();
              if(!Invoice::where('relation_id', $order->id)->where('relation_type', 'order')->first()){
                $invoice_controller->make($order->id);
              }
            }
            // OrderStatusJob::dispatch($order, 'user');
            $order->sendOrderStatusNotification('user');
          }

          $pickup_date = str_replace('T', ' ', $res_data[0]->Shipment->PickedupDate);
          $delivery_date = str_replace('T', ' ', $res_data[0]->Shipment->DeliveryDate);
          $expected_delivery_date = str_replace('T', ' ', $res_data[0]->Shipment->ExpectedDeliveryDate);

          $order->pickup_date = $pickup_date;
          $order->delivery_date = $delivery_date;
          $order->expected_delivery_date = $expected_delivery_date;
        }elseif($status && $status=='cancel'){
          $consignment->status = 'cancel';
        }
      }
      $api_log->save();
      $order->save();
      $consignment->save();
    }
}
