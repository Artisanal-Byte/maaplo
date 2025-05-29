<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class SvgMarkup implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return is_string($value)
            && stripos($value, '<svg') !== false
            && stripos($value, '</svg>') !== false;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute field must contain valid SVG Path.';
    }
}
