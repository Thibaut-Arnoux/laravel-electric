<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ElectricAuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): JsonResponse
    {
        // TODO : update migration to uuid with real user
        Auth::loginUsingId(1);

        $request->session()->regenerate();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
