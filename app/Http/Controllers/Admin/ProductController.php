<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\{Media ,ProductCategory, HsnCode, Tag, ProductTag, Attribute, ProductAttribute, PAttribute, PAttributeValue, ProductVideo, Pandit, DeliveryDate, ApiUrl, Vendor, ProductAndCategoryId, Astrologer};
use App\{Product , ProductSpecification , ProductDocument};
use Illuminate\Support\Facades\Auth;
use App\ProductFormField;
use App\Form;
use App\User;
use App\ProductGallery;
use App\ProductReview;
use App\Specification;
use \Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use DB;

class ProductController extends Controller
{
    public function index(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleName = 'Products';
      $moduleKey = 'product';
      $nav = 'product';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $nav = 'serviceProduct';
        $moduleName = 'Service Products';
        $moduleKey = 'service-product';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $nav = 'poojaProduct';
        $moduleName = 'Puja Products';
        $moduleKey = 'pooja-product';
      }
      if(!adminCan($moduleKey, 'view')) return dd('No Permission');
      $breads = [
        ['name'=>$moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
      ];
      $p_id = $request->p_id ?: 0;
      $product = Product::find($p_id);
      if($p_id && $product){
        $breads = [
          ['name'=> $moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
          ['name'=> $product->title, 'url'=> route('admin.product.variation.index', [$p_id, $productTypeName])],
          ['name'=>'Variation', 'url'=> ""],
        ];
      }
      $medias  = Media::where('type','product')->get();
      return view('admin.product.list',compact('medias','nav','breads', 'p_id', 'product', 'productTypeName', 'productTypeValue', 'moduleName', 'moduleKey'));
    }

    public function all(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      if($productTypeName == "Service"){
        $productTypeValue = 3;
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
      }
      $p_id = $request->p_id ?: 0;
      $products = Product::where('parent_id', $p_id)->where("type", $productTypeValue);

      // Exclude one-to-one puja products from regular puja products listing
      if($productTypeValue == 2) {
        $products->where(function($query) {
          $query->where('is_one_to_one_puja', 0)
                ->orWhereNull('is_one_to_one_puja');
        });
      }

      $products->orderBy('order','asc');
      if($request->minimum_quantity == "show"){
        $products->whereRaw("avaibility <= minimum_quantity");
      }
      if($title = $request->title){
        $products->where('title', 'like', "%$title%");
      }
      if($sku = $request->sku){
        $products->where('sku', 'like', "%$sku%");
      }
       if($base_price_from = $request->base_price_from){
         $products->where('base_price', '>=', (int)$base_price_from);
       }
      if($base_price_to = $request->base_price_to){
        $products->where('base_price', '<=', (int)$base_price_to);
      }
      if(isset($request->status) && is_numeric($request->status)) {
         $products->where('status', $request->status);
      }
      if(isset($request->is_best_seller) && is_numeric($request->is_best_seller)) {
         $products->where('is_best_seller', $request->is_best_seller);
      }
      if($category_id = $request->category_id) {
        $c = ProductCategory::where('name', 'like', "%$category_id%")->pluck('id')->toArray();
        $productAndCategoryIds=ProductAndCategoryId::whereIn("category_id",$c)->pluck("product_id")->toArray();
        // $products->whereIn('category_id', $c);
        $products->whereIn('id', $productAndCategoryIds);
      }
      if($updated_at_from = $request->updated_at_from) {
        $products->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to) {
        $products->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $products = $products->paginate(10);
        $data = [
        'rows' => view('admin.product.listrows', ['products' => $products, 'productTypeName' => $productTypeName])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$products])->render(),
      ];
      return response()->json($data, 200);
    }

    public function alldata(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $nav = 'productadd';
      $moduleName = 'Products';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $nav = 'serviceProductadd';
        $moduleName = 'Service Products';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $nav = 'poojaProductadd';
        $moduleName = 'Puja Products';
      }
      $id = $request->id?:0;
      $product = null;
      $categories = [];
      $formFields = Form::orderBy('form_label', 'ASC')->get();
      $hsnCodes = HsnCode::where('status', 1)->get();
      $shipping_based_on = [''=>'Select', '0'=>'Weight', '1'=>'Volume'];

