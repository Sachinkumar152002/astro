<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{ProductCategory,ProductAttribute, ProductVideo};
use App\{Product, ProductReview, User, CartItem};
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;
use DB;

class ProductController extends Controller
{
  public function categories(Request $request){
    $request->validate([
      "type" => "required"
    ]);
    $type = $request->type ?: "product";
    $productTypeValue = 1;
    if($type == "product"){
      $productTypeValue = 1;
    }elseif($type == "pooja"){
      $productTypeValue = 2;
    }elseif($type == "service"){
      $productTypeValue = 3;
    }
    $results = ProductCategory::select('product_categories.*')
    ->join('products', 'products.category_id', '=', 'product_categories.id')
    ->where('product_categories.type', $productTypeValue)
    ->where('product_categories.status', 1)
    ->groupBy('product_categories.id')
    ->orderBy('name', 'asc')
    ->get();
    $data = $results->map(function($item){
      return [
        'id' => $item->id,
        'slug' => $item->slug,
        'name' => $item->name,
        'image' => $item->image?$item->image->image(100):no_image(),
      ];
    });
     return response()->json([
       'success' => true,
        'data' => $data,
     ], 200);
  }

  public function types(Request $request){
    $data = [
      [
        'slug'=>'product',
        'name'=>'Product',
      ],
      [
        'slug'=>'pooja',
        'name'=>'Pooja',
      ],
      [
        'slug'=>'service',
        'name'=>'Service',
      ],
    ];
     return response()->json([
       'success' => true,
       'data' => $data
     ], 200);
  }

  public function sortBy(Request $request){
    $sortByData = [
      [
        'slug'=>'a_to_z',
        'name'=>"A To Z",
      ],
      [
        'slug'=>'whats_new',
        'name'=>"What's New",
      ],
      [
        'slug'=>'price_low_to_high',
        'name'=>'Price Low To High',
      ],
      [
        'slug'=>'price_high_to_low',
        'name'=>'Price High To Low',
      ],
    ];
     return response()->json([
       'success' => true,
       'data' => $sortByData
     ], 200);
  }

