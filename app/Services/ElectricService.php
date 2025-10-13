<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ElectricUserColumnsEnum;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ElectricService
{
    /**
     * @param  array<string, string>  $query
     */
    private function get(array $query): JsonResponse
    {
        try {
            $response = Http::timeout(config('services.electric.timeout'))
                ->get(
                    url: config('services.electric.url'),
                    query: [...$query, 'secret' => config('services.electric.secret')]
                )->throw();

            return $this->formatResponse($response);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        } catch (ConnectionException $e) {
            return $this->handleConnectionException($e);
        } catch (\Throwable $e) {
            return $this->handleThrowable($e);
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
            data: $response->json(),
            status: $response->status(),
        )->withHeaders($headers);
    }

    private function handleRequestException(RequestException $e): JsonResponse
    {
        Log::error('ElectricSQL service error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json(
            data: $e->response->json(),
            status: $e->response->status(),
        );
    }

    private function handleConnectionException(ConnectionException $e): JsonResponse
    {
        Log::error('ElectricSQL service unavailable', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'Service unavailable',
            'message' => 'ElectricSQL service is not responding',
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    private function handleThrowable(\Throwable $e): JsonResponse
    {
        Log::error('ElectricSQL service error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'Service error',
            'message' => 'Failed to process request',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param  array<string, string>  $query
     */
    public function getUser(array $query): JsonResponse
    {
        $query = [
            'table' => 'users',
            'columns' => ElectricUserColumnsEnum::values(),
            ...$query,
        ];

        return $this->get($query);
    }
}