      $breads = [
          ['name'=>$moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
          ['name'=>($id ? 'Edit': 'Add')]
      ];

      $attributes = Attribute::get();
      $p_id = $request->p_id?:0;
      if($p_id){
        $breads = [
            ['name'=>$moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
            ['name'=>'Variation', 'url'=> route('admin.product.variation.index', [$p_id, $productTypeName])],
            ['name'=>($id ? 'Edit': 'Add')]
        ];
        $pAttributes = PAttribute::where('product_id', $p_id)->get();
        $attributes = $pAttributes->map(function($item){
          return $item->attribute;
        });
      }
      // Get astrologers marked as pandit (status=1, is_verified=1, is_pandit=1)
      $pandits = Astrologer::where("status", 1)
        ->where("is_verified", 1)
        ->where("is_pandit", 1)
        ->orderBy('display_name', 'asc')
        ->pluck("display_name", "id")
        ->toArray();
      $pandits[0] = "Select";
      ksort($pandits);

      $deliveryDates = DeliveryDate::pluck("name", "id")->toArray();
      $deliveryDates[0] = "Select";
      ksort($deliveryDates);

      // Valid till multi-date for puja: month/year dropdowns + day grid
      $validTillMonths = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
        7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
      ];
      $currentYear = (int) date('Y');
      $validTillYears = range($currentYear - 2, $currentYear + 5);
      $validTillMonth = null;
      $validTillYear = null;
      $validTillDates = [];

      $apiUrls = ApiUrl::selectRaw("CONCAT(name,' - ',category,' api') AS label, id")->pluck("label", "id")->toArray();
      $apiUrls[0] = "Select";
      ksort($apiUrls);

      $vendors = Vendor::where("status", 1)->orderBy("name")->get();
      $categories = ProductCategory::where("type",$productTypeValue)->pluck('name','id')->toArray();
      // $categories=['' => 'Select']+$categories;
      $selectedCategories = ProductAndCategoryId::where("product_id",$id)->pluck('category_id')->toArray();

      // Get category refund and cancellation settings
      $categoryRefundSettings = null;
      if(!empty($selectedCategories)) {
        // Get first category's settings (or you can check all categories)
        $firstCategory = ProductCategory::whereIn('id', $selectedCategories)->first();
        if($firstCategory) {
          $categoryRefundSettings = [
            'refund_and_cancellation' => $firstCategory->refund_and_cancellation ?? 0,
            'refund_cancellation_days' => $firstCategory->refund_cancellation_days ?? null
          ];
        }
      }

      if($id){
          // Editing a product
          $product = Product::find($id);
          $expire_at = strtotime($product->expire_at);
          $product->expire_at_date = date('Y-m-d', $expire_at);
          $product->expire_at_time = date('H:i', $expire_at);
          // Multi valid-till dates for puja: from valid_till_dates or fallback to expire_at
          if ($productTypeValue == 2 && !$p_id) {
            $dates = $product->valid_till_dates;
            if (!empty($dates) && is_array($dates)) {
              $validTillDates = $dates;
              $first = $dates[0];
              $validTillMonth = (int) date('n', strtotime($first));
              $validTillYear = (int) date('Y', strtotime($first));
            } elseif ($product->expire_at) {
              $validTillDates = [date('Y-m-d', strtotime($product->expire_at))];
              $validTillMonth = (int) date('n', strtotime($product->expire_at));
              $validTillYear = (int) date('Y', strtotime($product->expire_at));
            }
          }
          $img_name = Media::find($product->image_id);
          $product_spec = ProductSpecification::where('product_id', $product->id)->first();
          $productFields = productFormField::where('product_id', $id)->pluck('form_field')->toArray();
          $productFieldsOrder = productFormField::where('product_id', $id)->pluck( 'order','form_field')->toArray();
          // return $productFieldsOrder;
          $medias = Media::where('type', 'product')->orderBy('created_at', 'DESC')->get();
          return view('admin.product.form', compact('productFields','productFieldsOrder','product','breads', 'nav', 'medias', 'product_spec', 'img_name', 'categories', "selectedCategories", 'formFields', 'hsnCodes', 'shipping_based_on', 'attributes', 'p_id', 'productTypeName', 'productTypeValue', 'moduleName', "pandits", "deliveryDates", "apiUrls", "vendors", "categoryRefundSettings", "validTillDates", "validTillMonths", "validTillYears", "validTillMonth", "validTillYear"));
      } else {
          $product = null;
          $validTillMonth = (int) date('n');
          $validTillYear = (int) date('Y');
      }
      $item = Product::all();
      $medias = Media::where('type', 'product')->orderBy('created_at', 'DESC')->get();
      return view('admin.product.form', compact('medias', 'nav','breads', 'categories', "selectedCategories", 'item', 'product', 'formFields', 'hsnCodes', 'shipping_based_on', 'attributes', 'p_id', 'productTypeName', 'productTypeValue', 'moduleName', "pandits", "deliveryDates", "apiUrls", "vendors", "categoryRefundSettings", "validTillDates", "validTillMonths", "validTillYears", "validTillMonth", "validTillYear"));
    }