  public function list(Request $request){
    $request->validate([
      "type" => "required"
    ]);
    $type = $request->type ?: 'product';
    $productTypeValue = 1;
    if($type=='pooja'){
      $productTypeValue = 2;
    }elseif($type=='service'){
      $productTypeValue = 3;
    }
    $exceptId = $request->except_id ?: ''; //id
    $categoryId = $request->category_id ?: 0;
    $sortBy = $request->sortBy ?: 'a_to_z';
    $maxPrice = $minPrice = $priceFrom = $priceTo = 0;
    $page = $request->page ?: 1;
    $limit = $request->limit ?: 9;
    $products = Product::where('status', 1)->where('parent_id', 0)->where("type", $productTypeValue)->where('id', '!=', $exceptId);

    // Filter one-to-one puja based on request
    if($productTypeValue == 2){
      if(isset($request->is_one_to_one_puja) && $request->is_one_to_one_puja == 1){
        // Show only one-to-one puja
        $products->where('is_one_to_one_puja', 1);
      }else{
        // Exclude one-to-one puja from regular puja listing
        $products->where(function($query) {
          $query->where('is_one_to_one_puja', 0)
                ->orWhereNull('is_one_to_one_puja');
        });
      }
    }

    if($categoryId > 0){
      $products->where('category_id', $categoryId);
    }else{
      $categoryIds = ProductCategory::where('status', 1)->where("type", $productTypeValue)->pluck("id")->toArray();
      $products->whereIn('category_id', $categoryIds);
    }
    $minPrice = $products->min(DB::raw('IF(sale_price_start_date <= CURDATE() AND sale_price_end_date >= CURDATE(), sale_price, base_price)')) ?: 0;
    $maxPrice = $products->max(DB::raw('IF(sale_price_start_date <= CURDATE() AND sale_price_end_date >= CURDATE(), sale_price, base_price)')) ?: 0;

    $date = date('Y-m-d');
    if($productTypeValue == 2){
      $products->where("expire_at", ">=", $date);
    }

    if($request->priceFrom > 0){
      $products->whereRaw('IF(sale_price_start_date <= CURDATE() AND sale_price_end_date >= CURDATE(), sale_price, base_price) >= ?', [$request->priceFrom]);
    }
    if($request->priceTo > 0){
      $products->whereRaw('IF(sale_price_start_date <= CURDATE() AND sale_price_end_date >= CURDATE(), sale_price, base_price) <= ?', [$request->priceTo]);
    }

    if($sortBy == 'whats_new'){
      $products->orderBy('created_at', 'DESC');
    }elseif($sortBy == 'price_high_to_low'){
      $products->orderByRaw('price_order DESC');
    }elseif($sortBy == 'price_low_to_high'){
      $products->orderByRaw('price_order ASC');
    }elseif($sortBy == 'a_to_z'){
      $products->orderBy('title', 'ASC');
    }

    $count = $products;
    $count = $count->count();
    // return response()->json(['results'=>$products->toSql()]);
    $results = $products->selectRaw('*, IF(sale_price_start_date IS NOT NULL AND sale_price_start_date <= CURDATE() AND sale_price_end_date >= CURDATE(), sale_price, base_price) as price_order')->paginate($limit);


    $data = $results->map(function($value) use ($type, $date, $productTypeValue){
      $sale_price = 0;
      $special_price_start_date = $value->sale_price_start_date;
      $special_price_end_date = $value->sale_price_end_date;
      if($special_price_start_date && ($special_price_start_date <= $date) && ($special_price_end_date >= $date)){
        $sale_price = $value->sale_price;
      }
      $add_to_cart = false;
      if($value->type == 1){
        $add_to_cart = true;
      }
      $image = $value->image;
      $expire_at = $value->expire_at;
      $expireAtSeconds = strtotime($expire_at);
      $pandit = $value->pandit;
      $can_purchase = true;
      if($productTypeValue == 1 && $value->avaibility <= 0){
        $can_purchase = false;
      }elseif($productTypeValue == 2 && $expireAtSeconds <=  strtotime("now")){
        $can_purchase = false;
      }
      return [
        'type'=>$type,
        'id'=>$value->id,
        'slug'=>$value->slug,
        'name'=>$value->title,
        'image'=>$image ? $image->image(400, 400) : no_image(),
        'base_price'=>$value->base_price,
        'sale_price'=>$sale_price,
        'can_add_to_cart'=>$add_to_cart,
        'can_purchase'=>$can_purchase,
        'expire_at'=>$expire_at && $expire_at != '1970-01-01 05:30:00' ? date('d-m-Y h:i A', $expireAtSeconds) : '',
        'pandit'=>$pandit ? $pandit->name : "",
        'place'=>$value->place,
        'stock_quantity'=>$value->avaibility,
      ];
    });

     return response()->json([
       'success' => true,
        'data' => [
          'category_id'=>$request->category,
          'minPrice'=>$minPrice,
          'maxPrice'=>$maxPrice,
          'current_page' => $results->currentPage(),
          'total' => $results->total(),
          'first_page_url' => url('/api/product/list'),
          'last_page_url' => $results->url($results->lastPage()),
          'prev_page_url' => $results->previousPageUrl(),
          'next_page_url' => $results->nextPageUrl(),
          'per_page' => $results->perPage(),
          'products'=>$data,
        ],
        'message' => 'Products'
     ], 200);
  }

