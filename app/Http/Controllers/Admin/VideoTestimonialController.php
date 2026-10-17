<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\VideoTestimonial;
use App\Model\Media;
use App\Model\OnPage;

class VideoTestimonialController extends Controller
{
    private $module = 'video_testimonial';
    private $moduleName = 'Video Testimonial';
    private $moduleNameSingle = 'video_testimonial';

    public function index(Request $request)
     {
        if(!adminCan('video-testimonial', 'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.video_testimonial.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
          $results = VideoTestimonial::orderBy('id', 'desc');
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
          if($request->has('page') && is_numeric($request->page)){
            $onPage = OnPage::where('table', 'testimonials')->where('page', $request->page)->pluck('table_id')->toArray();
            $results->whereIn('id', $onPage);
          }
          if($city= $request->city) {
              $results->where('city', 'like', "%$city%");
          }
          if(isset($request->status) && is_numeric($request->status)) {
              $results->where('status', $request->status);
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.video_testimonial.listrows',['results'=>$results])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {

       $id = $request->id;
        $nav = $this->module;
        $moduleName = $this->moduleName;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.videotestimonial.list')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = VideoTestimonial::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.video_testimonial.form', compact('nav', 'breads', 'item', 'medias', 'moduleName'));
     }

     public function submit(Request $request) {

       $id = $request->id;
       if(!$id && !adminCan('video-testimonial', 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id && !adminCan('video-testimonial', 'update')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
        $data_id = $request->data_id;
        $this->validate($request, [
            'page' => 'required|array',
            'title' => 'required|max:255',
            'description' => 'required',
            // 'city'=>'required',
            'url'=>'required',
            'video_id'=>'required',
            'image'=>'required',
            'order' => 'required',
        ], [
          'page.required' => 'Page is required',
          'page.array' => 'Page is required',
          'title.required' => 'Title is required',
          'description.required' => 'Description is required',
          // 'city.required' => 'City is required',
          'url.required' => 'Url is required',
          'video_id.required' => 'Video Id is required',
          'thumbnail.required' => 'Thumbnail is required',
          'order.required' => 'Order is required',
        ]);

        $item = VideoTestimonial::findOrNew($id);
        // $item->page = $request->page;
        $item->page = 0;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->url = $request->url;
        $item->video_id = $request->video_id;
        // $item->video_hash = $request->video_hash?:'';
        $item->city = $request->city?:'';
        $item->image = $request->image;
        $item->order = $request->order?:0;
        $item->status = $request->status?:0;
        $item->save();
        $onPage = OnPage::where('table', 'video_testimonials')->where('table_id', $item->id)->delete();
        foreach ($request->page as $key => $value){
          $onPage = new OnPage();
          $onPage->table='video_testimonials';
          $onPage->table_id=$item->id;
          $onPage->page=$value;
          $onPage->save();
        }
        $redirectTo =route('admin.videotestimonial.edit', [$item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan('video-testimonial', 'delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = VideoTestimonial::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
     public function duplicate(Request $request){
        if(!adminCan('video-testimonial', 'add')) return dd('No Permission');
          if($id = $request->id){
              $item = VideoTestimonial::find($id);
              $vItem = $vItem->replicate();
              $vItem->name = $item->title .' Cloned';
              $vItem->save();
              return response()->json(['message'=>'Testimonial Cloned successfully!'], 200);
          }
          return response()->json(['message'=>'Error in cloning data'], 401);
     }
}
