<?php

namespace App\Http\Controllers;


use App\Http\Controllers\{Controller, PushNotificationController, NotificationController};
use Illuminate\Http\Request;
use App\{Product,CartItem,Order,OrderItem, ProductReview,ProductSpecification};
use App\Model\{Course, ProductCategory,Testimonial, LmsCourse, VideoTestimonial, PAttribute, ProductAttribute, PAttributeValue, PujaAstrologerAssignment, Astrologer, AstrologerReferral};
use DB;
use App\Model\Banner;
use App\Model\Coupon;
use App\Model\TempUser;
use App\Model\RechargePlan;
use App\Model\ShippingAddress;
use App\Model\UserRechargeHistory;
use App\Model\Receipt;
use App\Model\ReceiptItem;
use App\Model\OnPage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Notification;
use App\Notifications\OrderPlaced;
use Session;
use File;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Jobs\{OrderPlaceJob, OrderFailedJob, CoursePurchaseJob};
use App\Model\TemplateFaq;
use App\Model\{OrderStatus,ProductAndCategoryId,UserTempCart,AdminSideCartItem,HsnCode};
use App\Services\CustomerReferralRewardService;
use App\Services\{Meta};

class ProductController extends Controller
{
    public function index(Request $request){
      $productTypeName = $request->productTypeName;
      $productTypeValue = $request->productTypeValue;
      $nav = $productTypeName;
      $bannerPage = $request->bannerPage;
      $today = date('Y-m-d');
      $banners = Banner::where('status', 1)
      ->where('type', $bannerPage)
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();
      // Check if this is a one-to-one puja request
      $isOneToOnePujaIndex = false;
      if($request->routeIs('one.to.one.puja.handle') || $request->segment(1) == 'one-to-one-puja' || (isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1)){
        $isOneToOnePujaIndex = true;
      }

      // Fetch categories that have products
      $categriesId = Product::join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
      ->where("type", $productTypeValue)
      ->where('status', 1)
      ->where('parent_id', 0);

      // Filter one-to-one puja based on request
      if($productTypeValue == 2){
        if($isOneToOnePujaIndex){
          // Show only one-to-one puja
          $categriesId->where('products.is_one_to_one_puja', 1);
        }else{
          // Exclude one-to-one puja from regular puja listing
          $categriesId->where(function($query) {
            $query->where('products.is_one_to_one_puja', 0)
                  ->orWhereNull('products.is_one_to_one_puja');
          });
        }
      }

      $categriesId = $categriesId->select("paci.category_id")
      ->distinct()
      ->pluck("paci.category_id")
      ->toArray();

      $categories = ProductCategory::where('status', 1)
      ->where("type", $productTypeValue)
      ->whereIn("id", $categriesId)
      ->orderBy('order', 'asc')
      ->get();
      $breads=[];
      if($productTypeValue==1){
        $breads=[
          ["name"=>"Collections","url"=>route("product.handle")]
        ];
      }elseif($productTypeValue==2){
        if($isOneToOnePujaIndex){
          $breads=[
            ["name"=>"One to One Puja","url"=>route("one.to.one.puja.handle")]
          ];
        }else{
          $breads=[
            ["name"=>"Pujas Collections","url"=>route("puja.handle")]
          ];
        }
      }else{
        $breads=[
          ["name"=>"Reports Collections","url"=>route("service.handle")]
        ];
      }
      $is_one_to_one_puja = $isOneToOnePujaIndex ? 1 : 0;
      return view(themeView().'product.index',compact("productTypeName", "productTypeValue", 'nav', "breads",'banners','categories', 'is_one_to_one_puja'));
    }

    public function addToCartForm(Request $request){
      $request->validate([
          'item_id' => 'required|integer|exists:products,id',
          'product_type_value' => 'required|in:2,3',
          'type' => 'required|in:shop,course',
          'form_data' => 'required|array',
          'address_id' => 'nullable|integer|exists:shipping_address,id',
      ]);
      if(auth()->guard('web')->check()){
          $request->user_id =  auth()->guard('web')->user()->id;
          $request->user_type = 'user';
      }elseif(Session::get("cart_user_id") == null){
          $request->user_id =  uniqid();
          $request->user_type = 'guest';
      }else{
        $request->user_id = Session::get("cart_user_id");
        $request->user_type = Session::get("cart_user_type");
      }
      Session::put("cart_user_id", $request->user_id);
      Session::put("cart_user_type", $request->user_type);

      $itemId = $request->item_id;
      $productTypeValue = $request->product_type_value ?: 2;
      $productTypeName = "Pooja";
      if($productTypeValue == 3){
        $productTypeName = "Service";
      }
      $formData = $request->form_data ? $request->form_data : [];


      // Ensure gotra field is always captured in formdata (for both one-to-one and group puja)
      if(!is_array($formData)) {
        $formData = [];
      }
      $hasGotraField = false;
      foreach($formData as $field) {
        if(is_array($field)) {
          $name = isset($field['name']) ? strtolower(trim($field['name'])) : '';
          $label = isset($field['label']) ? strtolower(trim($field['label'])) : '';
          if(
            ($name && (strpos($name, 'gotra') !== false)) ||
            ($label && (strpos($label, 'gotra') !== false))
          ) {
            $hasGotraField = true;
            break;
          }
        }
      }
      if(!$hasGotraField) {
        // Some front-end flows (especially group puja variations) might send gotra as a separate field
        $possibleGotraKeys = ['gotra', 'one_gotra', 'gotra_name'];
        $gotraValue = null;
        foreach($possibleGotraKeys as $key) {
          if($request->has($key)) {
            $gotraValue = trim((string)$request->input($key));
            break;
          }
        }
        // Always append gotra field if we found a key (even if value is empty string)
        if($gotraValue !== null) {
          $formData[] = [
            'name'  => 'gotra',
            'label' => 'Gotra',
            'value' => $gotraValue,
          ];
        }
      }
      $type = $request->type;

      $success = false;
      $message = 'Out of stock!';
      $redirectTo = '';
      if($type == "shop"){
        $product = Product::where('id', $itemId)->where("type", $productTypeValue)->first();
        if($product){
          $cartItem = CartItem::where('user_id', $request->user_id)
          ->where('user_type', $request->user_type)
          ->where('item_id', $request->item_id)
          ->where('type', 'shop')
          ->first();
          if(!$cartItem){
            $todayDate = date("Y-m-d");
            $productPrice = $product->base_price;
            $productSpecialPrice = $product->sale_price;
            $productSpecialPriceFromDate = $product->sale_price_start_date;
            $productSpecialPriceToDate = $product->sale_price_end_date;

            $cartItem = new CartItem();
            $cartItem->user_id = $request->user_id;
            $cartItem->user_type = $request->user_type;
            $cartItem->item_id = $product->id;
            $cartItem->type = $type;
            $cartItem->quantity = 1;
            $cartItem->unit_price = $productPrice;
            $cartItem->price = $productPrice;
            if($productSpecialPriceFromDate && ($todayDate >= $productSpecialPriceFromDate) && ($todayDate <= $productSpecialPriceToDate)){
              $cartItem->price = $productSpecialPrice;
            }
            $cartItem->item_type = 1;
          }
          $cartItem->formdata = json_encode($formData);
          $cartItem->save();
          $success = true;
          $message = '';

          // Build redirect URL - don't pass address_id to show all addresses on checkout
          // This matches the product buy flow where user selects address on checkout page
          $redirectParams = [strtolower($productTypeName),'item_slug'=>$product->slug];
          $redirectTo = route('checkout', $redirectParams);
        }
      }

      return response()->json([
          'success' => $success,
          'message' => $message,
          'redirectTo' => $redirectTo
      ]);
    }

    public function saveAshirwadAddress(Request $request){
      $user = auth()->guard('web')->user();
      if(!$user){
        return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
      }

      $request->validate([
        'address' => 'required',
        'state_id' => 'required|gt:0',
        'city_id' => 'required|gt:0',
        'pincode' => 'required|digits:6',
      ], [
        'address.required' => 'Address field is required.',
        'state_id.required' => 'State field is required.',
        'city_id.required' => 'City field is required.',
        'pincode.required' => 'Pincode field is required.',
        'pincode.digits' => 'Pincode must be 6 digits.',
      ]);

      $country = DB::table('master_countries')->where('id', 101)->first(); // Default to India
      $state = DB::table('master_states')->where('id', $request->state_id)->first();
      $city = DB::table('master_cities')->where('id', $request->city_id)->first();

      $shippingAddress = new ShippingAddress();
      $shippingAddress->user_id = $user->id;
      $shippingAddress->user_type = 'user';
      $shippingAddress->name = $user->name;
      $shippingAddress->email = $user->email;
      $shippingAddress->phone = $user->phone;
      $shippingAddress->country_id = $country ? $country->id : 101;
      $shippingAddress->country = $country ? $country->name : 'India';
      $shippingAddress->state_id = $state ? $state->id : $request->state_id;
      $shippingAddress->state = $state ? $state->name : '';
      $shippingAddress->city_id = $city ? $city->id : $request->city_id;
      $shippingAddress->city = $city ? $city->name : '';
      $shippingAddress->address = $request->address;
      $shippingAddress->zipcode = $request->pincode;
      $shippingAddress->save();

      return response()->json([
        'success' => true,
        'message' => 'Address saved successfully',
        'address_id' => $shippingAddress->id
      ], 200);
    }

        public function detailProduct(Request $request, $product_slug = null){
      $request->productTypeName = "Product";
      $request->productTypeValue = 1;
      if($product_slug) $request->merge(["product_slug" => $product_slug]);
      return $this->detail($request);
    }

        public function detailPuja(Request $request, $product_slug = null){ file_put_contents("debug_detailPuja.txt", "YES");
      $request->productTypeName = "Pooja";
      $request->productTypeValue = 2;
      if($product_slug) $request->merge(["product_slug" => $product_slug]);
      return $this->detail($request);
    }

    public function detailOneToOnePuja(Request $request, $product_slug){
      $request->productTypeName = "OneToOnePuja";
      $request->productTypeValue = 2;
      $request->is_one_to_one_puja = 1;
      $request->product_slug = $product_slug;
      return $this->detail($request);
    }

    public function handleOneToOnePujaSlug(Request $request, $slug){
      // Check if it's a product first
      $product = Product::where('slug', $slug)
        ->where('type', 2)
        ->where('is_one_to_one_puja', 1)
        ->where('status', 1)
        ->first();

      if($product){
        // It's a product, show detail page directly (no redirect to avoid loops)
        $request->productTypeName = "OneToOnePuja";
        $request->productTypeValue = 2;
        $request->is_one_to_one_puja = 1;
        $request->product_slug = $slug;
        return $this->detail($request);
      }

      // Check if it's a category
      $category = ProductCategory::where('slug', $slug)
        ->where('type', 2)
        ->where('status', 1)
        ->first();

      if($category){
        // It's a category, show listing with category - same flow as handlePujas
        $request->productTypeName = "OneToOnePuja";
        $request->productTypeValue = 2;
        $request->bannerPage = "puja";
        $request->is_one_to_one_puja = 1;
        $request->category_slug = $slug;

        // Set up user tracking (same as handleOneToOnePujas)
        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_OneToOnePuja");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "OneToOnePuja_{$timestamp}";
          $body["action_source"] = "website";
          $body["event_source_url"] = $request->fullUrl();
          $body["user_data"]["client_ip_address"] = $request->ip();
          $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
          $body["user_data"]["fn"] = sha256("guest");
          if($user){
            $body["user_data"]["em"] = sha256($user->email);
            $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
            $body["user_data"]["fn"] = sha256($user->name);
            $user_type = "user";
          }
          $metaService = new Meta();
          $metaService->sendEvent($body);
          Session::put("view_content_OneToOnePuja", $user_type);
        }

        // Call handleList same way as handlePujas does
        return $this->handleList($request);
      }

