<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexUserRequest;
use App\Services\ElectricService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexUserRequest $request, ElectricService $electric): StreamedResponse|JsonResponse
    {
        return $electric->getUsers(query: $request->safe()->all());
    }
}
