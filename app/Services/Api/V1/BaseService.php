<?php

namespace App\Services\Api\V1;

abstract class BaseService
{
    protected function toInt(mixed $value, int $default = 0): int
    {
        return is_numeric($value) ? (int) $value : $default;
    }

    protected function toFloat(mixed $value, float $default = 0): float
    {
        return is_numeric($value) ? (float) $value : $default;
    }
}