      // Neither product nor category, return 404
      file_put_contents("debug_404.txt", "ABORTED AT LINE " . __LINE__); return abort(404);
    }

        public function detailService(Request $request, $product_slug = null){
      $request->productTypeName = "Service";
      $request->productTypeValue = 3;
      if($product_slug) $request->merge(["product_slug" => $product_slug]);
      return $this->detail($request);
    }

    public function detail(Request $request){ file_put_contents("debug.txt", "SLUG: " . $request->product_slug . " TYPE: " . $request->productTypeValue);
      $productTypeName = $request->productTypeName;
      $productTypeValue = $request->productTypeValue;
      $nav = $productTypeName;
      $productSlug = $request->product_slug;
      $id = $request->id ?: 0;
      $product = null;

      // Handle referral code from URL parameter
      $referralCode = $request->get('ref');
      if($referralCode) {
        // Store referral code in session for checkout redirect
        Session::put('product_referral_code', $referralCode);

        // Check if user is logged in
        $user = auth()->guard('web')->user();
        if(!$user) {
          // User not logged in - store redirect URL and redirect to login page
          Session::put('redirect_from_login', url()->full());
          return redirect()->route('login')->with('message', 'Please login to continue to checkout.');
        }

        // User is logged in - redirect to checkout
        // Get product ID from slug or ID
        if(!$id && $productSlug) {
          $tempProduct = Product::where('slug', $productSlug)->where('type', $productTypeValue)->first();
          if($tempProduct) {
            $id = $tempProduct->id;
          }
        }

        if($id) {
          // Determine checkout route based on product type
          // Preserve referral code in URL for tracking
          $checkoutParams = ['shop', 'buynow'];
          if($productTypeValue == 1) {
            // Product
            $checkoutParams[] = 'Product';
            $checkoutParams[] = Product::find($id)->slug;
          } elseif($productTypeValue == 2) {
            // Puja
            $checkoutParams[] = 'Pooja';
            $checkoutParams[] = Product::find($id)->slug;
          } elseif($productTypeValue == 3) {
            // Service
            $checkoutParams[] = 'Service';
            $checkoutParams[] = Product::find($id)->slug;
          }
          // Add referral code as query parameter
          $checkoutUrl = route('checkout', $checkoutParams) . '?ref=' . urlencode($referralCode);
          return redirect($checkoutUrl);
        }
      }

      // Check if this is a one-to-one puja request
      $isOneToOnePujaDetail = false;
      if($request->routeIs('product.detailonetoonepuja') || (isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1)){
        $isOneToOnePujaDetail = true;
      }

      if($id > 0){
        $product = Product::where("type", $productTypeValue)
        ->where("status", 1)
        ->where('id', $id);

        // Filter one-to-one puja
        if($productTypeValue == 2 && $isOneToOnePujaDetail){
          $product->where('is_one_to_one_puja', 1);
        }elseif($productTypeValue == 2 && !$isOneToOnePujaDetail){
          $product->where(function($query) {
            $query->where('is_one_to_one_puja', 0)
                  ->orWhereNull('is_one_to_one_puja');
          });
        }

        $product = $product->first();
      }

      if(!$product){
        $product = Product::where("type", $productTypeValue)
        ->where("status", 1)
        ->where('slug', $productSlug);

        // Filter one-to-one puja
        if($productTypeValue == 2 && $isOneToOnePujaDetail){
          $product->where('is_one_to_one_puja', 1);
        }elseif($productTypeValue == 2 && !$isOneToOnePujaDetail){
          $product->where(function($query) {
            $query->where('is_one_to_one_puja', 0)
                  ->orWhereNull('is_one_to_one_puja');
          });
        }

        $product = $product->first();
      }

      if(!$product){
        // For one-to-one puja, if product not found, return 404
        // Category check is already done in detailOneToOnePuja method
        file_put_contents("debug_404.txt", "ABORTED AT LINE " . __LINE__); return abort(404);
      }

      // Get category - for one-to-one puja, use product_and_category_ids
            $category = null;
      if($product->category_id){
         $category = ProductCategory::find($product->category_id);
      }

      if(!$category){
         $productCategoryId = ProductAndCategoryId::where('product_id', $product->id)->first();
         if($productCategoryId){
            $category = ProductCategory::find($productCategoryId->category_id);
         }
      }

      if(!$category){
         // If still no category, just proceed without it instead of throwing 404
         // Some products might genuinely not have a category assigned
      }

      // Get related products
      $products = Product::where("type", $productTypeValue)
      ->where("status", 1)
      ->where('id', '!=', $product->id)
      ->where("parent_id", 0);

      // Filter one-to-one puja
      if($productTypeValue == 2){
        if($isOneToOnePujaDetail){
          $products->where('is_one_to_one_puja', 1);
          // Get products from same category using product_and_category_ids
          $categoryProductIds = ProductAndCategoryId::where('category_id', $category->id)
            ->where('product_id', '!=', $product->id)
            ->pluck('product_id')
            ->toArray();
          $products->whereIn('id', $categoryProductIds);
        }else{
          $products->where(function($query) {
            $query->where('is_one_to_one_puja', 0)
                  ->orWhereNull('is_one_to_one_puja');
          });
          $products->where("category_id", $product->category_id);
        }
      }else{
        $products->where("category_id", $product->category_id);
      }

      $products = $products->limit(10)->get();

      $productReview = ProductReview::where('product_id', $product->id)
      ->where('status', 1)
      ->orderBy('rating', 'DESC')
      ->select('rating')
      ->first();

      $rating = 4;

      if($productReview){
        $rating = $productReview->rating;
      }

      $onPage = OnPage::where('table', 'testimonials')
      ->where('page', 3)
      ->pluck('table_id')
      ->toArray();

      $textTestimonials = Testimonial::whereIn('id', $onPage)
      ->where('status', 1)
      ->orderBy('order', 'asc')
      ->limit(8)
      ->get();

      $onPage = OnPage::where('table', 'video_testimonials')
      ->where('page', 3)
      ->pluck('table_id')
      ->toArray();

      $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)
      ->where('status', 1)
      ->orderBy('order', 'asc')
      ->limit(8)
      ->get();

      $onPage = OnPage::where('table', 'template_faqs')
      ->whereIn('page', [0, $productTypeValue])
      ->pluck('table_id')
      ->toArray();

      $faqs = TemplateFaq::whereIn('id', $onPage)
      ->where('status', 1)
      ->where('category_id', $product->category_id)
      ->orderBy('order', 'asc')
      ->limit(5)
      ->get();

      $variations = [];
      $parent_id = $product->parent_id;

      if($parent_id){
        $variationsId = Product::where('status', 1)->where(function($query) use($product){
          $query->where('id', $product->parent_id)->orWhere('parent_id', $product->parent_id);
        })->pluck('id')->toArray();
      }else{
        $parent_id = $product->id;
        $variationsId = Product::where('status', 1)->where(function($query) use($product){
          $query->where('id', $product->id)->orWhere('parent_id', $product->id);
        })->pluck('id')->toArray();;
      }

      $attributes = PAttribute::where('product_id', $parent_id)->orderBy('attribute_id')->get();
      $attributeValue = ProductAttribute::whereIn('product_id', $variationsId)->groupBy('attribute_value')->get();
      $productAttributeValue = ProductAttribute::where('product_id', $product->id)->pluck('attribute_value')->toArray();
      $productAttributeValue = array_map('strtolower', $productAttributeValue);

      $productReviews = ProductReview::where('product_id', $product->id)
      ->where('status', 1)
      ->orderBy('rating', 'DESC')
      ->paginate(7);
      $maxRating = ProductReview::where('product_id', $product->id)
      ->where('status', 1)
      ->max('rating');
      $ratingCounts = [
          0 => 0,
          1 => ProductReview::where('product_id', $product->id)->where('status', 1)->where('rating', 1)->count(),
          2 => ProductReview::where('product_id', $product->id)->where('status', 1)->where('rating', 2)->count(),
          3 => ProductReview::where('product_id', $product->id)->where('status', 1)->where('rating', 3)->count(),
          4 => ProductReview::where('product_id', $product->id)->where('status', 1)->where('rating', 4)->count(),
          5 => ProductReview::where('product_id', $product->id)->where('status', 1)->where('rating', 5)->count(),
      ];

      $productId = $product->id;
      $now = now();
      $productVariations = Product::where('status', 1)->where('expire_at','>=',$now)
      ->where(function($query) use($parent_id){
        $query->orWhere('id', $parent_id)
        ->orWhere('parent_id', $parent_id);
      })
      ->where("id", "!=", $productId)
      ->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_{$productTypeName}_detail");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "{$productTypeName}_detail_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $product->id;
        $body["custom_data"]["content_name"] = "Detail Page";
        $body["custom_data"]["content_type"] = $productTypeName;
        $body["custom_data"]["currency"] = "RS";
        $todayDate = date("Y-m-d");
        if($product->sale_price_start_date && ($product->sale_price_start_date <= $todayDate) && ($product->sale_price_end_date >= $todayDate)){
          $body["custom_data"]["value"] = $product->sale_price;
        }else{
          $body["custom_data"]["value"] = $product->base_price;
        }
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_{$productTypeName}_detail", $user_type);
      }
      $breads=[];
      $categorySlug = $category ? $category->slug : "all";
      if($productTypeValue==1){
        $breads=[
          ["name"=>"Collections","url"=>route("product.handle")],
          ["name"=>"Collections List","url"=>route("product.handle",[$categorySlug])],
          ["name"=>"Product","url"=>route("product.detail",[$product->slug])]
        ];
      }elseif($productTypeValue==2){
        $breads=[
          ["name"=>"Pujas Collections","url"=>route("puja.handle")],
          ["name"=>"Pujas Collections List","url"=>route("puja.handle",[$categorySlug])],
          ["name"=>"Puja","url"=>route("product.detailpuja",[$product->slug])]
        ];
      }else{
        $breads=[
          ["name"=>"Reports Collections","url"=>route("service.handle")],
          ["name"=>"Reports Collections List","url"=>route("service.handle",[$categorySlug])],
          ["name"=>"Report","url"=>route("product.detailservice",[$product->slug])]
        ];
      }

      // For group puja: next upcoming valid date(s) for timer – timer shows countdown to first upcoming date, then next, etc.
      $pujaUpcomingValidDatetimes = [];
      $pujaNextValidDate = null;
      $pujaHasUpcomingDate = false;
      if ($productTypeValue == 2 && !$isOneToOnePujaDetail && $product->expire_at) {
        $timePart = date('H:i:s', strtotime($product->expire_at));
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $dates = $product->valid_till_dates;
        if (!empty($dates) && is_array($dates)) {
          foreach ($dates as $d) {
            $d = trim($d);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && $d >= $today) {
              $full = $d . ' ' . $timePart;
              if ($full >= $now) {
                $pujaUpcomingValidDatetimes[] = $full;
              }
            }
          }
          sort($pujaUpcomingValidDatetimes);
        }
        if (empty($pujaUpcomingValidDatetimes) && strtotime($product->expire_at) > time()) {
          $pujaUpcomingValidDatetimes = [$product->expire_at];
        }
        $pujaHasUpcomingDate = !empty($pujaUpcomingValidDatetimes);
        $pujaNextValidDate = $pujaUpcomingValidDatetimes[0] ?? null;
      }

      return view(themeView().'product.detail',compact("nav", "breads","category", 'product', "productTypeName", "productTypeValue", 'products', 'rating', 'textTestimonials', 'videoTestimonials', 'faqs', 'variations', 'parent_id','attributes','attributeValue','productAttributeValue', "productReviews", "productVariations","maxRating","ratingCounts", 'isOneToOnePujaDetail', 'pujaUpcomingValidDatetimes', 'pujaNextValidDate', 'pujaHasUpcomingDate'));
    }

    public function addtocart(Request $request){
      $request->validate([
          'item_id' => 'required|integer|exists:products,id',
      ]);
      if(auth()->guard('web')->check()){
          $request->user_id =  auth()->guard('web')->user()->id;
          $request->user_type = 'user';
      }elseif(Session::get("cart_user_id") == null){
          $request->user_id =  uniqid();
          $request->user_type = 'guest';
      }else{
        $request->user_id = Session::get("cart_user_id");
        $request->user_type = Session::get("cart_user_type");
      }
      Session::put("cart_user_id", $request->user_id);
      Session::put("cart_user_type", $request->user_type);
      $status = 'success';
      $message = 'Item added to cart successfully.';
      $product = Product::where('id', $request->item_id)->where("type", 1)->where('avaibility', '>', 0)->first();
      $redirectTo = "";
      if($product){
        $cartItem = CartItem::where('user_id', $request->user_id)
        ->where('user_type', $request->user_type)
        ->where('item_id', $request->item_id)
        ->where('type', 'shop')
        ->first();
        if($cartItem){
          $status = 'warning';
          $message = 'Item already added in cart.';
        }else{
          $cartItem = new CartItem();
          $cartItem->user_id = $request->user_id;
          $cartItem->user_type = $request->user_type;
          $cartItem->item_id = $request->item_id;
          $cartItem->type = 'shop';
          $cartItem->quantity = 1;
          if($product->min_order_qty > 1){
            $cartItem->quantity = $product->min_order_qty;
            $message = "Item added to cart successfully. Minimum QTY ($product->min_order_qty)";
          }
          $cartItem->unit_price = $product->base_price;
          $cartItem->price = $product->base_price;
          if($product->sale_price_start_date && (date('Y-m-d') >= $product->sale_price_start_date ) && (date('Y-m-d') <= $product->sale_price_end_date) ){
            $cartItem->price = $product->sale_price;
          }
          $cartItem->item_type = 0;
          $cartItem->formdata = json_encode([]);
        }
        $cartItem->save();
        if($request->has("buynow") && $request->buynow ==1){
          $redirectTo = route('checkout', ['product',"cartitemid"=>$cartItem->id]);
        }
      }else{
        $status = 'error';
        $message = 'Out of stock!';
      }

      return response()->json([
          'status' => $status,
          'message' => $message,
          'user_id' => $request->user_id,
          'redirectTo' => $redirectTo,
      ]);
    }

  public function addtocartcourse(Request $request){
    $request->validate([
        'item_id' => 'required|integer|exists:courses,id', // Product must exist in the products table
    ]);
    // if(Session::get("cart_user_id") == null && auth()->guard('web')->check()){
    if(auth()->guard('web')->check()){
        $request->user_id =  auth()->guard('web')->user()->id;
        $request->user_type = 'user';
    // }elseif(Session::get("cart_user_id") == null && auth()->guard('astrologer')->check()){
    }elseif(auth()->guard('astrologer')->check()){
      $request->user_id =  auth()->guard('astrologer')->user()->id;
      $request->user_type = 'astrologer';
    }elseif(Session::get("cart_user_id") == null){
        $request->user_id =  uniqid();
        $request->user_type = 'guest';
    }else{
      $request->user_id = Session::get("cart_user_id");
      $request->user_type = Session::get("cart_user_type");
    }
    Session::put("cart_user_id", $request->user_id);
    Session::put("cart_user_type", $request->user_type);
    $cartItem = CartItem::where('user_id', $request->user_id)->where('user_type', $request->user_type)->where('item_id', $request->item_id)->where('type', 'course')->first();
    if(!$cartItem){
      $course = Course::where('id', $request->item_id)->where('publish', 1)->first();
      if($course){
        $cartItem = new CartItem();
        $cartItem->user_id = $request->user_id;
        $cartItem->user_type = $request->user_type;
        $cartItem->item_id = $request->item_id;
        $cartItem->quantity = 1;
        $cartItem->unit_price = $course->price;
        $cartItem->price = $course->price;
        if($course->purchase_type == 4){
          $cartItem->price = 0;
        }elseif($course->sale_price_start_date && (date('Y-m-d') >= $course->special_from_date) && (date('Y-m-d') <= $course->special_to_date) ){
          $cartItem->price = $course->special_price;
        }
        $cartItem->type = 'course';
        $cartItem->save();
      }
    }

    $cartItems = CartItem::where('user_id', $request->user_id)->where('user_type', $request->user_type)->orderBy('created_at', 'desc')->get();
    $html = '';
    $totalPrice = 0;
    $totalCount = $cartItem?$cartItem->count():0;

    return response()->json([
        'status' => 'success',
        'message' => 'course added to cart successfully!',
        'html' => $html,
        'cart_item' => $cartItem,
        'user_id' => $request->user_id,
        'user_type' => $request->user_type,
        'totalCount' => $totalCount,
        'totalPrice' => $totalPrice
    ]);
}

public function cart(Request $request){
    $user_id = '';
    $user_type = '';
    if(auth()->guard('web')->check()){
        $user_id =  auth()->guard('web')->user()->id;
        $user_type = 'user';
    }elseif(auth()->guard('astrologer')->check()){
      $user_id =  auth()->guard('astrologer')->user()->id;
      $user_type = 'astrologer';
    }elseif(Session::get("cart_user_id")){
      $user_id = Session::get("cart_user_id");
      $user_type = Session::get("cart_user_type");
    }
    Session::put("cart_user_id", $user_id);
    Session::put("cart_user_type", $user_type);
    $cartItems = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->get();
    $shippingPrice = 0;
    $discount = 0;
    return view(themeView().'cartdetail.index', compact('cartItems', 'shippingPrice', 'discount'));
}

public function cartt($user_id){
    $user_id = '';
    $user_type = '';
    if(auth()->guard('web')->check()){
        $user_id =  auth()->guard('web')->user()->id;
        $user_type = 'user';
    }elseif(auth()->guard('astrologer')->check()){
      $user_id =  auth()->guard('astrologer')->user()->id;
      $user_type = 'astrologer';
    }elseif(Session::get("cart_user_id")){
      $user_id = Session::get("cart_user_id");
      $user_type = Session::get("cart_user_type");
    }
    Session::put("cart_user_id", $user_id);
    Session::put("cart_user_type", $user_type);
    $cartItems = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->get();
    $totalPrice = 0;
    $shippingCharges = 0;
    $discount = 0;
    $html = '';
    $course ='';
    foreach ($cartItems as $item) {
      $totalPrice += $item->price*$item->quantity;
      if($item->type == 'course'){
          $html .= '<div class="card border-0 border-bottom py-3" id="cart-item-' . $item->id . '">';
          $html .= '<button type="button" class="btn btn-close close_miniside remove-item" data-id="' . $item->id . '" name="button">
          <i class="bi bi-trash3-fill text-danger fs-5"></i>
          </button>';
          $html .= '<div class="row align-items-center mt-5">';
          $html .= '<div class="col-sm-2 col-6 mb-2">';
          $html .= '<img src="' . ($item->course->image ? $item->course->image->image(250, 250) : asset('assets/image/default.png')) . '" alt="'.$item->course->name.'" class="thumb img-thumbnail">'; // Correct image handling
          $html .= '</div>';
          $html .= '<div class="col-lg-10 col-6">';
          $html .= '<div class="row">';
          $html .= '<div class="col-sm-5 col-12 d-flex flex-column">';
            $html .= '<a href="'.route('course.handle', [$item->course->slug]).'">';
              $html .= '<span class="text-gold fs-6 text">' . $item->course->name . '</span>'; // Accessing course name
            $html .= '</a>';
          $html .= '</div>';
          $html .= '<div class="col-sm-2 col-12 text-center">';
          if($item->unit_price != $item->price){
            $html .= '<span class="fw-bold"><del class="text-black">₹'.($item->unit_price).'</del> <span class="text-ac-price">₹'.($item->price).'</span></span>';
          }else{
            $html .= '<span class="fw-bold text-ac-price">₹'.($item->price).'</span>';
          }
          $html .= '</div>';
          $html .= '<div class="col-sm-3 col-12 text-center">';
          $html .= '<div class="input-group add_number">';
          // $html .= '<input type="text" class="form-control text-center" id="quantity-' . $item->id . '" value="1" min="1" readonly>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '<div class="col-sm-2 col-12 text-center">';
          $html .= '<span class="fw-bold item-price"><i class="bi text-dark bi-currency-rupee"></i>' . $item->quantity*$item->price . '</span>'; // Fixed price typo
          $html .= '</div>';
          $html .= '</div>'; // Close row
          $html .= '</div>'; // Close col-lg-10
          $html .= '</div>'; // Close card
          $html .= '</div>'; // Close card
      }else{
          $html .= '<div class="card border-0 border-bottom py-3" id="cart-item-' . $item->id . '">';
          $html .= '<button type="button" class="btn btn-close close_miniside remove-item" data-id="' . $item->id . '" name="button">
          <i class="bi bi-trash3-fill text-danger fs-5"></i>
          </button>';
          $html .= '<div class="row align-items-center mt-5">';
          $html .= '<div class="col-sm-2 col-6 mb-2">';
          $html .= '<img src="'. ($item->product->image ? $item->product->image->image(250, 250) : asset('assets/image/default.png')) .'" alt="'.$item->product->title.'" class="thumb img-thumbnail">';
          $html .= '</div>';
          $html .= '<div class="col-lg-10 col-6">';
          $html .= '<div class="row">';
          $html .= '<div class="col-sm-5 col-12 d-flex flex-column">';
            $html .= '<a href="'.route('product.handle', [$item->product->slug]).'">';
              $html .= '<span class="text-gold fs-6 text">' . $item->product->title . '</span>';
            $html .= '</a>';
            $html .= '<span class="text-dark fs-8">Stock: ' . $item->product->avaibility . '</span>';
          $html .= '</div>';
          $html .= '<div class="col-sm-2 col-12 text-center">';
          if($item->product->type == 1){
            if($item->unit_price != $item->price){
              $html .= '<span class="fw-bold"><del class="text-black">₹'.($item->unit_price).'</del> <span class="text-ac-price ">₹'.($item->price).'</span> x <span class="text-black">'.($item->quantity).'</span></span>';
            }else{
              $html .= '<span class="text-ac-price fw-bold">₹'.($item->price).' x <span class="text-dark">'.($item->quantity).'</span></span>';
            }
          }else{
            if($item->unit_price != $item->price){
              $html .= '<span class="fw-bold"><del class="text-black">₹'.($item->unit_price).'</del> <span class="text-ac-price ">₹'.($item->price).'</span></span>';
            }else{
              $html .= '<span class="text-ac-price fw-bold">₹'.($item->price).'</span>';
            }
          }
          $html .= '</div>';
          $html .= '<div class="col-sm-3 col-12 text-center">';
          $html .= '<div class="input-group add_number">';
          if($item->product->type == 1){
            $html .= '<button class="btn btn-outline-secondary text-center decrease-quantity" type="button" data-id="' . $item->id . '">-</button>';
            $html .= '<input type="text" class="form-control text-center" id="quantity-' . $item->id . '" value="' . $item->quantity . '" min="1" readonly>';
            $html .= '<button class="btn btn-outline-secondary text-center increase-quantity" type="button" data-id="' . $item->id . '">+</button>';
          }
          $html .= '</div>';
          $html .= '</div>';
          $html .= '<div class="col-sm-2 col-12 text-center">';
          $html .= '<span class="fw-bold item-price"><i class="bi text-dark bi-currency-rupee"></i>' . $item->quantity*$item->price . '</span>';
          $html .= '</div>';
          $html .= '</div>'; // Close row
          $html .= '</div>'; // Close col-lg-10
          $html .= '</div>'; // Close card
          $html .= '</div>'; // Close card
      }
    }
    $finalTotalPrice = ($totalPrice + $shippingCharges) - $discount;

    $totalCount = $cartItems->count();
    return response()->json([
        'message'=>"Your Cart Is Empty",
        'html' => $html,
        'totalPrice' => $totalPrice,
        'shippingCharges' => $shippingCharges,
        'discount' => $discount,
        'finalTotalPrice' => $finalTotalPrice,
        'totalCount' => $totalCount,
         // Total count of products in the cart
    ]);
}

public function increaseQuantity($cartItemId){
    $cartItem = CartItem::find($cartItemId);
    if(!$cartItem){
      return response()->json(['status' => 'success', 'message' => 'Cart Item has not added in your cart.'], 402);
    }
    $increased = false;
    $message = "Reached maximum limit";
    if($cartItem->type == 'shop'){
      $product = $cartItem->product;
      if($product->type == 1 && $product->avaibility > $cartItem->quantity ){
        $cartItem->quantity = $cartItem->quantity + 1;
        $increased = true;
        if($cartItem->quantity < $product->min_order_qty){
          $message = "Minimum ".$product->min_order_qty." quantity required for checkout";
          $cartItem->quantity = $product->min_order_qty;
        }
        if($cartItem->quantity>=$product->max_order_qty){
          $message = "Mmaximum ".$product->max_order_qty." quantity allowed for checkout";
          $cartItem->quantity = $product->max_order_qty;
          $increased = false;
        }
        $cartItem->save();
      }
    }

    if($increased){
      $message = "Quantity increased!";
    }
    return response()->json(['status' => 'success', 'cart_item' => $cartItem, 'qty'=>$cartItem->quantity, 'message' => $message], 200);
}

public function decreaseQuantity($cartItemId){
    $cartItem = CartItem::find($cartItemId);
    if(!$cartItem){
      return response()->json(['status' => 'success', 'message' => 'Cart Item has not added in your cart.'], 402);
    }
    $decreased = false;
      $message = "Quantity decreased!";
    if($cartItem->type == 'shop'){
      $product = $cartItem->product;
      if($product->type == 1){
        if($cartItem->quantity > 1){
          $cartItem->quantity = $cartItem->quantity - 1;
          if($cartItem->quantity<$product->min_order_qty){
            $message = "Minimum ".$product->min_order_qty." quantity required for checkout";
            $cartItem->quantity = $product->min_order_qty;
          }
          if($cartItem->quantity>$product->max_order_qty){
            $message = "Mmaximum ".$product->max_order_qty." quantity allowed for checkout";
            $cartItem->quantity = $product->max_order_qty;
          }
          $cartItem->save();
          return response()->json(['status' => 'success', 'cart_item' => $cartItem, 'qty' => $cartItem->quantity, 'message'=>$message], 200);
        }else{
          $cartItem->delete();
          return response()->json(['status' => 'success', 'message' => 'Item removed successfully.', 'qty'=> 0], 200);
        }
      }
    }
}

