<?php

declare(strict_types=1);

namespace App\Rules;

use BackedEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use LogicException;

class DelimitedExistEnumValues implements ValidationRule
{
    public function __construct(
        protected string $enumUsed,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_subclass_of($this->enumUsed, BackedEnum::class) === false) {
            throw new LogicException('Enum used must be a subclass of BackedEnum');
        }

        if (is_array($value)) {
            throw new LogicException('This rule does not support arrays, use enum rule instead');
        }

        if (is_string($value) === false) {
            $fail('The :attribute must be a string.');

            return;
        }

        $enumValues = collect(explode(separator: ',', string: $value))
            ->unique();

        $isValid = $enumValues->every(fn (string $enumValue) => $this->enumUsed::tryFrom($enumValue) !== null);

        if (! $isValid) {
            $fail('Some of the :attribute does not exist or not authorized.');
        }
    }
}
