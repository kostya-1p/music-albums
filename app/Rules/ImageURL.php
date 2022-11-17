<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class ImageURL implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $headers = get_headers($value, 1);
            if (!str_contains($headers['Content-Type'], 'image/')) {
                $fail('The :attribute must be an image URL');
            }
        } else {
            $fail('The :attribute must be an image URL');
        }
    }
}
