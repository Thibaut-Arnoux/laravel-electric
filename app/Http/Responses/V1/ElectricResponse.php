<?php

declare(strict_types=1);

namespace App\Http\Responses\V1;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ElectricResponse implements Responsable
{
    public function __construct(
        private readonly \Closure $callback
    ) {}

    public function toResponse($request): JsonResponse
    {
        return DB::transaction(function () {
            ($this->callback)();

            $result = DB::select('SELECT pg_current_xact_id()::xid::text as txid');
            $txid = (int) $result[0]->txid;

            return response()->json(['txid' => $txid]);
        });
    }
}
