<?php

namespace App\Http\Controllers;
use Razorpay\Api\Api;

use Illuminate\Http\Request;
use App\Model\City;
use App\Model\LandingPage;
use App\Model\Landing;
use App\Model\LandingPageForm;
use App\Model\RudrakshaConsultation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnquiryNotification;
use App\Jobs\OrderPlaceJob;
use App\{Product,CartItem,Order,OrderItem, ProductReview};
use App\Services\CustomerReferralRewardService;
use Session;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
  public function index(Request $request){
    return view('landing.nk_janam_patrika');
  }
  public function ngSoftware(Request $request){
    // return view(themeView().'comingsoon.index');
    return view('landing.ng_software');
  }
  public function vastu(Request $request){
    // return view(themeView().'comingsoon.index');
    return view('landing.vastu-consultation');
  }
  public function astro(Request $request){
    return view('landing.astro_consultation');
  }
  public function page(Request $request){
    $slug = $request->slug;
    // $page =  Cache::rememberForever('lp-page-'.$slug, function () use($slug) {
    //       return  LandingPage::where('slug', $slug)->first();
    //   });
    $page = Landing::where('slug', $slug)->first();


    $amount = ''; $module = '';

    if($slug == 'rudraksh-recommendation'){
        $amount = 9; $module = 'Rudraksha Consultation';
    }
    elseif($slug == 'gemstone-selection'){
        $amount = 51; $module = 'Gemstones Consultation';
    }
    else{
        $amount = ''; $module = '';
    }


    //dd($page);
    // if(!$page) abort(404);
    // return view('landing.page', compact('page'));
    return view('landing.page', compact('page','amount','module'));
  }

  public function oldpage(Request $request){
    $slug = $request->slug;
    // $page =  Cache::rememberForever('lp-page-'.$slug, function () use($slug) {
    //       return  LandingPage::where('slug', $slug)->first();
    //   });
    $page = LandingPage::where('slug', $slug)->first();

    //dd($page);
    // if(!$page) abort(404);
    // return view('landing.page', compact('page'));
    return view('landing.page', compact('page'));
  }

  public function submitForm(Request $request){
    $validation['page_type'] = 'required';
    if($request->has('name')){
      $validation['name'] = 'required';
    }
    if($request->has('phone')){
      $validation['phone'] = 'required|digits:10||regex:/^[1-9]\d{9}$/';
    }
    if($request->has('email')){
      $validation['email'] = 'required|email';
    }
    $this->validate($request, $validation);
    $page = str_slug($request->page_type);

    $exit = LandingPageForm::where('page', 'like', "%$page%")->where('data', 'like', "%$request->phone%")->whereRaw('DATE(created_at) = ?' , [date('Y-m-d')])->first();
    if($exit){
      return response()->json(['success'=>false, 'errors'=>['message'=>['Phone Number already exists.']]], 422);
    }
    $request->ip_address = $request->ip();
    $landingpageForm = new LandingPageForm();
    $landingpageForm->page = $page;
    $landingpageForm->data = json_encode($request->all());
    $landingpageForm->save();

    // $mailArray['rupeshchauhan8978@gmail.com'] = 'Customer Care';
    // if($request->email){
    //   $mailArray[$request->email] = $request->name;
    // }

    $fields['campaign type'] = $request->page_type=='courses'?'course':$request->page_type;
    $fields['name'] = $request->name;
    $fields['email'] = $request->email;
    $fields['phone'] = $request->phone;
    $fields['mode of class'] = 'Offline Class';
    if($request->service){
      $fields['service'] = $request->service;
    }
    if($request->course){
      $fields['course name'] = $request->course;
    }
    $fields['description'] = $request->description?$request->description:$request->message;
    $body['fields'] = $fields;
    sendDataToCrm($body);

    Notification::route('mail', [config("vrdcms.admin_email") => 'Customer Care'])->notify(new EnquiryNotification($landingpageForm, $request->page_type, 'admin'));
    Notification::route('mail', [$request->email=>$request->name])->notify(new EnquiryNotification($landingpageForm, $request->page_type, 'user'));
    $redirectTo = null;

    if($page=='courses' && $url = fromSetting('course_brochure')){
      Session::put('download_brocher_link', url($url));
      $redirectTo = route('thank.you');
    }

    return response()->json(['success'=>true, 'message'=>'Your enquiry has successfully submitted.', 'redirectTo'=>$redirectTo], 200);
  }

  public function submitConsultation(Request $request)
    {
        $data = $request->validate([
            'fullName' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'dob' => 'nullable|date',
            'birthTime' => 'nullable',
            'birthPlace' => 'nullable',
            'message' => 'nullable',
            'amount' => 'nullable',
            'payment_status' => 'nullable',
            'module' => 'nullable',
        ]);

        $consultation = RudrakshaConsultation::create($data);

        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $amount = $request->amount * 100; // Convert to paise

        $order = $api->order->create([
            'receipt' => 'rcpt_'.$consultation->id,
            'amount' => $amount,
            'currency' => 'INR',
            'notes' => [
                'consultation_id' => $consultation->id,
            ],
        ]);

        return view('landing.razorpay-payment', [
            'order_id' => $order->id,
            'amount' => $amount,
            'name' => $consultation->fullName,
            'email' => $consultation->email,
            'phone' => $consultation->mobile,
            'module' => $consultation->module,
        ]);
    }

    public function handlePayment(Request $request)
    {
        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            if (!$request->razorpay_payment_id) {
                return response()->json(['error' => 'Payment ID missing.'], 422);
            }

            $payment = $api->payment->fetch($request->razorpay_payment_id);

            $paymentId = $payment->id;
            $paymentMethod = $payment->method;

            $consultation_id = $payment->notes->consultation_id ?? null;

            if (!$consultation_id) {
                return response()->json(['error' => 'Consultation ID not found in payment notes.'], 500);
            }

            if ($payment->status === 'authorized') {
                $payment->capture(['amount' => $payment->amount]);
            }

            RudrakshaConsultation::where('id', $consultation_id)
            ->update([
                'payment_status' => 1,
                'payment_id' => $paymentId,
                'payment_method' => $paymentMethod,
            ]);

            $data = RudrakshaConsultation::where('id', $consultation_id)->first();

            $user = DB::table('users')
            ->where('email', $data->email)
            ->first();

            if ($user) {
                $userid = $user->id;
            } else {
                $userid = DB::table('users')->insertGetId([
                    'name' => $data->fullName,
                    'email' => $data->email,
                    'phone' => $data->mobile,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $orderid = DB::table('orders')->insertGetId([
                'user_id' => $userid,
                'full_name' => $data->fullName,
                'email' => $data->email,
                'phone_number' => $data->mobile,
                'shipping_price' => 0.00,
                'discount' => 0.00,
                'total_price' => $data->amount.'.00',
                'status' => 'Start',
                'discount_amount' => 0.00,
                'sub_total' => $data->amount.'.00',
                'igst' => 0.00,
                'cgst' => 0.00,
                'sgst' => 0.00,
                'net_total' => $data->amount.'.00',
                'total' => $data->amount.'.00',
                'payment_mode' => 'Razorpay',
                'payment_mode' => 'Success',
                'customer_name' => $data->fullName,
                'customer_email' => $data->email,
                'customer_phone' => $data->mobile,
                'order_state_code' => 0,
                'user_type' => 'user',
                'coupon_amount' => 0.00,
                'payment_id' => $paymentId,
                'payment_method' => $paymentMethod,
                'payment_date_time' => now(),
                'order_status' => 'Ordered',
                'payable_amount' => $data->amount.'.00',
                'total_quantity' => 1,
                'received' => 0,
                'item_type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $order_number = 'MOONASTRO'.$orderid;

            DB::table('orders')->where('id', $orderid)
                ->update(['order_number' => $order_number]);


            $itemid = DB::table('order_items')->insertGetId([
                'order_id' => $orderid,
                'quantity' => 1,
                'price' => $data->amount.'.00',
                'total_price' => $data->amount.'.00',
                'type' => 'shop',
                'name' => $data->module,
                'unit_price' => $data->amount.'.00',
                'user_id' => $userid,
                'user_type' => 'user',
                'net_total' => $data->amount,
                'igst_percentage' => 0.00,
                'igst_amount' => 0.00,
                'cgst_percentage' => 0.00,
                'cgst_amount' => 0.00,
                'sgst_percentage' => 0.00,
                'sgst_amount' => 0.00,
                'hsn_code' => 0,
                'discount_amount' => 0.00,
                'coupon_amount' => 0.00,
                'shipping_amount' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $requestid = DB::table('order_requests')->insertGetId([
                'order_id' => $orderid,
                'user_id' => $userid,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $requestid = DB::table('order_requests')->insertGetId([
                'order_id' => $orderid,
                'user_id' => $userid,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $requestitemid = DB::table('order_request_items')->insertGetId([
                'request_id' => $requestid,
                'order_id' => $orderid,
                'user_id' => $userid,
                'item_id' => $itemid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $responseid = DB::table('order_request_responses')->insertGetId([
                'request_id' => $requestid,
                'order_id' => $orderid,
                'user_id' => $userid,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $statusid = DB::table('order_status')->insertGetId([
                'order_id' => $orderid,
                'status' => 'ordered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $invoiceid = DB::table('invoices')->insertGetId([
                'prefix' => 'INV-2502-',
                'number' => 1,
                'relation_id' => $orderid,
                'relation_type' => 'order',
                'user_id' => $userid,
                'user_type' => 'user',
                'name' => $data->fullName,
                'email' => $data->email,
                'phone' => $data->mobile,
                'description' => 'order',
                'total_quantity' => 1,
                'sgst' => 0.00,
                'igst' => 0.00,
                'cgst' => 0.00,
                'amount' => $data->amount.'.00',
                'total_amount' => $data->amount.'.00',
                'discount_amount' => 0.00,
                'subtotal_amount' => $data->amount.'.00',
                'coupon_amount' => 0.00,
                'shipping_amount' => 0.00,
                'payment_id' => $paymentId,
                'payment_method' => $paymentMethod,
                'payment_date_time' => now(),
                'billing_full_name' => $data->fullName,
                'billing_email' => $data->email,
                'billing_phone_number' => $data->mobile,
                'nature_of_supply' => 'goods',
                'status' => 1,
                'receipt_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $number = str_pad($invoiceid, 5, '0', STR_PAD_LEFT);

            DB::table('invoices')->where('id', $invoiceid)
                ->update(['number' => $number]);

            $invoice = DB::table('invoices')
            ->where('id', $invoiceid)
            ->first();

            $invitemid = DB::table('invoice_items')->insertGetId([
                'invoice_id' => $invoiceid,
                'invoice_prefix' => 'INV-2502-',
                'invoice_number' => $number,
                'relation_id' => $orderid,
                'relation_type' => 'order',
                'user_id' => $userid,
                'user_type' => 'user',
                'item_name' => $data->module,
                'quantity' => 1,
                'amount' => $data->amount.'.00',
                'total_amount' => $data->amount.'.00',
                'tax_type' => 'cgst_sgst',
                'igst_percentage' => 0.00,
                'igst_amount' => 0.00,
                'cgst_percentage' => 0.00,
                'cgst_amount' => 0.00,
                'sgst_percentage' => 0.00,
                'sgst_amount' => 0.00,
                'hsn_code' => 0,
                'base_amount' => $data->amount.'.00',
                'discount_amount' => 0.00,
                'coupon_amount' => 0.00,
                'net_total' => $data->amount.'.00',
                'shipping_amount' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

           $order_data = Order::where('id', $orderid)->latest()->first();
            if (!$order_data) {
                return redirect('/')->with('error', 'Order not found.');
            }

            $order_items = OrderItem::where('order_id', $order_data->id)->get();

            $order = (object) $order_data;
            $order->orderItems = $order_items;

            OrderPlaceJob::dispatch($order, '', 'user');
            OrderPlaceJob::dispatch($order, '', 'admin');

            // Distribute referral rewards if user used a referral code
            $order = Order::with('user')->find($order_data->id);
            if($order && $order->user) {
              $orderTotal = $order->total_price ?? $order->net_total ?? 0;
              CustomerReferralRewardService::distributeRewards(
                $order->user,
                $orderTotal,
                'Product Purchase/Puja',
                $order->id,
                'orders'
              );
            }


            return response()->json(['redirect_url' => route('payment.success')]);

        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

     public function paymentSuccess()
    {
        return redirect('/thank-you');
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $cities = DB::table('master_cities as c')
            ->join('master_states as s', 'c.state_id', '=', 's.id')
            ->join('master_countries as co', 's.country_id', '=', 'co.id')
            ->where('c.name', 'LIKE', "%{$query}%")
            /*->orderBy('c.name', 'asc')*/
            ->limit(10)
            ->get([
                'c.name as city_name',
                's.name as state_name',
                'co.name as country_name'
            ]);

        return response()->json($cities);
    }


}