public function removeItem($id)
{
    $cartItem = CartItem::find($id);

    if ($cartItem) {
        $cartItem->delete();
        return response()->json(['status' => 'success', 'message' => 'Item removed successfully']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Item not found']);
    }
}

public function showcart(Request $request)
{
    $user_id = 0;
    $user_type = '';
    $item_type = 0;
    if(auth()->guard('web')->check()){
        $user_id =  auth()->guard('web')->user()->id;
        $user_type = 'user';
    }elseif(Session::get("cart_user_id")){
      $user_id = Session::get("cart_user_id");
      $user_type = Session::get("cart_user_type");
    }
    Session::put("cart_user_id", $user_id);
    Session::put("cart_user_type", $user_type);
    $cartItems = CartItem::where('user_id', $user_id)->where('user_type', $user_type)->where('item_type', $item_type)->orderBy('created_at', 'desc')->get();
    $html = '';
    $totalPrice = 0;
    $totalCount = 0;
    $hasOutOfStockProduct = false;
    $productTypeName = "Product";
    $productTypeValue = 1;
    $todayDate = date("Y-m-d");
    foreach ($cartItems as $key => $item){
      $product = $item->product;
      if($product){
        $productSpecialPriceFromDate = $product->sale_price_start_date;
        $productSpecialPriceToDate = $product->sale_price_end_date;
        $productPrice = $product->base_price;
        $productSpecialPrice = $product->sale_price;
        $item->unit_price = $productPrice;
        $item->price = $productPrice;
        if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate)){
          $item->price = $productSpecialPrice;
        }
        $item->save();

        $totalCount += $item->quantity;
        $totalPrice += ($item->price*$item->quantity);
        if($product->avaibility <= 0){
          $hasOutOfStockProduct = true;
        }
        $html .= view(themeView()."cartdetail.product.sideBarCartItem", compact("item", "product", "productTypeName", "productTypeValue"))->render();
      }else{
        $item->delete();
      }
    }

    // Return the total count and generated HTML in the response
    return response()->json([
      'message'=>"Your Cart Is Empty.",
        'totalCount' => $totalCount, // Total count of products
        'html' => $html,
        'totalPrice' => sprintf('%.2f', $totalPrice),
        'user_id' => $user_id,
        'user_type' => $user_type,
        'hasOutOfStockProduct' => $hasOutOfStockProduct,
    ]);
}

public function checkoutform(Request $request){
    $user=auth()->guard('web')->user();
    $user_type="user";

    // Check for referral code in URL parameter (from Share and Earn link)
    $referralCodeFromUrl = $request->get('ref');
    if($referralCodeFromUrl) {
      Session::put('product_referral_code', $referralCodeFromUrl);
    }

    if($user==null){
      Session::put('redirect_from_login', url()->full());
      // Preserve product referral code if present
      if(Session::has('product_referral_code')) {
        // Keep it in session for after login
      }
      return redirect()->route('home');
    }

    // If user is logged in and product_referral_code exists, store it for commission processing
    // Always update used_referral_code if product_referral_code exists (for Share and Earn)
    if(Session::has('product_referral_code')) {
      $referralCode = Session::get('product_referral_code');
      // Store in user's used_referral_code (update even if already set, for Share and Earn)
      if($referralCode) {
        $user->used_referral_code = $referralCode;
        $user->save();
      }
    }
    if(!$user->phone){
      Session::put('redirect_from_login', url()->full());
      Session::flash('message', "Please fill your phone number.");
      return redirect()->route('user.profile', ["tab" => "change-phone-number"]);
    }
    if($user->country_id == 0 || $user->state_id == 0 || $user->city_id == 0){
      Session::put('redirect_from_login', url()->full());
      Session::flash('message', "Please fill your address details.");
      return redirect()->route('user.profile', ["tab" => "my-profile"]);
    }
    $user_temp_cart_id=$request->user_temp_cart_id?decrypt($request->user_temp_cart_id):0;
    if(UserTempCart::where("id",$user_temp_cart_id)->where("user_id",$user->id)->where("user_type",$user_type)->first()){
      return $this->checkoutformTwo($request);
    }
    $addresses = ShippingAddress::where('user_id', $user->id)->where('user_type', $user_type)->get();
    if($addresses->count()==0){
      $shippingAddress = new ShippingAddress();
      $shippingAddress->user_id = $user->id;
      $shippingAddress->user_type = $user_type;
      $shippingAddress->name = $user->name;
      $shippingAddress->email = $user->email;
      $shippingAddress->phone = $user->phone;
      $shippingAddress->city_id = $user->city_id;
      $shippingAddress->city = $user->city;
      $shippingAddress->state_id = $user->state_id;
      $shippingAddress->state = $user->state;
      $shippingAddress->country_id = $user->country_id;
      $shippingAddress->country = $user->country;
      $shippingAddress->address = $user->address;
      $shippingAddress->zipcode = $user->pincode;
      $shippingAddress->save();
      $addresses = ShippingAddress::where('user_id',$user->id)->where('user_type', $user_type)->get();
    }
    $productTypeName=$request->productTypeName?:"Product"; // Product, Pooja and Service
    $productTypeName=ucfirst($productTypeName); // Product, Pooja and Service
    $normalizedType = strtolower($productTypeName);
    if(in_array($normalizedType, ['onetoonepuja', 'one-to-one-puja', 'one_to_one_puja'])){
      $productTypeName = "Pooja";
    }
    $productTypeValue=1;
    $item_type=0;
    if($productTypeName=="Pooja"){
      $productTypeValue=2;
      $item_type=1;
    }elseif($productTypeName=="Service"){
      $productTypeValue=3;
      $item_type=1;
    }
    $itemType=$request->itemType?:"shop"; // shop, course
    $pincode=$user->pincode;
    $selecteAddressId=$request->address_id?:0;
    $address=$addresses->where("id",$selecteAddressId)->first();
    if($address){
      $pincode=$address->zipcode;
      // Use the provided address_id if it exists
      $selecteAddressId=$address->id;
    }else{
      $address=$addresses->first();
      if($address){
        $pincode=$address->zipcode;
        // Only set to first address if no address_id was provided in request
        if(!$request->address_id || $request->address_id == 0){
          $selecteAddressId=$address->id;
        } else {
          // If address_id was provided but not found, still preserve it
          // The address might have been just created and will be in the list
          $selecteAddressId=$request->address_id;
        }
      }
    }
    if($productTypeName=='Pooja'||$productTypeName=='Service'){
      // Preserve the address_id from request - don't reset it
      // This allows the selected address from puja form to be shown on checkout page
      $itemSlug=$request->item_slug?:"";
      $product=Product::where("type",$productTypeValue)->where("status", 1)->where('slug',$itemSlug)->first();

      // If product not found by slug, check if it's a variation product with cloned slug format
      // Variation products now have format: parent-slug-cloned-{parent-id}-{variation-id}
      if(!$product && $itemSlug) {
        // Check if slug matches cloned format pattern
        if(preg_match('/-cloned-(\d+)-(\d+)$/', $itemSlug, $matches)) {
          // Extract variation ID from slug (last number)
          $variationId = (int)$matches[2];
          $product = Product::where("type", $productTypeValue)
            ->where("status", 1)
            ->where('id', $variationId)
            ->first();
        }

        // If still not found, try to find parent product with this slug
        if(!$product) {
          $parentProduct = Product::where("type", $productTypeValue)
            ->where("status", 1)
            ->where('slug', $itemSlug)
            ->where('parent_id', 0)
            ->first();

          if($parentProduct) {
            // If parent found, check if there are any active variations
            $variationProduct = Product::where("type", $productTypeValue)
              ->where("status", 1)
              ->where('parent_id', $parentProduct->id)
              ->first();

            if($variationProduct) {
              $product = $variationProduct;
            } else {
              $product = $parentProduct;
            }
          }
        }
      }

      // IMPORTANT:
      // Even if product is not found (e.g. slug/type/status mismatch),
      // we should NOT redirect to home. That breaks checkout for some pujas.
      // Instead, just skip the "delete other cart items" logic and continue
      // with whatever cart items already exist.
      if($product){
        CartItem::where('user_id', $user->id)
        ->where('user_type', $user_type)
        ->where("item_id", "!=", $product->id)
        ->where("type", $itemType)
        ->where('item_type', $item_type)
        ->delete();
      }
    }
    $cartitemid=$request->cartitemid?:0;
    $cartItems = CartItem::where('user_id', $user->id)
    ->where('user_type', $user_type)
    ->where("type", $itemType)
    ->where('item_type', $item_type);
    if($cartitemid>0){
      $cartItems->where('id', $cartitemid);
    }
    $cartItems=$cartItems->get();
    if($cartItems->count()==0){
      return redirect()->route('home');
      // return "itmes not found";
    }
    $subTotal=0;
    $discount=0;
    $coupon_amount=0;
    $taxes=0;
    $shippingPrice=0;
    $total=0;
    $todayDate=date("Y-m-d");
    foreach($cartItems as $key=>$item){
      if($item->type=='shop'){
        $product=$item->product;
        $isOneToOnePuja = $product && $product->type == 2 && $product->is_one_to_one_puja == 1;
        if($product && $product->status && $product->avaibility>0 || $isOneToOnePuja){
          $productSpecialPriceFromDate=$product->sale_price_start_date;
          $productSpecialPriceToDate = $product->sale_price_end_date;
          $productPrice = $product->base_price;
          $productSpecialPrice = $product->sale_price;
          $item->unit_price = $productPrice;
          $item->price = $productPrice;
          if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate)){
            $item->price = $productSpecialPrice;
          }
          $item->save();
          $itemPrice = $item->unit_price; // base_price
          $itemSpecialPrice = $item->price; // sale_price
          $itemQuantity = $item->quantity;
          $subTotal += $itemPrice*$itemQuantity;
          $total += $itemSpecialPrice*$itemQuantity;
          if($productHnsCode = $product->hnsCode){
            $gstPercentage = $productHnsCode->gst_percentage;
            $taxes += ($itemQuantity*$itemSpecialPrice)*($gstPercentage/100);
          }
          if($product->apply_shipping && $pincode){
            $itemShippingAmount = calculateShippingAmount($product, $pincode)*$itemQuantity;
            $shippingPrice += $itemShippingAmount;
          }
        }else{
          $item->delete();
        }
      }
    }
    // return $subTotal;
    $discount=$subTotal-$total;

    // Apply referral discount ONLY if user came from remedies checkout link
    // Session is only set in remediesCheckout method, so this ensures discount only applies for suggested remedies
    $remediesReferralDiscount = 0;
    $remediesReferralCode = null;
    if(Session::has('remedies_referral_code')){
      $remediesReferralCode = Session::get('remedies_referral_code');

      // Check if referral code exists in astrologers table
      $referringAstrologer = \App\Model\Astrologer::where('referral_code', $remediesReferralCode)
        ->where('status', 1)
        ->where('is_verified', 1)
        ->first();

      // Get global referral settings for product discount
      if($referringAstrologer){
        $astrologerReferralSettings = AstrologerReferral::where('status', 1)->first();

        if($astrologerReferralSettings && $astrologerReferralSettings->product_discount > 0){
          // Calculate discount only on products (type 1) subtotal, not on reports (type 3)
          $productSubtotal = 0;
          foreach($cartItems as $item){
            if($item->type == 'shop'){
              $product = $item->product;
              if($product && $product->type == 1){ // Only products, not reports
                $productSubtotal += $item->price * $item->quantity;
              }
            }
          }
          // Apply discount on product subtotal
          $remediesReferralDiscount = ($productSubtotal * $astrologerReferralSettings->product_discount) / 100;
        }
      }
    }

    // Calculate total: subtotal - referral discount, then add shipping and taxes
    $total = $total - $remediesReferralDiscount;
    $total = $total + $shippingPrice + $taxes;

    // Show delivery address only when cart has at least one physical product (type 1). Puja (type 2) and Service (type 3) need only billing.
    $showDeliveryAddress = false;
    foreach($cartItems as $item){
      if($item->type == 'shop'){
        $product = $item->product;
        if($product && (int)$product->type === 1){
          $showDeliveryAddress = true;
          break;
        }
      }
    }

    $countries=DB::table('master_countries')->get();
    $states=DB::table('master_states')->where('country_id',$user->country_id)->get();
    // $cities=DB::table('master_cities')->where('state_id',$user->state_id)->get();
    $orderId=$request->order_id?:0;
    return view(themeView() . 'cartdetail.checkout', [
        'user' => $user,
        'addresses' => $addresses,
        'orderId' => $orderId,
        'selecteAddressId' => $selecteAddressId,
        'cartItems' => $cartItems,
        'subTotal' => $subTotal,
        'total' => $total,
        'discount' => $discount,
        'shippingPrice' => $shippingPrice,
        'taxes' => $taxes,
        'countries'=> $countries,
        'states'=> $states,
        // 'cities'=> $cities,
        'item_type'=> $item_type,
        'cartitemid' => $cartitemid,
        'coupon_amount' => $coupon_amount,
        'user_temp_cart' => false,
        'user_temp_cart_id' => ($request->user_temp_cart_id?:""),
        'remediesReferralDiscount' => $remediesReferralDiscount,
        'remediesReferralCode' => $remediesReferralCode,
        'showDeliveryAddress' => $showDeliveryAddress,
    ]);
}

public function checkoutformTwo(Request $request){
    $user=auth()->guard('web')->user();
    $user_type="user";
    $user_temp_cart_id=$request->user_temp_cart_id?decrypt($request->user_temp_cart_id):0;
    $userTempCart=UserTempCart::where("id",$user_temp_cart_id)->where("user_id",$user->id)->where("user_type",$user_type)->first();
    if($userTempCart==null){
      return redirect()->route("home");
    }
    $addresses = ShippingAddress::where('user_id', $user->id)->where('user_type', $user_type)->get();
    if($addresses->count()==0){
      $shippingAddress = new ShippingAddress();
      $shippingAddress->user_id = $user->id;
      $shippingAddress->user_type = $user_type;
      $shippingAddress->name = $user->name;
      $shippingAddress->email = $user->email;
      $shippingAddress->phone = $user->phone;
      $shippingAddress->city_id = $user->city_id;
      $shippingAddress->city = $user->city;
      $shippingAddress->state_id = $user->state_id;
      $shippingAddress->state = $user->state;
      $shippingAddress->country_id = $user->country_id;
      $shippingAddress->country = $user->country;
      $shippingAddress->address = $user->address;
      $shippingAddress->zipcode = $user->pincode;
      $shippingAddress->save();
      $addresses = ShippingAddress::where('user_id',$user->id)->where('user_type', $user_type)->get();
    }
    $productTypeName=$request->productTypeName?:"Product"; // Product, Pooja and Service
    $productTypeName=ucfirst($productTypeName); // Product, Pooja and Service
    $productTypeValue=1;
    $itemType="product";
    $selecteAddressId=$request->address_id?:0;
    $address=$addresses->where("id",$selecteAddressId)->first();
    if($address){
      // Use the provided address_id if it exists
      $selecteAddressId=$address->id;
    } else {
      $address=$addresses->first();
      if($address){
        // Only set to first address if no address_id was provided in request
        if(!$request->address_id || $request->address_id == 0){
          $selecteAddressId=$address->id;
        } else {
          // If address_id was provided but not found, still preserve it
          // The address might have been just created and will be in the list
          $selecteAddressId=$request->address_id;
        }
      }
    }
    $item_type=0;
    $cartItems=AdminSideCartItem::with('product')->where('user_temp_cart_id',$userTempCart->id)
    ->where('user_id',$user->id)
    ->where('user_type',$user_type)
    ->where("item_type",$itemType)
    ->get();
    if($cartItems->count()==0){
      return redirect()->route('home');
      // return "itmes not found";
    }
    $subTotal=$cartItems->sum("unit_base_price");
    $discount=$userTempCart->discount_amount;
    $coupon_amount=$userTempCart->extra_discount_amount;
    $taxes=0;
    $shippingPrice=$userTempCart->shipping_amount;
    $total=0;
    $orderId=$request->order_id?:0;
    $cartitemid=0;
    $todayDate=date("Y-m-d");
    $cartItemsCount=$cartItems->count();
    $cartTotalAmount=$cartItems->sum("unit_sale_price");
    foreach($cartItems as $key=>$item){
      $item->price=$item->sale_price;
      $item->unit_price=$item->base_price;
      $item->type="shop";
      $item->formdata=json_encode([]);
      $item->item_type=0;

      $itemQuantity=$item->quantity;
      $itemPrice=$item->base_price;
      $itemDiscountAmount=($item->sale_price/$cartTotalAmount)*$coupon_amount;
      $itemDiscountAmount=$itemDiscountAmount/$item->quantity;
      $itemSpecialPrice=$item->sale_price-$itemDiscountAmount;
      // $subTotal+=$itemPrice*$item->quantity;
      $gstPercentage=$item->tax_percentage;
      $taxes+=($itemQuantity*$itemSpecialPrice)*($gstPercentage/100);
      $total+=$itemSpecialPrice*$itemQuantity;
    }
    $discount=$subTotal-($total+$coupon_amount);
    $total+=$shippingPrice+$taxes;

    // Show delivery address only when cart has at least one physical product (type 1). Puja (2) and Service (3) need only billing.
    $showDeliveryAddress = false;
    foreach($cartItems as $item){
      $product = $item->product;
      if($product && (int)$product->type === 1){
        $showDeliveryAddress = true;
        break;
      }
    }

    $countries=DB::table('master_countries')->get();
    $states=DB::table('master_states')->where('country_id',$user->country_id)->get();
    // $cities=DB::table('master_cities')->where('state_id',$user->state_id)->get();
    return view(themeView() . 'cartdetail.checkout', [
        'user' => $user,
        'addresses' => $addresses,
        'orderId' => $orderId,
        'selecteAddressId' => $selecteAddressId,
        'cartItems' => $cartItems,
        'subTotal' => $subTotal,
        'total' => $total,
        'discount' => $discount,
        'shippingPrice' => $shippingPrice,
        'taxes' => $taxes,
        'countries'=> $countries,
        'states'=> $states,
        // 'cities'=> $cities,
        'item_type'=> $item_type,
        'cartitemid' => $cartitemid,
        'coupon_amount' => $coupon_amount,
        'user_temp_cart' => true,
        'user_temp_cart_id' => ($request->user_temp_cart_id?:""),
        'showDeliveryAddress' => $showDeliveryAddress,
    ]);
}

