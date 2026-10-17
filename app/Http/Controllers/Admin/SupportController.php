<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Support;
use App\Model\SupportReplies;
use Auth;

class SupportController extends Controller
{
  public function index(Request $request)
  {
    if(!adminCan('support','view')) return dd('No Permission');
    $nav = 'support';
      $breads = [
          ['name'=>'Support']
      ];
      return view('admin.support.list', compact('nav','breads'));
  }

  public function all(Request $request){
    $tickets = Support::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
     if($ticket_number = $request->ticket_number){
         $tickets->where('ticket_number', 'like', "%$ticket_number%");
     }
     if($subject = $request->subject){
         $tickets->where('subject', 'like', "%$subject%");
     }
    if($type = $request->type){
        $tickets->where('type', $type);
    }
    if($status = $request->status){
        $tickets->where('status', 'like', "%$status%");
    }
    if($created_at_from = $request->created_at_from) {
        $tickets->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ? " , $created_at_from);
    }
    if($created_at_to = $request->created_at_to) {
        $tickets->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ? " , $created_at_to);
    }
     $tickets = $tickets->paginate(10);

      $data = [
          'rows' => view('admin.support.listrows',['tickets'=>$tickets])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$tickets])->render(),
      ];
      return response()->json($data, 200);
  }

  public function form(Request $request)
  {
      $nav = 'support'; // Navigation identifier
      $id = $request->id; // Course ID (for edit)
      $breads = [
          ['name' => 'Support', 'url' => route('admin.support')],
          ['name' => ($id ? 'Edit' : 'Add')]
      ];

      $ticket = $id ? Support::find($id) : false;
      return view('admin.support.form', compact('nav', 'breads', 'ticket'));
  }

  public function submit(Request $request) {
    $id = $request->id;
    if(!$id && !adminCan('support', 'add')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
    if($id && !adminCan('support', 'update')){
      return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
    }
    $userid = Auth::guard('admin')->user()->id;
    $this->validate($request, [
    'subject' => 'required',
        // 'type' => 'required',
    'message' => 'required',
    'status' => 'required',
        ],[
    'subject.required' => 'Subject is required',
    // 'type.required' => 'Type is required',
    'message.required' => 'Message is required',
    ]);

    if($id) {
    $support = Support::find($id);
    }else {
    $support = new Support();
    }
    $support->subject = $request->subject;
    // $support->type = $request->type;
    $support->message = $request->message;
    $support->status = $request->status;
    $support->save();
    return response()->json(['message' => 'Support Saved Successfully', 'redirectto' => route('admin.support')], 200);
    }

  public function delete(Request $request)
  {
    if(!adminCan('support','delete')) return dd('No Permission');
    if($support_id = $request->id) {
      $support = Support::find($support_id);
      $support->delete();
      return response()->json(['message'=>'Support Deleted successfully!'], 200);
    }
    return response()->json(['message'=>'Error in deleting data'], 401);
  }

  public function replies(Request $request){
    $id = $request->id;
    $ticket = $id ? Support::find($id) : false;
    $image = asset('assets/images/profile.png');
    if($ticket->user_type=='user'){
      $userName = $ticket->user?$ticket->user->name:'';
      $userPhone = $ticket->user?$ticket->user->phone:'';
      $userEmail = $ticket->user?$ticket->user->email:'';
      $image = $ticket->user && $ticket->user->image? $ticket->user->image->image(200, 200) : asset('assets/images/profile.png');
    }elseif($ticket->user_type=='astrologer'){
      $userName = $ticket->astrologer?$ticket->astrologer->display_name:'';
      $userPhone = $ticket->astrologer?$ticket->astrologer->phone:'';
      $userEmail = $ticket->astrologer?$ticket->astrologer->email:'';
      $image = $ticket->astrologer && $ticket->astrologer->image? $ticket->astrologer->image->image(200, 200) : asset('assets/images/profile.png');
    }
    return response()->json(['data'=>view('admin.support.replies', compact('image', 'ticket'))->render()], 200);
  }
}
