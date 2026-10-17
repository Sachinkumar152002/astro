<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Testimonial;
use App\Model\Media;
use App\Model\OnPage;

class TestimonialController extends Controller
{
  public function index(Request $request)
   {
     if(!adminCan('text-testimonial', 'view')) return dd('No Permission');
       $nav = 'testimonial';
       $breads = [
           ['name'=> 'testimonial']
       ];
       return view('admin.testimonial.list', compact('nav','breads'));
   }

   public function form(Request $request)
   {
        $id = $request->id;
        $nav = 'testimonial';
       $lang = $request->lang;
       $breads = [
           ['name'=>'testimonial', 'url'=> route('admin.testimonial.list')],
           ['name'=>($id ? 'Edit': 'Add')]
       ];
       $article = false;

       if($id) { $article = Testimonial::find($id); }
       $medias  = Media::where('type','testimonial')->get();

       return view('admin.testimonial.form', compact('nav', 'breads', 'article', 'medias'));
   }
   public function all(Request $request)
   {
        $article = Testimonial::orderBy('updated_at', 'DESC');
        if($title = $request->title) {
            $article->where('title', 'like', "%$title%");
        }
        if($city = $request->city) {
            $article->where('city', 'like', "%$city%");
        }
        // dd($request->all());
        if($request->has('pageType') && is_numeric($request->pageType) && $request->pageType > 0){
          $onPage = OnPage::where('table', 'testimonials')->where('page', $request->pageType)->pluck('table_id')->toArray();
          $article->whereIn('id', $onPage);
        }
        // dd($article->get());
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $article->where('status', $status);
        }
        if($updated_at = $request->updated_at) {
            $article->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
         $article = $article->paginate(10);
         $data = [
             'rows' => view('admin.testimonial.listrows',['articles'=>$article])->render(),
             'pagination' => view('admin._partials.pagination',['results'=>$article])->render(),
         ];
         return response()->json($data, 200);
   }

   public function submit(Request $request) {
     $id = $request->id;
     if(!$id && !adminCan('text-testimonial', 'add')){
       return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
     }
     if($id && !adminCan('text-testimonial', 'update')){
       return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
     }
     $this->validate($request, [
       // 'image_id'=>'required|numeric|not_in:0',
       'pageType'=>'required|array',
       'title'=>'required',
       'description'=>'required',
       // 'city'=>'required',
       'order'=>'required|numeric|not_in:0',
     ],[
       // 'image_id.required'=>'Image is required',
       'pageType.required'=>'Page is required',
       'pageType.array'=>'Page is required',
       'title.required'=>'Title is required',
       'description.required'=>'Descritption is required',
       // 'city.required'=>'City is required',
       'order.required'=>'Order is required',
     ]);
       if($id){
           $testimonial = Testimonial::find($id);
       }else {
           $testimonial = new Testimonial();
       }
      $testimonial->image_id 		= $request->image_id ?: 0;
      // $testimonial->pageType = $request->pageType;
      $testimonial->pageType = 0;
      $testimonial->title 		= $request->title;
      $testimonial->description 		= $request->description;
      $testimonial->city       = $request->city ?: "";
      $testimonial->order 		    = $request->order ?: 1;
      $testimonial->status 		    = $request->status;
      // $article->type          = $request->type;
      // $article->vStatus 		    = $request->vStatus;
      // $article->name       = $request->name;
      // $article->video_link    = $request->video_link;
      // $article->image_testimonail_id    = $request->image_testimonail_id;
      // $article->vPageType = $request->vPageType;
      $testimonial->save();
      $onPage = OnPage::where('table', 'testimonials')->where('table_id', $testimonial->id)->delete();
      foreach ($request->pageType as $key => $value){
        $onPage = new OnPage();
        $onPage->table='testimonials';
        $onPage->table_id=$testimonial->id;
        $onPage->page=$value;
        $onPage->save();
      }
      return response()->json(['message'=>'Testimonial Saved Successfully', 'redirectto'=>route('admin.testimonial.edit', $testimonial->id)], 200);
   }
   public function delete(Request $request)
    {
      if(!adminCan('text-testimonial', 'delete')) return dd('No Permission');
      if($article_id = $request->id) {
        $article = Testimonial::find($article_id);
        $article->delete();
         return response()->json(['message'=>'Testimonial Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }
    public function duplicate(Request $request)
      {
        if(!adminCan('text-testimonial', 'add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Testimonial::find($id);
    $nitem = $item->replicate();
    $nitem->title = $item->title.' Cloned';
          $nitem->save();
    return response()->json(['message'=> 'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }
}
