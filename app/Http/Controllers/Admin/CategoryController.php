<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;

class CategoryController extends Controller
{
    private $module = 'category';
    public function index(Request $request)
     {
        if(!adminCan('blog-category','view')) return dd('No Permission');
         $lang = $request->lang;
         $nav = 'category';
         $breads = [
             ['name'=>'Categories']
         ];

         return view('admin.categories.list', compact('nav','breads', 'lang'));
     }

     public function all(Request $request)
     {
        $lang = $request->lang;
        $category = Category::orderBy($request->sort_by,$request->sort_dir);

        $category->where('lang', $lang);

        if($name = $request->name) {
            $category->where('name', 'like', "%$name%");
        }

        if(isset($request->status) && is_numeric($request->status)) {$status = $request->status;
            $category->where('status', $status);
        }


        if($parent_name = $request->parent_name) {
          $parentIds = Category::where('name', 'like', "%$parent_name%")->pluck('id')->toArray();
            $category->whereIn('parent_id', $parentIds);
        }

        if($updated_at = $request->updated_at) {
            $category->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $categories = $category->paginate(10);

        $parentcategories = Category::where('parent_id',0)->where('lang', $lang)->get()->pluck('name', 'id')->toArray();
        $parentcategories[0] = '';
        $data = [
            'rows' => view('admin.categories.listrows',['results'=>$categories, 'parents'=>$parentcategories, 'lang'=>$lang])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$categories])->render(),
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $id = $request->id;
         $lang = $request->lang;
         $nav = 'category';

         $breads = [
             ['name'=>'Category', 'url'=> route('admin.category.index', $lang)],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $category = false;
         if($id) { $category = Category::find($id); }
         $categories = Category::selectTreeArray(0, '', $lang, $id);
         $categories[0] = 'select parent';
         ksort($categories);

         return view('admin.categories.form', compact('nav', 'breads', 'category', 'categories', 'lang'));
     }

     public function submit(Request $request) {
         $id = $request->id;
         if(!$id && !adminCan('blog-category', 'add')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         if($id && !adminCan('blog-category', 'update')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         $lang = $request->lang;
         $this->validate($request, [
             'name' => 'required|max:255',
             'slug' => 'required|max:255|unique:categories,slug,'.$id.',id',
             'status' => 'required',
         ]);

        $id = $request->id;
        $parentID = $request->parent ? $request->parent : 0;
        $checkAlreadySomeParent = false;
        if($id){
            $category = Category::find($id);
            if($parentID && Category::where('parent', $id)->count()>0){
                $parentID = 0;
            }
        }else {
            $category = new Category();
        }

         $category->name 			= $request->name;
         $category->slug 			= $request->slug ? str_slug($request->slug) : str_slug($request->name);
         $category->parent_id 		= $request->parent_id;
         $category->status 		    = $request->status;
         $category->meta_title        = $request->meta_title;
         $category->meta_keywords        = $request->meta_keywords;
         $category->meta_description        = $request->meta_description;
         $category->show_in_navigation        = $request->show_in_navigation;
         $category->is_featured       = $request->is_featured;
         $category->lang       = $request->lang;
         $category->save();
         return response()->json(['message'=>'Category Saved Successfully', 'redirectto'=>route('admin.category.edit', [$lang, $category->id])], 200);
     }

       public function delete(Request $request){
        if(!adminCan('blog-category','delete')) return dd('No Permission');
         if($id = $request->id){
          $lang = $request->lang;
           $category = Category::find($id);
           $category->delete();
            return response()->json(['message'=>'Category Deleted successfully!'], 200);
         }
         return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
    {
       if(!adminCan('blog-category', 'add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Category::find($id);
    $nitem = $item->replicate();
        $nitem->name = $item->name.' Cloned';
        $nitem->slug = $item->slug.'-'.$item->id;
        $nitem->save();
    return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }
 }
