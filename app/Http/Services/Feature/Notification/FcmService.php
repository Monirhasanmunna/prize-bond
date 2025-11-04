<?php

// app/Services/FcmService.php
namespace App\Http\Services\Feature\Notification;

use Google\Client;
use Google\Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected string $credentialsPath;
    protected string $projectId;

    /**
     *
     */
    public function __construct()
    {
        $this->credentialsPath = config('services.fcm.credentials');
        $json = json_decode(file_get_contents($this->credentialsPath), true);
        $this->projectId = $json['project_id'];
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getAccessToken(): string
    {
        $client = new Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['access_token'])) {
            return $token['access_token'];
        }

        Log::error('FCM: unable to fetch access token', $token);

        throw new \Exception('FCM access token error');
    }

    /**
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     * @throws Exception
     * @throws ConnectionException
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = []): array
    {
        $endpoint = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                    ],
                ],
                'data' => $data,
            ]
        ];

        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($endpoint, $payload);

//        Log::info('FCM Response: ' . $response->body());

        return $response->json();
    }

    /**
     * Send to multiple tokens
     */
    public function sendToMany(array $tokens, string $title, string $body, array $data = []): void
    {
        foreach (array_chunk($tokens, 500) as $chunk) {
            foreach ($chunk as $token) {
                try {
                    $this->sendToToken($token, $title, $body, $data);
                    Log::info('Send Notification');
                } catch (\Throwable $e) {
                    Log::error('FCM send error for token '.$token.': '.$e->getMessage());
                }
            }
        }
    }


    /**
     * @param array $data
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        if (empty($data)) return [];

        // Convert list to key-value
        if (array_is_list($data)) {
            $out = [];
            foreach ($data as $i => $v) {
                $out["item_$i"] = is_scalar($v) ? (string) $v : json_encode($v);
            }
            return $out;
        }

        // Convert nested arrays/objects to strings
        return collect($data)
            ->map(fn($v) => is_scalar($v) ? (string)$v : json_encode($v))
            ->toArray();
    }

}
