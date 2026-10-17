<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Media;
use App\Http\Controllers\Controller;
use App\{CourseCategory};
use \Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    //
    private $module = 'coursecategory';
    public function index(Request $request)
     {
         if(!adminCan('course-category','view')) return dd('No Permission');
         $lang = $request->lang;
         $nav = 'courseCategory';
         $breads = [
             ['name'=>'Categories']
         ];

         return view('admin.coursecategory.list', compact('nav','breads', 'lang'));
     }

     public function all(Request $request)
     {
        $lang = $request->lang;
        $category = CourseCategory::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

        $category->where('lang', $lang);

        if($name = $request->name) {
            $category->where('name', 'like', "%$name%");
        }

        if(isset($request->status) && is_numeric($request->status)) {$status = $request->status;
            $category->where('status', $status);
        }

        if($parent_id = $request->parent_id) {
          $c = CourseCategory::where('name', 'like', "%$parent_id%")->first();
          $category->where('parent_id', $c ? $c->id : '');
        }

        if($updated_at = $request->updated_at) {
            $category->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $categories = $category->paginate(10);

        $parentcategories = CourseCategory::where('parent_id',0)->where('lang', $lang)->get()->pluck('name', 'id')->toArray();
        $parentcategories[0] = '';
        $data = [
            'rows' => view('admin.coursecategory.listrows',['results'=>$categories, 'parents'=>$parentcategories, 'lang'=>$lang])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$categories])->render(),
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $id = $request->id;
         $lang = $request->lang;
         $nav = 'courseCategory';
         $breads = [
             ['name'=>'Category', 'url'=> route('admin.coursecategory.index', $lang)],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $category = false;
        $categories = CourseCategory::pluck('name', 'id')->toArray();
         if($id) {
            $category = CourseCategory::find($id);
            $categories = CourseCategory::where('id', '!=', $id)->pluck('name', 'id')->toArray();
          }
        $categories[0] = 'Select Parent';
        ksort($categories);
        $medias  = Media::where('type','categories')->get();

         return view('admin.coursecategory.form', compact('nav', 'breads', 'category', 'categories', 'lang','medias'));
     }

     public function submit(Request $request) {
         $id = $request->id;
          if(!$id && !adminCan('course-category', 'add')){
            return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
          }
          if($id && !adminCan('course-category', 'update')){
            return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
          }
         $lang = $request->lang;
         $this->validate($request, [
             'name' => 'required|max:255|unique:course_categories,name,'.$id.',id',
             'slug' => 'required|max:255|unique:course_categories,slug,'.$id.',id',
             'icon_image_id' => 'required',
             'image_id' => 'required',
             'order' => 'required|numeric|not_in:0',
         ],[
             'name.required' => 'Name is Required',
             'slug.required' => 'Slug is Required',
             'icon_image_id' => 'Icon is Required',
             'image_id' => 'Image is Required',
             'order.required' => 'Order is Required',
         ]);
// |unique:categories,slug,'.$id.',id'
        $parentID = $request->parent ? $request->parent : 0;
        $checkAlreadySomeParent = false;
        if($id){
          $category = CourseCategory::find($id);
          if($parentID && CourseCategory::where('parent', $id)->count()>0){
              $parentID = 0;
          }
        }else {
            $category = new CourseCategory();
        }

         $category->name 			= $request->name;
         $category->slug 			= $request->slug?Str::slug($request->slug):Str::slug($request->name);
         $category->parent_id 		= $request->parent_id;
         $category->status 		    = $request->status ? $request->status : 0;
         $category->meta_title        = $request->meta_title;
         $category->meta_keywords        = $request->meta_keywords;
         $category->meta_description        = $request->meta_description;
         $category->show_in_navigation        = $request->show_in_navigation ? $request->show_in_navigation : 0;
         $category->is_featured       = $request->is_featured ? $request->is_featured : 0;
         $category->lang       = $request->lang;
         $category->image_id = $request->image_id;
         $category->icon_image_id = $request->icon_image_id;
         $category->order = $request->order?:0;
         $category->save();
         return response()->json(['message'=>'Category Saved Successfully', 'redirectto'=>route('admin.coursecategory.edit', [$lang, $category->id])], 200);
     }

       public function delete(Request $request){
        if(!adminCan('course-category','delete')) return dd('No Permission');
        if($id = $request->id) {
          $lang = $request->lang;
          $category = CourseCategory::find($id);
          $category->delete();
          return response()->json(['message'=>'Category Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
     public function duplicate(Request $request){
        if(!adminCan('course-category','add')) return dd('No Permission');
        if($id = $request->id){
            $category = CourseCategory::find($id);
            $nCategory = $category->replicate();
            $nCategory->name = $category->name .' Cloned';
            $nCategory->slug = $category->slug .'-'.$category->id;
            $nCategory->save();
            return response()->json(['message'=>'Category Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
     }
}
