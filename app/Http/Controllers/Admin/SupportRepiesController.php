<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Support;
use App\Model\SupportReplies;
use Auth;

class SupportRepiesController extends Controller
{
  public function index(Request $request)
  {
    $nav = 'replies';
    $support_id = $request->support_id;
    $support = Support::find($support_id);
      $breads = [
        ['name' => 'Support', 'url' => route('admin.support')],
        ['name'=>'Replies']
      ];
      return view('admin.support.replies.list', compact('nav','breads', 'support'));
  }

  public function all(Request $request){
    $support_id = $request->support_id;
    $support = Support::find($support_id);
    $replies = SupportReplies::where('support_id', $support_id)->orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
     if($reply = $request->reply){
         $replies->where('reply', 'like', "%$reply%");
     }
    if($reply_type = $request->reply_type) {
        $replies->where('reply_type', $reply_type);
    }
    if($created_at_from = $request->created_at_from) {
        $replies->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ? " , $created_at_from);
    }
    if($created_at_to = $request->created_at_to) {
        $replies->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ? " , $created_at_to);
    }
     $replies = $replies->paginate(10);

      $data = [
          'rows' => view('admin.support.replies.listrows',['replies'=>$replies, 'support'=>$support])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$replies])->render(),
          'support' => $support,
          'reply_type' => $reply_type,
      ];
      return response()->json($data, 200);
  }

  public function form(Request $request)
  {
    $support_id = $request->support_id;
    $support = Support::find($support_id);
      $nav = 'replies'; // Navigation identifier
      $support_id = $request->support_id;
      $id = $request->id; // Course ID (for edit)
      $breads = [
          ['name' => 'Support', 'url' => route('admin.support')],
          ['name' => 'Replies', 'url' => route('admin.support.replies', [$support_id])],
          ['name' => ($id ? 'Edit' : 'Add')]
      ];

      $replies = $id ? SupportReplies::find($id) : false;
      return view('admin.support.replies.form', compact('nav', 'breads', 'replies', 'support'));
  }

  public function submit(Request $request) {
    $support_id = $request->support_id;
    $id = $request->id;
    $userid = Auth::guard('admin')->user()->id;
      $this->validate($request, [
      'reply' => 'required',
        ],[
    'reply.required' => 'Reply is required',
      ]);
    $support = Support::find($support_id);

    if($id) {
    $reply = SupportReplies::find($id);
    }else {
    $reply = new SupportReplies();
      $reply->support_id = $support_id;
      $reply->reply_type = 'admin';
    }
    $reply->reply = $request->reply;
    $reply->reply_by = $userid;
    $reply->save();
    // return response()->json(['message' => 'Reply Saved Successfully', 'redirectto' => route('admin.support.replies', [$support->id])], 200);
    return response()->json(['message' => 'Reply Saved Successfully', 'redirectto' => route('admin.support.edit', [$support->id])], 200);
    }

  public function delete(Request $request){
    if($id = $request->id) {
      $reply = SupportReplies::find($id);
      $reply->delete();
      return response()->json(['message'=>'Reply Deleted successfully!'], 200);
    }
    return response()->json(['message'=>'Error in deleting data'], 401);
  }
}
