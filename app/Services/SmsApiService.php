<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsApiService
{
    private const API_URL = 'https://postback-sms.com/api/';
    private const TIMEOUT = 10;
    private const RETRY_TIMES = 3;
    private const RETRY_SLEEP = 100;

    public function getNumber(array $data): array
    {
        return $this->makeRequest(array_merge($data, ['action' => 'getNumber']));
    }

    public function getSms(array $data): array
    {
        return $this->makeRequest(array_merge($data, ['action' => 'getSms']));
    }

    public function cancelNumber(array $data): array
    {
        return $this->makeRequest(array_merge($data, ['action' => 'cancelNumber']));
    }

    public function getStatus(array $data): array
    {
        return $this->makeRequest(array_merge($data, ['action' => 'getStatus']));
    }

    private function makeRequest(array $params): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->withoutVerifying()
                ->retry(self::RETRY_TIMES, self::RETRY_SLEEP, function ($exception) {
                    return $exception instanceof ConnectionException;
                })
                ->get(self::API_URL, $params);

            if (!$response->successful()) {
                Log::error('SMS API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'params' => $this->sanitizeParams($params)
                ]);

                return [
                    'code' => 'error',
                    'message' => 'External API unavailable'
                ];
            }

            $data = $response->json();
            
            if (!is_array($data)) {
                return [
                    'code' => 'error',
                    'message' => 'Invalid API response format'
                ];
            }

            return $data;

        } catch (RequestException $e) {
            Log::error('SMS API request failed', [
                'error' => $e->getMessage(),
                'params' => $this->sanitizeParams($params)
            ]);

            return [
                'code' => 'error',
                'message' => 'Request failed'
            ];
        }
    }

    private function sanitizeParams(array $params): array
    {
        if (isset($params['token'])) {
            $params['token'] = substr($params['token'], 0, 8) . '...';
        }
        
        return $params;
    }
}