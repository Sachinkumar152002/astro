<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Advertisement;
use App\Model\Media;
use App\Model\Astrologer;
use Auth;

class PaidAdvertisementController extends Controller
{
    public $module= 'paid_advertisement';

    public function index(Request $request)
    {
      if(!adminCan('astrologer-advertisement', 'view')) return dd('No Permission');
    $nav = 'paid_advertisement';
        $breads = [
            ['name'=>'User Advertisements']
        ];
        return view('admin.paid_advertisements.list', compact('nav','breads'));
    }

    public function all(Request $request)
    {
        $advertisement = Advertisement::orderBy($request->sort_by,$request->sort_dir)->where('user_type','!=', 'admin');
        if($title = $request->title) {
            $advertisement->where('title', 'like', "%$title%");
        }
        if($user_id = $request->user_id) {
          $astrologer_ids = Astrologer::where('display_name', 'like', "%$user_id%")->pluck('id')->toArray();
          $advertisement->whereIn('user_id', $astrologer_ids);
        }
        if($amount_from = $request->amount_from) {
            $advertisement->where('amount', '>=', $amount_from);
        }
        if($amount_to = $request->amount_to) {
            $advertisement->where('amount', '<=', $amount_to);
        }
        if($page = $request->page) {
            $advertisement->where('page', $page);
        }
        if($request->has('status') && is_numeric($request->status)) {
            $advertisement->where('status', $request->status);
        }
        if($start_date = $request->start_date) {
            $advertisement->whereRaw("DATE_FORMAT(start_date, '%Y-%m-%d') = ? " , $start_date);
        }
        if($end_date = $request->end_date) {
            $advertisement->whereRaw("DATE_FORMAT(end_date, '%Y-%m-%d') = ? " , $end_date);
        }
        if($updated_at_form = $request->updated_at_form) {
            $advertisement->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_form);
        }
        if($updated_at_to = $request->updated_at_to) {
            $advertisement->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
        }
        $advertisement = $advertisement->paginate(10);
          $data = [
              'rows' => view('admin.paid_advertisements.listrows',['advertisements'=>$advertisement])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$advertisement])->render(),
          ];
        return response()->json($data, 200);
    }

    public function form(Request $request)
    {
    $nav = 'paid_advertisement';
      $id = $request->id;
      $breads = [
          ['name'=>'User Advertisements', 'url'=> route('admin.paid_advertisement.index')],
          ['name'=>($id ? 'Edit': 'Add')]
      ];
      $advertisement = false;
      if($id) { $advertisement = Advertisement::find($id); }
      $medias  = Media::where('type','advertisement')->get();
      $_status = statusArray();
      return view('admin.paid_advertisements.form', compact('nav', 'breads', 'advertisement', 'medias', '_status'));
    }

    public function submit(Request $request) {
      $id = $request->id;
      if(!$id && !adminCan('astrologer-advertisement', 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan('astrologer-advertisement', 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
    $this->validate($request, [
    'title' => 'required|max:255',
    'page' => 'required|max:255',
    'start_date' => 'nullable|date',
    'end_date' => 'nullable|date|after:start_date',
    'amount' => 'required|numeric',
    ]);
    if($id){
    $advertisement = Advertisement::find($id);
    }else {
    $advertisement = new Advertisement();
    }
    $advertisement->image_id    = $request->image_id;
    $advertisement->title       = $request->title;
    $advertisement->page       = $request->page;
    $advertisement->content     = $request->content;
    $advertisement->link 		= $request->link;
    $advertisement->start_date 	= $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
    $advertisement->end_date    = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null;
    $advertisement->status 		= $request->status?:0;
    $advertisement->order 		= $request->order ? $request->order : 0;
    $advertisement->amount 		= $request->amount?:0;

    $advertisement->save();

    return response()->json(['message'=>'Advertisement Saved Successfully', 'redirectto'=>route('admin.paid_advertisement.edit', [$advertisement->id])], 200);

    }

    public function delete(Request $request)
    {
      if(!adminCan('astrologer-advertisement', 'delete')) return dd('No Permission');
      if($advertisement_id = $request->advertisement_id) {
            $advertisement = Advertisement::find($advertisement_id);
            $advertisement->delete();
            return response()->json(['message'=>'Advertisement Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request)
    {
      if(!adminCan('astrologer-advertisement', 'add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Advertisement::find($id);
    $nitem = $item->replicate();
    $nitem->title =  $item->title.' Cloned';
        $nitem->save();

    return response()->json(['message'=>'Cloned successfully! '], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }
}
