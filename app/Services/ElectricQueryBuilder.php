<?php

declare(strict_types=1);

namespace App\Services;

class ElectricQueryBuilder
{
    /**
     * @param  array<string, mixed>  $query  Built query parameters
     */
    public function __construct(
        private array $query = []
    ) {
        $this->query = [
            'secret' => config('services.electric.secret'),
        ];
    }

    /**
     * Set the table name.
     */
    public function table(string $table): static
    {
        $this->query['table'] = $table;

        return $this;
    }

    /**
     * Set the safe columns from model's shape.
     */
    public function columns(string $safeColumns): static
    {
        $this->query['columns'] = $safeColumns;

        return $this;
    }

    /**
     * Set the WHERE clause for filtering.
     */
    public function where(string $where): static
    {
        $this->query['where'] = $where;

        return $this;
    }

    /**
     * Set positional parameters for WHERE clause.
     * 
     * Electric's HTTP API accepts parameters via individual query params (params[1]=value, params[2]=value)
     * which are used to safely substitute $1, $2 placeholders in WHERE clauses.
     * This prevents SQL injection while maintaining type safety.
     *
     * @param  array<int, mixed>  $params
     */
    public function params(array $params): static
    {
        foreach ($params as $index => $value) {
            $this->query['params['.($index + 1).']'] = (string) $value;
        }

        return $this;
    }

    /**
     * Set subset WHERE clause for snapshot filtering.
     */
    public function subsetWhere(string $subsetWhere): static
    {
        $this->query['subset__where'] = $subsetWhere;

        return $this;
    }

    /**
     * Set JSON-encoded parameters for subset WHERE clause.
     *
     * Electric SQL expects a JSON object with numeric string keys mapping to parameter values.
     * Example: {"1":"value1","2":"value2"} for $1 and $2 placeholders.
     *
     * @param  array<int, mixed>  $params
     */
    public function subsetParams(array $params): static
    {
        $jsonParams = [];
        foreach ($params as $index => $value) {
            $jsonParams[(string) ($index + 1)] = (string) $value;
        }

        $this->query['subset__params'] = json_encode($jsonParams);

        return $this;
    }

    /**
     * Set ORDER BY clause for subset snapshots.
     */
    public function subsetOrderBy(string $subsetOrderBy): static
    {
        $this->query['subset__order_by'] = $subsetOrderBy;

        return $this;
    }

    /**
     * Set validated client parameters.
     *
     * @param  array<string, mixed>  $clientParams
     */
    public function withClientParams(array $clientParams): static
    {
        $this->query = [...$this->query, ...$clientParams];

        return $this;
    }

    /**
     * Build and return the final query array.
     *
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->query;
    }
}
