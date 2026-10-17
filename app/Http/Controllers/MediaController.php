<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use File;
use App\Model\Media;

class MediaController extends Controller
{
  private $module = 'media';


  public function upload(Request $request)
  {
      $allowPdf = $request->boolean('allow_pdf');
      $maxKb = $allowPdf ? 5120 : 5120; // 5MB for both (was 1024)

      $rules = [
          'file' => $allowPdf
              ? 'required|file|mimes:jpg,jpeg,png,webp,gif,bmp,pdf|max:'.$maxKb
              : 'required|image|max:'.$maxKb,
      ];
      $this->validate($request, $rules);

      $mediaPath = public_path('media');
      if(!File::exists($mediaPath)) {
          File::makeDirectory($mediaPath, 0755, true);
      }
      $tempupload = public_path('media/tempupload');
      if(!File::exists($tempupload)) {
          File::makeDirectory($tempupload, 0755, true);
      }
      $time = time();

      $imageName = $time.'.'.$request->file->getClientOriginalExtension();
      $request->file->move(public_path('media/tempupload'), $imageName);

      $isPdf = strtolower($request->file->getClientOriginalExtension()) === 'pdf';

      if ($isPdf) {
          $attributes = ['H' => 0, 'W' => 0, 'file' => $imageName];
          $media = new Media();
          $media->file = $imageName;
          $media->title = $request->file->getClientOriginalName();
          $media->caption = '';
          $media->type = $request->type;
          $media->attributes = json_encode($attributes);
          $media->sizes = json_encode(['0-0']);
          $media->save();
          $finalPath = public_path('media/'.$media->id);
          if (!File::exists($finalPath)) {
              File::makeDirectory($finalPath, 0755, true);
          }
          rename(public_path('media/tempupload/').$imageName, $finalPath.'/'.$imageName);
          $attributes['img'] = asset('media/'.$media->id.'/'.$imageName);
          $attributes['media_id'] = $media->id;
          $attributes['original'] = $attributes['img'];
          return response()->json($attributes, 200);
      }

      $image = Image::make(public_path('media/tempupload/').$imageName);
      $attributes = [];
      $attributes['H']      = $image->width();
      $attributes['W']      = $image->height();
      $attributes['file']   = $imageName;

      $media = new Media();
      $media->file = $imageName;
      $media->title = $request->file->getClientOriginalName();
      $media->caption = '';
      $media->type = $request->type;
      $media->attributes = json_encode($attributes);
      $media->sizes = json_encode([$attributes['W'].'-'.$attributes['H']]);
      $media->save();

      $path = public_path('media/'.$media->id);
      if(!File::exists($path)) {
         File::makeDirectory($path, 0755, true);
      }
      $image->save($path.'/'.$imageName);
      $media->image(200,200);
      $attributes['img']  = $media->image(200,200);
      $attributes['media_id']  = $media->id;
      $attributes['original']  = str_replace('200-200/','', $attributes['img']);

      unlink(public_path('media/tempupload/').$imageName);

      return response()->json($attributes, 200);
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
    if($media_id = $request->media_id) {
      $media = Media::find($media_id);
      $media->delete();
       return response()->json(['message'=>'Media Deleted successfully!'], 200);
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
}
