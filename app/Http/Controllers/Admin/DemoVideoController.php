<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DemoVideo;
use App\Model\Media;

class DemoVideoController extends Controller
{
    private $module = 'video';
    private $moduleName = 'Video';
    private $moduleNameSingle = 'video';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $data_id = $request->data_id;
         return view('admin.demo_video.list', compact('nav','breads', 'moduleName', 'data_id'));
     }

     public function all(Request $request)
     {
        $data_id = $request->data_id;
          $results = DemoVideo::orderBy('id', 'desc')->where('page_id', $data_id);
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
          if(isset($request->status) && is_numeric($request->status)) {
              $results->where('status', $request->status);
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.demo_video.listrows',['results'=>$results, 'data_id'=>$data_id])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {

       $id = $request->id;
        $data_id = $request->data_id;
        $nav = $this->module;
        $moduleName = $this->moduleName;
        $breads = [
             ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName, 'url'=> route('admin.software.video', [$request->data_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = DemoVideo::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.demo_video.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'data_id'));
     }

     public function submit(Request $request) {

       $id = $request->id;
        $data_id = $request->data_id;
        $this->validate($request, [
            'title' => 'required|max:255',
            // 'title' => 'required|max:255|unique:module_procedures,title,'.$data_id.',data_id',
            'url'=>'required',
            'video_id'=>'required',
            // 'video_hash'=>'required',
            'thumbnail'=>'required',
            'description' => 'required',
            'order' => 'required',
        ], [
          'title.required' => 'Title is required',
          'description.required' => 'Description is required',
          'url.required' => 'Url is required',
          'video_id.required' => 'Video Id is required',
          'thumbnail.required' => 'Thumbnail is required',
          'order.required' => 'Order is required',
        ]);

        $item = DemoVideo::findOrNew($id);
        $item->page_id 		= $data_id?:0;
        $item->platform_type 		= 'youtube';
        $item->url 		= $request->url;
        $item->video_id             = $request->video_id;
        // $item->video_hash             = $request->video_hash?:'';
        $item->title             = $request->title;
        $item->description       = $request->description;
        $item->thumbnail         = $request->thumbnail;
        $item->status         = $request->status?:0;
        $item->order         = $request->order?:0;
        $item->save();
        $redirectTo =route('admin.software.video.edit', [$data_id, $item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            $item = DemoVideo::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
}
