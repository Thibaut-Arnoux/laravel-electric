<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ElectricSqlProxyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO : filter query params forward to electric service
        // use ELECTRIC_PROTOCOL_QUERY_PARAMS from :
        // https://github.com/electric-sql/electric/blob/main/packages/typescript-client/src/constants.ts
        // manage table, where, columns (via FormRequest ?) as it is explain in :
        // https://tanstack.com/db/latest/docs/collections/electric-collection#electric-proxy-example
        try {
            $response = Http::timeout(config('services.electric.timeout'))
                ->get(config('services.electric.url'), $request->query());

            return $this->formatResponse($response);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        } catch (\Exception $e) {
            return $this->handleGeneralException($e);
        }
    }

    private function formatResponse(ClientResponse $response): JsonResponse
    {
        // headers to remove from response
        // @see : https://tanstack.com/db/latest/docs/collections/electric-collection#electric-proxy-example
        $headers = collect($response->headers())
            ->except(['content-encoding', 'content-length', 'transfer-encoding'])
            ->all();

        return response()->json(
            $response->json(),
            $response->status()
        )->withHeaders($headers);
    }

    private function handleRequestException(RequestException $e): JsonResponse
    {
        Log::warning('ElectricSQL service unavailable', [
            'message' => $e->getMessage(),
            'response' => $e->response->body(),
        ]);

        return response()->json([
            'error' => 'Service unavailable',
            'message' => 'ElectricSQL service is not responding',
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    private function handleGeneralException(\Exception $e): JsonResponse
    {
        Log::error('ElectricSQL proxy error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'Proxy error',
            'message' => 'Failed to process request',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