public function applyCouponCode(Request $request){
  $this->validate($request, [
    'type'=>'required', // order, recharge
    'coupon_code'=>'required',
    // 'item_type'=>'required|in:0,1',
  ]);
  $callType = $request->callType ?: 'ajax';
  $type = $request->type;
  $item_type = $request->item_type ?: 0;
  $plan_id = $request->plan_id ?: 0;
  $code = trim($request->coupon_code);
  $user = null;
  $user_type = '';
  $discountAmount = 0;
  $taxAmount = 0;
  $cartitemid = $request->cartitemid ?: 0;

  $amount = 0;
  $carItems = null;
  $plan = null;
  if(auth()->guard('web')->user()){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
  }
  if(!$user){
    return response()->json([
      'success' => false,
      'message' => 'Invalid action.'], 402);
  }
  $currentDate = date('Y-m-d');
  $coupon = Coupon::where('code', $code)
  ->where('status', 1)
  ->where(function($query) use ($currentDate){
    $query->whereNull('from_date')
    ->orWhere(function($q) use ($currentDate){
      $q->whereDate('from_date', '<=', $currentDate)
      ->whereDate('to_date', '>=', $currentDate);
    });
  })
  // ->whereRaw('IF(from_date AND (from_date <= CURDATE() AND to_date >= CURDATE()), to_date, CURDATE()) >= ?', [$currentDate])
  ->first();
  if(!$coupon){
    return response()->json([
      'success' => false,
      'message' => 'Invalid coupon.',
      // 'code' => $code
    ], 402);
  }
  $code = $coupon->code;
  if($coupon->only_one_time){
    if($type == 'order'){
      $order = Order::whereNotIn('order_status', ['pending', 'cancel'])
      ->where('coupon_id', $coupon->id)
      ->where('coupon', $code)
      ->first();
      if($order){
        return response()->json([
          'success' => false,
          'message' => 'Invalid coupon.'
        ], 402);
        // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
      }
    }elseif($type == 'recharge'){
      $userRecharge = UserRechargeHistory::where('payment_status', 'success')
      ->where('coupon_id', $coupon->id)
      ->where('coupon_code', $code)
      ->first();
      if($userRecharge){
        return response()->json([
          'success' => false,
          'message' => 'Invalid coupon.'
        ], 402);
        // return response()->json(['success'=>false, 'message'=>'This coupon is valid only one time.'], 402);
      }
    }
  }
  if($coupon->one_time){
    if($type == 'order'){
      $order = Order::where('status', 'Success')
      ->where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('coupon_id', $coupon->id)
      ->where('coupon', $code)
      ->first();
      if($order){
        return response()->json([
          'success' => false,
          'message' => 'This coupon is valid only one time and you already used this coupon once.'
        ], 402);
      }
    }elseif($type == 'recharge'){
      $userRecharge = UserRechargeHistory::where('payment_status', 'success')
      ->where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('coupon_id', $coupon->id)
      ->where('coupon_code', $code)
      ->first();
      if($userRecharge){
        return response()->json([
          'success' => false,
          'message' => 'This coupon is valid only one time and you already used this coupon once.'
        ], 402);
      }
    }
  }
  if($type == 'order'){
    if($coupon->apply_on == 'recharge'){
      return response()->json([
        'success' => false,
        'message' => 'Not valid for shopping.'
      ], 402);
    }
    if($coupon->apply_on == 'any'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('item_type', $item_type);
      if($cartitemid > 0){
        $cartItems->where('id', $cartitemid);
      }
      $cartItems = $cartItems->get();
      if($cartItems->count() == 0){
        return response()->json([
          'success' => false,
          'message' => 'Please Add item in cart first.'
        ], 402);
      }
      $amount = $cartItems->sum(function($item){
          return $item->price * $item->quantity;
      });
    }elseif($coupon->apply_on == 'product'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('type', 'shop')
      ->where('item_type', $item_type);
      if($cartitemid > 0){
        $cartItems->where('id', $cartitemid);
      }
      $cartItems = $cartItems->get();
      if($cartItems->count() == 0){
        return response()->json([
          'success' => false,
          'message' => 'Coupon valid for product, please add product in your cart first.'
        ], 402);
      }
      $amount = $cartItems->sum(function($item){
          return $item->price * $item->quantity;
      });
    }elseif($coupon->apply_on == 'course'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('type', 'course')
      ->where('item_type', $item_type);
      if($cartitemid > 0){
        $cartItems->where('id', $cartitemid);
      }
      $cartItems = $cartItems->get();
      if($cartItems->count() == 0){
        return response()->json([
          'success' => false,
          'message' => 'Coupon valid for course, please add course in your cart first.'
        ], 402);
      }
      $amount = $cartItems->sum(function($item){
          return $item->price * $item->quantity;
      });
    }
  }elseif($type == 'recharge'){
    if($coupon->apply_on == 'product' || $coupon->apply_on == 'course'){
      return response()->json([
        'success' => false,
        'message' => 'Not valid for recharge.'
      ], 402);
    }
    $plan = RechargePlan::find($plan_id);
    if(!$plan){
      return response()->json([
        'success' => false,
        'message' => 'Item not found'], 402);
    }
    $amount = $plan->amount;
  }
  if($amount < $coupon->minimum_amount){
    return response()->json([
      'success' => false,
      'message' => 'Minimum amount to use this coupon is ₹'.$coupon->minimum_amount
    ], 402);
  }
  if($coupon->fixed_perc){
    $percentage = $coupon->amount;
    $discountAmount = ($amount/100)*$percentage;
  }else{
    $discountAmount = $coupon->amount;
  }
  $totalBaseAmount = 0;
  $totalSaleAmountt = 0;
  $totalDiscountAmount = 0;
  $totalAmount = 0;
  if($type == 'order'){
    $cartItems = CartItem::where('user_id', $user->id)
    ->where('user_type', $user_type)
    ->where('item_type', $item_type);
    if($coupon->apply_on == 'product'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('type', "shop")
      ->where('item_type', $item_type);
    }elseif($coupon->apply_on == 'course'){
      $cartItems = CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('type', "course")
      ->where('item_type', $item_type);
    }
    if($cartitemid > 0){
      $cartItems->where('id', $cartitemid);
    }
    $cartItems = $cartItems->get();
    $totalQuantity = $cartItems->sum('quantity');
    foreach($cartItems as $key => $cartItem){
      if($cartItem->type == 'shop'){
        $product = $cartItem->product;
        if($product){
          $itemTotalBaseAmount = $cartItem->unit_price*$cartItem->quantity;
          $totalBaseAmount += $itemTotalBaseAmount;
          $itemTotalSaleAmount = $cartItem->price*$cartItem->quantity;
          $totalSaleAmountt += $itemTotalSaleAmount;
          $itemDiscountAmount = $itemTotalBaseAmount-$itemTotalSaleAmount;
          $totalDiscountAmount += $itemDiscountAmount;
          $itemCouponAmount = ($itemTotalSaleAmount/$amount)*$discountAmount;
          $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
          $totalAmount += $itemTotalSaleAmount;
          if($product->hnsCode){
            $gst_percentage = $product->hnsCode->gst_percentage;
            $taxAmount += $itemTotalSaleAmount*($gst_percentage/100);
          }
        }
      }elseif($cartItem->type == 'course'){
        $course = $cartItem->course;
        if($course){
          $itemTotalAmount = $cartItem->price*$cartItem->quantity;
          $itemTotalBaseAmount = $cartItem->unit_price*$cartItem->quantity;
          $totalBaseAmount += $itemTotalBaseAmount;
          $itemTotalSaleAmount = $cartItem->price*$cartItem->quantity;
          $totalSaleAmountt += $itemTotalSaleAmount;
          $itemDiscountAmount = $itemTotalBaseAmount-$itemTotalSaleAmount;
          $totalDiscountAmount += $itemDiscountAmount;
          if($course->apply_gst){
            if($course->hnsCode){
              $gst_percentage = $course->hnsCode->gst_percentage;
            }
            if($course->price_type){
              // tax included
              $itemCouponAmount = ($itemTotalSaleAmount/$amount)*$discountAmount;
              $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
              $taxAmountt = $itemTotalSaleAmount*($gst_percentage/100);
              $itemTotalSaleAmount = $itemTotalSaleAmount - $taxAmountt;
              $taxAmount += $taxAmountt;
              $totalAmount += $itemTotalSaleAmount;
            }else{
              $itemCouponAmount = ($itemTotalSaleAmount/$itemTotalAmount)*$discountAmount;
              $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
              $taxAmount += $itemTotalSaleAmount*($gst_percentage/100);
              $totalAmount += $itemTotalSaleAmount;
            }
          }else{
            $itemCouponAmount = ($itemTotalSaleAmount/$itemTotalSaleAmount)*$discountAmount;
            $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;
            $totalAmount += $itemTotalSaleAmount;
          }
        }
      }
    }
  }
  if($callType == 'ajax'){
    return response()->json([
      'success' => true,
      'message' => 'Coupon code applied.',
      'id' => $coupon->id,
      'coupon_code' => $coupon->code,
      'discount' => $discountAmount,
      'taxes' => $taxAmount,
      'total' => $totalAmount,
    ], 200);
  }else{
    return response()->json([
      'success' => true,
      'message' => 'Coupon code applied.',
      'id' => $coupon->id,
      'code' => $coupon->code,
      'apply_on' => $coupon->apply_on,
      'discount' => $discountAmount,
      'taxes' => $taxAmount,
      'total' => $totalAmount,
    ], 200);
  }

}

