<?php

namespace App\Validation;

class CustomRules
{
    public function greater_than_field($str, string $field, array $data): bool
    {
        if (! isset($data[$field]) || ! is_numeric($str) || ! is_numeric($data[$field])) {
            return false;
        }

        return (float) $str > (float) $data[$field];
    }
}
