<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;

/**
 * Validates Electric SQL column selection against authorized model shapes.
 *
 * Electric SQL uses double-quoted PostgreSQL identifiers in comma-delimited format
 * for the `columns` query parameter. This rule ensures requested columns are
 * whitelisted in the model's shape to prevent:
 * - Information disclosure via unauthorized columns (e.g., password, tokens)
 * - Column enumeration attacks
 * - Data exposure beyond intended API surface
 *
 * Supports Electric SQL column format:
 * - Double-quoted column names: "id", "name", "email"
 * - Comma-delimited lists: "id","name","created_at"
 * - Mixed spacing: "id", "name", "email" or "id","name","email"
 *
 * Example usage in form requests:
 * ```php
 * 'columns' => ['sometimes', new ValidElectricColumns(allowedColumns: User::getShape())]
 * ```
 *
 * @see https://electric-sql.com/docs/api/http Electric SQL HTTP API
 * @see https://www.postgresql.org/docs/current/sql-syntax-lexical.html PostgreSQL identifiers
 */
class ValidElectricColumns implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param  string[]  $allowedColumns  Column names from model's shape
     */
    public function __construct(
        protected array $allowedColumns,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        $requestedColumns = $this->parseColumnList($value);

        if ($this->hasEmptyColumn($requestedColumns)) {
            $fail('The :attribute cannot contain empty column names.');

            return;
        }

        if (! $this->areAllColumnsAllowed($requestedColumns)) {
            $fail('Some of the :attribute does not exist or not authorized.');
        }
    }

    /**
     * Parse Electric SQL column list into individual column names.
     *
     * Handles comma-delimited, double-quoted column identifiers.
     * Removes quotes and whitespace, then deduplicates.
     *
     * @return Collection<int, string>
     *
     * @phpstan-return Collection<int, string>
     */
    protected function parseColumnList(string $columns): Collection
    {
        /** @var Collection<int, string> */
        return collect(explode(',', $columns))
            ->map(fn (string $column) => trim(trim($column), '"'))
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * Check if any column name is empty after parsing.
     *
     * @param  Collection<int, string>  $columns
     */
    protected function hasEmptyColumn(Collection $columns): bool
    {
        return $columns->isEmpty();
    }

    /**
     * Check if all requested columns are in the allowed shape.
     *
     * Uses strict comparison to ensure exact column name matching.
     *
     * @param  Collection<int, string>  $requestedColumns
     */
    protected function areAllColumnsAllowed(Collection $requestedColumns): bool
    {
        return $requestedColumns->every(
            fn (string $column) => in_array($column, $this->allowedColumns, true)
        );
    }
}
