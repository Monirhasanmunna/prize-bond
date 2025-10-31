<?php

namespace App\Http\Services\Feature\User;

use Illuminate\Support\Facades\Http;

class RawNotification
{
    protected string $projectId;
    protected string $serviceAccountPath;

    public function __construct()
    {
        $this->projectId         = config('services.firebase.project_id');
        $this->serviceAccountPath = config('services.firebase.service_account');
    }

    protected function getServiceAccount(): array
    {
        $json = file_get_contents($this->serviceAccountPath);
        return json_decode($json, true);
    }


    protected function getAccessToken(): string
    {
        $sa = $this->getServiceAccount();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $now = time();
        $claims = [
            'iss' => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwtHeader  = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $jwtClaims  = rtrim(strtr(base64_encode(json_encode($claims)), '+/', '-_'), '=');
        $signatureInput = $jwtHeader . '.' . $jwtClaims;

        openssl_sign($signatureInput, $signature, $sa['private_key'], 'sha256WithRSAEncryption');
        $jwtSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $jwtHeader . '.' . $jwtClaims . '.' . $jwtSignature;

        // এখন এই jwt দিয়ে access token আনবো
        $resp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if (! $resp->successful()) {
            throw new \Exception('Unable to fetch access token: '.$resp->body());
        }

        return $resp->json('access_token');
    }


    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        $accessToken = $this->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data,
            ],
        ];

        $resp = Http::withToken($accessToken)->post($url, $payload);

        return [
            'ok'     => $resp->successful(),
            'status' => $resp->status(),
            'body'   => $resp->json(),
        ];
    }
}