  public function detail(Request $request){
  // types:
  // product 1
  // pooja 2
  // service 3
    $id = $request->id?:0;
    $api_token = $request->api_token?:'';
    $parent_id=$request->parent_id?:0;
    $attributes_value=$request->attributes_value?:[];
    if($id == 0 && $parent_id > 0 && count($attributes_value)){
      $productIds=ProductAttribute::where('parent_id',$parent_id)
      ->where(function($query) use($attributes_value){
        foreach($attributes_value as $key=>$value){
          if($value){
            $query->orWhere('attribute_value',$value);
          }
        }
      })
      ->pluck('product_id')->toArray();
      if(count($productIds)>1){
        $temArray=[];
        for($i=0;$i<count($productIds);$i++){
          if(in_array($productIds[$i], $temArray)){
            $id=$productIds[$i];
            break;
          }else{
            $temArray[]=$productIds[$i];
          }
        }
      }elseif(count($productIds)==1){
        $id=$productIds[0];
      }
    }
    $product = Product::find($id);
    if(!$product){
      return response()->json([
          'errors' => "not found.",
          'status' => 402,
      ], 402);
    }
    $user = User::where('api_token', $api_token)->first();
    $user_type = 'user';
    $cartItem = null;
    if($user){
      $cartItem = CartItem::where('item_id', $product->id)->where('type', 'shop')->where('user_id', $user->id)->where('user_type', $user_type)->first();
    }else{
      $cartItem = CartItem::where('item_id', $product->id)->where('type', 'shop')->where('user_id', $api_token)->where('user_type', 'guest')->first();
    }
    $can_add_more = true;
    $show_form = false;
    $type = 'Product';
    $forms = [];
    $forms['fields'] = [];
    $item_type = (int) $product->type;
    if($item_type != 1){
      $can_add_more = false;
      $type = "Service";
      if($item_type == 2){
        $type = "Pooja";
      }
      $formFields = $product->fields->sortBy('order');
      $formFieldsCount=$formFields->count();
      if($formFieldsCount > 0){
        $show_form = true;

        for($i = 0; $i < $product->max_order_qty; $i++){
          foreach($formFields as $key => $value){
            if($field=$value->field){
              $required = $field->isrequired;
              if($i > $product->min_order_qty){
                $required = 0;
              }
              $item = [
                'type'=>$field->form_type,
                'key'=>Str::slug($field->form_label),
                'label'=>str_replace(["P1", "P2"], ["Boy", "Girl"], $field->form_label),
                'required'=>$required?true:false,
              ];
              $forms["fields"][] = $item;
            }
          }
        }

        // $x=0;
        // foreach($formFields as $key => $value){
        //   if($field=$value->field){
        //     $item = [
        //       'type'=>$field->form_type,
        //       'key'=>Str::slug($field->form_label),
        //       'label'=>str_replace(["P1", "P2"], ["Boy", "Girl"], $field->form_label),
        //       'required'=>$field->isrequired?true:false,
        //     ];
        //     $x=$key;
        //     for($i = 0; $i < $product->max_order_qty; $i++){
        //       $forms["fields"][$x]=$item;
        //       $x+=$formFieldsCount;
        //     }
        //   }
        // }
        // ksort($forms["fields"]);
      }
    }
    $galleries = [];
    $panditji = [
      "id" => 0,
      "name" => "",
      "image" => "",
    ];
    $pandit = $product->pandit;
    if($pandit){
      $panditImage = $pandit->image;
      $panditji = [
        "id" => $pandit->id,
        "name" => $pandit->name,
        "image" => $panditImage ? $panditImage->image() : no_image(),
      ];
    }
    // $forms['fields'] = [];
    foreach($product->productGall as $key => $value){
      if($image=$value->image){
        array_push($galleries, $image->image(100,100));
      }
    }
    $features = [];
    foreach($product->productFeatures as $key => $value){
      $image = $value->image;
      $item = [
        'image'=>$image?$image->image(20,20):no_image(),
        'heading'=>$value->heading,
      ];
      array_push($features, $item);
    }
    $specifications = [];
    foreach($product->productSpec as $key => $value){
      $item = [
        'heading'=>$value->heading,
        'text'=>$value->text,
      ];
      array_push($specifications, $item);
    }
    $has_variation=$product->has_variation?true:false;
    $parent_id=$product->parent_id?:$product->id;
    $attributes=[
      "Color"=>[],
      "Size"=>[],
      "Weight"=>[],
      "Type"=>[],
    ];
    $attribute=[
      "Color"=>"",
      "Size"=>"",
      "Weight"=>"",
      "Type"=>"",
    ];
    if($has_variation){
      $attributes=ProductAttribute::select('attributes.*')->join('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')->where('product_attributes.product_id',$parent_id)->pluck('attributes.id', 'attributes.name')->toArray();
      foreach($attributes as $key => $value){
        $attributesValue=ProductAttribute::where('parent_id',$parent_id)
        ->where('attribute_id',$value)
        ->groupBy('attribute_value')
        ->pluck('attribute_value')->toArray();
        $attributes[$key]=$attributesValue;
        $attributesValue=ProductAttribute::where('product_id', $id)->where('attribute_id', $value)->first();
        $attribute[$key]=$attributesValue->attribute_value;
      }
      if(count($attributes)>1){
        foreach($attribute as $key=>$value){
          foreach($attributes as $keyTwo=>$valueTwo){
            if($key!=$keyTwo && $value){
              $valueThree=[];
              foreach($valueTwo as $index=>$item){
                $productAttribute=ProductAttribute::select('product_attributes.*')
                ->join('products', 'products.id', '=', 'product_attributes.product_id')
                ->where('products.status',1)
                ->where('products.avaibility','>',0)
                ->where('product_attributes.parent_id',$parent_id)
                ->where(function($query) use($value, $item){
                  $query->where('product_attributes.attribute_value',$value)->orWhere('product_attributes.attribute_value',$item);
                })
                ->groupBy('product_attributes.product_id')
                ->havingRaw('COUNT(product_attributes.product_id) > 1')
                ->pluck('product_attributes.product_id')->toArray();
                $valueThree[]=[
                  'name'=>$item,
                  'available'=>count($productAttribute)?true:false,
                ];
              }
              $attributes[$keyTwo]=$valueThree;
            }
          }
        }
      }else{
        foreach($attributes as $key=>$value){
          $valueOne=[];
          foreach($value as $index=>$item){
            $productAttribute=ProductAttribute::select('product_attributes.*')
            ->join('products', 'products.id', '=', 'product_attributes.product_id')
            ->where('products.status',1)
            ->where('products.avaibility','>',0)
            ->where('product_attributes.parent_id',$parent_id)
            ->where('product_attributes.attribute_value',$item)
            ->pluck('product_attributes.attribute_value')->toArray();
            $valueOne[]=[
              'name'=>$item,
              'available'=>count($productAttribute)?true:false,
            ];
          }
          $attributes[$key]=$valueOne;
        }
      }
    }
    $rating = 4.5;
    $reviews_count = 0;
    $reviews = ProductReview::where('product_id',$product->id)->where('status', 1)->orderBy('rating', 'DESC')->get();
    if($reviews_count=$reviews->count()){
      $rating = floatval(sprintf('%.1f', $reviews[0]->rating));
    }
    $sale_price = 0;
    $sale_price_start_date = $product->sale_price_start_date;
    $sale_price_end_date = $product->sale_price_end_date;
    if($sale_price_start_date && (date('Y-m-d') >= $sale_price_start_date) && (date('Y-m-d') <= $sale_price_end_date)){
      $sale_price = $product->sale_price;
    }
    $image=$product->image;
    $category=$product->category;
    $description=$product->specifications;
    $productVideo = ProductVideo::where('product_id', $product->id)->first();
    $estimatedDelivery = [
      "from" => "",
      "to" => "",
    ];
    $productTypeValue = $product->type;
    if($deliveryDate = $product->deliveryDate){
      $estimatedDeliveryFrom = $deliveryDate->from;
      $estimatedDeliveryTo = $deliveryDate->to;
      $estimatedDelivery = [
        "from" => date("d-m-Y", strtotime("now+".$estimatedDeliveryFrom."days")),
        "to" => date("d-m-Y", strtotime("now+".$estimatedDeliveryTo."days")),
      ];
    }
    $expire_at = $product->expire_at;
    $expireAtSeconds = strtotime($expire_at);
    $can_purchase = true;
    if($productTypeValue == 1 && $product->avaibility <= 0){
      $can_purchase = false;
    }elseif($productTypeValue == 2 && $expireAtSeconds <=  strtotime("now")){
      $can_purchase = false;
    }
    $data = [
      'parent_id'=>$parent_id,
      'id'=>$product->id,
      'item_type'=>$item_type,
      'slug'=>$product->slug,
      'sku'=>$product->sku,
      'title'=>$product->title."-".$product->id,
      'image'=>$image?$image->image(800,600):no_image(),
      'video'=>[
        "type"=>"youtube",
        "video_url"=>$productVideo?$productVideo->video_url:"",
        "video_id"=>$productVideo?$productVideo->video_id:"",
        "title"=>$productVideo?$productVideo->title:"",
        "description"=>$productVideo?$productVideo->description:"",
        "image"=>$productVideo?$productVideo->image:"",
      ],
      'galleries'=>$galleries,
      'has_variation'=>$has_variation,
      'attribute'=>$attribute,
      'attributes'=>$attributes,
      'rating'=>$rating,
      'reviews_count'=>$reviews_count,
      'rating_overview' => [
        'one' => $reviews->where('rating','<=', 1)->count(),
        'two' => $reviews->where('rating', 2)->count(),
        'three' => $reviews->where('rating', 3)->count(),
        'four' => $reviews->where('rating', 4)->count(),
        'five' => $reviews->where('rating','>=', 5)->count()
      ],
      'base_price'=>$product->base_price,
      'sale_price'=>$sale_price,
      'sale_price_start_date'=>$product->sale_price_start_date,
      'sale_price_end_date'=>$product->sale_price_end_date,
      'affiliate_market_url'=>$product->affiliate_market_url,
      'category'=>[
        'name'=>$category?$category->name:'',
        'slug'=>$category?$category->slug:'',
        'image'=>$category&&$category->image?$category->image->image(50,50):no_image(),
      ],
      'type'=>$type,
      'can_add_more'=>$can_add_more,
      'item_id'=>$cartItem?$cartItem->id:0,
      'item_quantity'=>$cartItem?$cartItem->quantity:1,
      'show_form'=>$show_form,
      'form'=>$forms,
      'avaibility'=>$product->avaibility,
      'short_description'=>$description?$description->short_description:'',
      'description'=>$description?$description->description:'',
      'features'=>$features,
      'specifications'=>$specifications,
      'expire_at'=>$expire_at && $expire_at != '1970-01-01 05:30:00' ? date('d-m-Y h:i A', $expireAtSeconds) : '',
      // 'expire_at'=>$expire_at,
      'panditji'=>$panditji,
      'place'=>$product->place,
      'can_purchase'=>$can_purchase,
      'estimatedDelivery'=>$estimatedDelivery
    ];
     return response()->json([
       'success' => true,
        'data' => $data,
        'message' => '',
     ], 200);
  }

  public function review(Request $request){
    $request->validate([
      "id" => "required|gt:0"
    ]);
    $id = $request->id;
    $product = Product::find($id);
    $data = [];
    if($product){
      $reviews = ProductReview::where('product_id', $product->id)->where('status', 1)->orderBy('rating', 'DESC')->get();
      $data = $reviews->map(function($value, $key){
        $item["review"] = $value->review;
        $item["rating"] = (int) $value->rating>0?$value->rating:1;
        $item["date"] = date('d-m-Y', strtotime($value->created_at));
        $user = $value->user;
        if($user){
          $image = $user->image;
          $item["user_name"] = $user->name;
          $item["user_image_url"] = $image ? $image->image(200,200) : no_image();
        }else{
          $item["user_name"] = $user->name;
          $item["user_image_url"] = no_image();
        }
        return $item;
      });
    }
    return response()->json(['success'=>count($data) ? true : false, 'data'=>$data], 200);
  }

}
