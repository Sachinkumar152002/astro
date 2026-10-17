<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Meta {

  public function sendEvent($body) {
    /*
    $pixelId = fromSetting("meta_pixel_id");
    $capiAccessToken = fromSetting("meta_capi_access_token");
    $apiVersion = "v24.0";
    $url = "https://graph.facebook.com/{$apiVersion}/{$pixelId}/events";
    $payload = [
      'access_token' => $capiAccessToken,
      'data' => [$body],
    ];
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      ])->post($url, $payload);
      return $response->json();
        */
    }

}
