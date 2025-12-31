<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates Electric SQL ORDER BY clauses against authorized model shapes.
 *
 * Electric SQL uses PostgreSQL ORDER BY syntax for the `subset__order_by` query parameter
 * when pagination is enabled. This rule ensures ORDER BY columns are whitelisted in the
 * model's shape to prevent:
 * - SQL injection via functions/expressions (e.g., LOWER(), CONCAT())
 * - Information disclosure via unauthorized columns (e.g., password, tokens)
 * - Column enumeration attacks
 * - Performance degradation via unindexed column sorting
 *
 * Supports PostgreSQL ORDER BY syntax:
 * - Double-quoted column names: "id", "created_at"
 * - Direction modifiers: ASC, DESC
 * - NULL handling: NULLS FIRST, NULLS LAST
 * - Multiple columns: "id" DESC, "created_at" ASC
 * - Combined: "id" DESC NULLS FIRST, "created_at" ASC NULLS LAST
 *
 * Example usage in form requests:
 * ```php
 * 'subset__order_by' => [
 *     'required_with:subset__limit,subset__offset',
 *     'string',
 *     'max:255',
 *     new ValidElectricOrderBy(allowedColumns: User::getShape())
 * ]
 * ```
 *
 * @see https://electric-sql.com/docs/api/http Electric SQL HTTP API
 * @see https://www.postgresql.org/docs/current/queries-order.html PostgreSQL ORDER BY
 */
class ValidElectricOrderBy implements ValidationRule
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

        foreach ($this->parseOrderByParts($value) as $part) {
            $columnName = $this->extractColumnName($part);

            if ($this->containsSqlExpressions($columnName)) {
                $fail('The :attribute cannot contain functions or expressions.');

                return;
            }

            if (! $this->isAllowedColumn($columnName)) {
                $fail('Some of the :attribute does not exist or not authorized.');

                return;
            }
        }
    }

    /**
     * Parse ORDER BY clause into individual column parts.
     *
     * @return string[]
     */
    protected function parseOrderByParts(string $orderBy): array
    {
        return array_map('trim', explode(',', $orderBy));
    }

    /**
     * Extract clean column name from ORDER BY part.
     *
     * Removes PostgreSQL modifiers: ASC, DESC, NULLS FIRST, NULLS LAST
     * Removes double quotes from column identifiers
     */
    protected function extractColumnName(string $part): string
    {
        // Remove direction modifiers (ASC/DESC)
        $columnName = preg_replace('/\s+(ASC|DESC)(\s+|$)/i', ' ', $part);

        // Remove NULL handling modifiers (NULLS FIRST/LAST)
        $columnName = preg_replace('/\s+NULLS\s+(FIRST|LAST)(\s+|$)/i', ' ', $columnName);

        // Remove whitespace and double quotes
        return trim(trim($columnName), '"');
    }

    /**
     * Check if column name contains SQL functions or expressions.
     */
    protected function containsSqlExpressions(string $columnName): bool
    {
        return preg_match('/[()]/', $columnName) === 1;
    }

    /**
     * Check if column is in the allowed shape.
     */
    protected function isAllowedColumn(string $columnName): bool
    {
        return in_array($columnName, $this->allowedColumns, true);
    }
}
