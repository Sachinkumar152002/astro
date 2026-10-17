<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\{Controller, ApiController};
use Illuminate\Http\Request;
use App\{Order,User};
use PDF;
use App\{OrderItem, CartItem,Product};
use App\Model\Invoice;
use App\Model\TrackConsignment;
use App\Model\InvoiceItem;
use App\Model\OrderStatus;
use App\Model\Consignment;
use App\Model\ApiLog;
use App\Model\OrderRequest;
use App\Model\OrderRequestResponse;
use App\Model\{OrderRequestItem,AdminSideCartItem,ShippingAddress};
use App\Model\{Receipt,HsnCode,UserTempCart};
use App\Jobs\OrderStatusJob;
use App\Jobs\{OrderRequestJob,OrderPlaceJob};
use App\Http\Controllers\Admin\InvoiceController;
use DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index(Request $request)
    {
      if(!adminCan('order', 'view')) return dd('No Permission');
    $nav = 'order';
        $breads = [
            ['name'=>'Orders']
        ];
        return view('admin.orders.list', compact('nav','breads'));
    }

    public function all(Request $request){
      $orders = Order::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

       if($product_type = $request->product_type){
         $product_types = ["product" => 1 , "pooja" => 2, "service" => 3];
           $orders->where('product_type', $product_types[$product_type]);
       }

       if($order_number = $request->order_number){
          $orders->where('order_number', 'like', "%$order_number%");
       }
       if($invoice_number = $request->invoice_number){
         $invoices_ids = Invoice::whereRaw('CONCAT(prefix, number) like ?', ["%$invoice_number%"])->where('relation_type', 'order')->pluck('relation_id')->toArray();
           $orders->whereIn('id', $invoices_ids);
       }
       if($order_status = $request->order_status){
          $orders->where('order_status', $order_status);
       }
      if($status = $request->status){
          $orders->where('status', $status);
      }
      if($payment_status = $request->payment_status){
          $orders->where('payment_status', "like", "%$payment_status%");
      }
      if($updated_at_from = $request->updated_at_from){
          $orders->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to){
          $orders->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $orders = $orders->paginate(10);

      $data = [
          'rows' => view('admin.orders.listrows',['orders'=>$orders])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$orders])->render(),
      ];
      return response()->json($data, 200);
    }

    public function form(Request $request){
        $nav = 'order'; // Navigation identifier
        $id = $request->id; // Course ID (for edit)
        $breads = [
            ['name' => 'Order', 'url' => route('admin.order')],
            ['name' => ($id ? 'Edit' : 'Add')]
        ];

        $order = $id ? Order::with(['orderItems.product'])->find($id) : null;
        if(!$order) return dd('no order');
        $consignment = $order->consignment?:null;
        $trackConsignment = [];
        if($consignment){
          $trackConsignment = TrackConsignment::where('consignment_id', $consignment->id)->get();
        }

        $apiController = new ApiController();
        $pdf_url = $apiController->getResponse($order->id);

        return view('admin.orders.form', compact('nav', 'breads', 'order', 'consignment', 'trackConsignment', "pdf_url"));
    }

    public function submit(Request $request) {
    $id = $request->id;
      if(!$id && !adminCan('order', 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan('order', 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
    $this->validate($request, [
    'order_status' => 'required|not_in:Select',
        ],[
    'order_status.required' => 'Order Status is required',
    ]);
      $order = Order::find($id);
      $exist = null;
      if(!$order){
        return response()->json(['message'=>'Order not found'], 402);
      }
      if($order->order_status=='pending' || $order->order_status=='cancel'){
        return response()->json(['errors' =>['order_status'=>['Invalid Action. Order must be canceled or pending']]], 402);
      }
      $order_status = $request->order_status;
      if($order_status == 'delivered'){
        $exist = OrderStatus::where('order_id', $id)->where('status', 'dispatched')->first();
        if(!$exist){
          return response()->json(['errors' =>['order_status'=>['Please select first Dispatch']]], 402);
        }
      }elseif($order_status == 'dispatched'){
        $exist = OrderStatus::where('order_id', $id)->where('status', 'in transit')->first();
        if(!$exist){
          return response()->json(['errors' =>['order_status'=>['Please select first In Transit']]], 402);
        }
      }elseif($order_status == 'in transit'){
        $exist = OrderStatus::where('order_id', $id)->where('status', 'shipped')->first();
        if(!$exist){
          return response()->json(['errors' =>['order_status'=>['Please select first Shipped']]], 402);
        }
      }elseif($order_status == 'shipped'){
        $exist = OrderStatus::where('order_id', $id)->where('status', 'ordered')->first();
        if(!$exist){
          return response()->json(['errors' =>['order_status'=>['Please select first Ordered']]], 402);
        }
        if($order->cod == 0){
          $invoice_controller = new InvoiceController();
          if(!Invoice::where('relation_id', $order->id)->where('relation_type', 'order')->first()){
            $invoice_controller->make($order->id);
          }
        }
      }elseif($order_status == 'cancel'){
        $exist = OrderStatus::where('order_id', $id)->where('status', 'shipped')->first();
        if($exist){
          return response()->json(['errors' =>['order_status'=>['Cannot be canceled already shipped']]], 402);
        }
      }
      $exist = OrderStatus::where('order_id', $id)->where('status', $order_status)->first();
      if($exist){
        return response()->json(['errors' =>['order_status'=>['status already exist']]], 402);
      }
      $orderStatus = OrderStatus::where('order_id', $id)->where('status', $order_status)->first();
    if(!$orderStatus){
        $orderStatus = new OrderStatus;
    }
      $orderStatus->order_id = $id;
      $orderStatus->status = $order_status;
      $orderStatus->save();
      $order->order_status = $order_status;
      if($order_status == "delivered"){
        $order->delivery_date = date("Y-m-d H:i:s");
        // Credit pending commissions if credit_time is after_completion
        \App\Services\CommissionService::creditPendingCommissions($order);
      }
      if($order_status == "cancel"){
        // Cancel commissions for this order
        \App\Services\CommissionService::cancelOrderCommissions($order);
      }
      $order->save();
      if($request->notify){
        $order->sendOrderStatusNotification('user');
        // OrderStatusJob::dispatch($order, 'user');
      }
      // dd($request->all());
      return response()->json(['message' => 'Order Saved Successfully & mail sent', 'redirectto' => route('admin.order.edit', [$order->id])], 200);
    }

    public function delete(Request $request)
    {
      if(!adminCan('order', 'delete')) return dd('No Permission');
      if($order_id = $request->id){
        $order = Order::find($order_id);
        $order->delete();
        return response()->json(['message'=>'Order Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function isPincodeServiceable($pincode)
    {
      $api_token = config('vrdcms.one_delhivery_api_token');
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/c/api/pin-codes/json/?token=".$api_token."&filter_codes=".$pincode,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
      ]);
      $response = curl_exec($curl);
      $response = json_decode($response, true);
      if($response && count($response['delivery_codes'])){
        return true;
      }
      return false;
    }

    public function checkPincodeServiceability(Request $request)
    {
      $this->validate($request, [
        'pincode'=>'required'
      ]);
      $value = $this->isPincodeServiceable($request->pincode);
      if($value){
        return response()->json(['success'=>true, 'message'=>'This pincode is serviceable'], 200);
      }
      return response()->json(['errors'=>['pincode'=>['This pincode is not serviceable']]], 402);
    }

    private function shiprocketToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/auth/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "email" => 'kaushaldixinfotech@gmail.com',
                "password" => 'lAQ5bGE#%Imoa*0M7*r%jpJb^rf@5E^x'
            ]),
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['token'] ?? null;
    }

    public function assignShiprocketCourier($shipmentId)
    {
        $token = $this->shiprocketToken();

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/assign/awb",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "shipment_id" => $shipmentId
            ]),
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        }

        return json_decode($response, true);
    }

    public function trackShiprocketByAwb($awb)
    {
        $token = $this->shiprocketToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Shiprocket token error'
            ];
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/track/awb/" . $awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return [
                'success' => false,
                'message' => 'cURL Error',
                'error' => $err
            ];
        }

        return json_decode($response, true);
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'consignment_id' => 'required|exists:consignments,id'
        ]);

        $consignment = Consignment::find($request->consignment_id);

        if (!$consignment) {
            return response()->json([
                'success' => false,
                'message' => 'Consignment not found'
            ], 404);
        }

        // Check if AWB needs to be fetched for Shiprocket
        if ($consignment->delivery_type == 'shiprocket' || !$consignment->delivery_type) {
            // If waybill is shipment_id but no AWB yet, try to get AWB
            if ($consignment->waybill && !$consignment->awb_number && !preg_match('/^[A-Z]{2}\d{9}[A-Z]{2}$/', $consignment->waybill)) {
                // This looks like shipment_id, try to get AWB
                $assign = $this->assignShiprocketCourier($consignment->waybill);
                if ($assign && !empty($assign['awb_code'])) {
                    $consignment->awb_number = $assign['awb_code'];
                    $consignment->waybill = $assign['awb_code'];
                    $consignment->save();
                }
            }

            // Track using AWB
            $awb = $consignment->awb_number ?? $consignment->waybill;

            if (!$awb) {
                return response()->json([
                    'success' => false,
                    'message' => 'AWB number not available yet. Please try again later.'
                ], 400);
            }

            $tracking = $this->trackShiprocketByAwb($awb);
            return response()->json($tracking);
        } else {
            // Normal delivery - fetch from tracking URL
            if (!$consignment->tracking_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking URL not available'
                ], 400);
            }

            // Fetch tracking data from the tracking URL
            $trackingData = $this->fetchTrackingFromUrl($consignment->tracking_url, $consignment);

            return response()->json($trackingData);
        }
    }

    private function fetchTrackingFromUrl($trackingUrl, $consignment)
    {
        // Try to fetch tracking data from the URL
        // This is a generic implementation - you may need to customize based on courier company
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $trackingUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        // Log the request
        $apiLog = new ApiLog();
        $apiLog->table_name = 'consignments';
        $apiLog->table_id = $consignment->id;
        $apiLog->name = 'Normal delivery tracking';
        $apiLog->request = $trackingUrl;
        $apiLog->response = $response;
        $apiLog->api_status = $err ? 'fail' : 'success';
        $apiLog->save();

        if ($err || $httpCode != 200) {
            return [
                'success' => false,
                'message' => 'Unable to fetch tracking data from URL',
                'error' => $err ?: 'HTTP ' . $httpCode
            ];
        }

        // Parse HTML response to extract tracking data
        // This is a basic implementation - you may need to customize based on courier website structure
        $dom = new \DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new \DOMXPath($dom);

        // Try to find common tracking status patterns
        $trackStatus = [];

        // Common patterns to look for
        $statusPatterns = [
            '//div[contains(@class, "status")]',
            '//div[contains(@class, "tracking")]',
            '//span[contains(@class, "status")]',
            '//td[contains(@class, "status")]'
        ];

        foreach ($statusPatterns as $pattern) {
            $nodes = $xpath->query($pattern);
            if ($nodes->length > 0) {
                foreach ($nodes as $node) {
                    $text = trim($node->textContent);
                    if (!empty($text)) {
                        $trackStatus[] = [
                            'status' => $text,
                            'date' => date('Y-m-d H:i:s'),
                            'location' => ''
                        ];
                    }
                }
            }
        }

        // If no structured data found, return basic info
        if (empty($trackStatus)) {
            return [
                'success' => true,
                'data' => [
                    'track_status' => [
                        [
                            'status' => 'Tracking URL Available',
                            'status_date' => date('Y-m-d H:i:s'),
                            'location' => '',
                            'status_type' => 'info'
                        ]
                    ]
                ],
                'tracking_url' => $trackingUrl,
                'message' => 'Please check tracking URL for detailed status'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'track_status' => $trackStatus
            ],
            'tracking_url' => $trackingUrl
        ];
    }




    public function createConsignment(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('order_status', 'ordered')
            ->first();

        if (!$order) {
            return response()->json([
                'errors' => ['order' => ['Invalid order']]
            ], 422);
        }

        $deliveryType = $request->delivery_type ?? 'shiprocket';

        /** Save Consignment */
        $consignment = new Consignment();
        $consignment->order_id = $order->id;
        $consignment->user_id = $order->user_id;
        $consignment->delivery_type = $deliveryType;
        $consignment->type = 0;

        if ($deliveryType == 'normal') {
            // Normal delivery - just save to database without API call
            $this->validate($request, [
                'courier_company_name' => 'required|string',
                'awb_number' => 'required|string',
                'tracking_url' => 'required|url',
            ]);

            $consignment->courier_company_name = $request->courier_company_name;
            $consignment->awb_number = $request->awb_number;
            $consignment->tracking_url = $request->tracking_url;
            $consignment->waybill = $request->awb_number;
            $consignment->status = 'success';
            $consignment->save();

            return response()->json([
                'success' => true,
                'message' => 'Consignment created successfully'
            ]);
        } else {
            // Shiprocket delivery - call API
            $token = $this->shiprocketToken();

            if (!$token) {
                return response()->json([
                    'errors' => ['token' => ['Shiprocket token error']]
                ], 500);
            }

            $this->validate($request, [
                'weight' => 'required|numeric',
                'shipment_length' => 'required|numeric|gt:0',
                'shipment_width' => 'required|numeric|gt:0',
                'shipment_height' => 'required|numeric|gt:0',
                'pickup_time' => 'required',
                'pickup_date' => 'required|date',
                'shipping_mode' => 'required',
                'fragile' => 'required',
            ]);

            $consignment->weight = $request->weight;
            $consignment->shipment_length = $request->shipment_length;
            $consignment->shipment_width = $request->shipment_width;
            $consignment->shipment_height = $request->shipment_height;
            $consignment->pickup_time = $request->pickup_time;
            $consignment->pickup_date = $request->pickup_date;
            $consignment->shipping_mode = $request->shipping_mode;
            $consignment->fragile = $request->fragile;
            $consignment->status = 'fail';
            $consignment->save();

            /** Payload */
            $orderItems = [];
            foreach ($order->orderItems as $value) {
                $row = [
                    'name' => $value->name,
                    'sku' => $value->sku,
                    'units' => $value->quantity,
                    'selling_price' => $value->price
                ];

                array_push($orderItems, $row);
            }
            $data = [
                "order_id" => (string) $order->order_number,
                "order_date" => date('Y-m-d'),
                "pickup_location" => "work",

                "billing_customer_name" => $order->full_name,
                "billing_last_name" => "NA",
                "billing_address" => $order->address,
                "billing_city" => $order->city,
                "billing_pincode" => $order->zipcode,
                "billing_state" => $order->state,
                "billing_country" => $order->country,
                "billing_phone" => $order->phone_number,

                "shipping_is_billing" => true,

                "order_items" => $orderItems,

                "payment_method" => $order->payment_method == 'cod' ? 'COD' : 'Prepaid',
                "sub_total" => $order->total_price,

                "length" => $request->shipment_length,
                "breadth" => $request->shipment_width,
                "height" => $request->shipment_height,
                "weight" => $request->weight
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer $token"
                ],
                CURLOPT_POSTFIELDS => json_encode($data),
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            /** API Log */
            $apiLog = new ApiLog();
            $apiLog->table_name = 'consignments';
            $apiLog->table_id = $consignment->id;
            $apiLog->name = 'Shiprocket create consignment';
            $apiLog->request = json_encode($data);
            $apiLog->response = $response;
            $apiLog->save();

            if ($err) {
                $apiLog->api_status = 'fail';
                $consignment->status = 'fail';
                $consignment->save();
                $apiLog->save();
                $consignment->delete();
                return response()->json([
                    'errors' => ['shiprocket' => ['cURL error']]
                ], 422);
            }

            $res = json_decode($response, true);
            if (empty($res['shipment_id'])) {
                return response()->json([
                    'errors' => ['shiprocket' => ['Shipment creation failed']],
                    'response' => $res
                ], 422);
            }
            $shipmentId = $res['shipment_id'];

            // Save shipment_id first
            $consignment->waybill = $shipmentId; // Temporarily store shipment_id
            $consignment->pickup_location = 'PRIMARY';
            $consignment->package_count = 1;
            $consignment->status = 'success';
            $consignment->save();

            // Try to assign courier and get AWB (may not be immediate)
            $assign = $this->assignShiprocketCourier($shipmentId);

            if ($assign && !empty($assign['awb_code'])) {
                // AWB available immediately
                $consignment->awb_number = $assign['awb_code'];
                $consignment->waybill = $assign['awb_code'];
                $consignment->save();

                $apiLog->api_status = 'success';
                $apiLog->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Shipment created successfully. AWB: ' . $assign['awb_code'],
                    'awb_available' => true,
                    'awb' => $assign['awb_code']
                ]);
            } else {
                // AWB not available yet, shipment created successfully but AWB pending
                $apiLog->api_status = 'success';
                $apiLog->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Shipment created successfully. AWB will be assigned shortly.',
                    'awb_available' => false,
                    'shipment_id' => $shipmentId
                ]);
            }

            return response()->json([
                'errors' => ['shiprocket' => ['Invalid Shiprocket response']]
            ], 422);
        }
    }



    // public function createConsignment(Request $request){
    //   $this->validate($request,[
    //     'order_id'=>'required',
    //     'weight'=>'required', //gm
    //     'shipment_length'=>'required|numeric|gt:0', //cm
    //     'shipment_width'=>'required|numeric|gt:0', //cm
    //     'shipment_height'=>'required|numeric|gt:0', //cm
    //     'pickup_time'=>'required', //24hrs
    //     'pickup_date'=>'required',
    //     'shipping_mode'=>'required', //1->Express, 2->Air
    //     'fragile'=>'required', //1->Yes, 2->No
    //   ]);
    //   if(($request->shipment_length+$request->shipment_width+$request->shipment_height) < 15){
    //     return response()->json([
    //       'errors'=>['shipment_length' => ['Length + Breadth + Height should be at-least 15 cm']]
    //     ], 422);
    //   }
    //   $today_date = date('Y-m-d');
    //   if( strtotime($request->pickup_date.' '.$request->pickup_time) < strtotime('now+5hours') ){
    //     return response()->json([
    //       'errors'=>['pickup_date' => ['Pickup date time should be after 5 hrs later from now. ']]
    //     ], 422);
    //   }
    //   $order_id = $request->order_id;
    //   $api_token = config('vrdcms.one_delhivery_api_token');
    //   $order = Order::where('id', $request->order_id)->where('order_status', 'ordered')->first();
    //   if(!$order){
    //     return response()->json(['errors' =>['shipping_mode'=>["Can't create shippment. Shippment already created or Invalid order"]]], 402);
    //   }
    //   $value = $this->isPincodeServiceable($order->zipcode);
    //   if(!$value){
    //     return response()->json(['success'=>false, 'errors'=>['pincode'=>['This pincode is not serviceable']]], 402);
    //   }
    //   $weight = $request->weight;
    //   $shipment_length = $request->shipment_length;
    //   $shipment_width = $request->shipment_width;
    //   $shipment_height = $request->shipment_height;
    //   $pickup_time = $request->pickup_time;
    //   $pickup_date = $request->pickup_date;
    //   $shipping_mode = $request->shipping_mode;
    //   $fragile = $request->fragile;
    //   $document_type = 1; //1->DOX, 2->NDOX

    //   $consignment = new Consignment();
    //   $consignment->order_id = $order->id;
    //   $consignment->user_id = $order->user_id;
    //   $consignment->weight = $weight;
    //   $consignment->shipment_length = $shipment_length;
    //   $consignment->shipment_width = $shipment_width;
    //   $consignment->shipment_height = $shipment_height;
    //   $consignment->shipping_mode = $shipping_mode;
    //   $consignment->fragile = $fragile;
    //   $consignment->pickup_time = $pickup_time;
    //   $consignment->pickup_date = $pickup_date;
    //   $consignment->status = "fail";
    //   $consignment->type = 0;//order
    //   $consignment->save();

    //   $data = [];
    //   $shipments = [];
    //   $shipments['add'] = sprintf("%s, %s, %s, %s, %s", $order->address, $order->city, $order->state, $order->country, $order->zipcode);
    //   $shipments['seller_add'] = "IIAG Jyotish Park, 1887, sector 7-8 Dividing Rd, CISF, Sector 8, Faridabad, Haryana, India 121006";
    //   $shipments['seller_name'] = "IIAG Jyotish Sansthan";
    //   $shipments['shipping_mode'] = "Express";
    //   if($shipping_mode == 'air'){
    //     $data['shipping_mode'] = "Air";
    //   }
    //   $shipments['commodity_value'] = $order->total_price;
    //   $shipments['address_type'] = "office";
    //   $shipments['phone'] = $order->phone_number;
    //   $shipments['payment_mode'] = "Prepaid";
    //   $shipments['name'] = $order->full_name;
    //   $shipments['pin'] = $order->zipcode;
    //   // $shipments['order'] = (string) $order->id.rand(0, 100);
    //   $shipments['order'] = (string) $order->order_number;
    //   // $shipments['client'] = "IIAG Jyotish Sansthan";
    //   $shipments['client'] = "1d9b00-IIAGJYOTISHSANSTHAN-do";
    //   $shipments['seller_gst_tin'] = "06AABHY5236P1ZK";
    //   $shipments['client_gst_tin'] = 0;
    //   $shipments['city'] = $order->city;
    //   $shipments['country'] = $order->country;
    //   $shipments['weight'] = $weight;
    //   $shipments['document_type'] = "NDOX";
    //   if($document_type==2){
    //     $shipments['document_type'] = "DOX";
    //   }
    //   $shipments['fragile_shipment'] = (string) ($fragile?1:0);
    //   $shipments['shipment_height'] = $shipment_height;
    //   $shipments['shipment_width'] = $shipment_width;
    //   $shipments['shipment_length'] = $shipment_length;
    //   $shipments['category_of_goods'] = null;
    //   $shipments['dangerous_good'] = false;
    //   $shipments['order_date'] = date('Y-m-d H:i:s', strtotime($order->created_at));
    //   $shipments['total_amount'] = $order->total_price;
    //   $shipments['return_add'] = "IIAG Jyotish Park, 1887, sector sector 7-8 Dividing Rd, CISF, Sector 8, Faridabad, Haryana, India 121006";
    //   $shipments['return_pin'] = "121006";
    //   $shipments['return_name'] = "IIAG Jyotish Sansthan";
    //   $shipments['return_state'] = "Haryana";
    //   $shipments['return_city'] = "Faridabad";
    //   $shipments['return_country'] = "India";
    //   $shipments['return_phone'] = "8800850853";
    //   $shipments['quantity'] = "1";
    //   $shipments['products_desc'] = "IIAG Jyotish Sansthan product";
    //   $shipments['state'] = $order->state;

    //   $pickup_location = [];
    //   $pickup_location['name'] = "IIAG Jyotish Sansthan";

    //   $data['shipments'] = [$shipments];
    //   $data['pickup_location'] = $pickup_location;

    //   $consignment_request = json_encode($data);
    //   $request_data = 'format=json&data='.$consignment_request;

    //   $curl = curl_init();
    //   curl_setopt_array($curl, [
    //     CURLOPT_URL => "https://track.delhivery.com/api/cmu/create.json",
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 100,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => "POST",
    //     CURLOPT_POSTFIELDS => $request_data,
    //     CURLOPT_HTTPHEADER => [
    //       "Authorization: Token ".$api_token,
    //       "Content-Type: text/plain"
    //     ],
    //   ]);
    //   $response = curl_exec($curl);
    //   $err = curl_error($curl);
    //   curl_close($curl);


    //   $consignment->pickup_location = $pickup_location['name'];
    //   $consignment->package_count = 1;
    //   $consignment->save();

    //   $api_log = new ApiLog();
    //   $api_log->table_name = 'consignments';
    //   $api_log->table_id = $consignment->id;
    //   $api_log->name = 'create consignment';
    //   $api_log->request = $request_data;
    //   $api_log->response = $response;
    //   $api_log->save();

    //   $consignment->consignment_api_response = $response;
    //   $consignment->save();

    //   $consignment_response = json_decode($response);
    //   $pickupRequestResponse = null;
    //   if($err){
    //     $api_log->api_status = 'fail';
    //     $consignment->status = 'fail';
    //     $consignment->save();
    //     $api_log->save();
    //     $consignment->delete();
    //     return response()->json(['errors' =>['shipping_mode'=>["Failed. Check code serviceable or not if yes try again"]]], 402);
    //   }else{
    //     if(isset($consignment_response->packages[0])){
    //       // $consignment->status = $consignment_response->packages[0]->status;
    //       if($consignment_response->packages[0]->status == 'Success'){
    //         $api_log->api_status = 'success';
    //         $consignment->status = 'success';
    //         $consignment->waybill = $consignment_response->packages[0]->waybill;
    //         $consignment->consignment_api_response = '';
    //         $consignment->save();
    //         $pickupRequestResponse = $this->pickupRequest($consignment);
    //       }
    //     }
    //     $api_log->save();
    //     $consignment->save();
    //   }
    //   if($consignment->status!='success'){
    //     return response()->json(['errors' =>['shipping_mode'=>["Error in creating shippment."]]], 402);
    //   }
    //   if(!$pickupRequestResponse){
    //     return response()->json(['errors' =>['shipping_mode'=>["Failed. Pickup Request"]]], 402);
    //   }
    //   return response()->json(['success'=>true, 'message'=>'Shippment created successfully'], 200);
    // }

    public function pickupRequest(Consignment $consignment){
      $api_token = config('vrdcms.one_delhivery_api_token');
      $data = [];
      $data['pickup_time'] = $consignment->pickup_time;
      $data['pickup_date'] = $consignment->pickup_date;
      $data['pickup_location'] = $consignment->pickup_location;
      $data['expected_package_count'] = $consignment->package_count;

      $pickup_request = json_encode($data);

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/fm/request/new/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 100,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
          "Authorization: Token ".$api_token,
          "Content-Type: multipart/form-data"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      $api_log = new ApiLog();
      $api_log->table_name = 'consignments';
      $api_log->table_id = $consignment->id;
      $api_log->name = 'pickup request for consignment';
      $api_log->request = $pickup_request;
      $api_log->response = $response;
      $api_log->save();

      $consignment->pickup_api_response = $response;
      $consignment->save();

      $pickup_response = json_decode($response);

      $success = false;

      if($err){
        $api_log->api_status = 'fail';
      }else{
        if(isset($pickup_response->pickup_id)){
          $api_log->api_status = 'success';
          $success = true;
          $consignment->pickup_id = $pickup_response->pickup_id;
          $consignment->pickup_api_response = '';
        }
      }
      $consignment->save();
      $api_log->save();
      return $success;
    }

    public function rePickupRequest(Request $request){
      $consignment = Consignment::find($request->consignment_id);
      $api_token = config('vrdcms.one_delhivery_api_token');
      $data = [];
      $data['pickup_date'] = $request->pickup_date?$request->pickup_date:$consignment->pickup_date;
      $data['pickup_time'] = $request->pickup_time?$request->pickup_time:$consignment->pickup_time;
      $data['pickup_location'] = $consignment->pickup_location;
      $data['expected_package_count'] = $consignment->package_count;

      $pickup_request = json_encode($data);

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/fm/request/new/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 100,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
          "Authorization: Token ".$api_token,
          "Content-Type: multipart/form-data"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      $api_log = new ApiLog();
      $api_log->table_name = 'consignments';
      $api_log->table_id = $consignment->id;
      $api_log->name = 'Re pickup request for consignment';
      $api_log->request = $pickup_request;
      $api_log->response = $response;
      $api_log->save();

      $consignment->pickup_api_response = $response;
      $consignment->save();

      $pickup_response = json_decode($response);

      $success = false;

      if($err){
        $api_log->api_status = 'fail';
      }else{
        if(isset($pickup_response->pickup_id)){
          $api_log->api_status = 'success';
          $success = true;
          $consignment->pickup_id = $pickup_response->pickup_id;
          $consignment->pickup_api_response = '';
          $consignment->pickup_date = $data['pickup_date'];
          $consignment->pickup_time = $data['pickup_time'];
        }
      }
      $consignment->save();
      $api_log->save();
      if($success){
        return response()->json(['success'=>true, 'message'=>'Pickup request successfully'], 200);
      }else{
        return response()->json(['success'=>false, 'message'=>'Error in Pickup request'], 402);
      }
      return $success;
    }

    public function cancelShippment(Request $request){
      $this->validate($request, [
        'consignment_id'=>'required'
      ]);
      $api_token = config('vrdcms.one_delhivery_api_token');

      $consignment = Consignment::find($request->consignment_id);
      $order = Order::find($consignment->order_id);

      $data['waybill'] = $consignment->waybill;
      $data['cancellation'] = "true";

      $cancel_request = json_encode($data);

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/api/p/edit",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $cancel_request,
        CURLOPT_HTTPHEADER => [
          "Accept: application/json",
          "Authorization: Token ".$api_token,
          "Content-Type: application/json"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      $api_log = new ApiLog();
      $api_log->table_name = 'consignments';
      $api_log->table_id = $consignment->id;
      $api_log->name = 'cancel consignment';
      $api_log->request = $cancel_request;
      $api_log->response = $response;
      $api_log->save();

      if($err){
        $api_log->api_status = 'fail';
        $api_log->save();
        return response()->json(['success'=>false, 'errors' =>['shipping_mode'=>["Error in cancelling shippment"]]], 402);
      }else{
        $api_log->api_status = 'success';
        $api_log->save();
        $consignment->status = 'cancel';
        // if($request->has('cancel_order') && $request->cancel_order){
        //   $order->order_status = 'cancel';
        // }
        // $order->save();
        $consignment->save();
      }
      return response()->json(['success'=>true, 'message'=>'Shippment canceled successfully'], 200);
    }

    public function createReturnConsignment(Request $request){
      $this->validate($request, [
        'receipt_id'=>'required',
        'consignment_id'=>'required|not_in:0',
        'pickup_time'=>'required',
        'pickup_date'=>'required',
      ]);
      $api_token = config('vrdcms.one_delhivery_api_token');

      $receipt_id = $request->receipt_id;
      $consignment_id = $request->consignment_id;
      $pickup_time = $request->pickup_time;
      $pickup_date = $request->pickup_date;

      $consignment = Consignment::find($consignment_id);
      $receipt = Receipt::find($receipt_id);
      $order = Order::find($receipt->relation_id);

      $weight = $consignment->weight;
      $shipment_length = $consignment->shipment_length;
      $shipment_width = $consignment->shipment_width;
      $shipment_height = $consignment->shipment_height;
      $pickup_time = $pickup_time;
      $pickup_date = $pickup_date;
      $shipping_mode = $consignment->shipping_mode;
      $fragile = $consignment->fragile; //1->yes , 0->no
      $document_type = 1; //1->DOX, 2->NDOX

      $consignment = new Consignment();
      $consignment->order_id = $receipt->relation_id;
      $consignment->user_id = $receipt->user_id;
      $consignment->weight = $weight;
      $consignment->shipment_length = $shipment_length;
      $consignment->shipment_width = $shipment_width;
      $consignment->shipment_height = $shipment_height;
      $consignment->shipping_mode = $shipping_mode;
      $consignment->fragile = $fragile;
      $consignment->pickup_time = $pickup_time;
      $consignment->pickup_date = $pickup_date;
      $consignment->status = "Fail";
      $consignment->type = 1;//return
      $consignment->save();

      $data = [];
      $shipments = [];
      $shipments['add'] = sprintf("%s, %s, %s, %s, %s", $order->address, $order->city, $order->state, $order->country, $order->zipcode);
      $shipments['seller_add'] = "IIAG Jyotish Park, 1887, sector 7-8 Dividing Rd, CISF, Sector 8, Faridabad, Haryana, India 121006";
      $shipments['seller_name'] = "IIAG Jyotish Sansthan";
      $shipments['shipping_mode'] = $shipping_mode;
      $shipments['commodity_value'] = $receipt->total_amount;
      $shipments['address_type'] = "office";
      $shipments['phone'] = $order->phone_number;
      $shipments['payment_mode'] = "Pickup";
      $shipments['name'] = $order->full_name;
      $shipments['pin'] = $order->zipcode;
      $shipments['order'] = $order->id;
      // $shipments['client'] = "IIAG Jyotish Sansthan";
      $shipments['client'] = "1d9b00-IIAGJYOTISHSANSTHAN-do";
      $shipments['seller_gst_tin'] = "06AABHY5236P1ZK";
      $shipments['client_gst_tin'] = 0;
      $shipments['city'] = $order->city;
      $shipments['country'] = $order->country;
      $shipments['weight'] = $weight;
      $shipments['document_type'] = "NDOX";
      if($document_type==2){
        $shipments['document_type'] = "DOX";
      }
      $shipments['fragile_shipment'] = $fragile?true:false;
      $shipments['shipment_height'] = $shipment_height;
      $shipments['shipment_width'] = $shipment_width;
      $shipments['shipment_length'] = $shipment_length;
      $shipments['category_of_goods'] = null;
      $shipments['dangerous_good'] = false;
      $shipments['order_date'] = date('Y-m-d H:i:s', strtotime($order->created_at));
      $shipments['total_amount'] = $receipt->total_amount;
      $shipments['return_add'] = "IIAG Jyotish Park, 1887, sector 7-8 Dividing Rd, CISF, Sector 8, Faridabad, Haryana, India 121006";
      $shipments['return_pin'] = "121006";
      $shipments['return_name'] = "IIAG Jyotish Sansthan";
      $shipments['return_state'] = "Haryana";
      $shipments['return_city'] = "Faridabad";
      $shipments['return_country'] = "India";
      $shipments['return_phone'] = "8800850853";
      $shipments['quantity'] = "1";
      $shipments['products_desc'] = "IIAG Jyotish Sansthan product";
      $shipments['state'] = $order->state;

      $pickup_location = [];
      $pickup_location['name'] = "IIAG Jyotish Sansthan";

      $data['shipments'] = $shipments;
      $data['pickup_location'] = $pickup_location;

      $consignment_request = json_encode($data);
      $request_data = 'format=json&data='.$consignment_request;

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://track.delhivery.com/api/cmu/create.json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 100,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $request_data,
        CURLOPT_HTTPHEADER => [
          "Authorization: Token ".$api_token,
          "Content-Type: text/plain"
        ],
      ]);
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);


      $consignment->pickup_location = $pickup_location['name'];
      $consignment->package_count = 1;
      $consignment->save();

      $api_log = new ApiLog();
      $api_log->table_name = 'consignments';
      $api_log->table_id = $consignment->id;
      $api_log->name = 'create consignment';
      $api_log->request = $consignment_request;
      $api_log->response = $response;
      $api_log->save();

      $consignment->consignment_api_response = $response;
      $consignment->save();

      $consignment_response = json_decode($response);
      $pickupRequestResponse = null;
      if($err){
        $api_log->api_status = 'fail';
        $consignment->status = 'fail';
        $consignment->save();
        $api_log->save();
        return response()->json(['success'=>false, 'errors' =>['shipping_mode'=>["Failed. Check code serviceable or not if yes try again"]]], 402);
      }else{
        $api_log->api_status = 'success';
        $consignment->status = 'success';
        if(isset($consignment_response->packages[0])){
          // $consignment->status = $consignment_response->packages[0]->status;
          if($consignment_response->packages[0]->status == 'Success'){
            $consignment->waybill = $consignment_response->packages[0]->waybill;
            $consignment->consignment_api_response = '';
            $consignment->save();
            $pickupRequestResponse = $this->pickupRequest($consignment);
          }
        }
        $api_log->save();
        $consignment->save();
      }
      if(!$pickupRequestResponse){
        return response()->json(['success'=>false, 'errors' =>['shipping_mode'=>["Failed. Pickup Request"]]], 402);
      }
      return response()->json(['success'=>true, 'message'=>'Shippment created successfully'], 200);
    }

    public function submitRequestResponse(Request $request){
      $this->validate($request, [
        'request_id'=>'required|not_in:0',
        'request_status'=>'required|not_in:0',
        'message'=>'required',
      ]);
      $request_id = $request->request_id;
      $request_status = $request->request_status;
      $message = $request->message;
      $order_request = OrderRequest::find($request_id);
      if($order_request->request_type=='return' && $request->request_status==1){
        $this->validate($request, [
          'pickup_date'=>'required',
          'pickup_time'=>'required',
        ]);
      }
      $admin = auth()->guard('admin')->user();

      $order_request = OrderRequest::find($request_id);
      $order_request->status = $request_status;
      $order_request->save();

      $order_request_response = new OrderRequestResponse();
      $order_request_response->request_id = $order_request->id;
      $order_request_response->order_id = $order_request->order_id;
      $order_request_response->user_id = $order_request->user_id;
      $order_request_response->admin_id = $admin->id;
      $order_request_response->message = $message;
      $order_request_response->save();

      $order = Order::find($order_request->order_id);
      $consignment = Consignment::where('order_id', $order->id)->whereIn('status', ['success', 'cancel'])->first();
      $email = $order->billing_email;
      if($order->user){
        $email = $order->user->email;
      }

      if($request_status == 1){//accepted
        $include_shipping = true;
        $item_id_array = [];
        $invoice_controller = new InvoiceController();
        if($order_request->request_type == 'cancel'){
          if($consignment){
            $include_shipping=false;
            $cancelShippmentResponse = $this->cancelShippment(new Request([
              'consignment_id'=>$consignment->id
            ]));
          }
          $items_id_array = OrderItem::where('order_id', $order_request->order_id)->pluck('id')->toArray();
          $pdfUlr = $invoice_controller->makeReceipt($order->id, $items_id_array, 1, $include_shipping);
          if($order->invoice){
            $pdfUlr = $invoice_controller->makeReturnInvoice($order->id, $items_id_array, 1, $include_shipping);
          }
          $order->order_status = 'cancel';
          $order->save();
          $order_status = new OrderStatus();
          $order_status->order_id = $order->id;
          $order_status->status = 'cancel';
          $order_status->save();

          // Cancel commissions for this order
          \App\Services\CommissionService::cancelOrderCommissions($order);
        }elseif($order_request->request_type == 'return'){
          $include_shipping = false;
          $items_id_array = OrderRequestItem::where('request_id', $order_request->id)->pluck('item_id')->toArray();
          $makeReceiptResponse = $invoice_controller->makeReceipt($order_request->order_id, $items_id_array, 2, $include_shipping);
          $createReturnConsignmentResponse = $this->createReturnConsignment(new Request([
            'receipt_id'=>$makeReceiptResponse['receipt_id'],
            'consignment_id'=>$consignment?$consignment->id:0,
            'pickup_time'=>$request->pickup_time,
            'pickup_date'=>$request->pickup_date,
          ]));
          $createReturnConsignmentResponse = $createReturnConsignmentResponse->getData();
          if(!$createReturnConsignmentResponse->success){
            return response()->json(['success'=>false, 'errors' =>['shipping_mode'=>$createReturnConsignmentResponse->errors->shipping_mode]], 402);
          }
          $order->order_status = 'return';
          $order->save();
          $order_status = new OrderStatus();
          $order_status->order_id = $order->id;
          $order_status->status = 'return';
          $order_status->save();

          // Cancel commissions for returned items
          \App\Services\CommissionService::cancelOrderCommissions($order);
          $consignment = Consignment::where('order_id', $order->id)->where('status', 'success')->where('id', 'desc')->first();
        }
        OrderRequestJob::dispatch($email, $order, $order_request_response, $consignment, $order_request->request_type, 1);
      }else{ //rejected
        OrderRequestJob::dispatch($email, $order, $order_request_response, null, $order_request->request_type, 2);
      }

      return response()->json(['success'=>true, 'message'=>'Response Submit successfully'], 200);
    }

    public function orderReturnReceived(Request $request){
      $this->validate($request, [
        'request_id'=>'required|not_in:0',
      ]);
      $include_shipping = false;
      $order_request = OrderRequest::find($request->request_id);
      $order = Order::find($order_request->order_id);
      if($order->received){
        return response()->json(['success'=>false, 'errors'=>['received'=>['Invalid Action']]], 402);
      }
      $items_id_array = OrderRequestItem::where('request_id', $order_request->id)->pluck('item_id')->toArray();
      if($order->invoice){
        $invoice_controller = new InvoiceController();
        $invoice_controller->makeReturnInvoice($order->id, $items_id_array, 2, $include_shipping);
      }
      $order->received = 1;
      $order->save();
      return response()->json(['success'=>true, 'message'=>'Save successfully'], 200);
    }

    public function handlePaymentStatus(Request $request){
      $this->validate($request, [
        'order_id'=>'required|gt:0',
        'payment_status'=>'required|in:pending,cod,success',
      ]);

      $payment_status = $request->payment_status;
      $message = "Payment status updated successfully.";

      $order = Order::find($request->order_id);
      $order->payment_status = $payment_status;
      $order->save();

      $createInvoiceForOrderStatus = ['shipped', 'in transit', 'dispatch', 'delivered'];

      if($payment_status == "success" && in_array($order->order_status, $createInvoiceForOrderStatus)){
        $invoiceController = new InvoiceController();
        if(Invoice::where('relation_id', $order->id)->where('relation_type', 'order')->count() == 0){
          $invoiceController->make($order->id);
        }
      }

      return response()->json([
        'success'=>true,
        'message'=> $message
      ], 200);
    }

    public function downloadSlip(Request $request){
      $invoice_id = $request->invoice_id;
      $invoice = Invoice::find($invoice_id);
      if($invoice == null) return "invoice not found";

      $order = Order::find($invoice->relation_id);
      if($order == null) return "order not found";

      $pdf = PDF::loadView('admin.orders.pdf.slip', compact('invoice', 'order'));
      return $pdf->stream('slip.pdf');
      return view('admin.orders.pdf.slip', compact("invoice", 'order'));
    }

    public function show()
    {
      $invoice = [
            'company' => [
                'name' => 'MK DESIGNS',
                'address1' => 'D2/146, Sector 10, DLF Area',
                'address2' => 'Near Park Hospital, Faridabad, Haryana – 121006',
                'gst' => '06DWYPK3230D1ZH',
                'phone' => '9990944033',
            ],
            'invoice_no' => 'Retail00944',
            'order_no' => 'MK-1426',
            'invoice_date' => '2025-10-08',
            'ship_to' => [
                'name' => 'Jagdeep Singh',
                'address' => 'H No 114, St J, Near Clock Tower, Delta City, Malerkotla Road, Ranian',
                'city' => 'Ludhiana, Punjab',
                'pincode' => '141122',
                'phone' => '9090900601',
            ],
        ];
        // return HTML view
        return view('admin.orders.pdf.slip', compact('invoice'));
    }

    public function exportCsv(Request $request){
      $orders = Order::orderBy('updated_at', 'desc');

      if($product_type = $request->product_type){
        $product_types = ["product" => 1 , "pooja" => 2, "service" => 3];
        $orders->where('product_type', $product_types[$product_type]);
      }

      if($order_number = $request->order_number){
        $orders->where('order_number', 'like', "%$order_number%");
      }

      if($invoice_number = $request->invoice_number){
        $invoices_ids = Invoice::whereRaw('CONCAT(prefix, number) like ?', ["%$invoice_number%"])->where('relation_type', 'order')->pluck('relation_id')->toArray();
        $orders->whereIn('id', $invoices_ids);
      }

      if($order_status = $request->order_status){
        $orders->where('order_status', $order_status);
      }

      if($status = $request->status){
        $orders->where('status', $status);
      }

      if($payment_status = $request->payment_status){
        $orders->where('payment_status', "like", "%$payment_status%");
      }

      if($updated_at_from = $request->updated_at_from){
        $orders->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }

      if($updated_at_to = $request->updated_at_to){
        $orders->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }

      $orders = $orders->get();
      $filePath = public_path('csvs/orders/list.csv');

      $handle = fopen($filePath, 'w');

      fputcsv($handle, ["ORDER-ID", "Product Type", "Payment Status", "Payment Method", "Order Status", "Created at", "Currency", "Subtotal", "Coupon Amount", "Shipping", "Taxes", "Total", "Coupon Code", "Item Quanity", "Item Name", "Item Price", "Item Taxes", "Billing Name", "Billing Email", "Billing Phone", "Billing Address", "Billing Pincode", "Billing City", "Billing State", "Billing Country", "Shipping Name", "Shipping Email", "Shipping Phone", "Shipping Address", "Shipping Pincode", "Shipping City", "Shipping State", "Shipping Country"]);
      // Add rows
      $product_types = ["", "Product", "Pooja", "Service"];
      foreach ($orders as $order){
        $orderItems = $order->orderItems;
        $row = 1;
        foreach ($orderItems as $key => $item){
          if($row == 1){
            fputcsv($handle, [
              $order->order_number,
              $product_types[$order->product_type],
              $order->payment_status,
              $order->payment_method,
              $order->order_status,
              $order->created_at,
              "INR",
              ($order->sub_total - $order->discount_amount),
              $order->coupon_amount,
              $order->shipping_price,
              ($order->igst+$order->cgst+$order->sgst),
              $order->total_price,
              $order->coupon,
              $item->quantity,
              $item->name,
              $item->price,
              ($item->igst_amount+$item->cgst_amount+$item->sgst_amount),
              $order->billing_full_name,
              $order->billing_email,
              $order->billing_phone_number,
              $order->billing_address,
              $order->billing_zipcode,
              $order->billing_city,
              $order->billing_state,
              $order->billing_country,
              $order->full_name,
              $order->email,
              $order->phone_number,
              $order->address,
              $order->zipcode,
              $order->city,
              $order->state,
              $order->country
            ]);
          }else{
            fputcsv($handle, [
              "",
              $product_types[$order->product_type],
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              $item->quantity,
              $item->name,
              $item->price,
              ($item->igst_amount+$item->cgst_amount+$item->sgst_amount),
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              "",
              ""
            ]);
          }
          $row++;
        }
      }

      fclose($handle);

      $message = "Data Export Successfully";
      $redirectTo = url("csvs/orders/list.csv");

      return response()->json([
        'success'=>true,
        'message'=> $message,
        'redirectTo'=> $redirectTo,
      ], 200);
    }

    public function redirectToCheckout(Request $request){
      $customerController = new CustomerController();
      $customerController->login($request);

      $user_id = $request->user_id ?: 0;
      $user_type = "user";
      $order_id = $request->order_id ?: 0;
      $order = Order::where("id", $order_id)->where("user_id", $user_id)->where("user_type", $user_type)->first();
      if($order == null){
        return redirect()->back();
      }
      $url = route("checkout", ["shop", "cart", "Product", "items"]);
      $orderItems = OrderItem::where("order_id", $order_id)->get();
      $cartItemIds = [];
      foreach ($orderItems as $index => $item) {
        $cartItem = CartItem::where("user_id", $user_id)
        ->where("user_type", $user_type)
        ->where("item_id", $item->product_id)
        ->where("type", "shop")
        ->first();
        if(!$cartItem){
          $cartItem = new CartItem();
          $cartItem->user_id = $user_id;
          $cartItem->user_type = $user_type;
          $cartItem->item_id = $item->product_id;
          $cartItem->type = "shop";
        }
        $cartItem->quantity = $item->quantity;
        $cartItem->price = $item->quantity;
        $cartItem->unit_price = $item->unit_price;
        $cartItem->item_type = $order->item_type;
        $options = $item->options  ? json_decode($item->options, true) : [];
        $formdata = isset($options["formdata"]) ? $options["formdata"] : [];
        $cartItem->formdata = json_encode($formdata);
        $cartItem->save();
        $product = $cartItem->product;
        if($product){
          $cartItemIds[] = $cartItem->id;
          if($product->type == 1){
            $url = route("checkout", ["shop", "cart", "Product", "items"]);
          }elseif($product->type == 2){
            $url = route("checkout", ["shop", "cart", "Pooja", $product->slug]);
          }elseif($product->type == 1){
            $url = route("checkout", ["shop", "cart", "Service", $product->slug]);
          }
        }
      }
      return redirect($url);
    }
    public function create(Request $request){
      $nav="ordercreate";
      $breads=[
        ['name'=>"Orders", 'url'=> route('admin.order')],
        ['name'=>"Create Order"]
      ];
      $user_id=$request->user_id?:0;
      $user_type="user";
      $user=User::find($user_id);
      $old_user_id=$request->old_user_id?:0;
      if($user_id>0){
        if($old_user_id>0){
          AdminSideCartItem::where("user_id",$old_user_id)->where("user_type",$user_type)->update(["user_id"=>$user_id]);
        }else{
          AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->delete();
          UserTempCart::where("user_id",$user_id)->where("user_type",$user_type)->delete();
        }
      }
      $shippingAddresses=ShippingAddress::where("user_id",$user_id)->where("user_type","user")->get();
      $countries = DB::table('master_countries')->select('id','name')->orderBy('name','asc')->get();
      $hsnCodes = HsnCode::get();
      return view("admin.orders.create",compact("nav","breads","user","shippingAddresses","countries","hsnCodes"));
    }
    public function usersList(Request $request){
      $query= User::where("status",1)->orderBy("name","ASC");
      $old_user_id=$request->old_user_id?:0;
      $new_user_id=$request->new_user_id?:0;
      $customer=$request->customer?$request->customer:($new_user_id?"":"#abcd#");
      if($customer){
        $query->where(function($query) use($customer){
          $query->where("name","like","%$customer%")
          ->orWhere("email","like","%$customer%")
          ->orWhere("phone","like","%$customer%");
        });
      }elseif($new_user_id>0){
        $query->where("id",$new_user_id);
      }
      $results = $query->paginate(5);
      $data = [
        'rows' => view('admin.orders.customer.listrows',["results"=>$results,"old_user_id"=>$old_user_id,"new_user_id"=>$new_user_id])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
      ];
      return response()->json($data, 200);
    }
    public function editUser(Request $request){
      $request->validate([
        "name"=>"required|min:3",
        "email"=>"required|email|unique:users,email,id,id",
        "mobile_number"=>"required|digits:10|unique:users,phone,id,id",
      ]);
      $user_id=$request->user_id?:0;
      $name=$request->name;
      $email=$request->email;
      $mobile_number=$request->mobile_number;
      // $user=User::where(function($query) use($email,$mobile_number){
      //   $query->where("phone",$mobile_number)
      //   ->orWhere("email",$email);
      // })
      // ->first();
      $user=User::where("phone",$mobile_number)->first();
      if(!$user){
        $user=new User();
        $user->name=$name;
        $user->email=$email;
        $user->phone=$mobile_number;
        $user->status=1;
        $user->is_verified=1;
        $user->save();
      }
      $redirectTo=route("admin.order.create",["user_id"=>$user->id]);
      if($user_id){
        $redirectTo=route("admin.order.create",["user_id"=>$user->id,"old_user_id"=>$user_id]);
      }
      return response()->json(["success"=>true,"message"=>"Customer Added Successfully.","redirectTo"=>$redirectTo],200);
    }
    public function productsList(Request $request){
      $productTypeName=$request->productTypeName?:"Product";
      $productTypeValue=1;
      if($productTypeName=="Service"){
        $productTypeValue=3;
      }elseif($productTypeName=="Pooja"){
        $productTypeValue=2;
      }
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $item_type=$request->item_type?:"";
      $query= Product::where("status",1)->where("type",$productTypeValue);

      // Exclude one-to-one puja products from regular puja products listing
      if($productTypeValue == 2) {
        $query->where(function($q) {
          $q->where('is_one_to_one_puja', 0)
            ->orWhereNull('is_one_to_one_puja');
        });
      }

      $query->orderBy("order","asc");
      $product_title=$request->product_title?:"#abcd#";
      if($product_title){
        $query->where("title","like","%$product_title%");
      }
      $results = $query->paginate(5);
      $productsIdArray=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_type",$item_type)->pluck("item_id")->toArray();
      $data = [
        'rows' => view('admin.orders.product.listrows',["results"=>$results,"productsIdArray"=>$productsIdArray,"productTypeName"=>$productTypeName])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
      ];
      return response()->json($data, 200);
    }
    public function addProductToAdminSideCartItem(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $product_id=$request->product_id?:0;
      $item_type=$request->item_type?:"";
      $product=Product::find($product_id);
      $currentDate = date('Y-m-d');
      if($product){
        $adminSideCartItem=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_id",$product_id)->where("item_type",$item_type)->first();
        if(!$adminSideCartItem){
          $adminSideCartItem=new AdminSideCartItem();
          $adminSideCartItem->user_id=$user_id;
          $adminSideCartItem->user_type=$user_type;
          $adminSideCartItem->item_id=$product_id;
          $adminSideCartItem->item_type=$item_type;
          $adminSideCartItem->quantity=1;
        }
        $title=$product->title;
        $productAttributes=$product->attributes;
        $productAttributeArray=[];
        foreach($productAttributes as $key=>$valueTwo){
          if($attribute = $valueTwo->attribute){
            $productAttributeArray[]="$attribute->name: $valueTwo->attribute_value";
          }
        }
        if(count($productAttributeArray)){
          $title=implode(', ', $productAttributeArray);
          $title="$product->title ($title)";
        }
        $adminSideCartItem->title=$title;
        $adminSideCartItem->image_url=$product->image?$product->image->image():no_image();
        $adminSideCartItem->base_price=$product->base_price;
        $adminSideCartItem->sale_price=$product->base_price;
        if($product->sale_price && $product->sale_price_start_date && $product->sale_price_start_date<=$currentDate && $product->sale_price_end_date<=$currentDate){
          $adminSideCartItem->sale_price=$product->sale_price;
        }
        if($productHnsCode=$product->hnsCode){
          $adminSideCartItem->hsn_code_id=$productHnsCode->id;
          $adminSideCartItem->hsn_code=$productHnsCode->hsn_code;
          $adminSideCartItem->tax_percentage=$productHnsCode->gst_percentage;
        }
        $adminSideCartItem->save();
        $adminSideCartItem->unit_base_price=$adminSideCartItem->base_price*$adminSideCartItem->quantity;
        $adminSideCartItem->unit_sale_price=$adminSideCartItem->sale_price*$adminSideCartItem->quantity;
        $adminSideCartItem->save();
      }
      return response()->json(["success"=>true,"message"=>"Add Item Successfully."],200);
    }
    public function editAdminSideCartItem(Request $request){
      $request->validate([
        "title"=>"required|min:3",
        "sale_price"=>"required",
        "quantity"=>"required|gt:0",
        "user_id"=>"required"
      ]);
      $user_id=$request->user_id;
      $user_type="user";
      $admin_side_cart_item_id=$request->admin_side_cart_item_id;
      $item_type="product";
      $hsn_code_id=$request->hsn_code_id?:0;
      $adminSideCartItem=AdminSideCartItem::find($admin_side_cart_item_id);
      if(!$adminSideCartItem){
        $adminSideCartItem=new AdminSideCartItem();
        $adminSideCartItem->user_id=$user_id;
        $adminSideCartItem->user_type=$user_type;
        $adminSideCartItem->item_id=0;
        $adminSideCartItem->item_type=$item_type;
        $adminSideCartItem->image_url=no_image();
        $adminSideCartItem->save();
      }
      $adminSideCartItem->title=$request->title;
      if($adminSideCartItem->item_id==0){
        $adminSideCartItem->base_price=$request->sale_price;
      }
      $adminSideCartItem->sale_price=$request->sale_price;
      $adminSideCartItem->quantity=$request->quantity;
      if($hsnCode=HsnCode::find($hsn_code_id)){
        $adminSideCartItem->hsn_code_id=$hsnCode->id;
        $adminSideCartItem->hsn_code=$hsnCode->hsn_code;
        $adminSideCartItem->tax_percentage=$hsnCode->gst_percentage;
      }
      $adminSideCartItem->save();
      $adminSideCartItem->unit_base_price=$adminSideCartItem->base_price*$adminSideCartItem->quantity;
      $adminSideCartItem->unit_sale_price=$adminSideCartItem->sale_price*$adminSideCartItem->quantity;
      $adminSideCartItem->save();
      return response()->json(["success"=>true,"message"=>"Item Edit Successfully."],200);
    }
    public function increaseQuantityAdminSideCartItem(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $product_id=$request->product_id?:0;
      $item_type=$request->item_type?:"";
      $adminSideCartItem=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_id",$product_id)->where("item_type",$item_type)->first();
      if($adminSideCartItem){
        $adminSideCartItem->quantity=$adminSideCartItem->quantity+1;
        $adminSideCartItem->save();
        $adminSideCartItem->unit_base_price=$adminSideCartItem->base_price*$adminSideCartItem->quantity;
        $adminSideCartItem->unit_sale_price=$adminSideCartItem->sale_price*$adminSideCartItem->quantity;
        $adminSideCartItem->save();
      }
      return response()->json(["success"=>true,"message"=>"Quantity Increased Successfully."],200);
    }
    public function decreaseQuantityAdminSideCartItem(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $product_id=$request->product_id?:0;
      $item_type=$request->item_type?:"";
      $adminSideCartItem=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_id",$product_id)->where("item_type",$item_type)->first();
      if($adminSideCartItem){
        $adminSideCartItem->quantity=$adminSideCartItem->quantity-1;
        $adminSideCartItem->save();
        $adminSideCartItem->unit_base_price=$adminSideCartItem->base_price*$adminSideCartItem->quantity;
        $adminSideCartItem->unit_sale_price=$adminSideCartItem->sale_price*$adminSideCartItem->quantity;
        $adminSideCartItem->save();
      }
      if($adminSideCartItem->quantity<1){
        $adminSideCartItem->delete();
      }
      return response()->json(["success"=>true,"message"=>"Quantity decreased Successfully."],200);
    }
    public function removeProductFromAdminSideCartItem(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $product_id=$request->product_id?:0;
      $item_type=$request->item_type?:"";
      AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_id",$product_id)->where("item_type",$item_type)->delete();
      return response()->json(["success"=>true,"message"=>"Remove Item Successfully."],200);
    }
    public function adminSideCartItemList(Request $request){
      $shipping_address_id=$request->shipping_address_id?:0;
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"";
      $item_type=$request->item_type?:"";
      $productTypeName=$request->productTypeName?:"Product";
      $coupon_code=$request->coupon_code?:"";
      $discount_amount=$request->discount_amount?:0;
      $shipping_amount=$request->shipping_amount?:0;
      $adminSideCartItems=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->where("item_type",$item_type)->get();
      $shippingAddress=ShippingAddress::find($shipping_address_id);
      $couponAmount=0;
      if($coupon_code){
        $couponController=new CouponController();
        $newRequest=new Request([
          "user_id"=>$user_id,
          "user_type"=>$user_type,
          "item_type"=>$item_type,
          "type"=>"order",
          "coupon_code"=>$coupon_code,
        ]);
        $couponControllerApplyData=$couponController->apply($newRequest);
        $couponControllerApplyData=$couponControllerApplyData->getData();
        $couponAmount=$couponControllerApplyData->coupon_amount?:0;
      }
      // dd($couponAmount);
      $cartHtml=view("admin.orders.product.cart",["adminSideCartItems"=>$adminSideCartItems,"productTypeName"=>$productTypeName,"couponAmount"=>$couponAmount,"discount_amount"=>$discount_amount])->render();
      $couponHtml="";
      // $couponHtml=view("admin.orders.product.coupon",["coupon_code"=>$coupon_code,"couponAmount"=>$couponAmount,"discount_amount"=>$discount_amount])->render();
      $amountHtml=view("admin.orders.product.amount",["adminSideCartItems"=>$adminSideCartItems,"shippingAddress"=>$shippingAddress,"couponAmount"=>$couponAmount,"discount_amount"=>$discount_amount,"shipping_amount"=>$shipping_amount])->render();
      return response()->json(["success"=>true,"message"=>"fetch Successfully.","cartHtml"=>$cartHtml,"couponHtml"=>$couponHtml,"amountHtml"=>$amountHtml],200);
    }
    public function placeOrder(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"user";
      $user=User::find($user_id);
      if($user==null){
        return response()->json(["success"=>true,"message"=>"User not found."],402);
      }
      $shipping_address_id=$request->shipping_address_id?:0;
      $shippingAddress=ShippingAddress::find($shipping_address_id);
      if($shippingAddress==null){
        return response()->json(["success"=>true,"message"=>"Delivery address is required."],422);
      }
      $billingName=$shippingName=$shippingAddress->name;
      $billingEmail=$shippingEmail=$shippingAddress->email;
      $billingPhone=$shippingPhone=$shippingAddress->phone;
      $billingCountryId=$shippingCountryId=$shippingAddress->country_id;
      $billingCountry=$shippingCountry=$shippingAddress->country;
      $billingStateId=$shippingStateId=$shippingAddress->state_id;
      $billingState=$shippingState=$shippingAddress->state;
      $billingCityId=$shippingCityId=$shippingAddress->city_id;
      $billingCity=$shippingCity=$shippingAddress->city;
      $billingAddress=$shippingAddressAddress=$shippingAddress->address;
      $billingPincode=$shippingPincode=$shippingAddress->zipcode;
      $billing_address_type=$request->billing_address_type?:"different";
      if($billing_address_type=="different"){
        $request->validate([
          "billing_name"=>"required|min:3",
          "billing_email"=>"required|email",
          "billing_phone"=>"required",
          "billing_country"=>"required|gt:0",
          "billing_state"=>"required|gt:0",
          "billing_city"=>"required|gt:0",
          "billing_address"=>"required",
          "billing_pincode"=>"required|digits:6",
        ],[
          "billing_country"=>"Billing country is required.",
          "billing_state"=>"Billing state is required.",
          "billing_city"=>"Billing city is required."
        ]);
        $billingName=$request->billing_name;
        $billingEmail=$request->billing_email;
        $billingPhone=$request->billing_phone;
        $billingCountryId=$request->billing_country;
        $country=DB::table("master_countries")->where("id",$billingCountryId)->first();
        $billingCountry=$country?$country->name:$request->billing_country;
        $billingStateId=$request->billing_state;
        $state=DB::table("master_states")->where("id",$billingStateId)->first();
        $billingState=$state?$state->name:$request->billing_state;
        $billingCityId=$request->billing_city;
        $city=DB::table("master_cities")->where("id",$billingCityId)->first();
        $billingCity=$city?$city->name:$request->billing_city;
        $billingAddress=$request->billing_address;
        $billingPincode=$request->billing_pincode;
        if($user->country_id==0 || $user->state_id==0 || $user->city_id==0){
          $user->country_id=$billingCountryId;
          $user->country=$billingCountry;
          $user->state_id=$billingStateId;
          $user->state=$billingState;
          $user->city_id=$billingCityId;
          $user->city=$billingCity;
          $user->address=$billingAddress;
          $user->pincode=$billingPincode;
          $user->save();
        }
      }
      $item_type=$request->item_type?:"product";
      $payment_method=$request->payment_method?:"payment";
      $adminSideCartItems=AdminSideCartItem::where("user_id",$user_id)->where("user_type",$user_type)->get();
      if($adminSideCartItems->count()>0){
        $order_id=$request->order_id?:0;
        $coupon_id=0;
        $coupon_code=$request->coupon_code?:"Discount";
        $couponAmount=$request->discount_amount?:0;
        $coupon_apply_on="product";
        // if($coupon_code){
        //   $couponController=new CouponController();
        //   $newRequest=new Request([
        //     "user_id"=>$user_id,
        //     "user_type"=>$user_type,
        //     "item_type"=>$item_type,
        //     "type"=>"order",
        //     "coupon_code"=>$coupon_code,
        //   ]);
        //   $couponControllerApplyData=$couponController->apply($newRequest);
        //   $couponControllerApplyData=$couponControllerApplyData->getData();
        //   $coupon_id=$couponControllerApplyData->coupon_id?:0;
        //   $couponAmount=$couponControllerApplyData->coupon_amount?:0;
        //   $coupon_apply_on=$couponControllerApplyData->coupon_apply_on?:"";
        // }
        $order=Order::where("id",$order_id)->where("status","pending")->first();
        if(!$order){
          $order=new Order();
        }
        $order->user_id=$user->id;
        $order->user_type=$user_type;
        $order->billing_full_name=$billingName;
        $order->billing_email=$billingEmail;
        $order->billing_phone_number=$billingPhone;
        $order->billing_country = $billingCountry;
        $order->billing_state=$billingState;
        $order->billing_city=$billingCity;
        $order->billing_address=$billingAddress;
        $order->billing_zipcode=$billingPincode;
        $order->full_name=$shippingName;
        $order->email=$shippingEmail;
        $order->phone_number=$shippingPhone;
        $order->country=$shippingCountry;
        $order->state=$shippingState;
        $order->city=$shippingCity;
        $order->address=$shippingAddressAddress;
        $order->zipcode=$shippingPincode;
        if($payment_method=="payment"){
          $order->payment_mode="online";
          $order->cod=0;
        }else{
          $order->payment_mode="cod";
          $order->cod=1;
        }
        $order->status='pending';
        $order->order_status='pending';
        $order->payment_status='pending';
        $order->coupon=$coupon_code;
        $order->coupon_id=$coupon_id;
        $order->coupon_amount=$couponAmount;
        $order->client_ip=$request->ip();
        $order->client_agent=$request->userAgent();
        $order->item_type=$item_type=="product"?0:1;
        $order->payment_date_time=date('Y-m-d H:i:s');
        $order->save();
        $order_id=$order->id;
        $cartItemTotalSaleAmount=$adminSideCartItems->sum("unit_sale_price");
        $totalQuantity=0;
        $baseTotalAmount=0;
        $saleTotalAmount=0;
        $shippingAmount=$request->shipping_amount?:0;
        $cgstAmount=0;
        $sgstAmount=0;
        $igstAmount=0;
        OrderItem::where("order_id", $order_id)->delete();
        foreach($adminSideCartItems as $index=>$item){
          if($item->item_type=="product"){
            $product=$item->product;
            $order->product_type=$product?$product->type:1;
            $itemTotalSaleAmount=$item->unit_sale_price;
            $itemTotalBaseAmount=$item->unit_base_price;
            $totalQuantity+=$item->quantity;
            $saleTotalAmount+=$itemTotalSaleAmount;
            $baseTotalAmount+=$itemTotalBaseAmount;
            $discountAmount=$item->base_price-$item->sale_price;
            $itemCouponAmount=0;
            if($couponAmount>0){
              $itemCouponAmount=($itemTotalSaleAmount/$cartItemTotalSaleAmount)*$couponAmount;
            }
            $itemShippingAmount=0;
            if($shippingAmount>0){
              $itemShippingAmount=($shippingAmount/$adminSideCartItems->count());
            }
            $orderItem=new OrderItem();
            $orderItem->user_id=$user_id;
            $orderItem->user_type=$user_type;
            $orderItem->order_id=$order_id;
            $orderItem->product_id=$item->item_id;
            $orderItem->sku=$product?$product->sku:"CUSTOM";
            $orderItem->quantity=$item->quantity;
            $orderItem->price=$item->sale_price;
            $orderItem->unit_price=$item->base_price;
            $orderItem->coupon_amount=($itemCouponAmount/$item->quantity);
            $orderItem->discount_amount=$discountAmount;
            $orderItem->total_price=0; //with gst and couopon
            $orderItem->net_total=0; //single product with discount and coupon without gst
            $orderItem->shipping_amount=$itemShippingAmount;
            $orderItem->type=$item->item_type=="product"?"shop":"";
            $orderItem->name=$item->title;
            $orderItem->image=$product && $product->image?$product->image->image(200,200):no_image();
            $orderItem->description=$product && $product->specifications?$product->specifications->short_description:'This is a custom product.';
            $orderItem->hsn_code=0;
            $options=[];
            $orderItem->options=json_encode($options);
            $orderItem->save();
            $igstPercentage = 0;
            $cgstPercentage = 0;
            $sgstPercentage = 0;
            $igst_amount = 0;
            $csgt_amount = 0;
            $sgst_amount = 0;
            $gstPercentage=$item->tax_percentage;
            $orderItem->hsn_code=$item->hsn_code;
            $itemTotalSaleAmount=$itemTotalSaleAmount-$itemCouponAmount;
            //in state: divide
            if(strtolower($shippingState)=="uttar pradesh"){
              $gstType='cgst_sgst';
              $cgstPercentage=$sgstPercentage=$gstPercentage/2;
              $orderItem->tax_type=$gstType;
              $orderItem->cgst_percentage=$cgstPercentage;
              $csgt_amount=($itemTotalSaleAmount)*($cgstPercentage/100);
              $cgstAmount+=$csgt_amount;
              $orderItem->cgst_amount=$csgt_amount/$item->quantity;
              $orderItem->sgst_percentage=$sgstPercentage;
              $sgst_amount=($itemTotalSaleAmount)*($sgstPercentage/100);
              $sgstAmount+=$sgst_amount;
              $orderItem->sgst_amount=$sgst_amount/$item->quantity;
            }else{
              $gstType='igst';
              $igstPercentage=$gstPercentage;
              $orderItem->tax_type=$gstType;
              $orderItem->igst_percentage=$igstPercentage;
              $igst_amount=($itemTotalSaleAmount)*($igstPercentage/100);
              $igstAmount+=$igst_amount;
              $orderItem->igst_amount=$igst_amount/$item->quantity;
            }
            $orderItem->net_total=$item->sale_price-$orderItem->coupon_amount;
            $orderItem->total_price=$item->unit_sale_price-$itemCouponAmount+($igst_amount+$sgst_amount+$csgt_amount);
            $orderItem->save();
          }
        }
        $orderPrefix="ORD-".date('ym')."-";
        $orderNum=sprintf("%05d", $order_id);
        $prefix="$orderPrefix$orderNum";
        $discountAmount=$baseTotalAmount-$saleTotalAmount;
        $payableAmount=$saleTotalAmount-$couponAmount;
        $gstAmount=$igstAmount+$sgstAmount+$cgstAmount;
        $payableAmount=$payableAmount+$gstAmount+$shippingAmount;
        $order->order_number=$prefix;
        $order->total_quantity=$totalQuantity;
        $order->sub_total=$baseTotalAmount;
        $order->discount=$discountAmount;
        $order->discount_amount=$discountAmount;
        $order->coupon_amount=$couponAmount;
        $order->shipping_price=$shippingAmount;
        $order->igst=$igstAmount;
        $order->cgst=$cgstAmount;
        $order->sgst=$sgstAmount;
        $order->total_price=$payableAmount;
        $order->payable_amount=$payableAmount;
        $order->payment_request="";
        $order->status='success';
        $order->order_status='ordered';
        if($payableAmount>0){
          $order->payment_mode="cod";
          $order->payment_method="cod";
          $order->payment_response="cod";
          $order->payment_status="cod";
        }else{
          $order->payment_mode="nothing";
          $order->payment_response="zero amount";
          $order->payment_status="success";
        }
        $order->save();
        if($order->item_type==0){
          $orderStatus=new OrderStatus;
          $orderStatus->order_id=$order_id;
          $orderStatus->status="ordered";
          $orderStatus->save();
        }else{
          $status_array=['ordered','shipped','in transit','dispatch','delivered'];
          foreach($status_array as $key=>$value) {
            $orderStatus=new OrderStatus;
            $orderStatus->order_id=$order_id;
            $orderStatus->status=$value;
            $orderStatus->save();
          }
          $order->order_status="delivered";
          $order->save();
        }
        $order_items=OrderItem::where('order_id',$order_id)->get();
        $itemIdArray=[];
        $product_types=['','product','pooja','service'];
        $product_type='product';
        foreach($order_items as $key=>$oI){
          $itemIdArray[] = $oI->id;
          if($oI->type=='shop'){
            $product=$oI->product;
            if($product){
              $product_type = $product_types[$product->type];
              if($product->type==1){
                $product->avaibility -= $oI->quantity;
                $product->save();
              }
            }
          }
        }
        $pdf_url = '';
        $mail_credential=[$order->email=>$order->full_name];
        OrderPlaceJob::dispatch($mail_credential,$order,'user',$product_type,$pdf_url);
        $mail_credential=[config("vrdcms.admin_email")=>"Admin"];
        OrderPlaceJob::dispatch($mail_credential,$order,'admin',$product_type,$pdf_url);
        AdminSideCartItem::where('user_id',$user_id)->where('user_type',$user_type)->delete();
        $redirectTo=route("admin.order.edit",[$order_id]);
        return response()->json(['success'=>true,'message'=>'Order Placed Successfully',"order_id"=>$order_id,"redirectTo"=>$redirectTo],200);
      }
    }
    public function generateCheckoutLink(Request $request){
      $user_id=$request->user_id?:0;
      $user_type=$request->user_type?:"user";
      $shipping_address_id=$request->shipping_address_id?:0;
      $discount_amount=$request->discount_amount?:0;
      $coupon_amount=$request->coupon_amount?:0;
      $shipping_amount=$request->shipping_amount?:0;
      $adminSideCartItems=AdminSideCartItem::where('user_id',$user_id)->where('user_type',$user_type)->get();
      $adminSideCartItemsCount=$adminSideCartItems->count();
      if($adminSideCartItemsCount==0){
        return response()->json(["success"=>true,"message"=>"Your Cart is empty."],422);
      }
      $userTempCart=UserTempCart::where("user_id",$user_id)->where("user_type",$user_type)->first();
      if($userTempCart==null){
        $userTempCart=new UserTempCart();
        $userTempCart->user_id=$user_id;
        $userTempCart->user_type=$user_type;
      }
      $userTempCart->discount_amount=0;
      // $userTempCart->extra_discount_amount=$coupon_amount;
      $userTempCart->extra_discount_amount=$discount_amount;
      $userTempCart->shipping_amount=$shipping_amount;
      $userTempCart->save();
      $sub_total_amount=0;
      $igst_amount=0;
      $cgst_amount=0;
      $sgst_amount=0;
      $totalCartItemsAmount=$adminSideCartItems->sum("unit_sale_price");
      foreach($adminSideCartItems as $index=>$item){
        $item->user_temp_cart_id=$userTempCart->id;
        $item->save();
        $unit_sale_price=$item->unit_sale_price;
        $sub_total_amount+=$unit_sale_price;
        $item_coupon_amount=($unit_sale_price/$totalCartItemsAmount)*$discount_amount;
        $unit_sale_price=$unit_sale_price-$item_coupon_amount;
        $gst_amount=$unit_sale_price*($item->tax_percentage/100);
        $igst_amount=$gst_amount;
        $cgst_amount=$sgst_amount=($gst_amount/2);
      }
      $shippingAddress=ShippingAddress::find($shipping_address_id);
      if($shippingAddress && strtolower($shippingAddress->state)=="uttar pradesh"){
        $cgst_amount=$sgst_amount=0;
      }else{
        $igst_amount=0;
      }
      $gst_tax_amount=$igst_amount+$cgst_amount+$sgst_amount;
      $total_amount=($sub_total_amount-$discount_amount-$coupon_amount)+($gst_tax_amount+$shipping_amount);
      $userTempCart->sub_total_amount=$sub_total_amount;
      $userTempCart->igst_amount=$igst_amount;
      $userTempCart->cgst_amount=$cgst_amount;
      $userTempCart->sgst_amount=$sgst_amount;
      $userTempCart->gst_tax_amount=$gst_tax_amount;
      $userTempCart->total_amount=$total_amount;
      $userTempCart->save();
      $link=route('guest.redirecttodirectcheckout',['user_id'=>encrypt($userTempCart->user_id),'user_temp_cart_id'=>encrypt($userTempCart->id),"address_id"=>$shipping_address_id]);
      return response()->json(["success"=>true,"message"=>"Link Generated Successfully.","link"=>$link],200);
    }

}