public function placeOrder(Request $request){
  $user = auth()->guard('web')->user();
  $user_type = 'user';
  if(!$user){
    $errors = [
      "message" => ["Please login"]
    ];
    return response()->json([
      'errors' => $errors
    ], 402);
  }
  $billingName = $shippingName = $user->name;
  $billingEmail = $shippingEmail = $user->email;
  $billingPhone = $shippingPhone = $user->phone;
  $billingCountry = $shippingCountry = $user->country;
  $billingState = $shippingState = $user->state;
  $billingCity = $shippingCity = $user->city;
  $billingPincode = $shippingPincode = $user->pincode;
  $billingAddress = $shippingAddress = $user->address;

  $addressId = $request->address_id ?: 0;
  $shippingAddressData = ShippingAddress::find($addressId);
  if($shippingAddressData){
    $shippingName = $shippingAddressData->name;
    $shippingEmail = $shippingAddressData->email;
    $shippingPhone = $shippingAddressData->phone;
    $shippingCountry = $shippingAddressData->country;
    $shippingState = $shippingAddressData->state;
    $shippingCity = $shippingAddressData->city;
    $shippingPincode = $shippingAddressData->zipcode;
    $shippingAddress = $shippingAddressData->address;
  }

  $billing_address = $request->billing_address;
  if($billing_address == "different"){
    $request->validate([
      'billing_address_country' => 'required|gt:0',
      'billing_address_address' => 'required',
      'billing_address_pincode' => 'required|digits:6',
      'billing_address_city' => 'required|gt:0',
      'billing_address_state' => 'required|gt:0',
      "billing_address_phone" => "required|digits:10|regex:/^[1-9]\d{9}$/",
      "billing_address_email" => "required|email|max:100",
      "billing_address_full_name" => "required|max:100",
    ], [
      'billing_address_country' => 'The Billing Address Contry field is required.',
      'billing_address_state' => 'The Billing Address State field is required.',
      'billing_address_city' => 'The Billing Address City field is required.',
    ]);

    $billingName = $request->billing_address_full_name;
    $billingEmail = $request->billing_address_email;
    $billingPhone = $request->billing_address_phone;
    $billingPincode = $request->billing_address_pincode;
    $billingAddress = $request->billing_address_address;
    $billingCountryId = $request->billing_address_country;
    $billingStateId = $request->billing_address_state;
    $billingCityId = $request->billing_address_state;

    $country = DB::table("master_countries")->where("id", $billingCountryId)->first();
    $billingCountry = $country ? $country->name : $billingCountryId;

    $state = DB::table("master_states")->where("id", $billingStateId)->first();
    $billingState = $state ? $state->name : $billingStateId;

    $city = DB::table("master_cities")->where("id", $billingCityId)->first();
    $billingCity = $city ? $city->name : $billingCityId;
  }elseif($billing_address == "shipping"){
    $billingName = $shippingName;
    $billingEmail = $shippingEmail;
    $billingPhone = $shippingPhone;
    $billingCountry = $shippingCountry;
    $billingState = $shippingState;
    $billingCity = $shippingCity;
    $billingPincode = $shippingPincode;
    $billingAddress = $shippingAddress;
  }

  $item_type = $request->item_type ?: 0;

  $totalQuantity = 0;
  $saleTotalAmount = 0;
  $baseTotalAmount = 0;
  $discountAmount = 0;
  $shippingAmount = 0;
  $couponId = 0;
  $couponCode = $request->coupon_code ?: "";
  $couponApplyOn = "";
  $couponAmount = 0;
  $gstType = 'igst';
  $igstAmount = 0;
  $cgstAmount = 0;
  $sgstAmount = 0;
  $totalAmount = 0;
  $cartitemid = $request->cartitemid ?: 0;
  $cartItems = [];

  if($couponCode){
    $newRequest = new Request([
      'callType'=>'not_ajax',
      'type' => 'order',
      'coupon_code' => $couponCode,
      'item_type' => $item_type,
      'cartitemid' => $cartitemid,
    ]);
    $couponData = $this->applyCouponCode($newRequest);
    $couponData = $couponData->getData();
    $couponApplyOn = $couponData->apply_on;
    $couponAmount = $couponData->discount;
    $couponCode = $couponData->code;
    $couponId = $couponData->id;
  }

  $cartItems = CartItem::where('user_id', $user->id)
  ->where('item_type', $item_type);
  if($cartitemid > 0){
    $cartItems->where('id', $cartitemid);
  }
  $cartItems = $cartItems->get();
  $user_temp_cart_id=$request->user_temp_cart_id?decrypt($request->user_temp_cart_id):0;
  $userTempCart=UserTempCart::find($user_temp_cart_id);
  if($userTempCart){
    $item_type=0;
    $couponCode="Discount";
    $couponAmount=$userTempCart->extra_discount_amount;
    $couponApplyOn="product";
    $shippingAmount=$userTempCart->shipping_amount;
    $cartItems = AdminSideCartItem::where('user_temp_cart_id', $userTempCart->id)
    ->where('user_id', $user->id)
    ->where('user_type', "user")
    ->where('item_type', "product")
    ->get();
  }
  if($cartItems->count() == 0){
    return response()->json([
      "message" => "Your cart is empty."
    ], 400);
  }
  $order_id = $request->order_id;

  $order = Order::where("id", $order_id)->where("status", "pending")->first();
  if(!$order){
    $order = new Order();
  }
  $order->user_id = $user->id;
  $order->user_type = $user_type;
  $order->billing_full_name = $billingName;
  $order->billing_email = $billingEmail;
  $order->billing_phone_number = $billingPhone;
  $order->billing_country = $billingCountry;
  $order->billing_state = $billingState;
  $order->billing_city = $billingCity;
  $order->billing_zipcode = $billingPincode;
  $order->billing_address = $billingAddress;
  $order->full_name = $shippingName;
  $order->email = $shippingEmail;
  $order->phone_number = $shippingPhone;
  $order->country = $shippingCountry;
  $order->state = $shippingState;
  $order->city = $shippingCity;
  $order->zipcode = $shippingPincode;
  $order->address = $shippingAddress;
  $order->payment_mode = 'online';
  $order->status = 'pending';
  $order->order_status = 'pending';
  $order->payment_status = 'pending';
  $order->coupon = $couponCode;
  $order->coupon_id = $couponId;
  $order->coupon_amount = $couponAmount;
  $order->client_ip = $request->ip();
  $order->client_agent = $request->userAgent();
  $order->item_type = $item_type;
  $order->payment_date_time = date('Y-m-d H:i:s');
  $order->cod = 0;

  // Get ashirwad_box value from cart items formdata
  $ashirwadBox = 0;
  foreach($cartItems as $cartItem) {
    if($cartItem->formdata) {
      $formdataArray = json_decode($cartItem->formdata, true);
      if(is_array($formdataArray)) {
        foreach($formdataArray as $formField) {
          if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
            // Handle both string "1" and integer 1, and also "true" string
            $value = isset($formField['value']) ? $formField['value'] : 0;
            if($value === 'true' || $value === true) {
              $ashirwadBox = 1;
            } else {
              $ashirwadBox = (int)$value;
            }
            break 2; // Break both loops
          }
        }
      }
    }
  }
  $order->ashirwad_box = $ashirwadBox;

  // Save product referral code if present (from Share and Earn link)
  if(Session::has('product_referral_code')) {
    $productReferralCode = Session::get('product_referral_code');
    // Save to user's used_referral_code for this order
    if($productReferralCode) {
      $user->used_referral_code = $productReferralCode;
      $user->save();
      // Also save to order for tracking
      $order->referral_code = $productReferralCode;
    }
  }

  $order->save();

  // Refresh and verify ashirwad_box was saved correctly
  $order->refresh();
  if($order->ashirwad_box != $ashirwadBox) {
    // If value was lost, set it again and save
    $order->ashirwad_box = $ashirwadBox;
    $order->save();
  }

  $orderId = $order->id;
  $cartItemTotalSaleAmount=0;
  if($userTempCart){
    $cartItemTotalSaleAmount=$cartItems->sum(function($item) {
        return $item->sale_price*$item->quantity;
    });
  }else{
    $cartItemTotalSaleAmount=$cartItems->sum(function($item) {
        return $item->price*$item->quantity;
    });
  }

  OrderItem::where("order_id", $orderId)->delete();
  foreach ($cartItems as $index=>$item){
    if($item->type == 'shop'){
      $product = $item->product;
      if($product && $product->status){
        $order->product_type = $product->type;

        $itemTotalSaleAmount = $item->quantity*$item->price;
        $itemTotalBaseAmount = $item->quantity*$item->unit_price;
        $totalQuantity += $item->quantity;
        $saleTotalAmount += $itemTotalSaleAmount;
        $baseTotalAmount += $itemTotalBaseAmount;
        $discountAmount = $item->unit_price-$item->price;
        $itemCouponAmount = 0;

        if($couponAmount > 0){
          if($couponApplyOn=='any' || $couponApplyOn=='product'){
            $itemCouponAmount = ($itemTotalSaleAmount/$cartItemTotalSaleAmount)*$couponAmount;
          }
        }

        $itemShippingAmount = 0;

        if($product->apply_shipping){
          $itemShippingAmount = calculateShippingAmount($product, $order->zipcode)*$item->quantity;
          $shippingAmount += $itemShippingAmount;
        }

        $orderItem = new OrderItem();
        $orderItem->user_id = $order->user_id;
        $orderItem->user_type = $order->user_type;
        $orderItem->order_id = $orderId;
        $orderItem->product_id = $product->id;
        $orderItem->sku = $product->sku;
        $orderItem->quantity = $item->quantity;
        $orderItem->price = $item->price;//sale_price only
        $orderItem->unit_price = $item->unit_price; //base_price only
        $orderItem->coupon_amount = $itemCouponAmount/$item->quantity; //single product coupon discount only
        $orderItem->discount_amount = $discountAmount; //single product discount only
        $orderItem->total_price = 0; //with gst and couopon
        $orderItem->net_total = 0;//single product with discount and coupon without gst
        $orderItem->shipping_amount = $itemShippingAmount;
        $orderItem->type = $item->type;

        $title = $product->title;
        $productAttributes = $product->attributes;
        $productAttributeArray = [];

        foreach($productAttributes as $key => $valueTwo){
          if($attribute = $valueTwo->attribute){
            $productAttributeArray[] = "$attribute->name: $valueTwo->attribute_value";
          }
        }

        if(count($productAttributeArray)){
          $title = implode(', ', $productAttributeArray);
          $title="$product->title ($title)";
        }

        $orderItem->name = $title;
        $orderItem->image = $product->image ? $product->image->image(200,200) : no_image();
        $orderItem->description = $product->specifications ? $product->specifications->short_description : '';
        $orderItem->hsn_code = 0;
        $options = [];

        if($apiUrl = $product->apiUrl){
          $options["api_url_id"] = $apiUrl->id;
          $options["api_url"] = $apiUrl->url;
          $options["api_url_report_code"] = $apiUrl->report_code;
        }

        $formdataArray = json_decode($item->formdata, true);
        if(count($formdataArray) > 0){
          $options["formdata"] = $formdataArray;
        }

        // Extract gotra from formdata and store separately on order_items table
        // For group puja our gotra field is a single static input named exactly "gotra",
        // so here we only look for that specific field (ignore other dynamic fields).
        $gotraValue = null;
        if(is_array($formdataArray) && count($formdataArray) > 0) {
          foreach($formdataArray as $formField) {
            if(!is_array($formField)) {
              continue;
            }
            $name = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
            if($name === 'gotra') {
              $value = isset($formField['value']) ? trim((string)$formField['value']) : '';
              // allow empty string to be stored as-is if user chose "I do not know my gotra"
              $gotraValue = $value;
              break;
            }
          }
        }

        if($video_call_url = $product->video_call_url){
          $options["video_call_url"] = $video_call_url;
        }

        if($place = $product->place){
          $options["place"] = $place;
        }

        // Save gotra separately only for group puja (type == 2 and is_one_to_one_puja != 1)
        $isGroupPuja = ($product->type == 2 && (int)($product->is_one_to_one_puja ?? 0) !== 1);
        if($isGroupPuja && $gotraValue !== null) {
          $orderItem->gotra = $gotraValue;
        }

        // Set start_at for both regular puja (from expire_at) and one-to-one puja
        $isOneToOnePuja = $product->type == 2 && $product->is_one_to_one_puja == 1;
        if($isOneToOnePuja){
          // For one-to-one puja, use expire_at if available, otherwise use a default future date
          if($expire_at = $product->expire_at){
            $options["start_at"] = $expire_at;
          } else {
            // If no expire_at, set start_at to a future date (e.g., 7 days from now)
            $options["start_at"] = date("Y-m-d H:i:s", strtotime("+7 days"));
          }
        } else {
          // For regular puja, use expire_at as start_at
          if($expire_at = $product->expire_at){
            $options["start_at"] = $expire_at;
          }
        }

        $orderItem->options = json_encode($options);
        $orderItem->save();

        // Assign astrologer to puja if product is one-to-one puja type (type == 2 and is_one_to_one_puja == 1)
        \Log::info("=== Checking Puja Assignment Condition ===");
        \Log::info("Product ID: {$product->id} | Product Name: {$product->title}");
        \Log::info("Product Type: {$product->type} | Is One-to-One Puja: " . ($product->is_one_to_one_puja ?? 'NULL') . " (Value: " . ($product->is_one_to_one_puja == 1 ? 'true' : 'false') . ")");

        if($product->type == 2 && $product->is_one_to_one_puja == 1){
          \Log::info("Condition PASSED - Calling assignAstrologerToPuja method");
          $this->assignAstrologerToPuja($order, $orderItem, $product);
        } else {
          \Log::warning("Condition FAILED - Assignment method NOT called");
          \Log::warning("Reason: type == 2: " . ($product->type == 2 ? 'true' : 'false') . " | is_one_to_one_puja == 1: " . ($product->is_one_to_one_puja == 1 ? 'true' : 'false'));
        }

        $igstPercentage = 0;
        $cgstPercentage = 0;
        $sgstPercentage = 0;
        $igst_amount = 0;
        $csgt_amount = 0;
        $sgst_amount = 0;
        $gstPercentage = 0;

        if($hnsCode = $product->hnsCode){
          $gstPercentage = $hnsCode->gst_percentage;
          $orderItem->hsn_code = $hnsCode->hsn_code;
        }

        // Note: Referral discount is applied on subtotal level, not per item
        $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;

        //in state: divide
        if(strtolower($shippingState)=="uttar pradesh"){
          $gstType = 'cgst_sgst';
          $cgstPercentage = $sgstPercentage = $gstPercentage/2;
          $orderItem->tax_type = $gstType;
          $orderItem->cgst_percentage = $cgstPercentage;
          $csgt_amount = ($itemTotalSaleAmount)*($cgstPercentage/100);
          $cgstAmount += $csgt_amount;
          $orderItem->cgst_amount = $csgt_amount/$item->quantity;
          $orderItem->sgst_percentage = $sgstPercentage;
          $sgst_amount = ($itemTotalSaleAmount)*($sgstPercentage/100);
          $sgstAmount += $sgst_amount;
          $orderItem->sgst_amount = $sgst_amount/$item->quantity;
        }else{
          $gstType = 'igst';
          $igstPercentage = $gstPercentage;
          $orderItem->tax_type = $gstType;
          $orderItem->igst_percentage = $igstPercentage;
          $igst_amount = ($itemTotalSaleAmount)*($igstPercentage/100);
          $igstAmount += $igst_amount;
          $orderItem->igst_amount = $igst_amount/$item->quantity;
        }

        $orderItem->net_total = $item->price-$orderItem->coupon_amount;
        $orderItem->total_price = ($item->price*$item->quantity)-$itemCouponAmount+($igst_amount+$sgst_amount+$csgt_amount);
        $orderItem->save();
      }else{
        $item->delete();
      }
    }elseif($item->item_type=="product"){
      $product=$item->product;
      $order->product_type = 1;
      $itemTotalSaleAmount = $item->unit_sale_price;
      $itemTotalBaseAmount = $item->unit_base_price;
      $totalQuantity += $item->quantity;
      $saleTotalAmount += $itemTotalSaleAmount;
      $baseTotalAmount += $itemTotalBaseAmount;
      $discountAmount = $item->base_price-$item->sale_price;
      $itemCouponAmount = 0;
      if($couponAmount > 0){
        $itemCouponAmount=($itemTotalSaleAmount/$cartItemTotalSaleAmount)*$couponAmount;
      }
      $itemShippingAmount=0;
      if($shippingAmount>0){
        $itemShippingAmount=($shippingAmount/$cartItems->count());
      }
      $orderItem = new OrderItem();
      $orderItem->user_id = $order->user_id;
      $orderItem->user_type = $order->user_type;
      $orderItem->order_id = $orderId;
      $orderItem->product_id = $item->item_id;
      $orderItem->sku = $product?$product->sku:"CUSTOM";
      $orderItem->quantity = $item->quantity;
      $orderItem->price = $item->sale_price;
      $orderItem->unit_price = $item->base_price;
      $orderItem->coupon_amount = $itemCouponAmount/$item->quantity; //single product coupon discount only
      $orderItem->discount_amount = $discountAmount; //single product discount only
      $orderItem->total_price = 0; //with gst and couopon
      $orderItem->net_total = 0;//single product with discount and coupon without gst
      $orderItem->shipping_amount = $itemShippingAmount;
      $orderItem->type = "shop";

      $orderItem->name=$item->title;
      $orderItem->image=$product && $product->image?$product->image->image(200,200):$item->image_url;
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
      $gstPercentage = 0;
      $gstPercentage=$item->tax_percentage;
      $orderItem->hsn_code=$item->hsn_code;
      $itemTotalSaleAmount=$itemTotalSaleAmount-$itemCouponAmount;
      //in state: divide
      if(strtolower($shippingState)=="uttar pradesh"){
        $gstType = 'cgst_sgst';
        $cgstPercentage = $sgstPercentage = $gstPercentage/2;
        $orderItem->tax_type = $gstType;
        $orderItem->cgst_percentage = $cgstPercentage;
        $csgt_amount = ($itemTotalSaleAmount)*($cgstPercentage/100);
        $cgstAmount += $csgt_amount;
        $orderItem->cgst_amount = $csgt_amount/$item->quantity;
        $orderItem->sgst_percentage = $sgstPercentage;
        $sgst_amount = ($itemTotalSaleAmount)*($sgstPercentage/100);
        $sgstAmount += $sgst_amount;
        $orderItem->sgst_amount = $sgst_amount/$item->quantity;
      }else{
        $gstType = 'igst';
        $igstPercentage = $gstPercentage;
        $orderItem->tax_type = $gstType;
        $orderItem->igst_percentage = $igstPercentage;
        $igst_amount = ($itemTotalSaleAmount)*($igstPercentage/100);
        $igstAmount += $igst_amount;
        $orderItem->igst_amount = $igst_amount/$item->quantity;
      }
      $orderItem->net_total=$item->sale_price-$orderItem->coupon_amount;
      $orderItem->total_price=$item->unit_sale_price-$itemCouponAmount+($igst_amount+$sgst_amount+$csgt_amount);
      $orderItem->save();
    }
  }

  $orderPrefix = 'ORD-'.date('ym').'-';
  $orderNum = sprintf('%05d', $orderId);
  $prefix = "$orderPrefix$orderNum";

  $discountAmount = $baseTotalAmount-$saleTotalAmount;

  // Apply referral discount if remedies referral code exists - on subtotal first
  // Only apply if user came from remedies checkout link (session must exist)
  $remediesReferralDiscount = 0;
  $remediesReferralCode = null;
  if(Session::has('remedies_referral_code')){
    $remediesReferralCode = Session::get('remedies_referral_code');
  }
  if($remediesReferralCode){
    // Check if referral code exists in astrologers table
    $referringAstrologer = \App\Model\Astrologer::where('referral_code', $remediesReferralCode)
      ->where('status', 1)
      ->where('is_verified', 1)
      ->first();

    // Get global referral settings for product discount
    if($referringAstrologer){
      $astrologerReferralSettings = AstrologerReferral::where('status', 1)->first();

      if($astrologerReferralSettings && $astrologerReferralSettings->product_discount > 0){
        // Calculate discount only on products (type 1) subtotal, not on reports (type 3)
        $productSubtotal = 0;
        foreach($cartItems as $item){
          if($item->type == 'shop'){
            $product = $item->product;
            if($product && $product->type == 1){ // Only products, not reports
              $productSubtotal += $item->price * $item->quantity;
            }
          }
        }
        // Apply discount on product subtotal
        $remediesReferralDiscount = ($productSubtotal * $astrologerReferralSettings->product_discount) / 100;
      }
    }
  }

  // Calculate total: subtotal - referral discount - coupon, then add GST and shipping
  $payableAmount = $saleTotalAmount - $remediesReferralDiscount - $couponAmount;
  $gstAmount = $igstAmount+$sgstAmount+$cgstAmount;
  $payableAmount = $payableAmount + $gstAmount + $shippingAmount;

  $paymentRequest = [
      'receipt' => $prefix,
      'amount' => ceil($payableAmount*100),
      'currency' => 'INR'
  ];

  $order->order_number = $prefix;
  $order->total_quantity = $totalQuantity;
  $order->sub_total = $baseTotalAmount;
  $order->discount = $discountAmount;
  $order->discount_amount = $discountAmount;
  $order->coupon_amount = $couponAmount;
  $order->shipping_price = $shippingAmount;
  $order->igst = $igstAmount;
  $order->cgst = $cgstAmount;
  $order->sgst = $sgstAmount;
  // Store remedies referral discount if applicable (only if coming from remedies checkout)
  if($remediesReferralDiscount > 0 && Session::has('remedies_referral_code')){
    $order->referral_discount = $remediesReferralDiscount;
    $order->referral_code = $remediesReferralCode;
  }

  // Mark remedies suggestion as used if order is being placed
  $remediesSuggestionToken = Session::get('remedies_suggestion_token');
  if($remediesSuggestionToken){
    DB::table('astrologer_remedy_suggestions')
      ->where('token', $remediesSuggestionToken)
      ->where('is_used', 0)
      ->update(['is_used' => 1]);
    Session::forget('remedies_suggestion_token');
  }

  // Clear referral code session after using it to prevent reuse
  if(Session::has('remedies_referral_code')){
    Session::forget('remedies_referral_code');
  }
  $order->total_price = $payableAmount;
  $order->payable_amount = $payableAmount;
  $order->payment_request = $paymentRequest;

  $payment_option = $request->payment_option ?: "now";
  if($payment_option == "cod"){
    $paymentRequest['amount'] = 0;
    $order->cod = 1;
  }

  // Get ashirwad_box value - priority: request > order > cart items
  $ashirwadBox = 0;
  // First check if it's in the request (from checkout page hidden field)
  if($request->has('ashirwad_box')) {
    $ashirwadBox = (int)$request->ashirwad_box;
  } else {
    // Then check if it's already in the order
    $ashirwadBox = $order->ashirwad_box ?? 0;
    if($ashirwadBox == 0) {
      // Finally, try to get from cart items formdata
      foreach($cartItems as $cartItem) {
        if($cartItem->formdata) {
          $formdataArray = json_decode($cartItem->formdata, true);
          if(is_array($formdataArray)) {
            foreach($formdataArray as $formField) {
              if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
                // Handle both string "1" and integer 1, and also "true" string
                $value = isset($formField['value']) ? $formField['value'] : 0;
                if($value === 'true' || $value === true) {
                  $ashirwadBox = 1;
                } else {
                  $ashirwadBox = (int)$value;
                }
                break 2; // Break both loops
              }
            }
          }
        }
      }
    }
  }
  // Always set ashirwad_box before save to ensure it's preserved
  $order->ashirwad_box = $ashirwadBox;

  // Save product referral code if present (from Share and Earn link)
  if(Session::has('product_referral_code')) {
    $productReferralCode = Session::get('product_referral_code');
    // Save to user's used_referral_code for this order
    if($productReferralCode) {
      $user->used_referral_code = $productReferralCode;
      $user->save();
      // Also save to order for tracking
      $order->referral_code = $productReferralCode;
    }
  }

  $order->save();

  if($paymentRequest['amount'] > 0){
    $razorpayKey = fromSetting("razorpay_api_key");
    $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

    $api = new Api($razorpayKey, $razorpaySecretKey);
    $orderData  = $api->order->create($paymentRequest); // Creates Razorpay order
    $orderDataId = $orderData ? $orderData['id'] : 0;
    $appName = config('app.name');

    $paymentRequestData = [
        "key" => $razorpayKey,
        "amount" => $paymentRequest['amount'],
        "currency" => "INR",
        "name"=> $appName,
        "library_name" => $appName,
        "image" => logo(),
        "description" => "Placing order Order_id : ".$orderId,
        "order_id" => $orderDataId,
        "callback_url" => route('payment.verify', [$orderId, $orderDataId, "cartitemid" => $cartitemid,"user_temp_cart_id"=>($request->user_temp_cart_id?:"")]),
        "prefill"=>[
          "name"=> $billingName,
          "email"=> $billingEmail,
          "contact"=> $billingPhone,
        ],
        "theme"=>[
          "color" => "#528FF0",
        ],
    ];

    $order->payment_request = $paymentRequest;
    // Preserve ashirwad_box value - get from request first, then order, then cart items
    $ashirwadBox = 0;
    if($request->has('ashirwad_box')) {
      $ashirwadBox = (int)$request->ashirwad_box;
    } else {
      $ashirwadBox = $order->ashirwad_box ?? 0;
      if($ashirwadBox == 0) {
        foreach($cartItems as $cartItem) {
          if($cartItem->formdata) {
            $formdataArray = json_decode($cartItem->formdata, true);
            if(is_array($formdataArray)) {
              foreach($formdataArray as $formField) {
                if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
                  $value = isset($formField['value']) ? $formField['value'] : 0;
                  if($value === 'true' || $value === true) {
                    $ashirwadBox = 1;
                  } else {
                    $ashirwadBox = (int)$value;
                  }
                  break 2;
                }
              }
            }
          }
        }
      }
    }
    // Always set ashirwad_box before save to ensure it's preserved
    $order->ashirwad_box = $ashirwadBox;

    // Save product referral code if present (from Share and Earn link)
    if(Session::has('product_referral_code')) {
      $productReferralCode = Session::get('product_referral_code');
      // Save to user's used_referral_code for this order
      if($productReferralCode) {
        $user->used_referral_code = $productReferralCode;
        $user->save();
        // Also save to order for tracking
        $order->referral_code = $productReferralCode;
      }
    }

    $order->save();
    return response()->json([
      'success' => true,
      'order_id' => $order->id,
      'message' => 'order place successfully',
      'next' => 'payment',
      'paymentRequestData' => $paymentRequestData
    ], 200);

    // Note: Session will be cleared after payment verification in VerifyPaymentController
    }else{
      $order->status = 'success';
      $order->order_status = 'ordered';

      if($payment_option == "cod"){
        $order->payment_mode = 'cod';
        $order->payment_method = 'cod';
        $order->payment_response = 'cod';
        $order->payment_status = 'cod';
      }else{
        $order->payment_mode = 'nothing';
        $order->payment_response = 'zero amount';
        $order->payment_status = 'success';
      }

    // Preserve ashirwad_box value
    $existingAshirwadBox = $order->getOriginal('ashirwad_box') ?? $order->ashirwad_box ?? 0;
    if($existingAshirwadBox == 0) {
      // Try to get from cart items if not set
      foreach($cartItems as $cartItem) {
        if($cartItem->formdata) {
          $formdataArray = json_decode($cartItem->formdata, true);
          if(is_array($formdataArray)) {
            foreach($formdataArray as $formField) {
              if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
                $existingAshirwadBox = isset($formField['value']) ? (int)$formField['value'] : 0;
                break 2;
              }
            }
          }
        }
      }
    }
    $order->ashirwad_box = $existingAshirwadBox;

    // Save product referral code if present (from Share and Earn link) for COD/zero amount orders
    if(Session::has('product_referral_code')) {
      $productReferralCode = Session::get('product_referral_code');
      // Save to user's used_referral_code for this order
      if($productReferralCode) {
        $user->used_referral_code = $productReferralCode;
        $user->save();
        // Also save to order for tracking
        $order->referral_code = $productReferralCode;
      }
    }

    $order->save();

    // Mark remedies suggestion as used for COD/zero amount orders
    $remediesSuggestionToken = Session::get('remedies_suggestion_token');
    if($remediesSuggestionToken){
      DB::table('astrologer_remedy_suggestions')
        ->where('token', $remediesSuggestionToken)
        ->where('is_used', 0)
        ->update(['is_used' => 1]);
      Session::forget('remedies_suggestion_token');
    }

    // Clear remedies referral code session after order is placed (whether COD or zero amount)
    Session::forget('remedies_referral_code');

    // Clear product referral code session after order is placed (whether COD or zero amount)
    Session::forget('product_referral_code');

    if($order->item_type == 0){
      // real
      $orderStatus = new OrderStatus;
      $orderStatus->order_id = $order->id;
      $orderStatus->status = 'ordered';
      $orderStatus->save();
    }else{
      // virtual
      $status_array = ['ordered', 'shipped', 'in transit', 'dispatch', 'delivered'];
      foreach ($status_array as $key => $value) {
        $orderStatus = new OrderStatus;
        $orderStatus->order_id = $order->id;
        $orderStatus->status = $value;
        $orderStatus->save();
      }
      $order->order_status = 'delivered';
      $order->save();
    }

    $order_items = OrderItem::where('order_id', $orderId)->get();
    $itemIdArray = [];
    $product_types = ['', 'product', 'pooja', 'service'];
    $product_type = 'product';

    // Check if order contains group puja and generate reference number
    $hasGroupPuja = false;
    $groupPujaParentProduct = null;
    foreach($order_items as $key => $oI){
      $itemIdArray[] = $oI->id;

      if($oI->type == 'shop'){
        $product = $oI->product;
        if($product){
          $product_type = $product_types[$product->type];
          if($product->type==1){
            $product->avaibility -= $oI->quantity;
            $product->save();
          }

          // Check if it's a group puja (type == 2 and is_one_to_one_puja != 1)
          // If product is a variation (has parent_id), check parent product instead
          $pujaProduct = $product;
          if($product->parent_id && $product->parent_id > 0){
            $parentProduct = \App\Product::find($product->parent_id);
            if($parentProduct){
              $pujaProduct = $parentProduct;
              \Log::info("Variation detected - Using parent product. Variation ID: {$product->id}, Parent ID: {$parentProduct->id}");
            }
          }

          if($pujaProduct->type == 2){
            $isOneToOne = (int)$pujaProduct->is_one_to_one_puja;
            if($isOneToOne != 1){
              $hasGroupPuja = true;
              if ($groupPujaParentProduct === null) {
                $groupPujaParentProduct = $pujaProduct;
              }
              \Log::info("Group Puja detected - Product ID: {$pujaProduct->id}, Variation ID: " . ($product->id != $pujaProduct->id ? $product->id : 'N/A') . ", is_one_to_one_puja: {$pujaProduct->is_one_to_one_puja}, pandit_id: {$pujaProduct->pandit_id}");
            }
          }
        }
      }
    }

    // Generate group puja reference number and snapshot valid date if order contains group puja
    if($hasGroupPuja){
      \Log::info("Generating group puja reference for Order ID: {$order->id}");
      // Refresh order to get latest data
      $order->refresh();

      // Only generate if not already set
      if(!$order->group_puja_reference){
        // Get the last group puja reference number
        $lastReference = Order::whereNotNull('group_puja_reference')
          ->where('group_puja_reference', 'like', 'FM%')
          ->orderBy('id', 'desc')
          ->first();

        if($lastReference && $lastReference->group_puja_reference){
          // Extract number from last reference (e.g., FM0001 -> 1)
          $lastNumber = (int)substr($lastReference->group_puja_reference, 2);
          $newNumber = $lastNumber + 1;
        } else {
          $newNumber = 1;
        }

        // Format as FM0001, FM0002, etc.
        $order->group_puja_reference = 'FM' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $order->save();
        \Log::info("Group puja reference saved: {$order->group_puja_reference} for Order ID: {$order->id}");
      } else {
        \Log::info("Group puja reference already exists: {$order->group_puja_reference} for Order ID: {$order->id}");
      }
      // Snapshot valid date at booking so admin updates to product dates don't affect this order
      if ($groupPujaParentProduct && !$order->group_puja_valid_date) {
        $validDate = $this->getGroupPujaValidDateAtBooking($order, $groupPujaParentProduct);
        if ($validDate) {
          $order->group_puja_valid_date = $validDate;
          $order->save();
          \Log::info("Group puja valid date saved: {$validDate} for Order ID: {$order->id}");
        }
      }
    } else {
      \Log::info("No group puja found in Order ID: {$order->id}");
    }

    $pdf_url = '';

    // Note: Referral rewards will be distributed in VerifyPaymentController (after payment success)
    // or in OrderPlaceJob (for COD/zero amount orders with status='success')
    // Rewards are NOT distributed here to avoid duplicate distribution

    $mail_credential = [$order->email => $order->full_name];
    OrderPlaceJob::dispatch($mail_credential, $order, 'user', $product_type, $pdf_url);

    $mail_credential = [config("vrdcms.admin_email") => "Admin"];
    OrderPlaceJob::dispatch($mail_credential, $order, 'admin', $product_type, $pdf_url);

    Session::flash('message', "<p>Your Order Has Been Placed Successfully.</p><p>Order Number is <strong>$order->order_number</strong></p>");
    $message = "Your Order Has Been Placed Successfully. Order Number is $order->order_number";
    if($order->item_type == 1){
      Session::flash('message', "<p>Order confirmed. Your service will be processed shortly.</p><p>Order Number is <strong>$order->order_number</strong></p>");
      $message = "Order confirmed. Your service will be processed shortly. Order Number is $order->order_number";
    }

    // normal notification
    $notification['user_id'] = $user->id;
    $notification['astrologer_id'] = 0;
    $notification['message'] = $message;
    $notification['image_id'] = 0;
    $notification['link'] = route('user.purchase.item');
    $notification['app_link'] = "order_history";
    $notification['for'] = 1;
    $notification['on'] = 0;
    $notification['status'] = 1;
    $notificationController = new NotificationController();
    $notificationController->create($notification);

    // push notification
    $title="Order";
    $body=$notification['message'];
    $dataArray = [
      'type' => $notification['app_link']
    ];
    $pushNotificationController = new PushNotificationController();
    $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

    if($userTempCart){
      AdminSideCartItem::where('user_temp_cart_id', $userTempCart->id)
      ->where('user_id', $order->user_id)
      ->where('user_type', $order->user_type)
      ->where('item_type', "product")
      ->delete();
      $userTempCart->delete();
    }else{
      $cartItems = CartItem::where('user_id', $order->user_id)
      ->where('user_type', $order->user_type)
      ->where('item_type', $order->item_type);
      if($cartitemid > 0){
        $cartItems->where('id', $cartitemid);
      }
      $cartItems->delete();
    }

    return response()->json([
      'success'=>true,
      'message' => 'order place successfully',
      'next'=>'ordered'
    ], 200);
  }

}

