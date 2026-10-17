<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendTemplateMessage(
        array $payload,
        ?string $phoneNumberId = null,
        ?string $domain = null,
        ?string $version = null,
        ?string $accessToken = null
    ): array {
        $domain = $domain ?? config('vrdcms.whatsapp_meta_domain', 'https://graph.facebook.com');
        $version = $version ?? config('vrdcms.whatsapp_meta_version', 'v18.0');
        $phoneNumberId = $phoneNumberId ?? config('vrdcms.whatsapp_phone_number_id');
        $accessToken = $accessToken ?? config('vrdcms.whatsapp_meta_access_token');

        $url = rtrim($domain, '/') . '/' . ltrim($version, '/') . '/' . $phoneNumberId . '/messages';

        $headers = ['Content-Type: application/json'];
        if ($accessToken) {
            $headers[] = 'Authorization: Bearer ' . $accessToken;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseBody = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            Log::error('WhatsApp Meta API cURL error', [
                'url' => $url,
                'message' => $curlError,
                'payload' => $payload,
            ]);
            return [
                'success' => false,
                'response' => ['error' => $curlError],
                'status' => 0,
            ];
        }

        $body = json_decode($responseBody, true) ?? [];
        $success = $httpCode >= 200 && $httpCode < 300;

        if (!$success) {
            Log::warning('WhatsApp Meta API error', [
                'url' => $url,
                'status' => $httpCode,
                'body' => $body,
                'payload' => $payload,
            ]);
        }

        return [
            'success' => $success,
            'response' => $body,
            'status' => $httpCode,
        ];
    }

    /**
     * Send order confirmation template (whatsapp_order_confirmation) when order is placed successfully.
     * Template body has 4 text parameters: e.g. order_number, customer_name, total_amount, order_date.
     *
     * @param string $phoneCode e.g. 91
     * @param string $phone 10-digit phone
     * @param string $param1 First body variable (e.g. order number)
     * @param string $param2 Second body variable (e.g. customer name)
     * @param string $param3 Third body variable (e.g. total amount)
     * @param string $param4 Fourth body variable (e.g. order date)
     */
    public static function sendOrderConfirmation(
        string $phoneCode,
        string $phone,
        string $param1,
        string $param2,
        string $param3,
        string $param4
    ): array {
        $to = preg_replace('/\D/', '', $phoneCode) . preg_replace('/\D/', '', $phone);

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'recipient_type' => 'individual',
            'type' => 'template',
            'template' => [
                'name' => 'whatsapp_order_confirmation',
                'language' => [
                    'policy' => 'deterministic',
                    'code' => 'en',
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $param1],
                            ['type' => 'text', 'text' => $param2],
                            ['type' => 'text', 'text' => $param3],
                            ['type' => 'text', 'text' => $param4],
                        ],
                    ],
                ],
            ],
        ];

        $result = self::sendTemplateMessage($payload);
        if (empty($result['success'])) {
            Log::warning('WhatsApp order confirmation failed', [
                'to' => $to,
                'params' => [$param1, $param2, $param3, $param4],
                'response' => $result['response'] ?? null,
                'status' => $result['status'] ?? null,
            ]);
        }
        return $result;
    }

}
