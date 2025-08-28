<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ElectricUserRequest;
use App\Services\ElectricService;
use Illuminate\Http\JsonResponse;

class ElectricUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ElectricUserRequest $request, ElectricService $electric): JsonResponse
    {
        return $electric->getUser(query: $request->safe()->all());
    }
}