public function verifyPayment($id, $order_id, Request $request){
  $user = auth()->guard('web')->user();
  $type = 'user';
  $success = true;
  $error = "Payment Failed!";
  $orderResponse = [];
  $order = Order::find($id);
  // Preserve ashirwad_box value before any updates
  $existingAshirwadBox = $order->ashirwad_box ?? 0;
  $payment_response = $request->all();
  if(empty($request->razorpay_payment_id) === false){
    $razorpayKey = fromSetting("razorpay_api_key");
    $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

    $api = new Api($razorpayKey, $razorpaySecretKey);

    try{
      $attributes = [
          'razorpay_order_id' => $request->razorpay_order_id,
          'razorpay_payment_id' => $request->razorpay_payment_id,
          'razorpay_signature' => $request->razorpay_signature
      ];
      $api->utility->verifyPaymentSignature($attributes);
      $orderResponse = $api->payment->fetch($request->razorpay_payment_id);
      $payment_response = array_merge($payment_response, $orderResponse->toArray());
      $order->status = 'success';
      $order->order_status = 'ordered';
      $order->payment_status = 'success';
      $order->payment_id = isset($payment_response['razorpay_payment_id'])?$payment_response['razorpay_payment_id']:'';
      $order->payment_method = isset($payment_response['method'])?$payment_response['method']:'';
      $order->payment_date_time = isset($payment_response['created_at'])?date('Y-m-d H:i:s', $payment_response['created_at']):date('Y-m-d H:i:s');
    }catch(SignatureVerificationError $e){
      $success = false;
      $error = 'Razorpay Error : ' . $e->getMessage();
      $order->status = 'failed';
      $order->payment_status = 'failed';
    }
  }
  $order->payment_response = json_encode($payment_response);
  // Preserve ashirwad_box value when updating payment response
  $order->ashirwad_box = $existingAshirwadBox;
  $order->save();
  if($success === true){
    // Check if order contains group puja and generate reference number
    $order_items = OrderItem::where('order_id', $order->id)->get();
    $hasGroupPuja = false;
    $groupPujaParentProduct = null;

    foreach($order_items as $oI){
      if($oI->type == 'shop'){
        $product = $oI->product;
        if($product){
          // Check if it's a group puja (type == 2 and is_one_to_one_puja != 1)
          // If product is a variation (has parent_id), check parent product instead
          $pujaProduct = $product;
          if($product->parent_id && $product->parent_id > 0){
            $parentProduct = \App\Product::find($product->parent_id);
            if($parentProduct){
              $pujaProduct = $parentProduct;
              \Log::info("Payment Verify - Variation detected - Using parent product. Variation ID: {$product->id}, Parent ID: {$parentProduct->id}");
            }
          }

          if($pujaProduct->type == 2){
            $isOneToOne = (int)$pujaProduct->is_one_to_one_puja;
            if($isOneToOne != 1){
              $hasGroupPuja = true;
              if ($groupPujaParentProduct === null) {
                $groupPujaParentProduct = $pujaProduct;
              }
              \Log::info("Payment Verify - Group Puja detected - Product ID: {$pujaProduct->id}, Variation ID: " . ($product->id != $pujaProduct->id ? $product->id : 'N/A') . ", is_one_to_one_puja: {$pujaProduct->is_one_to_one_puja}, pandit_id: {$pujaProduct->pandit_id}");
            }
          }
        }
      }
    }

    // Generate group puja reference number and snapshot valid date if order contains group puja
    if($hasGroupPuja){
      \Log::info("Payment Verify - Generating group puja reference for Order ID: {$order->id}");
      $order->refresh();

      // Only generate if not already set
      if(!$order->group_puja_reference){
        // Get the last group puja reference number
        $lastReference = Order::whereNotNull('group_puja_reference')
          ->where('group_puja_reference', 'like', 'FM%')
          ->orderBy('id', 'desc')
          ->first();

        if($lastReference && $lastReference->group_puja_reference){
          // Extract number from last reference (e.g., FM0001 -> 1)
          $lastNumber = (int)substr($lastReference->group_puja_reference, 2);
          $newNumber = $lastNumber + 1;
        } else {
          $newNumber = 1;
        }

        // Format as FM0001, FM0002, etc.
        $order->group_puja_reference = 'FM' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $order->save();
        \Log::info("Payment Verify - Group puja reference saved: {$order->group_puja_reference} for Order ID: {$order->id}");
      } else {
        \Log::info("Payment Verify - Group puja reference already exists: {$order->group_puja_reference} for Order ID: {$order->id}");
      }
      // Snapshot valid date at booking so admin updates to product dates don't affect this order
      if ($groupPujaParentProduct && !$order->group_puja_valid_date) {
        $validDate = $this->getGroupPujaValidDateAtBooking($order, $groupPujaParentProduct);
        if ($validDate) {
          $order->group_puja_valid_date = $validDate;
          $order->save();
          \Log::info("Payment Verify - Group puja valid date saved: {$validDate} for Order ID: {$order->id}");
        }
      }
    } else {
      \Log::info("Payment Verify - No group puja found in Order ID: {$order->id}");
    }

    $data['id'] = $order->id;
    $data['name'] = $order->full_name;
    $data['email'] = $order->email;
    $data['phone'] = $order->phone_number;
    $data['address'] = $order->address;
    $data['pincode'] = $order->zipcode;
    $data['city'] = $order->city;
    $data['state'] = $order->state;
    $data['country'] = $order->country;
    $data['billing_full_name'] = $order->billing_full_name;
    $data['billing_email'] = $order->billing_email;
    $data['billing_phone_number'] = $order->billing_phone_number;
    $data['billing_address'] = $order->billing_address;
    $data['billing_country'] = $order->billing_country;
    $data['billing_state'] = $order->billing_state;
    $data['billing_city'] = $order->billing_city;
    $data['billing_zipcode'] = $order->billing_zipcode;
    $data['total_quantity'] = 0;
    $data['amount'] = $order->sub_total; //base
    $data['subtotal_amount'] = $order->sub_total; //base
    $data['discount_amount'] = $order->discount_amount;
    $data['coupon_amount'] = $order->coupon_amount;
    $data['sgst'] = $order->sgst;
    $data['cgst'] = $order->cgst;
    $data['igst'] = $order->igst;
    $data['shipping_amount'] = $order->shipping_price;
    $data['total_amount'] = $order->total_price; //payable
    $data['order_id'] = $order->order_number;
    $data['payment_id'] = $order->payment_id;
    $data['payment_method'] = $order->payment_method;
    $data['payment_date_time'] = $order->payment_date_time;

    $item_data = [];
    $order_items = OrderItem::where('order_id', $order->id)->get();
    $itemIdArray = [];
    $product_types = ['', 'product', 'pooja', 'service'];
    $product_type = 'product';

    foreach($order_items as $key => $oI){
      $itemIdArray[] = $oI->id;

      if($oI->type == 'shop'){
        $product = $oI->product;
        if($product){
          $product_type = $product_types[$product->type];
          if($product->type == 1){
            $product->avaibility -= $oI->quantity;
            $product->save();
          }
        }
      }

      $data['total_quantity'] += $oI->quantity;

      $item_datas['name'] = $oI->name;
      $item_datas['description'] = $oI->description;
      $item_datas['quantity'] = $oI->quantity;
      $item_datas['amount'] = $oI->price; //sale
      $item_datas['base_amount'] = $oI->unit_price; //base
      $item_datas['discount_amount'] = $oI->discount_amount;
      $item_datas['coupon_amount'] = $oI->coupon_amount;
      $item_datas['tax_type'] = $oI->tax_type;
      $item_datas['igst_percentage'] = $oI->igst_percentage;
      $item_datas['igst_amount'] = $oI->igst_amount;
      $item_datas['cgst_percentage'] = $oI->cgst_percentage;
      $item_datas['cgst_amount'] = $oI->cgst_amount;
      $item_datas['sgst_percentage'] = $oI->sgst_percentage;
      $item_datas['sgst_amount'] = $oI->sgst_amount;
      $item_datas['total_amount'] = $oI->total_price;
      $item_datas['shipping_amount'] = $oI->shipping_amount;
      $item_datas['hsn_code'] = $oI->hsn_code;
      array_push($item_data, $item_datas);
    }

    $invoiceController = new InvoiceController();
    $pdf_url = '';

    if($order->item_type == 0){
      // real
      $orderStatus = new OrderStatus;
      $orderStatus->order_id = $order->id;
      $orderStatus->status = 'ordered';
      $orderStatus->save();
    }else{
      // virtual
      $status_array = ['ordered', 'shipped', 'in transit', 'dispatch', 'delivered'];
      foreach ($status_array as $key => $value){
        $orderStatus = new OrderStatus;
        $orderStatus->order_id = $order->id;
        $orderStatus->status = $value;
        $orderStatus->save();
      }
      $order->order_status = 'delivered';
      $order->save();
      $pdf_url = $invoiceController->index($user, $type, $data, 'order', $item_data);
    }

    // Note: Referral rewards will be distributed in OrderPlaceJob
    // (when order status is 'success' and payment_status is 'success' or 'cod')
    // Rewards are NOT distributed here to avoid duplicate distribution

    $mail_credential = [$order->email => $order->full_name];
    OrderPlaceJob::dispatch($mail_credential, $order, 'user', $product_type, $pdf_url);

    $mail_credential = [config("vrdcms.admin_email") => "Admin"];
    OrderPlaceJob::dispatch($mail_credential, $order, 'admin', $product_type, $pdf_url);

    Session::flash('message', "<p>Your Order Has Been Placed Successfully.</p><p>Order Number is <strong>$order->order_number</strong></p>");
    $message = "Your Order Has Been Placed Successfully. Order Number is $order->order_number";
    if($order->item_type == 1){
      Session::flash('message', "<p>Order confirmed. Your service will be processed shortly.</p><p>Order Number is <strong>$order->order_number</strong></p>");
      $message = "Order confirmed. Your service will be processed shortly. Order Number is $order->order_number";
    }

    // normal notification
    $notification['user_id'] = $user->id;
    $notification['astrologer_id'] = 0;
    $notification['message'] = $message;
    $notification['image_id'] = 0;
    $notification['link'] = route('user.purchase.item');
    $notification['app_link'] = "order_history";
    $notification['for'] = 1;
    $notification['on'] = 0;
    $notification['status'] = 1;
    $notificationController = new NotificationController();
    $notificationController->create($notification);

    // push notification
    $title="Order";
    $body=$notification['message'];
    $dataArray = [
      'type' => $notification['app_link']
    ];
    $pushNotificationController = new PushNotificationController();
    $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

    $cartitemid = $request->cartitemid ?: 0;

    $user_temp_cart_id=$request->user_temp_cart_id?decrypt($request->user_temp_cart_id):0;
    $userTempCart=UserTempCart::find($user_temp_cart_id);

    if($userTempCart){
      AdminSideCartItem::where('user_temp_cart_id', $userTempCart->id)
      ->where('user_id', $order->user_id)
      ->where('user_type', $order->user_type)
      ->where('item_type', "product")
      ->delete();
      $userTempCart->delete();
    }else{
      $cartItems = CartItem::where('user_id', $order->user_id)
      ->where('user_type', $order->user_type)
      ->where('item_type', $order->item_type);
      if($cartitemid > 0){
        $cartItems->where('id', $cartitemid);
      }
      $cartItems->delete();
    }

    return redirect()->route('thank.you');
  }else{
    OrderFailedJob::dispatch($order);
    return redirect()->back();
  }
}

public function handleProducts(Request $request){
  $request->productTypeName = "Product";
  $request->productTypeValue = 1;
  $request->bannerPage = "product";

  $user = auth()->guard("web")->user();
  $user_type = "guest";
  $event = Session::get("view_content_Product");
  if($event == null || ($event == "guest" && $user != null)){
    $timestamp = time();
    $body["event_name"] = "ViewContent";
    $body["event_time"] = $timestamp;
    $body["event_id"] = "Product_{$timestamp}";
    $body["action_source"] = "website";
    $body["event_source_url"] = $request->fullUrl();
    $body["user_data"]["client_ip_address"] = $request->ip();
    $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
    $body["user_data"]["fn"] = sha256("guest");
    if($user){
      $body["user_data"]["em"] = sha256($user->email);
      $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
      $body["user_data"]["fn"] = sha256($user->name);
      $user_type = "user";
    }
    $metaService = new Meta();
    $metaService->sendEvent($body);
    Session::put("view_content_Product", $user_type);
  }
  if($request->category_slug == ""){
    return $this->index($request);
  }
  return $this->handleList($request);
}

