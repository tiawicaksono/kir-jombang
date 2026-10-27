<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TotalUjiHariIniService;
use Illuminate\Http\JsonResponse;

class TotalUjiHariIniController extends Controller
{
    public function __construct(
        private readonly TotalUjiHariIniService $totalUjiHariIniService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->totalUjiHariIniService->getTotal()
        );
    }
}