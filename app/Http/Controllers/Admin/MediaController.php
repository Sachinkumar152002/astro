<?php

namespace App\Http\Controllers\Admin;

use App\Model\MediaFolder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use File;
use App\Model\Media;


class MediaController extends Controller
{
    private $module = 'media';

    public function index()
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');
        $nav = 'media';
        $types = Media::types();
        $breads = [
            ['name'=>'Media']
        ];
        return view('admin.media.list', compact('types', 'nav', 'breads'));
    }

    public function upload(Request $request)
    {

        $this->validate($request, [
             // 'file' => 'required|image|dimensions:max_width=4000,max_height=4000'
        ]);
        $type = $request->type;
        $folder_id = $request->folder_id ? : 0;
        if($folder_id) {
            $folder = MediaFolder::find($folder_id);
            $type = $folder->identifier;
        }

        $mediaPath = public_path('media');
        if(!File::exists($mediaPath)) {
            File::makeDirectory($mediaPath, 0755, true);
        }
        $tempupload = public_path('media/tempupload');
        if(!File::exists($tempupload)) {
            File::makeDirectory($tempupload, 0755, true);
        }
        $time = time();

        $fileName = $time.'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('media/tempupload'), $fileName);

        // Check if it's a video file
        $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
        $fileExtension = strtolower($request->file->getClientOriginalExtension());
        $isVideo = in_array($fileExtension, $videoExtensions);

        if($isVideo) {
            // Handle video upload
            $media = new Media();
            $media->file = $fileName;
            $media->title = $request->file->getClientOriginalName();
            $media->caption = '';
            $media->type = $type;
            $media->folder_id = $folder_id;
            $attributes['H'] = 0;
            $attributes['W'] = 0;
            $attributes['file'] = $fileName;
            $media->attributes = json_encode($attributes);
            $media->sizes = json_encode(['0-0']);
            $media->save();

            $path = public_path('media/'.$media->id);
            if(!File::exists($path)) {
               File::makeDirectory($path, 0755, true);
            }
            // Move video file to media folder
            rename(public_path('media/tempupload/').$fileName, $path.'/'.$fileName);

            $attributes['media_id'] = $media->id;
            $attributes['original'] = asset('media/'.$media->id.'/'.$fileName);
            $attributes['img'] = asset('media/'.$media->id.'/'.$fileName);

            return response()->json($attributes, 200);
        } else {
            // Handle image upload (existing logic)
            $image = Image::make(public_path('media/tempupload/').$fileName);
            $attributes['H']      = $image->width();
            $attributes['W']      = $image->height();
            $attributes['file']   = $fileName;

            $media = new Media();
            $media->file = $fileName;
            $media->title = $request->file->getClientOriginalName();
            $media->caption = '';
            $media->type = $type;
            $media->folder_id = $folder_id;
            $media->attributes = json_encode($attributes);
            $media->sizes = json_encode([$attributes['W'].'-'.$attributes['H']]);
            $media->save();

            $path = public_path('media/'.$media->id);
            if(!File::exists($path)) {
               File::makeDirectory($path, 0755, true);
            }
            $image->save($path.'/'.$fileName);
            $media->image(200,200);
            $attributes['img']  = $media->image(200,200);
            $attributes['media_id']  = $media->id;
            $attributes['original']  = str_replace('200-200/','', $attributes['img']);

            unlink(public_path('media/tempupload/').$fileName);

            return response()->json($attributes, 200);
        }
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');

        $media = Media::orderBy($request->sort_by,$request->sort_dir);

        if($title = $request->title) {
            $media->where('title', 'like', "%$title%");
        }

        if($type = $request->type) {
            $media->where('type', 'like', "%$type%");
        }

        if($created_at = $request->created_at) {
            $media->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
        }
        if($updated_at = $request->updated_at) {
            $media->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $media = $media->paginate(10);
          // $media = Media::orderBy('created_at', 'desc')->paginate(20);
          $types = Media::types();
          $data = [
              'rows' => view('admin.media.listrows',['medias'=>$media, 'types'=>$types])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$media])->render(),
          ];
          return response()->json($data, 200);
    }

    public function delete(Request $request)
    {
        if($folder_media = $request->folder_media) {
            if($folder_media=='folder') {
                if($folder = MediaFolder::find($request->media_id)) {
                    $children = MediaFolder::selectTreeArray($folder->id);
                    foreach($children as $childFolderId=>$cf){
                        MediaFolder::find($childFolderId)->delete();
                        Media::where('folder_id', $childFolderId)->delete();
                    }
                    Media::where('folder_id', $folder->id)->delete();
                    $folder->delete();
                }
            }elseif($folder_media=='image'){
                if($media_id = $request->media_id) {
                    $media = Media::find($media_id);
                    $media->delete();
                  }
            }
            return response()->json(['message'=>'Media Deleted successfully!'], 200);
        }else {
            if($media_id = $request->media_id) {
                $media = Media::find($media_id);
                $media->delete();
                 return response()->json(['message'=>'Media Deleted successfully!'], 200);
              }
        }

      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function submit(Request $request)
    {
        $id = $request->media_id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

           $media = Media::find($request->media_id);
           $media->title = $request->title;
           $media->caption = $request->caption;
           $media->type = $request->type;
           $media->save();

           return response()->json([
            'message'=>'Updated successfully.
            '], 200);
    }

    public function fetchByFolder(Request $request){

        $folders = MediaFolder::where('parent_id', $request->folder_id);
        $medias = Media::where('folder_id', $request->folder_id);

        if($term = $request->term){
            $folders->where('name', 'like', "%$term%");
            $medias->where('title', 'like', "%$term%");
        }
        $folders = $folders->get();
        $medias = $medias->get();

        $breads = MediaFolder::breads($request->folder_id);
        $dropDownValues = MediaFolder::selectTreeArray();
        $moveUpId = 0;
        if(count($breads)>1){
            $moveUpId = array_keys($breads)[count($breads)-2];
        }
        $data = [
            'folderimages' => view('admin.media.folders',['folders'=>$folders, 'medias'=>$medias])->render(),
            'breadcrumb' => view('admin.media.breadcrumb',['breads'=>$breads])->render(),
            'movefolders' => view('admin.media.movefolders',['dropDownValues'=>$dropDownValues])->render(),
            'up_id' => $moveUpId
        ];

        return response()->json($data, 200);
    }

    public function newfolder(Request $request){
        if($request->name) {
            if($old_folder_id = $request->old_folder_id) {
                $newFolder = MediaFolder::find($old_folder_id);
            }else {
                $newFolder = new MediaFolder();
            }
            $newFolder->name = $request->name;
            $newFolder->identifier = str_slug($request->name);
            $newFolder->parent_id = $request->folder_id;
            $newFolder->save();
        }
        return response()->json(['folder_id'=> $request->folder_id], 200);
    }

    public function moveToFolder(Request $request){
        $media_id = $request->media_id;
        $folder_media = $request->folder_media;
        $new_folder_id = $request->new_folder_id;
        if($folder_media=='folder' && $new_folder_id!=$media_id) {
            $mediaFolder = MediaFolder::find($media_id);
            $mediaFolder->parent_id = $new_folder_id;
            $mediaFolder->save();
        }elseif($folder_media=='image'){
            $media = Media::find($media_id);
            $media->folder_id = $new_folder_id;
            $media->save();
        }
        return response()->json(['message'=>'Moved Successfully'], 200);
    }

}
