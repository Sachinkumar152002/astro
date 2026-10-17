<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Model\{ApiLog, GptResponse, AiAstrologerAppointmentChat};

class GptController extends Controller {
  public function getResponse(Request $request){
    $request->validate([
      'prompt' => 'required|string|max:1000',
    ]);
    $user_id=$request->user_id?:0;
    $consultation_id=$request->consultation_id?:0;
    $prompt=$request->prompt;
    // $aiAstrologerAppointmentChat=AiAstrologerAppointmentChat::where('appointment_id', $consultation_id)->limit(2)->get();
    $aiAstrologerAppointmentChat=AiAstrologerAppointmentChat::where('appointment_id', $consultation_id)->get();
    $url="https://api.openai.com/v1/responses";
    $body["model"]="gpt-4.1-mini-2025-04-14";
    // $body["model"]="gpt-4.1-2025-04-14";
    $body["temperature"]=0.2;
    $body["input"]=[];
    foreach($aiAstrologerAppointmentChat as $index => $item){
      $role = $item->message_by;
      if($role == "astrologer"){
        $role = "assistant";
      }
      $message = $item->message;
      $body["input"][]=["role" => $role, "content" => $message];
    }
    $body["input"][]=["role" => "system", "content" => "Give accurate and short answers only."];
    $body["input"][]=["role"=>"user", "content"=>$prompt];
    $openai_api_key = fromSetting("openai_api_key");

    $response = Http::withHeaders([
      'Authorization' => "Bearer $openai_api_key",
      'Content-Type' => 'application/json',
    ])->post($url, $body);

    $data = $response->json();

    $apiLog=new ApiLog();
    $apiLog->table_name="gpt_responses";
    $apiLog->table_id=0;
    $apiLog->name=$url;
    $apiLog->request=json_encode($body);
    $apiLog->response=$response;
    $apiLog->api_status=0;
    $apiLog->save();

    if(isset($data['error'])){
      return "";
    }else{
      $gptResponse=new GptResponse();
      $gptResponse->prompt=$message;
      $gptResponse->response=$response;
      $gptResponse->save();

      $apiLog->table_id=$gptResponse->id;
      $apiLog->api_status=1;
      $apiLog->save();
      return isset($data["output"]) ? $response : "";
    }
  }
}