public function handlePujas(Request $request){
  $request->productTypeName = "Pooja";
  $request->productTypeValue = 2;
  $request->bannerPage = "puja";

  $user = auth()->guard("web")->user();
  $user_type = "guest";
  $event = Session::get("view_content_Pooja");
  if($event == null || ($event == "guest" && $user != null)){
    $timestamp = time();
    $body["event_name"] = "ViewContent";
    $body["event_time"] = $timestamp;
    $body["event_id"] = "Pooja_{$timestamp}";
    $body["action_source"] = "website";
    $body["event_source_url"] = $request->fullUrl();
    $body["user_data"]["client_ip_address"] = $request->ip();
    $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
    $body["user_data"]["fn"] = sha256("guest");
    if($user){
      $body["user_data"]["em"] = sha256($user->email);
      $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
      $body["user_data"]["fn"] = sha256($user->name);
      $user_type = "user";
    }
    $metaService = new Meta();
    $metaService->sendEvent($body);
    Session::put("view_content_Pooja", $user_type);
  }
  if($request->category_slug == ""){
    return $this->index($request);
  }
  return $this->handleList($request);
}

public function handleOneToOnePujas(Request $request){
  $request->productTypeName = "OneToOnePuja";
  $request->productTypeValue = 2;
  $request->bannerPage = "puja";
  $request->is_one_to_one_puja = 1; // Flag to indicate one-to-one puja
  $request->category_slug = "";

  $user = auth()->guard("web")->user();
  $user_type = "guest";
  $event = Session::get("view_content_OneToOnePuja");
  if($event == null || ($event == "guest" && $user != null)){
    $timestamp = time();
    $body["event_name"] = "ViewContent";
    $body["event_time"] = $timestamp;
    $body["event_id"] = "OneToOnePuja_{$timestamp}";
    $body["action_source"] = "website";
    $body["event_source_url"] = $request->fullUrl();
    $body["user_data"]["client_ip_address"] = $request->ip();
    $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
    $body["user_data"]["fn"] = sha256("guest");
    if($user){
      $body["user_data"]["em"] = sha256($user->email);
      $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
      $body["user_data"]["fn"] = sha256($user->name);
      $user_type = "user";
    }
    $metaService = new Meta();
    $metaService->sendEvent($body);
    Session::put("view_content_OneToOnePuja", $user_type);
  }

  return $this->index($request);
}

public function handleServices(Request $request){
  $request->productTypeName = "Service";
  $request->productTypeValue = 3;
  $request->bannerPage = "service";

  $user = auth()->guard("web")->user();
  $user_type = "guest";
  $event = Session::get("view_content_Service");
  if($event == null || ($event == "guest" && $user != null)){
    $timestamp = time();
    $body["event_name"] = "ViewContent";
    $body["event_time"] = $timestamp;
    $body["event_id"] = "Service_{$timestamp}";
    $body["action_source"] = "website";
    $body["event_source_url"] = $request->fullUrl();
    $body["user_data"]["client_ip_address"] = $request->ip();
    $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
    $body["user_data"]["fn"] = sha256("guest");
    if($user){
      $body["user_data"]["em"] = sha256($user->email);
      $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
      $body["user_data"]["fn"] = sha256($user->name);
      $user_type = "user";
    }
    $metaService = new Meta();
    $metaService->sendEvent($body);
    Session::put("view_content_Service", $user_type);
  }
  if($request->category_slug == ""){
    return $this->index($request);
  }
  return $this->handleList($request);
}

