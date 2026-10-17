<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use App\Model\Media;
use Auth;

class BannerController extends Controller
{
    public $module= 'banner';

    public function index(Request $request)
    {
      if(!adminCan('banner', 'view')) return dd('No Permission');
    $nav = 'banner';
        $breads = [
            ['name'=>'Banners']
        ];
        return view('admin.banners.list', compact('nav','breads'));
    }

    public function all(Request $request)
    {
        $banner = Banner::orderBy($request->sort_by,$request->sort_dir);

        if($title = $request->title) {
            $banner->where('title', 'like', "%$title%");
        }

        if($type = $request->type) {
            $banner->where('type', 'like', "%$type%");
        }

        if($start_date = $request->start_date) {
            $banner->whereRaw("DATE_FORMAT(start_date, '%Y-%m-%d') = ? " , $start_date);
        }

         if($end_date = $request->end_date) {
            $banner->whereRaw("DATE_FORMAT(end_date, '%Y-%m-%d') = ? " , $end_date);
        }

        if($order = $request->order) {
            $banner->where('order', 'like', "%$order%");
        }

        if($created_by = $request->created_by) {
            $banner->where('createdby', 'like', "%$createdby%");
        }

        if($created_at = $request->created_at) {
            $banner->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
        }
        if($updated_by = $request->updated_by) {
            $banner->where('updatedby', 'like', "%$updatedby%");
        }
        if($updated_at = $request->updated_at) {
            $banner->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $banner = $banner->paginate(10);
     // $banner = Banner::paginate(10);

          $data = [
              'rows' => view('admin.banners.listrows',['banners'=>$banner])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$banner])->render(),
          ];
          return response()->json($data, 200);
    }

    public function form(Request $request)
    {
    $nav = 'banner';
        $id = $request->id;
        $breads = [
            ['name'=>'Banners', 'url'=> route('admin.banner.index')],
            ['name'=>($id ? 'Edit': 'Add')]
        ];
        $banner = false;
        if($id) { $banner = Banner::find($id); }
        $medias  = Media::where('type','banner')->get();
        $_status = statusArray();

        return view('admin.banners.form', compact('nav', 'breads', 'banner', 'medias', '_status'));
    }

    public function submit(Request $request) {
      $id = $request->id ?? 0;
      if(!$id && !adminCan('banner', 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan('banner', 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
    $this->validate($request, [
    'title' => 'required|max:255',
    'alt' => 'required|max:255',
    'image_id' => 'required|numeric',
    'mobile_image_id' => 'required|numeric',
          'type' => 'required'
    ],[
    'image_id.required'=> 'Banner is required',
    'mobile_image_id.required'=> 'Mobile Banner is required'
    ]);

      if($request->start_date || $request->end_date){
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
      }

      $oneBannerTypes = ["product_categories", "product", "puja_categories", "puja", "service_categories", "service", "astrologer", "ai_astrologer", 'kundali', "kundali_matching", "panchang", "horoscope"];
      if(in_array($request->type, $oneBannerTypes)){
        if(Banner::where('type', $request->type)->where('id', '!=', $id)->count() > 0){
          return response()->json(['errors'=> ['type' => ['Page already taken.']]], 422);
        }
      }

    $userid = Auth::guard('admin')->user()->id;
      $banner = Banner::find($id);
      if(!$banner){
        $banner = new Banner();
        $banner->created_by 	= $userid;
      }
      $banner->title 			= $request->title;
      $banner->alt 			= $request->alt;
      $banner->link 		= $request->link ?: "";
    $banner->image_id 		= $request->image_id;
      $banner->mobile_image_id = $request->mobile_image_id;
      $banner->start_date 	= $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : '' ;
      $banner->end_date 		=  $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : '';
      $banner->type  = $request->type;
      $banner->order 			= $request->order ? $request->order : 0;
      $banner->status 		= $request->status;
    $banner->caption 		= $request->caption ?: "";
      $banner->updated_by 	= $userid;
      $banner->save();
      return response()->json(['message'=>'Banner Saved Successfully', 'redirectto'=>route('admin.banner.edit', [$banner->id])], 200);
    }

    public function delete(Request $request)
    {
      if(!adminCan('banner', 'delete')) return dd('No Permission');
      if($banner_id = $request->banner_id) {
        $banner = Banner::find($banner_id);
        $banner->delete();
         return response()->json(['message'=>'Banner Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request)
    {
      if(!adminCan('banner', 'add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Banner::find($id);
    $nitem = $item->replicate();
        $nitem->title = $nitem->title.' Cloned';
    $nitem->save();
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }
}
