<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Video;

class VideoController extends Controller
{

    private $module = 'videos';
    private $moduleName = 'Videos';
    private $moduleNameSingle = 'Video';

    public function index(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
          $video = Video::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
          if($title = $request->title) {
              $video->where('title', 'like', "%$title%");
          }
          if($category = $request->category) {
              $video->where('category', $category);
          }
          if(is_numeric($request->status)) {
            $status = $request->status;
            $video->where('status', $status);
        }

          if($updated_at = $request->updated_at) {
              $video->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }

           $video = $video->paginate(10);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$video])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$video])->render(),
           ];
           return response()->json($data, 200);
     }

     public function form(Request $request)
     {
        $id = $request->id;
         $nav = $this->module;
         $moduleName = $this->moduleNameSingle;
         $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $item = false;
         $item = Video::findOrNew($id);
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan($this->module, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($this->module, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
         $this->validate($request, [
             'title' => 'required|max:255',
             'video_url' => 'required',
             'video_id' => 'required',
             'thumbnail' => 'required',
             'category' => 'required',
         ]);

         $id = $request->id;

        $video = Video::findOrNew($id);
        $video->video_url = $request->video_url;
        $video->video_id = $request->video_id;
        $video->title = $request->title;
        $video->thumbnail = $request->thumbnail;
        $video->description = $request->description;
        $video->category = $request->category;
        $video->status = $request->status?:0;
        $video->save();
        return response()->json(['message'=>$this->moduleName.' Saved Successfully', 'redirectto'=>route('admin.'.$this->module.'.index')], 200);
     }

    public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
       if($video_id = $request->id) {
         $video = Video::find($video_id);
         $video->delete();
          return response()->json(['message'=>$this->moduleName.' Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function videoDetails(Request $request)
     {
        $title = $image = $description = $videoId = '';
        if($request->platform=='vimeo') {
            $videoId = vimeoId($request->url);
            if($videoId) {
              $files = file_get_contents("https://vimeo.com/api/v2/video/$videoId.json'");
              $files = json_decode($files, true);
              $title = isset($files[0]) ? $files[0]['title'] : '';
              $description = isset($files[0]) ? $files[0]['description'] : '';
              $image = isset($files[0]) ? $files[0]['thumbnail_large'] : '';
            }
        }else {

          $videoId = youtubeId($request->url);

          if($videoId){
              $apikey = 'AIzaSyCay4G-bn_qb2V3bhWiYPvw6oSfWG3PL6I';
              $googleApiUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apikey . '&part=snippet';

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
              curl_setopt($ch, CURLOPT_VERBOSE, 0);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              $response = curl_exec($ch);
              curl_close($ch);
              $data = json_decode($response);
              $value = json_decode(json_encode($data), true);
              $title = $value['items'][0]['snippet']['title'];
              $description = $value['items'][0]['snippet']['description'];
              $image = sprintf("https://img.youtube.com/vi/%s/0.jpg", $videoId);
          }
        }
        return response()->json(['title'=>$title, 'image'=>$image, 'description'=>$description, 'video_id'=>$videoId], 200);
     }
 }
