<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ExotelService
{
    protected $client;
    protected $apiKey;
    protected $apiToken;
    protected $apiSid;
    protected $callerId;

    public function __construct(){
      $this->client = new Client();
      $this->apiKey = fromSetting('exotel_api_key');
      $this->apiToken = fromSetting('exotel_api_token');
      $this->apiSid = fromSetting('exotel_api_sid');
      $this->callerId = fromSetting('exotel_api_caller_id');
    }

    /**
     * Initiate a call using the Exotel API.
     *
     * @param string $from The caller's phone number.
     * @param string $to The recipient's phone number.
     *
     * @return array The API response data.
     */
    public function clickToCall($from, $to, $timeOut, $appointment_id)
    {
      $statusCallback = route('call.statusCallback', [$appointment_id]);
        $url = "https://api.exotel.com/v1/Accounts/moonastro1/Calls/connect.json";
        $auth = [config('vrdcms.exotel_api_key'), config('vrdcms.exotel_api_token')];
        $params = [
            'From' => $from,
            'To' => $to,
            'CallerId' => config('vrdcms.exotel_caller_id'),
            'CallType' => 'trans',
            'TimeLimit' => $timeOut,
            'Record' => "true",
            'StatusCallback' => $statusCallback,
            // 'StatusCallbackEvents' => ['terminal', 'answered'],
            'StatusCallbackEvents' => ['terminal'],
        ];

        try {
            $response = $this->client->post($url, [
                'auth' => $auth,
                'form_params' => $params,
            ]);

            $response->getBody()->seek(0); // Reset the response body pointer
            $responseData = json_decode($response->getBody()->getContents(), true);
            // Log::useDailyFiles(storage_path().'/logs/calllog.log');
            Log::info('Exotel API Response: ', $responseData);
            return $responseData;

        } catch (RequestException $e) {
            Log::error('Exotel API Error: ', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? (string)$e->getResponse()->getBody() : null
            ]);

            return [
                'error' => true,
                'message' => 'An error occurred while trying to initiate the call.',
                'details' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode((string)$e->getResponse()->getBody(), true) : null

            ];
        } catch (\Exception $e) {
            Log::error('Exotel General Error: ', [
                'message' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ];
        }
    }
   public function sendSms($from, $to, $body){
      $post_fields = http_build_query([
          'From' => $from,
          'To' => $to,
          'Body' => $body,
          'DltEntityId' => "1701168468655900113",
          'DltTemplateId' => "1707175343191192796",
      ]);
      $api_key=$this->apiKey;
      $api_token=$this->apiToken;
      $api_sid = $this->apiSid;
      $authorization_token=base64_encode("$api_key:$api_token");
      $url = "https://api.exotel.com/v1/Accounts/{$api_sid}/Sms/send.json";
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => [
          "Authorization: Basic $authorization_token",
          "accept: application/json"
        ],
      ]);
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if($err){
        return false;
      }
      return true;
   }
}
