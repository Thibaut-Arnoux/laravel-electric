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
        try {
            $endpoint = $this->buildShapeEndpoint($request->query());
            $headers = $this->prepareHeaders($request);

            $response = Http::withHeaders($headers)
                ->timeout(config('services.electric.timeout'))
                ->get($endpoint);

            return $this->formatResponse($response);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        } catch (\Exception $e) {
            return $this->handleGeneralException($e);
        }
    }

    /**
     * @param  array<string, string>  $queryParams
     */
    private function buildShapeEndpoint(array $queryParams): string
    {
        $endpoint = rtrim(config('services.electric.url'), '/').'/v1/shape';

        if (! empty($queryParams)) {
            $endpoint .= '?'.http_build_query($queryParams);
        }

        return $endpoint;
    }

    /**
     * @return array<string, string>
     */
    private function prepareHeaders(Request $request): array
    {
        return collect($request->headers->all())
            ->except(['host', 'content-length', 'connection', 'accept-encoding'])
            ->mapWithKeys(function ($value, $key) {
                return [$key => implode(', ', $value)];
            })
            ->toArray();
    }

    private function formatResponse(ClientResponse $response): JsonResponse
    {
        $headers = collect($response->headers())
            ->except(['transfer-encoding', 'connection'])
            ->all();

        $data = $response->json();

        return response()->json(
            $data,
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
