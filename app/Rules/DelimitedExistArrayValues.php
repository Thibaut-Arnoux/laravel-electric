<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DelimitedExistArrayValues implements ValidationRule
{
    public function __construct(
        /** @var string[] */
        protected array $arrayUsed,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) === false) {
            $fail('The :attribute must be a string.');

            return;
        }

        $arrayUsed = collect(explode(separator: ',', string: $value))
            ->unique();

        $isValid = $arrayUsed->every(fn (string $arrayValue) => in_array(needle: $arrayValue, haystack: $this->arrayUsed));

        if (! $isValid) {
            $fail('Some of the :attribute does not exist or not authorized.');
        }
    }
}