public function handleList(Request $request){
  $productTypeName = $request->productTypeName;
  $productTypeValue = $request->productTypeValue;
  $bannerPage = $request->bannerPage;
  $nav = $request->segment(2) ?: $productTypeName;
  $categorySlug = $request->category_slug ?: "all";

  // Check if this is a one-to-one puja request by checking route name, URL segment, or request flag
  $isOneToOnePuja = false;
  if($request->routeIs('one.to.one.puja.handle') || $request->routeIs('one.to.one.puja.slug') || $request->segment(1) == 'one-to-one-puja' || (isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1)){
    $isOneToOnePuja = true;
  }
  $slug = $categorySlug;
  $type = $productTypeValue;
  $sortBy = $request->sortBy?:"";
  $minPrice = 0;
  $maxPrice = 0;
  $priceFrom = $request->priceFrom?:0;
  $priceTo = $request->priceTo?:0;
  $priceRangeKey = 'all';
  $pf = (int)($request->priceFrom ?? 0);
  $pt = (int)($request->priceTo ?? 0);
  if ($pf && $pt) {
    if ($pf === 1000 && $pt === 2000) $priceRangeKey = '1000-2000';
    elseif ($pf === 2001 && $pt === 4000) $priceRangeKey = '2001-4000';
    elseif ($pf === 4001 && $pt === 6000) $priceRangeKey = '4001-6000';
    elseif ($pf === 6001 && $pt === 10000) $priceRangeKey = '6001-10000';
  }
  $page = $request->page?:1;
  $ratingFilter = $request->rating;
  $ratingMin = null;
  if ($ratingFilter !== null && $ratingFilter !== '' && $ratingFilter !== 'all') {
    $ratingMin = (int) $ratingFilter;
    if ($ratingMin >= 1 && $ratingMin <= 5) {
      // keep ratingMin for filter below
    } else {
      $ratingMin = null;
    }
  }
  $category = null;
  $products = Product::where("type", $productTypeValue)
  ->where("products.status", 1)
  ->where("products.parent_id", 0)
  ->join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
  ->leftJoin(DB::raw('(SELECT product_id, AVG(rating) as avg_rating FROM product_reviews WHERE status = 1 GROUP BY product_id) as review_avg'), 'products.id', '=', 'review_avg.product_id')
  ->selectRaw('products.*, IF(products.sale_price_start_date IS NOT NULL AND products.sale_price_start_date <= CURDATE() AND products.sale_price_end_date >= CURDATE(), products.sale_price, products.base_price) as price_order, ROUND(review_avg.avg_rating, 1) as avg_rating');
  if($categorySlug != "all"){
    $category = ProductCategory::where("type", $productTypeValue)->where('slug', $categorySlug)->where("status", 1)->first();
    if(!$category){
      file_put_contents("debug_404.txt", "ABORTED AT LINE " . __LINE__); return abort(404);
    }
    $products->where("paci.category_id", $category->id);
  }
  if($productTypeValue == 2){
    // Filter one-to-one puja based on request
    if($isOneToOnePuja){
      // Show only one-to-one puja (one-to-one puja might not have expire_at)
      $products->where('products.is_one_to_one_puja', 1);
    }else{
      // Exclude one-to-one puja from regular puja listing
      $products->where(function($query) {
        $query->where('products.is_one_to_one_puja', 0)
              ->orWhereNull('products.is_one_to_one_puja');
      });
      // Regular puja should have expire_at
      $products->where('products.expire_at', ">=", date("Y-m-d H:i:s"));
    }
  }
  $productsForPrices = $products;
  $minPrice = $productsForPrices->min(DB::raw('IF(products.sale_price_start_date <= CURDATE() AND products.sale_price_end_date >= CURDATE(), products.sale_price, products.base_price)')) ?: 0;
  $maxPrice = $productsForPrices->max(DB::raw('IF(products.sale_price_start_date <= CURDATE() AND products.sale_price_end_date >= CURDATE(), products.sale_price, products.base_price)')) ?: 0;
  if($priceFrom>0){
    $products->whereRaw('IF(products.sale_price_start_date <= CURDATE() AND products.sale_price_end_date >= CURDATE(), products.sale_price, products.base_price) >= ?', [$priceFrom]);
  }
  if($priceTo>0){
    $products->whereRaw('IF(products.sale_price_start_date <= CURDATE() AND products.sale_price_end_date >= CURDATE(), products.sale_price, products.base_price) <= ?', [$priceTo]);
  }
  if ($ratingMin !== null) {
    $products->whereNotNull('review_avg.avg_rating')->whereRaw('review_avg.avg_rating >= ?', [$ratingMin]);
  }
  $productSearchQuery = trim((string)($request->product_name ?? $request->product_search ?? ''));
  if ($productSearchQuery !== '') {
    $products->where('products.title', 'like', '%' . $productSearchQuery . '%');
  }
  if($sortBy == "a_to_z"){
    $products->orderBy("products.title", "ASC");
  }elseif($sortBy == "price_low_to_high"){
    $products->orderByRaw("price_order ASC");
  }elseif($sortBy == "price_high_to_low"){
    $products->orderByRaw("price_order DESC");
  }elseif($sortBy == "whats_new"){
    $products->orderBy("products.created_at", "DESC");
  }elseif($category){
    $products->orderBy("paci.order", "asc");
  }else{
    $products->orderBy("products.order", "asc");
  }
  $take = 24;
  $products = $products->paginate($take);
  $count = $products->total();
  if($priceFrom==0){
    $priceFrom = $minPrice;
  }
  if($priceTo==0){
    $priceTo = $maxPrice;
  }
  $url = $request->getRequestUri();
  if($request->ajax()){
    $data = ['rows' => view(themeView()."product.category-list", ["products" => $products])->render(), "page" => $page];
    return response()->json($data,200);
  }else{
    $bestSellerProducts = Product::where('type', $productTypeValue)
    ->where('status', 1)
    ->where('is_best_seller', 1)
    ->where('parent_id', 0);

    // Filter one-to-one puja based on request
    if($productTypeValue == 2){
      if($isOneToOnePuja){
        // Show only one-to-one puja
        $bestSellerProducts->where('is_one_to_one_puja', 1);
      }else{
        // Exclude one-to-one puja from regular puja listing
        $bestSellerProducts->where(function($query) {
          $query->where('is_one_to_one_puja', 0)
                ->orWhereNull('is_one_to_one_puja');
        });
      }
    }

    if($category){
      $bestSellerProducts->join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
      ->where("paci.category_id", $category->id);

      // Also filter one-to-one puja for category-based best sellers
      if($productTypeValue == 2 && $isOneToOnePuja){
        $bestSellerProducts->where('products.is_one_to_one_puja', 1);
      }elseif($productTypeValue == 2 && !$isOneToOnePuja){
        $bestSellerProducts->where(function($query) {
          $query->where('products.is_one_to_one_puja', 0)
                ->orWhereNull('products.is_one_to_one_puja');
        });
      }
    }

    $bestSellerProducts = $bestSellerProducts->orderBy('products.order', 'asc')
    ->limit(10)
    ->get();

    if($bestSellerProducts->count() == 0){
      $bestSellerProducts = Product::where('type', $productTypeValue)
      ->where('status', 1)
      ->where('is_best_seller', 1)
      ->where('parent_id', 0);

      // Filter one-to-one puja based on request
      if($productTypeValue == 2){
        if($isOneToOnePuja){
          // Show only one-to-one puja
          $bestSellerProducts->where('is_one_to_one_puja', 1);
        }else{
          // Exclude one-to-one puja from regular puja listing
          $bestSellerProducts->where(function($query) {
            $query->where('is_one_to_one_puja', 0)
                  ->orWhereNull('is_one_to_one_puja');
          });
        }
      }

      // If category exists, also filter by category
      if($category){
        $bestSellerProducts->join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
        ->where("paci.category_id", $category->id);
      }

      $bestSellerProducts = $bestSellerProducts->orderBy('products.order', 'asc')
      ->limit(10)
      ->get();
    }
    $categriesId = Product::join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
    ->where("type", $productTypeValue)
    ->where('status', 1)
    ->where('parent_id', 0);

    // Filter one-to-one puja based on request
    if($productTypeValue == 2){
      if($isOneToOnePuja){
        // Show only one-to-one puja
        $categriesId->where('products.is_one_to_one_puja', 1);
      }else{
        // Exclude one-to-one puja from regular puja listing
        $categriesId->where(function($query) {
          $query->where('products.is_one_to_one_puja', 0)
                ->orWhereNull('products.is_one_to_one_puja');
        });
      }
    }

    $categriesId = $categriesId->select("paci.category_id")
    ->distinct()
    ->pluck("paci.category_id")
    ->toArray();
    $categories = ProductCategory::where("type", $productTypeValue)
    ->where("status", 1)
    ->whereIn("id", $categriesId)
    ->orderBy('order', 'asc')
    ->get();

    $today = date('Y-m-d');
    $banners = Banner::where('status', 1)
    ->where('type', $bannerPage)
    ->where(function($start_date) use ($today){
      $start_date->where('start_date', '<=', $today)
      ->orWhereNull('start_date')
      ->orWhere('start_date', '1970-01-01')
      ->orWhere('start_date', '0000-00-00');
    })
    ->where(function($end_date) use ($today){
      $end_date->where('end_date', '>=', $today)
      ->orWhereNull('end_date')
      ->orWhere('end_date', '1970-01-01')
      ->orWhere('end_date', '0000-00-00');
    })
    ->orderBy('order', 'asc')
    ->limit(1)
    ->get();
    $breads=[];
    if($productTypeValue==1){
      $breads=[
        ["name"=>"Collections","url"=>route("product.handle")],
        ["name"=>"Collections List","url"=>route("product.handle",[$categorySlug])]
      ];
      }elseif($productTypeValue==2){
        // Check if this is one-to-one puja by checking route or URL
        $isOneToOnePujaForBread = false;
        if($request->routeIs('one.to.one.puja.handle') || $request->routeIs('one.to.one.puja.slug') || $request->segment(1) == 'one-to-one-puja' || (isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1)){
          $isOneToOnePujaForBread = true;
        }

        if($isOneToOnePujaForBread){
          $breads=[
            ["name"=>"One to One Puja","url"=>route("one.to.one.puja.handle")],
            ["name"=>"One to One Puja List","url"=>$categorySlug ? route("one.to.one.puja.slug",[$categorySlug]) : route("one.to.one.puja.handle")]
          ];
        }else{
          $breads=[
            ["name"=>"Pujas Collections","url"=>route("puja.handle")],
            ["name"=>"Pujas Collections List","url"=>route("puja.handle",[$categorySlug])]
          ];
        }
      }else{
      $breads=[
        ["name"=>"Reports Collections","url"=>route("service.handle")],
        ["name"=>"Reports Collections List","url"=>route("service.handle",[$categorySlug])]
      ];
    }
    $is_one_to_one_puja = isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1 ? 1 : 0;
    $isOneToOnePuja = $is_one_to_one_puja == 1;
    return view(themeView().'product.categorylist', compact("nav", "breads",'products', "category", 'categories', 'slug', "productTypeName", "productTypeValue", 'type', 'count', 'maxPrice', 'minPrice', 'priceFrom', 'priceTo', 'priceRangeKey', 'sortBy', 'page', 'url', 'take', 'bestSellerProducts', "banners", 'is_one_to_one_puja', 'isOneToOnePuja', 'ratingFilter', 'productSearchQuery'));
  }
}

  /**
   * AJAX: Product name suggestions for category list filter (autocomplete).
   */
  public function productSearchSuggestions(Request $request)
  {
    $q = trim((string)($request->q ?? $request->query ?? ''));
    if ($q === '' || strlen($q) < 2) {
      return response()->json(['suggestions' => []]);
    }
    $productTypeValue = (int)($request->type ?? 1);
    $query = Product::where('type', $productTypeValue)
      ->where('status', 1)
      ->where('parent_id', 0)
      ->where('title', 'like', '%' . $q . '%');
    if ($productTypeValue == 2) {
      $isOneToOne = (int)($request->is_one_to_one_puja ?? 0) === 1;
      if ($isOneToOne) {
        $query->where('is_one_to_one_puja', 1);
      } else {
        $query->where(function ($qb) {
          $qb->where('is_one_to_one_puja', 0)->orWhereNull('is_one_to_one_puja');
        });
      }
    }
    $list = $query->orderBy('title', 'asc')->limit(10)->get(['id', 'title']);
    return response()->json(['suggestions' => $list->map(function ($p) {
      return ['id' => $p->id, 'title' => $p->title];
    })->values()]);
  }

  public function reviewSubmit(Request $request){
    $request->validate([
      "order_id" => "required|gt:0",
      "product_id" => "required|gt:0",
      'rating' => 'required|numeric|min:1',
      "comments" => "required",
    ], [
      "rating" => "The rating field is required",
      "comments" => "The review field is required",
    ]);
    $user = auth()->guard("web")->user();
    $review = ProductReview::where("order_id", $request->order_id)->where("review_by", $user->id)->where("product_id", $request->product_id)->first();

    if(!$review){
      $review = new ProductReview();
    }

    $review->rating = $request->rating;
    $review->review = $request->comments;
    $review->review_by = $user->id;
    $review->product_id = $request->product_id;
    $review->order_id = $request->order_id;
    $review->status = 0;
    $review->save();

    return response()->json([
       'status' => 'success',
       'message' => 'Review submitted successfully.'
   ]);
  }

  public function handleVariation(Request $request){
    $this->validate($request, [
      'parent_id'=>'required|gt:0',
      'variation'=>'required|array'
    ]);
    // $user = auth()->guard('web')->user();
    $parent_id = $request->parent_id;
    $aV = $request->variation;
    $inArray = [];
    $pA=null;
    foreach($aV as $key => $value){
      if($key==0){
        $inArray = ProductAttribute::where('parent_id',$parent_id)->where('attribute_value', $value)->pluck('product_id')->toArray();
      }elseif($key!=(count($aV)-1)){
        $inArray = ProductAttribute::whereIn('product_id', $inArray)->where('attribute_value', $value)->pluck('product_id')->toArray();
      }
    }
    $pA = ProductAttribute::whereIn('product_id', $inArray)->where('attribute_value', $value)->first();
    if($pA && $product=$pA->product){
      if($product->status){
        $productTypes = ["", "Product", "Pooja", "Service"];
        $redirectTo = "";
        if($product->type == 1){
          $redirectTo = route('product.detail', [$product->slug, "id" => $product->id]);
        }elseif($product->type == 2){
          $redirectTo = route('product.detailpuja', [$product->slug, "id" => $product->id]);
        }elseif($product->type == 3){
          $redirectTo = route('product.detailservice', [$product->slug, "id" => $product->id]);
        }

        $price = view(themeView().'product.price', compact('product'))->render();
        $isGroupPuja = ($product->type == 2 && (int)($product->is_one_to_one_puja ?? 0) !== 1);
        if ($isGroupPuja) {
          $price = '';
        }
        // $gallery = view(themeView().'product.gallery', compact('product','user'))->render();
        // $addtocart = view(themeView().'product.addtocart', compact('product','user'))->render();
        // $price = "";
        $gallery = "";
        $addtocart = "";
        return response()->json(['success'=>true, 'redirectTo'=>$redirectTo, 'product_id'=>$product->id, 'price'=>$price, 'gallery'=>$gallery, 'addtocart'=>$addtocart], 200);
      }
    }
    return response()->json(['success'=>false, 'message'=>'not found.'], 404);
  }

  public function getVariationForm(Request $request){
    $request->validate([
      'variation_id' => 'required|integer|exists:products,id'
    ]);

    $variationId = $request->variation_id;
    $product = Product::where('id', $variationId)
      ->where('status', 1)
      ->first();

    if(!$product){
      return response()->json(['success'=>false, 'message'=>'Variation not found.'], 404);
    }

    $productFormFields = $product->fields;
    $productTypeValue = $product->type;
    $formButtonText = "Buy Now";
    $formSubmitButtonText = "Buy Now";
    if($productTypeValue == 2){
      $formButtonText = "Participate";
      $formSubmitButtonText = "Book Puja";
    }

    // Get variation details
    $variationImage = $product->image;
    $variationName = $product->attributes->first() ? $product->attributes->first()->attribute_value : $product->title;
    $todayDate = date("Y-m-d");
    $variationPrice = $product->base_price;
    $variationSpecialPrice = $product->sale_price;
    $variationSpecialPriceFromDate = $product->sale_price_start_date;
    $variationSpecialPriceToDate = $product->sale_price_end_date;

    // Check if special price is active
    if($variationSpecialPriceFromDate && ($variationSpecialPriceFromDate <= $todayDate) && ($variationSpecialPriceToDate >= $todayDate)){
      $finalPrice = $variationSpecialPrice;
    } else {
      $finalPrice = $variationPrice;
    }

    // Get image URL
    $imageUrl = $variationImage ? $variationImage->image(80, 80) : no_image();

    // Render form HTML
    $formHtml = view(themeView().'product._variation_form', compact('product', 'productFormFields', 'productTypeValue', 'formButtonText'))->render();

    // Get persons count
    $personsCount = $product->max_order_qty;
    $personsText = $personsCount . ' person' . ($personsCount > 1 ? 's' : '');

    $isGroupPuja = ($product->type == 2 && (int)($product->is_one_to_one_puja ?? 0) !== 1);

    return response()->json([
      'success' => true,
      'form_html' => $formHtml,
      'product_id' => $product->id,
      'product_title' => $product->title,
      'variation_name' => $variationName,
      'variation_price' => $isGroupPuja ? null : $finalPrice,
      'variation_image' => $imageUrl,
      'variation_persons' => $personsText,
      'variation_persons_count' => $personsCount
    ], 200);
  }

  /**
   * Compute the valid date (Y-m-d) for a group puja order at booking time.
   * Used to snapshot the date so admin updates to product valid_till_dates don't affect this order.
   */
  private function getGroupPujaValidDateAtBooking($order, $parentProduct)
  {
    if (!$order || !$parentProduct) {
      return null;
    }
    $tz = config('app.timezone', 'Asia/Kolkata');
    $orderDt = $order->created_at ? Carbon::parse($order->created_at)->setTimezone($tz) : null;
    $orderTs = $orderDt ? $orderDt->timestamp : 0;
    $dates = $parentProduct->valid_till_dates;
    $timePart = $parentProduct->expire_at ? Carbon::parse($parentProduct->expire_at)->format('H:i:s') : '23:59:59';
    if (!empty($dates) && is_array($dates)) {
      $dates = array_values(array_filter(array_map('trim', $dates), function ($d) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
      }));
      sort($dates);
      foreach ($dates as $d) {
        $expiryTs = Carbon::parse($d . ' ' . $timePart, $tz)->timestamp;
        if ($orderTs <= $expiryTs) {
          return $d;
        }
      }
      return count($dates) > 0 ? end($dates) : null;
    }
    if ($parentProduct->expire_at) {
      return Carbon::parse($parentProduct->expire_at)->format('Y-m-d');
    }
    return null;
  }

  /**
   * Assign astrologer to puja based on round-robin distribution system
   * Logic:
   * - Check if an astrologer already has an active assignment for THIS specific puja
   * - If yes, assign to another astrologer who doesn't have an active assignment for this puja
   * - When all astrologers have active assignments for this puja, cycle back to the first one
   */
  private function assignAstrologerToPuja($order, $orderItem, $product)
  {
    try {
      \Log::info("=== Starting Puja Assignment Process ===");
      \Log::info("Product ID: {$product->id} | Product Name: {$product->title} | Type: {$product->type} | Is One-to-One: " . ($product->is_one_to_one_puja ?? 0));
      \Log::info("Order ID: {$order->id} | Order Item ID: {$orderItem->id}");

      // Get all astrologers assigned to this puja (from puja_astrologers pivot table)
      // First check without status filter to see if any astrologers are linked at all
      $allLinkedAstrologers = \DB::table('puja_astrologers')
        ->where('product_id', $product->id)
        ->get();

      \Log::info("Total astrologers linked to puja (any status): " . $allLinkedAstrologers->count());
      foreach($allLinkedAstrologers as $linked) {
        \Log::info("  - Linked Astrologer ID: {$linked->astrologer_id} | Status: {$linked->status} | Priority: {$linked->priority}");
      }

      // Get active astrologers (status = 1)
      // Query directly to avoid orderByPivot conflict with relationship
      $pujaAstrologers = \App\Model\Astrologer::join('puja_astrologers', 'astrologers.id', '=', 'puja_astrologers.astrologer_id')
        ->where('puja_astrologers.product_id', $product->id)
        ->where('puja_astrologers.status', 1)
        ->whereNull('astrologers.deleted_at')
        ->select('astrologers.*', 'puja_astrologers.priority as pivot_priority', 'puja_astrologers.status as pivot_status',
                 'puja_astrologers.product_id as pivot_product_id', 'puja_astrologers.astrologer_id as pivot_astrologer_id')
        ->orderBy('puja_astrologers.priority', 'asc')
        ->get();

      if($pujaAstrologers->isEmpty()){
        // No astrologers assigned to this puja
        \Log::error("=== CRITICAL: No ACTIVE astrologers found for puja ===");
        \Log::error("Puja ID: {$product->id} | Product: {$product->title}");
        \Log::error("Total linked (any status): " . $allLinkedAstrologers->count());
        if($allLinkedAstrologers->isNotEmpty()) {
          \Log::error("Astrologers are linked but have status != 1. Please check puja_astrologers table.");
        } else {
          \Log::error("No astrologers are linked to this puja at all. Please link astrologers in admin panel.");
        }
        return;
      }

      \Log::info("Found {$pujaAstrologers->count()} astrologer(s) for this puja:");
      foreach($pujaAstrologers as $ast) {
        \Log::info("  - Astrologer ID: {$ast->id} | Name: " . ($ast->display_name ?? $ast->firstname . ' ' . $ast->lastname));
      }

      // Get already assigned astrologers for this order item (to avoid duplicate assignment for same order)
      $alreadyAssignedAstrologerIds = PujaAstrologerAssignment::where('order_item_id', $orderItem->id)
        ->where('status', 'assigned')
        ->pluck('astrologer_id')
        ->toArray();

      // Get all active assignments for THIS specific product (puja) - for round-robin tracking
      $activeAssignmentsForThisPuja = PujaAstrologerAssignment::where('product_id', $product->id)
        ->where('status', 'assigned')
        ->orderBy('created_at', 'asc')
        ->get();

      // Get astrologer IDs who have active assignments for THIS specific puja only
      $astrologersWithActiveAssignmentForThisPuja = $activeAssignmentsForThisPuja
        ->pluck('astrologer_id')
        ->unique()
        ->toArray();

      // Get the last assigned astrologer for THIS puja (for round-robin within same puja)
      $lastAssignedAstrologerIdForThisPuja = null;
      if($activeAssignmentsForThisPuja->isNotEmpty()){
        $lastAssignedAstrologerIdForThisPuja = $activeAssignmentsForThisPuja->last()->astrologer_id;
      }

      // Check which astrologers have ANY active assignment (across all pujas) - Checkpoint
      $astrologersWithAnyActiveAssignment = PujaAstrologerAssignment::where('status', 'assigned')
        ->pluck('astrologer_id')
        ->unique()
        ->toArray();

      // First priority: Find astrologers who don't have THIS specific puja assigned (for round-robin)
      $availableAstrologers = $pujaAstrologers->filter(function($astrologer) use ($alreadyAssignedAstrologerIds, $astrologersWithActiveAssignmentForThisPuja) {
        // Exclude if already assigned to this order item OR has active assignment for this specific puja
        return !in_array($astrologer->id, $alreadyAssignedAstrologerIds)
               && !in_array($astrologer->id, $astrologersWithActiveAssignmentForThisPuja);
      });

      // If all astrologers have this puja assigned, cycle back using round-robin
      if($availableAstrologers->isEmpty() && $lastAssignedAstrologerIdForThisPuja){
        // All astrologers have this puja assigned, so cycle based on last assignment for this puja
        $lastIndex = -1;
        foreach($pujaAstrologers as $index => $astrologer){
          if($astrologer->id == $lastAssignedAstrologerIdForThisPuja){
            $lastIndex = $index;
            break;
          }
        }

        // Start from next astrologer after the last assigned one for this puja
        if($lastIndex >= 0 && $lastIndex < ($pujaAstrologers->count() - 1)){
          $availableAstrologers = $pujaAstrologers->slice($lastIndex + 1);
        } else {
          // If last assigned was the last one, cycle to first
          $availableAstrologers = $pujaAstrologers->take(1);
        }
      } elseif($availableAstrologers->isEmpty() && !$lastAssignedAstrologerIdForThisPuja) {
        // No previous assignment for this puja, start with first astrologer
        $availableAstrologers = $pujaAstrologers->take(1);
      }

      // Checkpoint: Among available astrologers, prefer those who don't have ANY active assignment
      // This handles: if astrologer1 has puja1, and puja2 is booked, assign puja2 to astrologer2 (if free)
      if($availableAstrologers->isNotEmpty()){
        // Filter to get astrologers who don't have ANY active assignment (preferred)
        $freeAstrologers = $availableAstrologers->filter(function($astrologer) use ($astrologersWithAnyActiveAssignment) {
          return !in_array($astrologer->id, $astrologersWithAnyActiveAssignment);
        });

        // If we have free astrologers (no active assignments), use them (checkpoint works)
        if($freeAstrologers->isNotEmpty()){
          $availableAstrologers = $freeAstrologers;
        }
        // If all available astrologers are busy with other pujas, use the first available one
        // This allows assignment even when all astrologers have other pujas
      }

      // If still no available astrologers, get all astrologers except ones already assigned to this order item
      if($availableAstrologers->isEmpty()){
        $availableAstrologers = $pujaAstrologers->filter(function($astrologer) use ($alreadyAssignedAstrologerIds) {
          return !in_array($astrologer->id, $alreadyAssignedAstrologerIds);
        });
      }

      // If still empty (shouldn't happen), use first astrologer
      if($availableAstrologers->isEmpty()){
        $availableAstrologers = $pujaAstrologers->take(1);
      }

      // Log available astrologers before selection
      \Log::info("Available astrologers count: {$availableAstrologers->count()}");
      foreach($availableAstrologers as $ast) {
        \Log::info("  - Available: Astrologer ID: {$ast->id} | Name: " . ($ast->display_name ?? $ast->firstname . ' ' . $ast->lastname));
      }

      // Select the first available astrologer
      $selectedAstrologer = $availableAstrologers->first();

      if($selectedAstrologer){
        // Verify order has user_id before creating assignment
        if(!$order->user_id) {
          \Log::error("=== CRITICAL: Order has no user_id ===");
          $orderNumber = $order->order_number ?? 'N/A';
          \Log::error("Order ID: {$order->id} | Order Number: {$orderNumber}");
          \Log::error("Cannot create assignment without valid user_id");
          return;
        }

        // Verify user exists
        $user = \App\User::find($order->user_id);
        if(!$user) {
          \Log::error("=== CRITICAL: User not found ===");
          \Log::error("Order ID: {$order->id} | User ID: {$order->user_id}");
          \Log::error("Cannot create assignment - user does not exist");
          return;
        }

        // Create assignment
        $assignment = new PujaAstrologerAssignment();
        $assignment->order_id = $order->id;
        $assignment->order_item_id = $orderItem->id;
        $assignment->product_id = $product->id;
        $assignment->astrologer_id = $selectedAstrologer->id;
        $assignment->user_id = $order->user_id; // Set user_id from order (guaranteed to exist now)
        $assignment->status = 'assigned';
        $assignment->assigned_at = now();

        \Log::info("Creating assignment with user_id: {$order->user_id} from order ID: {$order->id}");

        try {
          $assignment->save();

          // Verify the assignment was saved
          $savedAssignment = PujaAstrologerAssignment::find($assignment->id);
          if(!$savedAssignment) {
            \Log::error("Assignment was not saved! Assignment ID: {$assignment->id}");
            throw new \Exception("Assignment save failed");
          }

          \Log::info("=== Puja Assignment Success ===");
          \Log::info("Puja ID: {$product->id} | Puja Name: {$product->title}");
          \Log::info("Astrologer ID: {$selectedAstrologer->id} | Astrologer Name: " . ($selectedAstrologer->display_name ?? $selectedAstrologer->firstname . ' ' . $selectedAstrologer->lastname));
          \Log::info("Order ID: {$order->id} | Order Item ID: {$orderItem->id}");
          \Log::info("Assignment ID: {$assignment->id} | Status: {$assignment->status}");
          \Log::info("Assigned At: {$assignment->assigned_at}");
          \Log::info("Created At: {$assignment->created_at}");

          // Verify assignment exists in database
          $verifyAssignment = PujaAstrologerAssignment::where('id', $assignment->id)
            ->where('astrologer_id', $selectedAstrologer->id)
            ->where('status', 'assigned')
            ->first();
          if($verifyAssignment) {
            \Log::info("Assignment verified in database successfully");
          } else {
            \Log::error("Assignment NOT found in database after save!");
          }

          // Notification will be sent when order is successful (in OrderPlaceJob)
        } catch (\Exception $e) {
          \Log::error("Error saving assignment: " . $e->getMessage());
          \Log::error("Stack trace: " . $e->getTraceAsString());
        }
      } else {
        \Log::warning("=== Puja Assignment Failed ===");
        \Log::warning("Puja ID: {$product->id} | Puja Name: {$product->title}");
        \Log::warning("Total astrologers for this puja: {$pujaAstrologers->count()}");
        \Log::warning("Available astrologers count: {$availableAstrologers->count()}");
        \Log::warning("No astrologer could be selected");
      }
    } catch (\Exception $e) {
      // Log error but don't break order placement
      \Log::error('Error assigning astrologer to puja: ' . $e->getMessage());
      \Log::error('Stack trace: ' . $e->getTraceAsString());
    }
  }

  public function remediesCheckout(Request $request, $token){
    // Check if user is logged in
    if(!auth()->guard('web')->check()){
      $redirectUrl = route('remedies.checkout', ['token' => $token]);
      if($request->has('ref')){
        $redirectUrl .= '?ref=' . urlencode($request->ref);
      }
      Session::put('redirect_from_login', $redirectUrl);
      return redirect()->route('login')->with('message', 'Please login to view suggested remedies');
    }

    $user = auth()->guard('web')->user();
    $user_type = 'user';

    // Get suggestion data - check expiry (24 hours) and not used
    $suggestion = DB::table('astrologer_remedy_suggestions')
      ->where('token', $token)
      ->where('user_id', $user->id)
      ->where('is_used', 0)
      ->where('created_at', '>=', now()->subHours(24))
      ->first();

    if(!$suggestion){
      // Check if it exists but expired or used
      $expiredSuggestion = DB::table('astrologer_remedy_suggestions')
        ->where('token', $token)
        ->where('user_id', $user->id)
        ->first();

      if($expiredSuggestion){
        if($expiredSuggestion->is_used == 1){
          return redirect()->route('home')->with('error', 'This remedy suggestion link has already been used.');
        } else {
          return redirect()->route('home')->with('error', 'This remedy suggestion link has expired. Please request a new one from your astrologer.');
        }
      }
      return redirect()->route('home')->with('error', 'Invalid remedy suggestion link');
    }

    // Get referral code from query parameter or suggestion
    $referralCodeFromUrl = $request->get('ref');
    $referralCode = $referralCodeFromUrl ?: $suggestion->referral_code;

    // Clear existing cart items for this user
    CartItem::where('user_id', $user->id)
      ->where('user_type', $user_type)
      ->where('item_type', 0)
      ->delete();

    $products = json_decode($suggestion->products, true) ?: [];
    $reports = json_decode($suggestion->reports, true) ?: [];
    $todayDate = date("Y-m-d");

    // Add products to cart
    foreach($products as $productId){
      $product = Product::where('id', $productId)
        ->where('status', 1)
        ->where('type', 1)
        ->first();

      if($product && $product->avaibility > 0){
        $productPrice = $product->base_price;
        $productSpecialPrice = $product->sale_price;
        $productSpecialPriceFromDate = $product->sale_price_start_date;
        $productSpecialPriceToDate = $product->sale_price_end_date;

        $cartItem = new CartItem();
        $cartItem->user_id = $user->id;
        $cartItem->user_type = $user_type;
        $cartItem->item_id = $product->id;
        $cartItem->type = 'shop';
        $cartItem->quantity = 1;
        $cartItem->unit_price = $productPrice;
        $cartItem->price = $productPrice;
        if($productSpecialPriceFromDate && ($todayDate >= $productSpecialPriceFromDate) && ($todayDate <= $productSpecialPriceToDate)){
          $cartItem->price = $productSpecialPrice;
        }
        $cartItem->item_type = 0;
        $cartItem->formdata = json_encode([]);
        $cartItem->save();
      }
    }

    // Add reports to cart
    foreach($reports as $reportId){
      $report = Product::where('id', $reportId)
        ->where('status', 1)
        ->where('type', 3)
        ->first();

      if($report){
        $reportPrice = $report->base_price;
        $reportSpecialPrice = $report->sale_price;
        $reportSpecialPriceFromDate = $report->sale_price_start_date;
        $reportSpecialPriceToDate = $report->sale_price_end_date;

        $cartItem = new CartItem();
        $cartItem->user_id = $user->id;
        $cartItem->user_type = $user_type;
        $cartItem->item_id = $report->id;
        $cartItem->type = 'shop';
        $cartItem->quantity = 1;
        $cartItem->unit_price = $reportPrice;
        $cartItem->price = $reportPrice;
        if($reportSpecialPriceFromDate && ($todayDate >= $reportSpecialPriceFromDate) && ($todayDate <= $reportSpecialPriceToDate)){
          $cartItem->price = $reportSpecialPrice;
        }
        $cartItem->item_type = 0;
        $cartItem->formdata = json_encode([]);
        $cartItem->save();
      }
    }

    // Don't mark as used here - only mark when order is successfully placed
    // This allows user to revisit the link if they don't complete the order

    // Store referral code in session for checkout (prioritize URL parameter if present)
    if($referralCode){
      Session::put('remedies_referral_code', $referralCode);
    } elseif($suggestion->referral_code){
      Session::put('remedies_referral_code', $suggestion->referral_code);
    }

    // Store suggestion token in session to mark as used after successful order
    Session::put('remedies_suggestion_token', $token);

    // Redirect to checkout
    return redirect()->route('checkout', ['productTypeName' => 'product']);
  }

}
