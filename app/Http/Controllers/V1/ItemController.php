<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexItemRequest;
use App\Services\ElectricService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexItemRequest $request, ElectricService $electric): StreamedResponse|JsonResponse
    {
        return $electric->getItems(clientParams: $request->safe()->all());
    }
}
