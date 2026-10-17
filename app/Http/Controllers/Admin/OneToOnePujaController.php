<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Product, ProductGallery, ProductSpecification};
use App\Model\{Media, Astrologer, ProductCategory, ProductAndCategoryId};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class OneToOnePujaController extends Controller
{
    private $module = 'one_to_one_puja';
    private $moduleName = 'One to One Puja';
    private $moduleNameSingle = 'One to One Puja';
    private $productTypeValue = 2; // Puja type

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = $this->module;
        $breads = [
            ['name' => $this->moduleName]
        ];
        $moduleName = $this->moduleName;
        return view('admin.'.$this->module.'.list', compact('nav', 'breads', 'moduleName'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $results = Product::where('type', $this->productTypeValue)
            ->where('is_one_to_one_puja', 1)
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($title = $request->title) {
            $results->where('title', 'like', "%$title%");
        }
        if($base_price_from = $request->base_price_from) {
            $results->where('base_price', '>=', $base_price_from);
        }
        if($base_price_to = $request->base_price_to) {
            $results->where('base_price', '<=', $base_price_to);
        }
        if(is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', $status);
        }
        if($updated_at_from = $request->updated_at_from) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ?", $updated_at_from);
        }
        if($updated_at_to = $request->updated_at_to) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ?", $updated_at_to);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.'.$this->module.'.listrows', ['results' => $results, 'nav' => $this->module])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];
        return response()->json($data, 200);
    }

    public function form(Request $request)
    {
        $id = $request->id;

        if($id && !adminCan($this->module, 'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module, 'add')) return dd('No Permission');

        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
            ['name' => $this->moduleName, 'url' => route('admin.'.$this->module.'.index')],
            ['name' => ($id ? 'Edit' : 'Add')]
        ];

        $product = $id ? Product::find($id) : null;
        $medias = Media::where('type', 'product')->orderBy('created_at', 'DESC')->get();
        $astrologers = Astrologer::where('status', 1)->where('is_pandit', 1)->where('is_verified', 1)->orderBy('firstname', 'ASC')->get();
        $selectedAstrologers = $id && $product ? $product->astrologers->pluck('id')->toArray() : [];
        $galleries = $id && $product ? $product->productGall : collect([]);
        $productSpec = $id && $product ? ProductSpecification::where('product_id', $id)->first() : null;

        // Fetch puja categories (type = 2)
        $categories = ProductCategory::where('status', 1)->where('type', $this->productTypeValue)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $selectedCategories = $id && $product ? ProductAndCategoryId::where('product_id', $id)->pluck('category_id')->toArray() : [];

        return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'product', 'medias', 'astrologers', 'selectedAstrologers', 'galleries', 'productSpec', 'moduleName', 'categories', 'selectedCategories'));
    }

    public function submit(Request $request)
    {
        $id = $request->id;

        if($id && !adminCan($this->module, 'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module, 'add')) return dd('No Permission');

        $this->validate($request, [
            'title' => 'required|max:255',
            'base_price' => 'required|numeric|min:0',
            'description' => 'required',
            'image_id' => 'required',
            'astrologer_id' => 'required|array|min:1',
            'astrologer_id.*' => 'required|exists:astrologers,id',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'required|exists:product_categories,id',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
        ], [
            'title.required' => 'Puja name is required',
            'base_price.required' => 'Price is required',
            'description.required' => 'Description is required',
            'image_id.required' => 'Image is required',
            'astrologer_id.required' => 'At least one astrologer is required',
            'category_id.required' => 'At least one category is required',
            'commission_type.required' => 'Commission type is required',
            'commission_value.required' => 'Commission value is required',
        ]);

        $userid = Auth::guard('admin')->user()->id;

        $product = Product::findOrNew($id);
        $product->title = $request->title;
        $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        if($id) {
            $existing = Product::where('slug', $product->slug)->where('id', '!=', $id)->where('type', $this->productTypeValue)->where('is_one_to_one_puja', 1)->first();
            if($existing) {
                $product->slug = $product->slug . '-' . $id;
            }
        } else {
            $existing = Product::where('slug', $product->slug)->where('type', $this->productTypeValue)->where('is_one_to_one_puja', 1)->first();
            if($existing) {
                $product->slug = $product->slug . '-' . time();
            }
            $product->created_by = $userid;
        }
        $product->base_price = $request->base_price;
        $product->sale_price = $request->sale_price ?: 0;
        $product->sale_price_start_date = $request->sale_price_start_date ?: null;
        $product->sale_price_end_date = $request->sale_price_end_date ?: null;
        $product->image_id = is_array($request->image_id) ? $request->image_id[0] : $request->image_id;
        $product->type = $this->productTypeValue;
        // Always set is_one_to_one_puja to 1 for one-to-one puja form
        $product->is_one_to_one_puja = 1;
        $product->commission_type = $request->commission_type;
        $product->astrologer_commission_percentage = $request->astrologer_commission_percentage ?: null;
        $product->status = $request->status ?: 0;
        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->meta_description = $request->meta_description;
        $product->sku = "OTO-PUJA" . ($id ?: time());
        $product->category_id = 0;
        $product->video_call_url = $request->video_call_url ?: null;
        $product->updated_by = $userid;
        $product->save();

        // Save categories in product_and_category_ids table
        $productCategories = $request->category_id ?: [];
        $productCategoryIds = [];
        if(count($productCategories) > 0){
            foreach($productCategories as $categoryId){
                $productAndCategoryId = ProductAndCategoryId::where('product_id', $product->id)->where('category_id', $categoryId)->first();
                if(!$productAndCategoryId){
                    $productAndCategoryId = new ProductAndCategoryId();
                    $productAndCategoryId->product_id = $product->id;
                    $productAndCategoryId->category_id = $categoryId;
                    $productAndCategoryId->order = 1;
                    $productAndCategoryId->save();
                }
                $productCategoryIds[] = $productAndCategoryId->category_id;
            }
        }
        // Delete categories that are not in the request
        ProductAndCategoryId::where('product_id', $product->id)->whereNotIn('category_id', $productCategoryIds)->delete();

        // Save description in ProductSpecification
        $productSpec = ProductSpecification::where('product_id', $product->id)->first();
        if(!$productSpec) {
            $productSpec = new ProductSpecification();
            $productSpec->product_id = $product->id;
            $productSpec->created_by = $userid;
        }
        $productSpec->description = $request->description;
        $productSpec->short_description = $request->short_description ?: '';
        $productSpec->updated_by = $userid;
        $productSpec->save();

        // Sync astrologers
        if($request->astrologer_id && is_array($request->astrologer_id)) {
            $product->astrologers()->sync($request->astrologer_id);
        }

        // Handle gallery images - image_id[0] is main image, rest are gallery
        ProductGallery::where('product_id', $product->id)->delete();
        if(isset($request->image_id) && is_array($request->image_id)) {
            foreach($request->image_id as $key => $image_id) {
                if($image_id && $key > 0) { // Skip first image (index 0) as it's main image
                    $gallery = new ProductGallery();
                    $gallery->product_id = $product->id;
                    $gallery->image_id = $image_id;
                    $gallery->alt_text = isset($request->alt_text[$key-1]) && $request->alt_text[$key-1] ? $request->alt_text[$key-1] : "";
                    $gallery->save();
                }
            }
        }

        $redirectTo = route('admin.'.$this->module.'.index');
        return response()->json(['message' => $this->moduleNameSingle.' Saved Successfully', 'redirectto' => $redirectTo], 200);
    }

    public function delete(Request $request)
    {
        if(!adminCan($this->module, 'delete')) return dd('No Permission');
        if($id = $request->id) {
            $product = Product::where('id', $id)->where('type', $this->productTypeValue)->where('is_one_to_one_puja', 1)->first();
            if($product) {
                $product->delete();
                return response()->json(['message' => $this->moduleNameSingle.' Deleted successfully!'], 200);
            }
        }
        return response()->json(['message' => 'Error in deleting data'], 401);
    }
}
