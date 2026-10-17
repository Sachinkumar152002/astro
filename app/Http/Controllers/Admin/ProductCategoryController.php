<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\{ProductCategory,ProductAndCategoryId};
use App\Model\Media;
use \Illuminate\Support\Str;
use App\{Product};

class ProductCategoryController extends Controller {
    private $module = 'productcategory';

    public function index(Request $request){
      $productTypeName = $request->productTypeName ?: "Product";
      $productTypeValue = 1;
      $moduleName = 'Product';
      $moduleKey = 'product-category';
      $nav = 'productCategory';
      if($productTypeName == "Service"){
        $productTypeValue = 3;
        $moduleName = 'Service Product';
        $moduleKey = 'service-product-category';
        $nav = 'serviceProductCategory';
      }elseif($productTypeName == "Pooja"){
        $productTypeValue = 2;
        $moduleName = 'Puja Product';
        $moduleKey = 'pooja-product-category';
        $nav = 'poojaProductCategory';
      }
      $lang = $request->lang;
      if(!adminCan($moduleKey, 'view')) return dd('No Permission');
      $breads = [
        ['name' => $moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
        ['name' => 'Categories'],
      ];
      return view('admin.productcategory.list', compact('nav','breads', 'lang', 'productTypeName', 'productTypeValue'));
    }

     public function all(Request $request)
     {
        $lang = $request->lang;
        $productTypeName = $request->productTypeName ?: "Product";
        $productTypeValue = 1;
        if($productTypeName == "Service"){
          $productTypeValue = 3;
        }elseif($productTypeName == "Pooja"){
          $productTypeValue = 2;
        }
        $category = ProductCategory::where("type", $productTypeValue)
        // ->orderBy($request->sort_by,$request->sort_dir);
        ->orderBy("order","asc");
        $category->where('lang', $lang);
        if($name = $request->name) {
            $category->where('name', 'like', "%$name%");
        }
        if(isset($request->status) && is_numeric($request->status)) {$status = $request->status;
            $category->where('status', $status);
        }
        if($parent_id = $request->parent_id) {
          $c = ProductCategory::where('name', 'like', "%$parent_id%")->first();
          $category->where('parent_id', $c ? $c->id : '');
        }
        if($updated_at = $request->updated_at) {
            $category->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $categories = $category->paginate(10);

        $parentcategories = ProductCategory::where('parent_id',0)->where('lang', $lang)->get()->pluck('name', 'id')->toArray();
        $parentcategories[0] = '';
        $data = [
            'rows' => view('admin.productcategory.listrows',['results'=>$categories, 'parents'=>$parentcategories, 'lang'=>$lang, 'productTypeName' => $productTypeName])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$categories])->render(),
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $productTypeName = $request->productTypeName ?: "Product";
         $productTypeValue = 1;
         $moduleName = 'Product';
         $moduleKey = 'product-category';
         $nav = 'productCategoryadd';
         if($productTypeName == "Service"){
           $productTypeValue = 3;
           $moduleName = 'Service Product';
           $moduleKey = 'service-product-category';
           $nav = 'serviceProductCategoryadd';
         }elseif($productTypeName == "Pooja"){
           $productTypeValue = 2;
           $moduleName = 'Puja Product';
           $moduleKey = 'pooja-product-category';
           $nav = 'poojaProductCategoryadd';
         }
         $id = $request->id;
         $lang = $request->lang;
         $breads = [
           ['name' => $moduleName, 'url'=> route('admin.product.index', [$productTypeName])],
           ['name' => 'Categories', 'url'=> route('admin.productcategory.index', ["productTypeName" => $productTypeName,'lang' => $lang])],
           ['name'=>($id ? 'Edit': 'Add')]
         ];
         $category = ProductCategory::find($id);
         $categories = ProductCategory::pluck('name', 'id')->toArray();
         $categories = ['' => 'Select Parent'] + $categories;
        //  ksort($categories);
        $medias  = Media::where('type','productCategories')->get();
        return view('admin.productcategory.form', compact('nav', 'breads', 'category', 'categories', 'lang', 'medias', 'productTypeName', 'productTypeValue'));
     }

     public function submit(Request $request) {
         $productTypeName = $request->productTypeName ?: "Product";
         $productTypeValue = 1;
         $moduleKey = 'product-category';
         if($productTypeName == "Service"){
           $productTypeValue = 3;
           $moduleKey = 'service-product-category';
         }elseif($productTypeName == "Pooja"){
           $productTypeValue = 2;
           $moduleKey = 'pooja-product-category';
         }
        $id = $request->id;
        $lang = $request->lang;
        if(!$id && !adminCan($moduleKey, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($moduleKey, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $this->validate($request, [
            'name' => "required|max:255|unique:product_categories,name,$id,id,type,$productTypeValue",
            'slug' => "required|max:255|unique:product_categories,slug,$id,id,type,$productTypeValue",
            'image_id' => 'required',
            'c_order' => 'required|numeric|not_in:0',
        ],[
            'name.required' => 'Name is Required',
            'slug.required' => 'Slug is Required',
            'image_id' => 'Image is Required',
            'c_order' => 'Order is Required',
        ]);
        $parentID = $request->parent ? $request->parent : 0;
        if($id){
          $category = ProductCategory::find($id);
          if($parentID && Category::where('parent', $id)->count()>0){
            $parentID = 0;
          }
        }else{
          $category = new ProductCategory();
        }
        $category->name 			= $request->name;
        $category->slug 			= $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->parent_id 		= $request->parent_id ? $request->parent_id : 0;
        $category->status 		    = $request->status ? $request->status : 0;
        $category->meta_title        = $request->meta_title;
        $category->meta_keywords        = $request->meta_keywords;
        $category->meta_description        = $request->meta_description;
        $category->show_in_navigation        = $request->show_in_navigation ? $request->show_in_navigation : 0;
        $category->is_featured       = $request->is_featured ? $request->is_featured : 0;
        $category->lang       = $request->lang;
        $category->image_id = $request->image_id;
        $category->order 		    = $request->c_order ? $request->c_order : 0;
        $category->type = $productTypeValue;
        $category->description = $request->description;
        $oldRefundSetting = $category->refund_and_cancellation;
        $oldRefundDays = $category->refund_cancellation_days;

        $category->refund_and_cancellation = $request->refund_and_cancellation ? $request->refund_and_cancellation : 0;
        $category->refund_cancellation_days = $request->refund_cancellation_days ? $request->refund_cancellation_days : null;
        $category->save();

        // Update all products in this category when category settings change
        // Apply category settings to products that don't have explicit overrides
        $productIds = \App\Model\ProductAndCategoryId::where('category_id', $category->id)->pluck('product_id')->toArray();

        if(!empty($productIds)) {
          if($category->refund_and_cancellation) {
            // Category enabled - enable all products in category (they can override later)
            \App\Product::whereIn('id', $productIds)
              ->update([
                'refund_and_cancellation' => 1,
                'refund_cancellation_days' => $category->refund_cancellation_days
              ]);
          } else {
            // Category disabled - disable products that were using category settings
            // Only disable if they match the old category settings (were using category)
            if($oldRefundSetting) {
              \App\Product::whereIn('id', $productIds)
                ->where('refund_and_cancellation', 1)
                ->where(function($query) use ($oldRefundDays) {
                  if($oldRefundDays) {
                    $query->where('refund_cancellation_days', $oldRefundDays);
                  } else {
                    $query->whereNull('refund_cancellation_days');
                  }
                })
                ->update([
                  'refund_and_cancellation' => 0,
                  'refund_cancellation_days' => null
                ]);
            }
          }
        }

        return response()->json(['message'=>'Category Saved Successfully', 'redirectto'=>route('admin.productcategory.edit', [$lang, $category->id, $productTypeName])], 200);
     }

    public function delete(Request $request){
      $lang = $request->lang;
      $id = $request->id;
      $category = ProductCategory::find($id);
       if($category){
         $productTypeName = "Product";
         $productTypeValue = 1;
         $moduleKey = "product-category";
         if($category->type == 3){
           $productTypeName = "Service";
           $productTypeValue = 3;
           $moduleKey = "service-product-category";
         }elseif($category->type == 2){
           $productTypeName = "Pooja";
           $productTypeValue = 2;
           $moduleKey = "pooja-product-category";
         }
         if(!adminCan($moduleKey, 'delete')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         $category->delete();
          return response()->json(['message'=>'Category Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request) {
       $id = $request->id;
       $category = ProductCategory::find($id);
       if($category){
        $productTypeName = "Product";
        $productTypeValue = 1;
        $moduleKey = "product-category";
        if($category->type == 3){
          $productTypeName = "Service";
          $productTypeValue = 3;
          $moduleKey = "service-product-category";
        }elseif($category->type == 2){
          $productTypeName = "Pooja";
          $productTypeValue = 2;
          $moduleKey = "pooja-product-category";
        }
        if(!adminCan($moduleKey, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $ncategory = $category->replicate();
        $ncategory->name = $category->name.' Cloned';
        $ncategory->slug = $category->slug.'-'.$id;
        $ncategory->save();
        return response()->json(['message'=>'Category Cloned successfully!'], 200);
      }
      return response()->json(['message'=>'Error in cloning data'], 401);
    }
    public function handleCatgoryProductsList(Request $request){
      $productTypeName=$request->productTypeName ?: "Product";
      $productTypeValue=1;
      if($productTypeName=="Service"){
        $productTypeValue=3;
      }elseif($productTypeName=="Pooja"){
        $productTypeValue=2;
      }
      $query= Product::where("type",$productTypeValue)->where("parent_id",0);

      // Exclude one-to-one puja products from regular puja products listing
      if($productTypeValue == 2) {
        $query->where(function($q) {
          $q->where('is_one_to_one_puja', 0)
            ->orWhereNull('is_one_to_one_puja');
        });
      }
      $product_title=$request->product_title;
      $category_id=$request->category_id;
      if($product_title){
        $query->where("title","like","%$product_title%");
      }
      if($product_title=="" && $category_id){
        // $productAndCategoryIdsArray=ProductAndCategoryId::where("category_id",$category_id)->pluck("product_id")->toArray();
        // $query->whereIn("id",$productAndCategoryIdsArray);
        $query->join("product_and_category_ids as paci", "products.id", "=", "paci.product_id")
          ->where("paci.category_id", $category_id)
          ->orderBy("paci.order", "asc")
          ->select("products.*");
      }else{
        $query->orderBy("updated_at","DESC");
      }
      $results = $query->paginate(10);
      $data = [
        'rows' => view('admin.productcategory.product.listrows',["results"=>$results,"category_id"=>$category_id,"productTypeName"=>$productTypeName])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
      ];
      return response()->json($data, 200);
    }
    public function handleUpdateProductAndCategoryId(Request $request){
      $action=$request->action?:"";
      $product_id=$request->product_id?:0;
      $category_id=$request->category_id?:0;
      $order=$request->order?:0;
      if($action && $product_id>0 && $category_id>0){
        $childProductsId=Product::where("parent_id",$product_id)->pluck("id")->toArray();
        if($action=="add"){
          $productAndCategoryId=ProductAndCategoryId::where("product_id",$product_id)->where("category_id",$category_id)->first();
          if(!$productAndCategoryId){
            $productAndCategoryId=new ProductAndCategoryId();
            $productAndCategoryId->product_id=$product_id;
            $productAndCategoryId->category_id=$category_id;
          }
          $productAndCategoryId->order=$order;
          $productAndCategoryId->save();
          foreach($childProductsId as $index=>$id){
            $productAndCategoryId=ProductAndCategoryId::where("product_id",$id)->where("category_id",$category_id)->first();
            if(!$productAndCategoryId){
              $productAndCategoryId=new ProductAndCategoryId();
              $productAndCategoryId->product_id=$id;
              $productAndCategoryId->category_id=$category_id;
            }
            $productAndCategoryId->order=$order;
            $productAndCategoryId->save();
          }
        }elseif($action=="delete"){
          ProductAndCategoryId::where("product_id",$product_id)->where("category_id",$category_id)->delete();
          ProductAndCategoryId::whereIn("product_id",$childProductsId)->where("category_id",$category_id)->delete();
        }
      }
      return response()->json(["success"=>true,"message"=>"Update Successfully."],200);
    }
}
