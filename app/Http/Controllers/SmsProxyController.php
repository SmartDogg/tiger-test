<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelNumberRequest;
use App\Http\Requests\GetNumberRequest;
use App\Http\Requests\GetSmsRequest;
use App\Http\Requests\GetStatusRequest;
use App\Services\SmsApiService;
use Illuminate\Http\JsonResponse;

class SmsProxyController extends Controller
{
    public function __construct(
        private readonly SmsApiService $smsApiService
    ) {}

    public function getNumber(GetNumberRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Redis: кэш номеров по стране и сервису на 30 секунд
        $result = $this->smsApiService->getNumber($data);
        
        return response()->json($result);
    }

    public function getSms(GetSmsRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Redis: кэш SMS по activation_id на 10 секунд, чтобы не спамили
        $result = $this->smsApiService->getSms($data);
        
        return response()->json($result);
    }

    public function cancelNumber(CancelNumberRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        $result = $this->smsApiService->cancelNumber($data);
        
        // Redis: удалить связанные кэши
        return response()->json($result);
    }

    public function getStatus(GetStatusRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Redis: кэш статуса на 15 секунд
        $result = $this->smsApiService->getStatus($data);
        
        return response()->json($result);
    }
}
