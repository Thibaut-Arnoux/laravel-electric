<?php

namespace App\Http\Controllers\V1;

use App\Actions\StoreItemUserAction;
use App\Actions\UpdateItemUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexItemUserRequest;
use App\Http\Requests\V1\StoreItemUserRequest;
use App\Http\Requests\V1\UpdateItemUserRequest;
use App\Http\Responses\V1\ElectricResponse;
use App\Models\ItemUser;
use App\Services\ElectricService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemUserController extends Controller
{
    /**
     * Display a listing of favorite items for the authenticated user.
     */
    public function index(IndexItemUserRequest $request, ElectricService $electric): StreamedResponse|JsonResponse
    {
        return $electric->getItemsUser(query: $request->safe()->all());
    }

    /**
     * Store a newly created item-user relationship.
     */
    public function store(StoreItemUserRequest $request, StoreItemUserAction $action): JsonResponse
    {
        $response = new ElectricResponse(
            callback: fn () => $action($request->validated())
        );

        return $response->toResponse($request);
    }

    /**
     * Update the specified item-user relationship.
     */
    public function update(
        ItemUser $item,
        UpdateItemUserRequest $request,
        UpdateItemUserAction $action
    ): JsonResponse {
        $response = new ElectricResponse(
            callback: fn () => $action($item, $request->validated())
        );

        return $response->toResponse($request);
    }
}
