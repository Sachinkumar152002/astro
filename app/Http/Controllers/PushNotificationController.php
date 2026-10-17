<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;

class PushNotificationController extends Controller
{
  /**
   * Send FCM (Firebase Cloud Messaging) v1 push notification.
   * Requires storage_path('app/fcm-service-account.json') and config('vrdcms.fcm_project_id').
   *
   * @param string $token FCM device token
   * @param string $title Notification title
   * @param string $body Notification body
   * @param array $data Optional key-value data (all values are cast to string for FCM v1)
   */
  public function sendNotification($token, $title, $body, $data = [])
  {
    if (empty($token) || !is_string($token) || trim($token) === '') {
      Log::warning('PushNotification: skipped empty token');
      return false;
    }

    $project_id = config('vrdcms.fcm_project_id');
    if (empty($project_id)) {
      Log::error('PushNotification: fcm_project_id not set in config');
      return false;
    }

    $fcmurl = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";

    // FCM v1 requires all data values to be strings
    $dataStrings = [];
    foreach ($data as $key => $value) {
      $dataStrings[(string) $key] = is_scalar($value) ? (string) $value : json_encode($value);
    }

    $message = [
      'token' => $token,
      'notification' => [
        'title' => $title,
        'body' => $body,
      ],
      'data' => $dataStrings,
    ];

    try {
      $accessToken = $this->getAccessToken();
      if (!$accessToken) {
        Log::error('PushNotification: failed to get FCM access token. Ensure storage/app/fcm-service-account.json exists.');
        return false;
      }

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
      ])->post($fcmurl, ['message' => $message]);

      if (!$response->successful()) {
        Log::error('PushNotification: FCM request failed', [
          'token_preview' => substr($token, 0, 20) . '...',
          'status' => $response->status(),
          'body' => $response->body(),
        ]);
        return false;
      }

      return true;
    } catch (Exception $e) {
      Log::error('PushNotification: exception sending to token', [
        'token_preview' => substr($token, 0, 20) . '...',
        'message' => $e->getMessage(),
      ]);
      return false;
    }
  }

  /**
   * Get OAuth2 access token using FCM service account JSON.
   */
  public function getAccessToken()
  {
    $path = storage_path('app/fcm-service-account.json');
    if (!file_exists($path)) {
      Log::warning('PushNotification: service account file not found at ' . $path);
      return null;
    }

    try {
      $credentials = new ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        $path
      );
      $authToken = $credentials->fetchAuthToken();
      return $authToken['access_token'] ?? null;
    } catch (Exception $e) {
      Log::error('PushNotification: getAccessToken failed', ['message' => $e->getMessage()]);
      return null;
    }
  }
}
