<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Support, SupportReplies};
use App\Jobs\{SupportUserJob};
use PDF;
use File;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{SupportAdminNotification};

class SupportController extends Controller
{
  public function types(Request $request){
    $typesData = [
      [
        'name'=>'Product',
        'value'=>'product',
      ],
      [
        'name'=>'Astrologer',
        'value'=>'astrologer',
      ],
      [
        'name'=>'Platform',
        'value'=>'platform',
      ],
      [
        'name'=>'Other',
        'value'=>'other',
      ],
    ];
     return response()->json([
       'success' => true,
        'data' => $typesData,
     ], 200);
  }

  public function submitTicket(Request $request){
    $this->validate($request, [
      'subject'=>'required',
      'type'=>'required',
      'message'=>'required',
    ],[
      'subject.required'=>'Subject is required',
      'type.required'=>'Type is required',
      'message.required'=>'Message is required',
    ]);
    $user = $request->user();
    $user_type = 'user';
    $type = $request->type;
    $support = Support::where('user_id', $user->id)->where('user_type', $user_type)->where('type', $type)->where('status', 'open')->first();
    if($support){
      return response()->json(['success'=>false, 'message'=>'You already raised ticked for this type.'], 403);
    }
    $filePath = '';
    if($request->hasFile('document')){
      $this->validate($request, [
        'document'=>'mimes:pdf,jpg,jpeg,png|max:2048',
      ]);
      $filePath = public_path('support');
      if(!File::exists($filePath)) {
          File::makeDirectory($filePath, 0777, true);
      }
      $filePath = public_path('support/user');
      if(!File::exists($filePath)){
          File::makeDirectory($filePath, 0777, true);
      }
      $filePath = public_path('support/user/'.$type);
      if(!File::exists($filePath)){
        File::makeDirectory($filePath);
      }
      $time = time();
      $fileName = $time.'.'.$request->document->getClientOriginalExtension();
      $request->document->move($filePath, $fileName);
      $filePath = 'support/user/'.$type.'/'.$fileName;
    }
    $support = new Support();
    $support->user_id = $user->id;
    $support->user_type = $user_type;
    $support->subject = $request->subject;
    $support->type = $type;
    $support->message = $request->message;
    $support->file = $filePath;
    $support->status = 'open';
    $support->save();

    $ticket_number = 'REQ-'.date('ym');
    if($support->id <= 9){
      $ticket_number = 'REQ-'.date('ym').'0000'.$support->id;
    }elseif($support->id <= 99){
      $ticket_number = 'REQ-'.date('ym').'000'.$support->id;
    }elseif($support->id <= 999){
      $ticket_number = 'REQ-'.date('ym').'00'.$support->id;
    }elseif($support->id <= 9999){
      $ticket_number = 'REQ-'.date('ym').'0'.$support->id;
    }else{
      $ticket_number = 'REQ-'.date('ym').$support->id;
    }
    $support->ticket_number = $ticket_number;
    $support->save();

    SupportUserJob::dispatch($user, $support);
    Notification::route('mail', [
      config('vrdcms.custome_care_email') => 'Customer Care', //admin/
    ])->notify(new SupportAdminNotification($user, 'user', $support));
    $data['ticket_number'] = $support->ticket_number;
    return response()->json(['success' => true, 'message' => 'Thank You! Your query has been submitted successfully.', 'data'=>$data], 200);
  }

  public function list(Request $request){
    $user = $request->user();
    $user_type = 'user';
    // $tickets = Support::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'DESC')->limit(8)->get();
    $tickets = Support::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'DESC')->get();
    $list = $tickets->map(function($item){
      $d['id'] = $item->id;
      $d['status'] = $item->status;
      $d['ticket_number'] = $item->ticket_number;
      $d['type'] = ucfirst($item->type);
      $d['subject'] = $item->subject;
      $d['message'] = $item->message;
      $d['document'] = '';
      if($item->file){
        $d['document'] = asset($item->file);
      }
      $d['created_at'] = date('d-m-Y', strtotime($item->created_at));
      return $d;
    });
    $data['list'] = $list;
    return response()->json(['success' => true, 'data'=>$data], 200);
  }

  public function messages(Request $request){
    $this->validate($request, [
      'ticket_id'=>'required|gt:0'
    ]);
    $user = $request->user();
    $user_type = 'user';
    $ticket_id = $request->ticket_id;
    $support = Support::where('id', $ticket_id)->where('user_id', $user->id)->first();
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'Ticket not found'], 404);
    }
    $ticket['id'] = $support->id;
    $ticket['status'] = $support->status;
    $ticket['ticket_number'] = $support->ticket_number;
    $ticket['type'] = ucfirst($support->type);
    $ticket['subject'] = $support->subject;
    $ticket['document'] = '';
    if($support->file){
      $ticket['document'] = asset($support->file);
    }
    $ticket['created_at'] = date('d-m-Y', strtotime($support->created_at));
    $messages = [
      [
      'message_by'=>'user',
      'message'=>$support->message,
      'created_at'=>date('h:i a', strtotime($support->created_at))
      ]
    ];
    $replies = SupportReplies::where('support_id', $support->id)->get();
    foreach ($replies as $index => $item) {
      $d['message_by']=$item->reply_type=='user'?'user':'admin';
      $d['message']=$item->reply;
      $d['created_at']=date('h:i a', strtotime($item->created_at));
      array_push($messages, $d);
    }
    $data['ticket'] = $ticket;
    $data['messages'] = $messages;
    return response()->json(['success' => true, 'data'=>$data], 200);
  }

  public function submitMessage(Request $request){
    $this->validate($request, [
      'message'=>'required',
      'ticket_id'=>'required|gt:0'
    ]);
    $user = $request->user();
    $user_type = 'user';
    $ticket_id = $request->ticket_id;
    $support = Support::where('id', $ticket_id)->where('user_id', $user->id)->first();
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'Ticket not found'], 404);
    }
    $reply = new SupportReplies();
    $reply->support_id = $support->id;
    $reply->reply = $request->message;
    $reply->reply_by = $user->id;
    $reply->reply_type = 'user';
    $reply->save();
    $message = [
      'message_by'=>'user',
      'message'=>$reply->reply,
      'created_at'=>date('h:i a', strtotime($reply->created_at))
    ];
    $data['message'] = $message;
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }
}