    public function submit(Request $request){
        $productTypeName = $request->productTypeName ?: "Product";
        $productTypeValue = 1;
        $moduleKey = "product";

        if($productTypeName == "Service"){
          $productTypeValue = 3;
          $moduleKey = "service-product";
        }elseif($productTypeName == "Pooja"){
          $productTypeValue = 2;
          $moduleKey = "pooja-product";
        }

        $id = $request->id ?: 0;
        $p_id = $request->p_id ?: 0;

        if(!$id && !adminCan($moduleKey, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

        if($id && !adminCan($moduleKey, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

    $userid = Auth::guard('admin')->user()->id;

        $validatedData = $request->validate([
    'title' => 'required|max:255',
    'slug' => $p_id == 0 ? "required|max:255|unique:products,slug,$id,id,type,$productTypeValue,parent_id,0" : "nullable",
            'category_id'   => $p_id == 0 ? 'required|array|min:1' : 'nullable|array',
            'category_id.*'   => 'nullable|integer|gt:0',
            'type' => 'required|gt:0',
            'base_price' => 'required|gt:0',
            'image_id' => 'required',
            // 'short_description' => 'required|string',
            'description' => $p_id ? 'nullable|string' : 'required|string',
        ],[
    'title.required' => 'Title is required',
    'slug.required' => 'Slug is required',
          'category_id.required' => 'Category is required',
          'category_id.*' => 'Category is required',
    'type.required' => 'Type is required',
    'base_price.required' => 'Price is required',
    'image_id.required' => 'Image is required',
    'short_description.required' => 'Short Description is required',
    'description.required' => 'Description is required',
    ]);
        if($request->type == 1){
    $this->validate($request, [
            'sku' => $p_id == 0 ? "required|max:255|unique:products,sku,$id,id,type,$productTypeValue,parent_id,0" : "nullable",
            'hsn_id' => 'required|numeric|gt:0',
            'delivery_date_id' => 'required|numeric|gt:0',
    'avaibility' 				=> 'required',
            'shipping_based_on' => 'required',
            // 'weight' => 'required|numeric|gt:0',
            // 'length' => 'required|numeric|gt:0',
            // 'breadth' => 'required|numeric|gt:0',
            // 'height' => 'required|numeric|gt:0',
    ],[
            'sku.required' => 'Sku is required',
            'hsn_id' => 'HSN Code is required',
            'delivery_date_id' => 'Delivery Date is required',
    'avaibility.required' => 'Quantity is required',
    ]);
        }
        if($request->type == 2){
          // For variation (p_id set), category/pandit/expire/place come from parent – no validation needed
          if($p_id == 0){
            if($request->pandit_id){
              $validAstrologer = Astrologer::where('id', $request->pandit_id)
                ->where('status', 1)
                ->where('is_verified', 1)
                ->where('is_pandit', 1)
                ->exists();
              if(!$validAstrologer){
                return response()->json([
                  'errors' => ['pandit_id' => ['Selected astrologer is not valid or not marked as pandit']]
                ], 422);
              }
            }
            $this->validate($request, [
              'pandit_id' => 'required|gt:0',
              'valid_till_dates' => 'required|array|min:1',
              'valid_till_dates.*' => 'required|date_format:Y-m-d',
              'expire_at_time' => 'required',
              'place' => 'required',
            ], [
              "pandit_id" => "Pandit Ji is Required",
              "valid_till_dates.required" => "Select at least one date for Valid Till Date.",
            ]);
          }
        }
        // if($request->type == 3){
        // }

        if($request->sale_price && $request->sale_price > 0){
    $this->validate($request, [
            'base_price' => 'required|gt:sale_price',
    'sale_price_start_date' 				=> 'required|date',
    'sale_price_end_date' 		=> 'required|date|after:sale_price_start_date',
    ],[
            'base_price.gt' => 'Price must be greate than sale price',
    'sale_price_start_date.required' => 'Special Price From Date is required',
    'sale_price_end_date.required' => 'Special Price To Date is required',
    ]);
        }

        if($request->sale_price_start_date || $request->sale_price_end_date ){
    $this->validate($request, [
    'sale_price' 				=> 'required',
    ],[
    'sale_price.required' => 'Special Price is required',
    ]);
        }

        if($request->has_variation){
    $this->validate($request, [
    'atributes' => 'required|array',
    ]);
        }

        if($request->has_variation && $request->atributes){
          $validation = [];
          foreach ($request->atributes as $key => $value){
            $attribute = Attribute::find($value);
            if($attribute){
              $validation['attribute.'.$attribute->key] = 'required';
            }
          }
    $this->validate($request, $validation);
        }

        $productCategories = is_array($request->category_id) ? $request->category_id : [];
        if($p_id) {
            $parentForCat = Product::find($p_id);
            if($parentForCat) {
                $productCategories = ProductAndCategoryId::where('product_id', $parentForCat->id)->pluck('category_id')->toArray();
                if(empty($productCategories)) {
                    $productCategories = $parentForCat->category_id ? [$parentForCat->category_id] : [];
                }
            }
        }

    if($id){
    $product = Product::where('id', $id)->first();
    $product->updated_by = $userid;
          $description = ProductSpecification::where('product_id', $id)->first();
          $description->updated_by = $userid;
    }else {
    $product = new Product();
    $product->created_by = $userid;
          $description = new ProductSpecification();
          $description->created_by = $userid;
    }
        $product->parent_id =  $p_id;

        // If creating a variation product (p_id is set), clone title from parent
        // Slug will be set after product is saved (to include variation ID)
        if($p_id) {
          $parentProduct = Product::find($p_id);
          if($parentProduct) {
            // Clone title from parent product for variation
            $product->title = $parentProduct->title;
            // Slug will be set after save to include parent-id and variation-id
            $product->slug = ''; // Temporary, will be set after save
          } else {
            // Fallback if parent not found
            $product->title = $request->title;
            $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
          }
        } else {
          // For non-variation products, use request values
          $product->title = $request->title;
          $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        }
        $product->sku = $request->sku ?: "dummySku";
        $product->image_id = count($request->image_id) > 0 ? $request->image_id[0] : 0;
        $product->alt_text = count($request->alt_text) > 0 ? $request->alt_text[0] : "";
        $product->category_id = isset($productCategories[0]) ? $productCategories[0] : 0;
        $product->type = $request->type;
        $product->avaibility = $request->avaibility ?: 1;
        $product->base_price = $request->base_price;
        $product->sale_price = $request->sale_price?$request->sale_price:0.00;
        $product->sale_price_start_date = $request->sale_price_start_date?$request->sale_price_start_date:'';
        $product->sale_price_end_date =  $request->sale_price_end_date?$request->sale_price_end_date:'';
        $product->status = $request->status?$request->status:0;
        $product->is_featured = $request->is_featured?$request->is_featured:0;
        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->meta_description = $request->meta_description;
        $product->apply_shipping = $request->apply_shipping?:0;
        $product->is_best_seller = $request->is_best_seller?:0;
        $product->show_in_astrologer_profile = $request->show_in_astrologer_profile ? 1 : 0;
        $product->shipping_based_on = $request->shipping_based_on?:0;
        $product->weight = $request->weight?:0;
        $product->length = $request->length?:0;
        $product->breadth = $request->breadth?:0;
        $product->height = $request->height?:0;
        $hsnCode = HsnCode::find($request->hsn_id);
        $product->hsn_id = $hsnCode ? $hsnCode->id : 0;
        $product->hsn_code = $hsnCode ? $hsnCode->hsn_code : '';
        $product->delivery_date_id = $request->delivery_date_id ?: 0;
        $product->gst_percentage = $hsnCode?$hsnCode->gst_percentage:0;
        $product->has_variation = $request->has_variation?:0;
        $product->min_order_qty = $request->min_order_qty?:1;
        $product->max_order_qty = $request->max_order_qty?:1;
        $product->model_no =  $request->model_no;
        $product->isbn =  $request->isbn;
        $product->sub_type =  $request->sub_type;
        $product->is_one_to_one_puja = $request->is_one_to_one_puja ?: 0;

        // Handle refund and cancellation settings
        if(!$p_id) {
            $productCategories = is_array($request->category_id) ? $request->category_id : [];
        }

        // Save product-level settings (admin override)
        $product->refund_and_cancellation = $request->refund_and_cancellation ? 1 : 0;
        $product->refund_cancellation_days = $request->refund_cancellation_days ? $request->refund_cancellation_days : null;

        $previous_expire_at = $product->expire_at;
        // Build valid_till_dates from calendar multi-select (array of Y-m-d)
        $valid_till_dates = [];
        $current_expire_at = null;
        if ($productTypeValue == 2 && !$p_id && $request->expire_at_time) {
          $dates = is_array($request->valid_till_dates) ? $request->valid_till_dates : [];
          foreach ($dates as $d) {
            $d = trim($d);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
              $valid_till_dates[] = $d;
            }
          }
          $valid_till_dates = array_unique($valid_till_dates);
          sort($valid_till_dates);
          if (!empty($valid_till_dates)) {
            $lastDate = end($valid_till_dates);
            $current_expire_at = $lastDate . ' ' . preg_replace('/^(\d{1,2}:\d{2})(:\d{2})?$/', '$1:00', $request->expire_at_time);
          }
        }

        if($productTypeValue == 2){
          // Variation: always use parent puja's valid till date/time, place, pandit
          $parentIdForPuja = $p_id ?: (isset($product->parent_id) ? $product->parent_id : 0);
          if($parentIdForPuja) {
            $parentPuja = Product::find($parentIdForPuja);
            if($parentPuja) {
              $product->expire_at = $parentPuja->expire_at;
              $product->valid_till_dates = $parentPuja->valid_till_dates;
              $product->place = $parentPuja->place;
              $product->pandit_id = $parentPuja->pandit_id ?: 0;
              $product->pandit_name = $parentPuja->pandit_name ?? '';
            }
          } else {
            $product->expire_at = $current_expire_at;
            $product->valid_till_dates = !empty($valid_till_dates) ? $valid_till_dates : null;
            $product->place = $request->place;
            $astrologer = Astrologer::where('id', $request->pandit_id)
              ->where('status', 1)
              ->where('is_verified', 1)
              ->where('is_pandit', 1)
              ->first();
            $product->pandit_id = $astrologer ? $astrologer->id : 0;
            $product->pandit_name = $astrologer ? $astrologer->display_name : "";
          }
          $product->video_call_url = $request->video_call_url ?? ($p_id ? (Product::find($p_id)->video_call_url ?? '') : ($product->video_call_url ?? ''));
          if($product->is_one_to_one_puja == 1) {
            $product->commission_type = $request->commission_type ?: 'percentage';
            $product->astrologer_commission_percentage = $request->astrologer_commission_percentage ?: 0;
          }
        }

        $product->minimum_quantity =  $request->minimum_quantity ?: 1;
        $product->api_url_id =  $request->api_url_id ?: 0;

        if($request->renew && $current_expire_at && $previous_expire_at != $current_expire_at){
          $product->renew = $product->renew+1;
        }
        $product->vendor_id = $request->vendor_id ?: 0;
        $product->save();
        $product->order = $request->product_order ?: $product->id;
        if($request->type == 2){
          $sku = "POOJA";
          for($i = 1; $i <= $product->renew; $i++){
            $sku .= "R";
          }
          $product->sku =  "$sku$product->id";
        }elseif($request->type == 3){
          $sku = "SERVICE";
          for($i = 1; $i <= $product->renew; $i++){
            $sku .= "R";
          }
          $product->sku =  "$sku$product->id";
        }

        // If this is a variation product, set slug in format: parent-slug-cloned-{parent-id}-{variation-id}
        if($p_id && empty($product->slug)) {
          $parentProduct = Product::find($p_id);
          if($parentProduct && $parentProduct->slug) {
            $product->slug = $parentProduct->slug . '-cloned-' . $p_id . '-' . $product->id;
          } else {
            // Fallback if parent not found or has no slug
            $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
          }
        }

        $product->save();

        // When parent puja's valid till date/time is updated, update all its variations to same expire_at, valid_till_dates, place, pandit
        if($productTypeValue == 2 && !$p_id && $product->parent_id == 0) {
          Product::where('parent_id', $product->id)
            ->update([
              'expire_at' => $product->expire_at,
              'valid_till_dates' => $product->valid_till_dates ? json_encode($product->valid_till_dates) : null,
              'place' => $product->place,
              'pandit_id' => $product->pandit_id ?: 0,
              'pandit_name' => $product->pandit_name ?? '',
            ]);
        }

        // Sync puja_astrologers pivot for puja products (type = 2)
        if($productTypeValue == 2) {
          // Remove existing astrologer links for this puja
          DB::table('puja_astrologers')->where('product_id', $product->id)->delete();

          if($product->pandit_id) {
            // Create/refresh link so puja appears in astrologer Puja tab
            DB::table('puja_astrologers')->insert([
              'astrologer_id' => $product->pandit_id,
              'product_id' => $product->id,
              'priority' => 0,
              'status' => 1,
              'created_at' => now(),
              'updated_at' => now(),
            ]);
          }
        }
        $productCategoryIds=[];
        if(count($productCategories)>0){
          foreach($productCategories as $key => $value){
            $productAndCategoryId=ProductAndCategoryId::where("product_id",$product->id)->where("category_id",$value)->first();
            if(!$productAndCategoryId){
              $productAndCategoryId=new ProductAndCategoryId();
              $productAndCategoryId->product_id=$product->id;
              $productAndCategoryId->category_id=$value;
              $productAndCategoryId->order=1;
              $productAndCategoryId->save();
            }
            $productCategoryIds[]=$productAndCategoryId->category_id;
          }
        }
        ProductAndCategoryId::where("product_id",$product->id)->whereNotIn("category_id",$productCategoryIds)->delete();

        // Video URL (Gallery tab) only for main product – skip for variation to avoid overwriting/deleting
        if(!$p_id){
          if($request->video_url){
            $returnArray = videoDetails($request->video_url);
            if($returnArray['video_id'] && $returnArray['description']){
              $productVideo = ProductVideo::where('product_id', $product->id)->first();
              if(!$productVideo){
                $productVideo = new ProductVideo();
              }
              $productVideo->product_id = $product->id;
              $productVideo->video_url = $request->video_url;
              $productVideo->video_id = $returnArray['video_id'];
              $productVideo->video_hash = '';
              $productVideo->title = $returnArray['title'];
              $productVideo->description = $returnArray['description'];
              $productVideo->image = $returnArray['image'];
              $productVideo->save();
            }
          }else{
            $productVideo = ProductVideo::where('product_id', $product->id)->delete();
          }
        }


        $description->product_id = $product->id;
        if (!$p_id) {
            $description->short_description = $request->short_description;
            $description->description = $request->description;
        }
        $description->save();

        ProductFormField::where('product_id', $product->id)->delete();
        if(isset($request->form_field[0]) && $request->form_field[0]){
          foreach($request->form_field as $key => $field){
            $formField = new ProductFormField();
            $formField->product_id = $product->id;
            $formField->form_field = $field;
            $formField->order = isset($request->order[$field]) ? $request->order[$field] : 1;
            $formField->save();
          }
        }elseif($request->api_url_id > 0){
          $apiUrl = ApiUrl::find($request->api_url_id);
          if($apiUrl != null){
            $request_body_params = json_decode($apiUrl->request_body_params, true);
            // dd($request_body_params);
            foreach($request_body_params as $index => $item){
              $label = ucwords(str_replace("_", " ", $item["name"]));
              $form = Form::where("form_label", $label)->first();
              if($form == null){
                $form = new Form();
                $form->form_label = $label;
                $form->form_type = $item["type"];
                $form->isrequired = $item["required"] ? 1 : 0;
                $form->save();
              }
              $formField = new ProductFormField();
              $formField->product_id = $product->id;
              $formField->form_field = $form->id;
              $formField->order = 1;
              $formField->save();
            }
            $form = Form::where("form_label", "Language")->first();
            if($form){
              $formField = new ProductFormField();
              $formField->product_id = $product->id;
              $formField->form_field = $form->id;
              $formField->order = 1;
              $formField->save();
            }
          }
        }elseif($productTypeValue == 2){
          // Puja: form fields section hidden – save Name with order 1 for new/updated puja
          $nameForm = Form::where('form_label', 'Name')->first();
          if($nameForm){
            $formField = new ProductFormField();
            $formField->product_id = $product->id;
            $formField->form_field = $nameForm->id;
            $formField->order = 1;
            $formField->save();
          }
        }

        if(isset($request->image_id[0]) && $request->image_id[0]){
          ProductGallery::where('product_id', $product->id)->delete();
          foreach($request->image_id as $key => $image_id){
            if($image_id){
              $pImage = new ProductGallery();
              $pImage->product_id = $product->id;
              $pImage->image_id = $image_id;
              $pImage->alt_text = isset($request->alt_text[$key]) && $request->alt_text[$key] ? $request->alt_text[$key] : "";
              $pImage->save();
            }
          }
        }

        if(isset($request->heading[0]) && $request->heading[0]){
          Specification::where('item_id', $product->id)->where('type', 'specification')->delete();
          foreach($request->heading as $key => $item){
            $specification = new Specification();
            $specification->item_id = $product->id;
            $specification->icon_id = 0;
            $specification->heading = $item;
            $specification->text = $request->text[$key];
            $specification->item_type = 'shop';
            $specification->type = 'specification';
            $specification->save();
          }
        }

        if(isset($request->f_heading[0]) && $request->f_heading[0]){
          Specification::where('item_id', $product->id)->where('type', 'feature')->delete();
          foreach($request->f_heading as $key => $item){
            $specification = new Specification();
            $specification->item_id = $product->id;
            $specification->icon_id = $request->icon_f_id[$key];
            // $specification->icon_id = 0;
            $specification->f_heading = $item;
            $specification->text = '';
            $specification->item_type = 'shop';
            $specification->type = 'feature';
            $specification->save();
          }
        }

        if(is_array($request->tags)){
          $tagIdArray = [];
          foreach($request->tags as $index => $value){
            $tag = Tag::where('id', $value)->first();
            if(!$tag){
              $tag = new Tag();
              $tag->slug =str_slug($value);
              $tag->name = $value;
              $tag->save();
            }
            $value = $tag->id;
            $productTag = ProductTag::where('product_id', $product->id)->where('tag_id', $value)->first();
            if(!$productTag){
              $productTag = new ProductTag();
              $productTag->product_id = $product->id;
              $productTag->tag_id = $value;
              $productTag->save();
            }
            $tagIdArray[] = $value;
          }
          ProductTag::where('product_id', $product->id)->whereNotIn('tag_id', $tagIdArray)->delete();
        }

        // dd($request->attribute);

        $product_idd = $product->id;
        if($product->parent_id){
          $product_idd = $product->parent_id;
        }


        $request->atributes = $request->atributes?:[];
        $attributes = Attribute::get();

        if($request->has_variation){
          foreach ($request->attribute as $key => $value){
            $attribute = $attributes->where('key', $key)->first();
            if($value && in_array($attribute->id, $request->atributes)){
              $productAttribute = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $attribute->id)->first();
              if(!$productAttribute){
                $productAttribute = new ProductAttribute();
                $productAttribute->product_id = $product->id;
                $productAttribute->attribute_id = $attribute->id;
              }
              $productAttribute->parent_id = $product_idd;
              $productAttribute->attribute_value = $value;
              $productAttribute->save();

              $pAttribute = PAttribute::where('product_id', $product_idd)->where('attribute_id', $attribute->id)->first();
              if(!$pAttribute){
                $pAttribute = new PAttribute();
                $pAttribute->product_id = $product_idd;
                $pAttribute->attribute_id = $attribute->id;
                $pAttribute->save();
              }
            }else{
              // ProductAttribute::where('product_id', $product->id)->where('attribute_id', $attribute->id)->delete();
              ProductAttribute::where('parent_id', $product_idd)->where('attribute_id', $attribute->id)->delete();
              PAttribute::where('product_id', $product_idd)->whereNotIn('attribute_id', $request->atributes)->delete();
            }
          }
        }else{
          $p_ids = Product::where('parent_id', $product_idd)->pluck('id')->toArray();
          Product::whereIn('id',$p_ids)->delete();
          array_push($p_ids, $product->id);
          ProductAttribute::whereIn('product_id',$p_ids)->delete();
          PAttribute::whereIn('product_id',$p_ids)->delete();
          PAttributeValue::whereIn('product_id',$p_ids)->delete();
        }


        $productIdsArray = [];
        if($product->parent_id==0){
          $productIdsArray = Product::where('parent_id', $product->id)->pluck('id')->toArray();
          array_push($productIdsArray, $product->id);
        }else{
          $productIdsArray = Product::where('parent_id', $product->parent_id)->pluck('id')->toArray();
          array_push($productIdsArray, $product->parent_id);
        }
        $pAttributes = PAttribute::where('product_id', $product_idd)->get();
        PAttributeValue::where('product_id', $product_idd)->delete();
        foreach ($pAttributes as $key => $value){
          $productAttributes = ProductAttribute::whereIn('product_id', $productIdsArray)->where('attribute_id', $value->attribute_id)->get();
          foreach ($productAttributes as $key1 => $value1) {
            $pAttributeValue = new PAttributeValue();
            $pAttributeValue->product_id = $product_idd;
            $pAttributeValue->p_attribute_id = $value->id;
            $pAttributeValue->value = $value1->attribute_value;
            $pAttributeValue->save();
          }
        }
        $redirectTo = route('admin.product.edit', [$product->id, $productTypeName]);
        if($p_id){
          $redirectTo = route('admin.product.variation.edit', [$product->id, $p_id, $productTypeName]);
        }
    return response()->json(['message' => 'Product Saved Successfully', 'redirectto' => $redirectTo], 200);
    }

    public function delete(Request $request){
      $product_id = $request->id;
      $product = Product::find($product_id);
      if($product){
        $productTypeName = "Product";
        $productTypeValue = 1;
        $moduleKey = "product";
        if($product->type == 3){
          $productTypeName = "Service";
          $productTypeValue = 3;
          $moduleKey = "service-product";
        }elseif($product->type == 2){
          $productTypeName = "Pooja";
          $productTypeValue = 2;
          $moduleKey = "pooja-product";
        }
        if(!adminCan($moduleKey, 'delete')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $lang = $request->lang;
        $product->delete();
        ProductAttribute::where('product_id',$product_id)->delete();
        PAttribute::where('product_id',$product_id)->delete();
        PAttributeValue::where('product_id',$product_id)->delete();
        $parent_id = $product_id;
        if($product->parent_id){
          $parent_id = $product->parent_id;
        }
        $parent_product = Product::where('id', $parent_id)->first();
        if($parent_product && $parent_product->variations->count()==0){
          ProductAttribute::where('product_id',$parent_product->id)->delete();
          PAttribute::where('product_id',$parent_product->id)->delete();
          PAttributeValue::where('product_id',$parent_product->id)->delete();
          $parent_product->has_variation = 0;
          $parent_product->save();
        }
        return response()->json(['message'=>'Product Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request){
      $id = $request->id;
      $product = Product::find($id);
        if($product){
          $productTypeName = "Product";
          $productTypeValue = 1;
          $moduleKey = "product";
          if($product->type == 3){
            $productTypeName = "Service";
            $productTypeValue = 3;
            $moduleKey = "service-product";
          }elseif($product->type == 2){
            $productTypeName = "Pooja";
            $productTypeValue = 2;
            $moduleKey = "pooja-product";
          }
          if(!adminCan($moduleKey, 'add')){
            return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
          }
          $nproduct = $product->replicate();
          $nproduct->status = 0;
          $nproduct->is_featured = 0;
          $nproduct->save();
          if($request->has('type') && $request->type == 'parent'){
            $nproduct->parent_id = $product->id;
          }
          $nproduct->save();

          $ProductSpecification = ProductSpecification::where('product_id', $id)->get();
          foreach($ProductSpecification as $s){
            $ns = $s->replicate();
            $ns->product_id = $nproduct->id;
            $ns->save();
          }

          $ProductFormField = ProductFormField::where('product_id', $id)->get();
          foreach($ProductFormField as $s){
            $ns = $s->replicate();
            $ns->product_id = $nproduct->id;
            $ns->save();
          }

          $productGallery = ProductGallery::where('product_id', $id)->get();
          foreach($productGallery as $s){
            $ns = $s->replicate();
            $ns->product_id = $nproduct->id;
            $ns->save();
          }

          $productVideo = ProductVideo::where('product_id', $id)->get();
          foreach($productVideo as $pV){
            $newProductVideo = $pV->replicate();
            $newProductVideo->product_id = $nproduct->id;
            $newProductVideo->save();
          }

          $specification = Specification::where('item_id', $id)->where('item_type', 'shop')->where('type', 'specification')->get();
          foreach($specification as $s){
            $ns = $s->replicate();
            $ns->item_id = $nproduct->id;
            $ns->save();
          }

          $specification = Specification::where('item_id', $id)->where('item_type', 'shop')->where('type', 'feature')->get();
          foreach($specification as $s){
            $ns = $s->replicate();
            $ns->item_id = $nproduct->id;
            $ns->save();
          }

          $productTags = ProductTag::where('product_id', $id)->get();
          foreach($productTags as $productTag){
            $newProductTag = $productTag->replicate();
            $newProductTag->product_id = $nproduct->id;
            $newProductTag->save();
          }

          // $productAttributes = ProductAttribute::where('product_id', $id)->get();
          // foreach($productAttributes as $productAttribute){
          //   $newProductAttribute = $productAttribute->replicate();
          //   $newProductAttribute->product_id = $nproduct->id;
          //   $newProductAttribute->save();
          //   $newProductAttribute->attribute_value = $newProductAttribute->attribute_value.' Cloned';
          //   $newProductAttribute->save();
          // }
          // if($nproduct->parent_id==0){
          //   $pAttributes = PAttribute::where('product_id', $id)->get();
          //   foreach($pAttributes as $pAttribute){
          //     $newPAttribute = $pAttribute->replicate();
          //     $newPAttribute->product_id = $nproduct->id;
          //     $newPAttribute->save();
          //   }
          // }
          return response()->json(['message'=>'Product Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
    }

    public function getTags(Request $request){
      $tags = Tag::where('name', 'like', "%$request->tag%")->get(['id', 'name'])->map(function($tag){
        return [
          'id'=>$tag->id,
          'text'=>$tag->name,
        ];
      });
      return response()->json(['items'=>$tags], 200);
    }

    public function review(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleName = 'Products';
      $moduleKey = 'product-review';
      $nav = 'productReview';
      $statusName = $request->status ?: "";
      $status = -1;
      if($statusName == "inactive"){
        $status = 0;
        $nav = 'productReviewpending';
      }
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $moduleName = 'Service Products';
        $moduleKey = 'service-product-review';
        $nav = 'serviceProductReview';
        if($statusName == "inactive"){
          $status = 0;
          $nav = 'serviceProductReviewpending';
        }
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $moduleName = 'Puja Products';
        $moduleKey = 'pooja-product-review';
        $nav = 'poojaProductReview';
        if($statusName == "inactive"){
          $status = 0;
          $nav = 'poojaProductReviewpending';
        }
      }
      if(!adminCan($moduleKey, 'view')) return dd('No Permission');
      $breads = [
        ['name' => $moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
        ['name' => 'Review', 'url'=> route('admin.product.review', ["productTypeName" => $productTypeName])],
      ];
      return view('admin.productreview.list', compact('nav', 'breads', "moduleName", "moduleKey", "productTypeName", "status"));
    }

    public function reviewAll(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleKey = 'product-review';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $moduleKey = 'service-product-review';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $moduleKey = 'pooja-product-review';
      }
      $reviews = ProductReview::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc')
        ->whereHas("products", function ($query) use ($productTypeValue){
          $query->where("type", $productTypeValue);
        });
       if($review = $request->review) {
           $reviews->where('review', 'like', "%$review%");
       }
       if($product_id = $request->product_id) {
         $products = Product::where('title', 'like', "%$product_id%")->pluck('id')->toArray();
        $reviews->whereIn('product_id', $products);
       }
      if($review_by = $request->review_by) {
        $users = User::where('name', 'like', "%$review_by%")->pluck('id')->toArray();
        $reviews->whereIn('review_by', $$review_by);
      }
       if($rating_from = $request->rating_from) {
           $reviews->where('rating', '>=', (int)$rating_from);
       }
      if($rating_to = $request->rating_to) {
          $reviews->where('rating', '<=', (int)$rating_to);
      }
       if(isset($request->status) && is_numeric($request->status)){
          $reviews->where('status', $request->status);
       }
      if($updated_at_from = $request->updated_at_from) {
          $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to) {
          $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $reviews = $reviews->paginate(10);
      $data = [
          'rows' => view('admin.productreview.listrows',['results'=>$reviews, "productTypeName" => $productTypeName, 'moduleKey' => $moduleKey])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$reviews])->render(),
      ];
      return response()->json($data, 200);
    }

    public function edit(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleName = 'Products';
      $moduleKey = 'product-review';
      $nav = 'productReviewadd';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $moduleName = 'Service Products';
        $moduleKey = 'service-product-review';
        $nav = 'serviceProductReviewadd';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $moduleName = 'Puja Products';
        $moduleKey = 'pooja-product-review';
        $nav = 'poojaProductReviewadd';
      }
      $id = $request->id;
      $breads = [
        ['name' => $moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
        ['name' => 'Review', 'url'=> route('admin.product.review', ["productTypeName" => $productTypeName])],
        ['name' => ( $id ? "Edit" : "Add")]
      ];
      $review = ProductReview::find($id);
      return view('admin.productreview.form', compact('review','nav', 'breads'));
    }

    public function reviewSubmit(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleKey = 'product-review';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $moduleName = 'Service Products';
        $moduleKey = 'service-product-review';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $moduleName = 'Puja Products';
        $moduleKey = 'pooja-product-review';
      }
      $id = $request->id;
      if(!$id && !adminCan($moduleKey, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($moduleKey, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $this->validate($request, [
        'comments' => 'required|string',
        'status' => 'required',
        'id' => 'required|exists:product_reviews,id'
      ]);
      $review = ProductReview::find($id);
      if($review){
        $review->review = $request->comments;
        $review->status = $request->status;
        $review->save();
      }
      return response()->json(['success' => true, 'message' => 'Review Submitted Successfully!', 'redirectto' => route('admin.product.review.edit', [$review->id, "productTypeName" => $productTypeName])]);
    }

    public function exportCsv(Request $request){
      $time_limit = $request->time_limit;
      $product_id = $request->product_id;

      $product = Product::find($product_id);

      $product_types = ["", "Product", "Pooja", "Service"];
      $product_type = $product_types[$product->type];
      $sku = $product->sku;
      $order_items = [];

      if($time_limit == "all"){
        $order_items = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status', 'success')
        ->where('order_items.product_id', $product_id)
        ->select('order_items.*', 'orders.order_number')
        ->get();
      }elseif($time_limit == "current"){
        $order_items = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status', 'success')
        ->where('order_items.product_id', $product_id)
        ->select('order_items.*', 'orders.order_number')
        ->get();
      }

      $dataArray = [];
      $labelsArray = ["Order ID", "Type"];
      $labelsKeyArray = [];

      foreach($order_items as $key => $value){
        $item["order_number"] = $value->order_number;
        $item["detail"] = [];
        $labels = [];
        $options = json_decode($value->options, true);

        if(count($options) > 0 && isset($options["formdata"]) && count($options["formdata"]) > 0){
          $formData = $options["formdata"];

          foreach($formData as $index => $formDataItem){
            if(isset($labels[$formDataItem["name"]])){
              $labels[ $formDataItem["name"] ]++;
              $item[ "detail" ][ $labels[ $formDataItem["name"] ] ][ $formDataItem["name"] ] = $formDataItem["value"];
            }else{
              if(!in_array($formDataItem["name"], $labelsArray)){
                $labelsArray[] = $formDataItem["label"];
                $labelsKeyArray[] = $formDataItem["name"];
              }
              $labels[ $formDataItem["name"] ] = 0;
              $item["detail"][0][$formDataItem["name"]] = $formDataItem["value"];
            }
          }

        }
        $dataArray[] = $item;
      }

      // return response()->json([
      //   'success'=>true,
      //   'labelsArray'=> $labelsArray,
      //   'dataArray'=> $dataArray,
      // ], 200);

      $filePath = public_path('csvs/product/orders/list.csv');

      $handle = fopen($filePath, 'w');

      fputcsv($handle, $labelsArray);
      // Add rows
      foreach ($dataArray as $dataIndex => $dataItem){
        $row = 1;
        if(count($dataItem["detail"]) > 0){
          foreach ($dataItem["detail"] as $dataItemDetailIndex => $dataItemDetailItem){
            if($row == 1){
              $itemData = [$dataItem["order_number"], $product_type];
              foreach($labelsKeyArray as $labelsKeyIndex => $labelsKeyItem){
                if(isset($dataItemDetailItem[$labelsKeyItem])){
                  $itemData[] = $dataItemDetailItem[$labelsKeyItem];
                }
              }
              fputcsv($handle, $itemData);
            }else{
              $itemData = ["", ""];
              foreach($labelsKeyArray as $labelsKeyIndex => $labelsKeyItem){
                if(isset($dataItemDetailItem[$labelsKeyItem])){
                  $itemData[] = $dataItemDetailItem[$labelsKeyItem];
                }
              }
              fputcsv($handle, $itemData);
            }
            $row++;
          }
        }else{
          fputcsv($handle, [$dataItem["order_number"], $product_type]);
        }
      }

      fclose($handle);

      $message = "Data Export Successfully";
      $redirectTo = url("csvs/product/orders/list.csv");

      return response()->json([
        'success'=>true,
        'message'=> $message,
        'redirectTo'=> $redirectTo
      ], 200);
    }

    public function exportListCsv(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      if($productTypeName == "Service"){
        $productTypeValue = 3;
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
      }
      $p_id = $request->p_id ?: 0;
      $products = Product::where('parent_id', $p_id)->where("type", $productTypeValue);

      // Exclude one-to-one puja products from regular puja products listing
      if($productTypeValue == 2) {
        $products->where(function($query) {
          $query->where('is_one_to_one_puja', 0)
                ->orWhereNull('is_one_to_one_puja');
        });
      }

      $products->orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
      if($request->minimum_quantity == "show"){
        $products->whereRaw("avaibility <= minimum_quantity");
      }
      if($title = $request->title){
        $products->where('title', 'like', "%$title%");
      }
      if($sku = $request->sku){
        $products->where('sku', 'like', "%$sku%");
      }
       if($base_price_from = $request->base_price_from){
         $products->where('base_price', '>=', (int)$base_price_from);
       }
      if($base_price_to = $request->base_price_to){
        $products->where('base_price', '<=', (int)$base_price_to);
      }
      if(isset($request->status) && is_numeric($request->status)) {
         $products->where('status', $request->status);
      }
      if(isset($request->is_best_seller) && is_numeric($request->is_best_seller)) {
         $products->where('is_best_seller', $request->is_best_seller);
      }
      if($category_id = $request->category_id) {
        $c = ProductCategory::where('name', 'like', "%$category_id%")->pluck('id')->toArray();
        $products->whereIn('category_id', $c);
      }
      if($updated_at_from = $request->updated_at_from) {
        $products->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to) {
        $products->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }

      $results = $products->get();

      $filePath = public_path('csvs/product/list.csv');

      $handle = fopen($filePath, 'w');

      if($productTypeName == "Product"){
        fputcsv($handle, ["SKU", "Type", "Name", "Quantity", "Minumum Quanity", "Status", "Created at"]);
        foreach ($results as $index => $item){
          fputcsv($handle, [
            $item->sku,
            $productTypeName,
            $item->title,
            $item->avaibility,
            $item->minimum_quantity,
            $item->status ? "Active" : "Inactive",
            $item->created_at
          ]);
        }
      }elseif($productTypeName == "Pooja"){
        fputcsv($handle, ["SKU", "Type", "Name", "Status", "Created at"]);
        foreach ($results as $index => $item){
          fputcsv($handle, [
            $item->sku,
            $productTypeName,
            $item->title,
            $item->status ? "Active" : "Inactive",
            $item->created_at
          ]);
        }
      }else{
        fputcsv($handle, ["SKU", "Type", "Name", "Status", "Created at"]);
        foreach ($results as $index => $item){
          fputcsv($handle, [
            $item->sku,
            $productTypeName,
            $item->title,
            $item->status ? "Active" : "Inactive",
            $item->created_at
          ]);
        }
      }

      fclose($handle);

      $message = "Data Export Successfully";
      $redirectTo = url("csvs/product/list.csv");

      return response()->json([
        'success'=>true,
        'message'=> $message,
        'redirectTo'=> $redirectTo,
      ], 200);
    }
    public function handleChangeOrder(Request $request){
      $product_id=$request->product_id?:0;
      $order=$request->order?:0;
      $product=Product::find($product_id);
      if($product){
        $product->order=$order;
        $product->save();
      }
      return response()->json(["success"=>true,"message"=>"Order Change Successfully."],200);

    }

}
