<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Setting;
use File;
use Auth;
use App\{Order, OrderItem, Product, User, CartItem, ProductReview, Admin};
use App\Model\{Astrologer, Course, Invoice, AstrologerAppointment, LmsCourse, AiAstrologerAppointment, Pandit, KundaliEnquiry, KundaliMatchingEnquiry, Support, AstrologerReview, WalletTransition, ContactEnquiry};
use App\Model\UserRechargeHistory;
use App\Model\Withdraw;
use PDF;
use DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $nav = 'dashboard';
      $notIn = ['pending', 'cancel'];

      $orders = Order::where('status', "success")->orderBy('id', 'desc')->get();
      $orders_id = $orders->pluck("id")->toArray();

      $sold_products = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 1);
      })
      ->whereIn("order_id", $orders_id)
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();

      $sold_poojas = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 2);
      })
      ->whereIn("order_id", $orders_id)
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();

      $sold_services = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 3);
      })
      ->whereIn("order_id", $orders_id)
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();

      $cartItems = CartItem::where('user_type', "!=", 'guest')
      ->orderBy('id', 'desc')
      ->get();

      $astrologers = Astrologer::where("type", "Astrologer")
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->orderBy('id', 'desc')
      ->get();

      $newAstrologers = Astrologer::where("type", "Astrologer")
      ->where('new', 1)
      ->orderBy('id', 'desc')
      ->get();

      $aiAstrologers = Astrologer::where("type", "AI")
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->orderBy('id', 'desc')
      ->get();

      $pandits = Pandit::orderBy('id', 'desc')
      ->get();

      $kundaliEnquiry = KundaliEnquiry::orderBy('id', 'desc')
      ->get();

      $kundaliMatchingEnquiry = KundaliMatchingEnquiry::orderBy('id', 'desc')
      ->get();

      $support = Support::where("status", "open")
      ->orderBy('id', 'desc')
      ->get();

      $productReviews = ProductReview::orderBy('id', 'desc')
      ->get();

      $astrologerReviews = AstrologerReview::orderBy('id', 'desc')
      ->get();

      $customerFeedbackCount = ContactEnquiry::where('type', 'like', '%feedback%')->count();

      $nofityProducts = Product::whereRaw("avaibility <= minimum_quantity")
      ->where("type", 1)
      ->where("status", 1)
      ->orderBy('id', 'desc')
      ->get();

      $sold_products = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 1);
      })
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();
      $sold_poojas = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 2);
      })
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();
      $sold_services = OrderItem::selectRaw('*, COUNT(*) as total_sold')
      ->where('type', 'shop')
      ->whereHas('product', function($query){
        $query->where("type", 3);
      })
      ->groupBy('product_id')
      ->orderBy('total_sold', 'desc')
      ->get();
      $astrologerAppointments = AstrologerAppointment::selectRaw('*, COUNT(*) as total_appointments')
      ->groupBy('astrologer_id')
      ->where("status", "done")
      ->orderBy('total_appointments', 'desc')
      ->get();
      $aiAstrologerAppointments = AiAstrologerAppointment::selectRaw('*, COUNT(*) as total_appointments')
      ->groupBy('astrologer_id')
      ->where("paid", "1")
      ->orderBy('total_appointments', 'desc')
      ->get();

      $activeUsers = User::where("status", 1)
      ->whereHas("orders", function($query){
        $query->whereNotIn("order_status", ["pending", "failed", "cancel", "return"]);
      })
      ->orderBy('id', 'desc')
      ->get();

      $activeHumenAstrologers = Astrologer::where("type", "Astrologer")
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->whereHas("appointments")
      ->orderBy('id', 'desc')
      ->get();

      $activeAiAstrologers = Astrologer::where("type", "AI")
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->whereHas("aiAppointments", function($query){
        $query->where("paid", 1);
      })
      ->orderBy('id', 'desc')
      ->get();

      $users = User::where("status", 1)
      ->orderBy('id', 'desc')
      ->paginate(5);

      // Fetch admins with online/offline status
      // Consider admin online if last_logined is within last 15 minutes
      // Exclude current logged-in admin from listing
      $currentAdmin = Auth::guard('admin')->user();
      $currentAdminId = $currentAdmin ? $currentAdmin->id : 0;
      $onlineThreshold = now()->subMinutes(15);
      $allAdminsWithStatus = Admin::where("status", 1)
        ->where('id', '!=', $currentAdminId)
        ->orderBy('last_logined', 'desc')
        ->orderBy('id', 'desc')
        ->get()
        ->map(function($admin) use ($onlineThreshold) {
          $admin->is_online = $admin->last_logined && $admin->last_logined >= $onlineThreshold;
          return $admin;
        });

      $invoices = Invoice::where('prefix', 'like', "INV%")->orderBy('id', 'desc')->get();

      $astrologerIllegalChatAppointmentsCount=AstrologerAppointment::where('package',2)->whereHas('hasIllegalActivityInChatAppointments',function($query){
        $query->where('has_illegal_activity',1);
      })->count();
      return view('admin.dashboard', compact(
        'nav',
        'orders',
        "sold_products",
        "sold_poojas",
        "sold_services",
        "cartItems",
        'newAstrologers',
        'astrologers',
        'aiAstrologers',
        'pandits',
        'kundaliEnquiry',
        'kundaliMatchingEnquiry',
        'support',
        'productReviews',
        'astrologerReviews',
        'customerFeedbackCount',
        "nofityProducts",
        "sold_poojas",
        "sold_services",
        "astrologerAppointments",
        "aiAstrologerAppointments",
        "activeUsers",
        "activeHumenAstrologers",
        "activeAiAstrologers",
        'users',
        'allAdminsWithStatus',
        'invoices',
        'astrologerIllegalChatAppointmentsCount',
      ));
    }

    public function settings(Request $request){
        $nav = 'settings';
        $settings = Setting::get();
        $tab = $request->tab;
        $nav = $tab;
        $breads = [
          ['name' => "Settings"],
          ['name' => $tab ?: "Settings"]
        ];
        $admin = auth()->guard('admin')->user();
        $settings->map(function ($item) use ($admin) {
          if($item->key == "username"){
            $item->value = $admin->name;
          }
          if($item->key == "email"){
            $item->value = $admin->email;
          }
        });
        return view('admin.settings', compact('nav', "breads", 'settings', "tab"));
    }

    public function settingsSave(Request $request){
        $data = $request->all();
        unset($data['_token']);

        $username = $data["username"];
        unset($data['username']);
        $email = $data["email"];
        unset($data['email']);
        $password = $data["password"];
        unset($data['password']);
        $confirm = $data["confirm"];
        unset($data['confirm']);

        if($password){
          $request->validate([
            "confirm" => "required"
          ]);
        }
        if($confirm){
          $request->validate([
            "password" => "required"
          ]);
        }
        if($password && $confirm && ($password != $confirm)){
          return response()->json(["errors" => ["password" => ["The conifrm should be same as password."]]] ,402);
        }
        $admin = auth()->guard('admin')->user();
        if($username){
          $admin->name = $username;
        }
        // if($email){
        //   $admin->email = $email;
        // }
        if($password){
          $admin->password = bcrypt($password);
        }
        $admin->save();

        foreach($data as $key => $value){
          $setting = Setting::where('key', $key)->first();
          if($setting){
            $setting->value = $value != null || $value != "" ? $value : '';
            $setting->save();
          }
          $setting = null;
        }
        if(!$request->course_brochure_value){
          $setting = Setting::where('key', 'course_brochure')->first();
          if($setting && $setting->value && File::exists(public_path($setting->value))) {
            unlink(public_path($setting->value));
            $setting->value = '';
            $setting->save();
          }
        }
        if($request->hasFile('course_brochure')){
          $this->validate($request, [
            'course_brochure'=>'required|mimes:pdf'
          ]);
          $setting = Setting::where('key', 'course_brochure')->first();
          if($setting->value && File::exists(public_path($setting->value))) {
            unlink(public_path($setting->value));
          }
          $fileName = '';
          $filePath = public_path('course');
          if(!File::exists($filePath)) {
              File::makeDirectory($filePath, 0777, true);
          }
          $filePath = public_path('course/broucher');
          if(!File::exists($filePath)) {
              File::makeDirectory($filePath, 0777, true);
          }
          $filePath = public_path('course/broucher/0');
          if(!File::exists($filePath)) {
              File::makeDirectory($filePath, 0777, true);
          }
          $time = time();
          $fileName = $time.'.'.$request->course_brochure->getClientOriginalExtension();
          $request->course_brochure->move($filePath, $fileName);
          $setting->value = 'course/broucher/0/'.$fileName;
          $setting->save();
        }
        $tab = $request->tab ?: "";
        $redirectTo = route('admin.settings');
        if($tab){
          $redirectTo = route('admin.settings', ["tab" => $tab]);
        }
        // dd($request->all());
        return response()->json(['message'=>'Config Saved Successfully', 'redirectto'=> $redirectTo], 200);
    }

    public function checkStatus(){
      $user  = auth()->guard('admin')->user();
        if(!$user->status) {
          return route('admin.logout');
        }
      return '';
    }
    public function search(Request $request){
      $search_string = $request->search_string?:"";
      $astrologers=[];
      $ai_astrologers=[];
      $products=[];
      $pujas=[];
      $services=[];
      if($search_string!=""){
        $astrologers=Astrologer::where('display_name','LIKE',"%$search_string%")
        ->where('type','Astrologer')
        ->where('is_verified',1)
        ->where('status',1)
        ->where('is_delete_requested',0)
        ->take(5)
        ->get();
        $ai_astrologers=Astrologer::where('display_name','LIKE',"%$search_string%")
        ->where('type','AI')
        ->where('is_verified',1)
        ->where('status',1)
        ->where('is_delete_requested',0)
        ->take(5)
        ->get();
        $products=Product::where('title','LIKE',"%$search_string%")
        ->where('type',1)
        ->where('status',1)
        ->where('parent_id',0)
        ->take(5)
        ->get();
        $pujas=Product::where('title','LIKE',"%$search_string%")
        ->where('type',2)
        ->where('status',1)
        ->where('parent_id',0)
        ->take(5)
        ->get();
        $services=Product::where('title','LIKE',"%$search_string%")
        ->where('type',3)
        ->where('status',1)
        ->where('parent_id',0)
        ->take(5)
        ->get();
      }
      $data=[
        'rows'=>view('admin.search.listrows',['astrologers'=>$astrologers,"astrologers"=>$astrologers,"ai_astrologers"=>$ai_astrologers,"products"=>$products,"pujas"=>$pujas,"services"=>$services])->render(),
        'pagination'=>""
      ];
      return response()->json(["success"=>true,"data"=>$data]);
    }

    public function downloadTransactionReport()
    {
      $transactions = [];

      // Get all wallet transitions
      $walletTransitions = WalletTransition::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();

      foreach ($walletTransitions as $transition) {
        $name = 'Unknown';
        $transactionType = 'Unknown';

        // Get user name based on user_type
        if ($transition->user_type == 'user') {
          $user = User::find($transition->user_id);
          $name = $user ? ($user->name ?? $user->firstname ?? 'User #' . $transition->user_id) : 'User #' . $transition->user_id;
        } elseif ($transition->user_type == 'astrologer') {
          $astrologer = Astrologer::find($transition->user_id);
          $name = $astrologer ? ($astrologer->display_name ?? $astrologer->firstname ?? 'Astrologer #' . $transition->user_id) : 'Astrologer #' . $transition->user_id;
        }

        // Determine transaction type based on table_name and remark
        switch ($transition->table_name) {
          case 'astrologer_appointments':
            $appointment = AstrologerAppointment::find($transition->table_id);
            if ($appointment) {
              $packageTypes = [1 => 'Video Call', 2 => 'Chat', 3 => 'Phone Call'];
              $packageType = $packageTypes[$appointment->package] ?? 'Consultation';
              $transactionType = $transition->action == 1 ? "Consultation - $packageType (Credit)" : "Consultation - $packageType (Debit)";
            } else {
              $transactionType = $transition->action == 1 ? 'Consultation (Credit)' : 'Consultation (Debit)';
            }
            break;
          case 'user_recharge_histories':
            $transactionType = $transition->action == 1 ? 'Wallet Recharge (Credit)' : 'Wallet Recharge (Debit)';
            break;
          case 'orders':
            $transactionType = $transition->action == 1 ? 'Product Purchase (Credit)' : 'Product Purchase (Debit)';
            break;
          default:
            $transactionType = $transition->remark ?? ($transition->action == 1 ? 'Credit' : 'Debit');
            break;
        }

        $transactions[] = [
          'name' => $name,
          'amount' => $transition->amount,
          'transaction_type' => $transactionType,
          'date' => $transition->created_at,
        ];
      }

      // Get all successful orders (product purchases)
      $orders = Order::where('status', 'success')
        ->orderBy('created_at', 'desc')
        ->get();

      foreach ($orders as $order) {
        // Check if this order is already in wallet transitions
        $existsInWallet = WalletTransition::where('table_name', 'orders')
          ->where('table_id', $order->id)
          ->exists();

        if (!$existsInWallet) {
          $name = 'Unknown';
          if ($order->user_type == 'user' && $order->user_id) {
            $user = User::find($order->user_id);
            $name = $user ? ($user->name ?? $user->firstname ?? 'User #' . $order->user_id) : 'User #' . $order->user_id;
          } elseif ($order->user_type == 'astrologer' && $order->user_id) {
            $astrologer = Astrologer::find($order->user_id);
            $name = $astrologer ? ($astrologer->display_name ?? $astrologer->firstname ?? 'Astrologer #' . $order->user_id) : 'Astrologer #' . $order->user_id;
          } else {
            // Fallback to order email or name
            $name = $order->full_name ?? $order->email ?? 'Order #' . $order->id;
          }

          $transactions[] = [
            'name' => $name,
            'amount' => $order->payable_amount ?? $order->total_price ?? 0,
            'transaction_type' => 'Product Purchase',
            'date' => $order->created_at,
          ];
        }
      }

      // Get all appointments (consultations)
      $appointments = AstrologerAppointment::where('status', 'done')
        ->where('paid', 1)
        ->orderBy('created_at', 'desc')
        ->get();

      foreach ($appointments as $appointment) {
        // Check if this appointment is already in wallet transitions
        $existsInWallet = WalletTransition::where('table_name', 'astrologer_appointments')
          ->where('table_id', $appointment->id)
          ->exists();

        if (!$existsInWallet) {
          $user = $appointment->user;
          $name = $user ? ($user->name ?? $user->firstname ?? 'User #' . $appointment->user_id) : 'User #' . $appointment->user_id;

          $packageTypes = [1 => 'Video Call', 2 => 'Chat', 3 => 'Phone Call'];
          $packageType = $packageTypes[$appointment->package] ?? 'Consultation';

          $transactions[] = [
            'name' => $name,
            'amount' => $appointment->total_price ?? 0,
            'transaction_type' => "Consultation - $packageType",
            'date' => $appointment->created_at,
          ];
        }
      }

      // Get all wallet recharges
      $recharges = UserRechargeHistory::where('payment_status', 'success')
        ->orderBy('created_at', 'desc')
        ->get();

      foreach ($recharges as $recharge) {
        // Check if this recharge is already in wallet transitions
        $existsInWallet = WalletTransition::where('table_name', 'user_recharge_histories')
          ->where('table_id', $recharge->id)
          ->exists();

        if (!$existsInWallet) {
          $user = User::find($recharge->user_id);
          $name = $user ? ($user->name ?? $user->firstname ?? 'User #' . $recharge->user_id) : 'User #' . $recharge->user_id;

          $transactions[] = [
            'name' => $name,
            'amount' => $recharge->taktime_value ?? 0,
            'transaction_type' => 'Wallet Recharge',
            'date' => $recharge->created_at,
          ];
        }
      }

      // Get all withdrawals
      $withdrawals = Withdraw::where('status', 'success')
        ->orderBy('created_at', 'desc')
        ->get();

      foreach ($withdrawals as $withdrawal) {
        $name = 'Unknown';
        if (isset($withdrawal->user_type) && $withdrawal->user_type == 'user' && $withdrawal->user_id) {
          $user = User::find($withdrawal->user_id);
          $name = $user ? ($user->name ?? $user->firstname ?? 'User #' . $withdrawal->user_id) : 'User #' . $withdrawal->user_id;
        } elseif ((!isset($withdrawal->user_type) || $withdrawal->user_type == 'astrologer') && $withdrawal->user_id) {
          // Withdrawals are typically for astrologers
          $astrologer = Astrologer::find($withdrawal->user_id);
          $name = $astrologer ? ($astrologer->display_name ?? $astrologer->firstname ?? 'Astrologer #' . $withdrawal->user_id) : 'Astrologer #' . $withdrawal->user_id;
        }

        $transactions[] = [
          'name' => $name,
          'amount' => $withdrawal->amount ?? 0,
          'transaction_type' => 'Withdrawal',
          'date' => $withdrawal->created_at,
        ];
      }

      // Sort all transactions by date (newest first)
      usort($transactions, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
      });

      $pdf = PDF::loadView('admin.transactions.report', compact('transactions'));
      return $pdf->download('transaction-reports-' . date('Y-m-d') . '.pdf');
    }
}
