<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Item;
use App\Models\ItemUser;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElectricService
{
    /**
     * @param  array<string, string>  $query
     */
    private function get(array $query): StreamedResponse|JsonResponse
    {
        try {
            $response = Http::timeout(config('services.electric.timeout'))
                ->withOptions(['stream' => true])
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

    private function formatResponse(ClientResponse $response): StreamedResponse
    {
        // headers management
        // @see : https://tanstack.com/db/latest/docs/collections/electric-collection#electric-proxy-example
        // @see : https://electric-sql.com/docs/guides/auth#the-solution-vary-header
        $headers = collect($response->headers())
            ->except(['content-encoding', 'content-length'])
            ->put('vary', 'cookie')
            ->all();

        // waiting fix from laravel on condition for ob_flush in Illuminate\Routing\ResponseFactory::stream
        if (! ob_get_level()) {
            ob_start();
        }

        return response()->stream(function () use ($response): \Generator {
            $body = $response->toPsrResponse()->getBody();

            while (! $body->eof()) {
                yield $body->read(16384);
            }
        }, status: $response->status(), headers: $headers);
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
    public function getUsers(array $query): StreamedResponse|JsonResponse
    {
        $query = [
            'columns' => User::getImplodedShape(),
            ...$query,
            'table' => 'users',
        ];

        return $this->get($query);
    }

    /**
     * @param  array<string, string>  $query
     */
    public function getItems(array $query): StreamedResponse|JsonResponse
    {
        $query = [
            'columns' => Item::getImplodedShape(),
            ...$query,
            'table' => 'items',
        ];

        return $this->get($query);
    }

    /**
     * @param  array<string, string>  $query
     */
    public function getItemsUser(array $query): StreamedResponse|JsonResponse
    {
        $query = [
            'columns' => ItemUser::getImplodedShape(),
            ...$query,
            'table' => 'item_user',
            'where' => 'user_id = '.auth()->id(),
        ];

        return $this->get($query);
    }
}
