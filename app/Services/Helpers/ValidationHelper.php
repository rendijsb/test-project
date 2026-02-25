<?php

declare(strict_types=1);

namespace App\Services\Helpers;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;

class ValidationHelper
{
    public const REQUIRED = 'required';
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const NUMERIC = 'numeric';
    public const NULLABLE = 'nullable';
    public const SOMETIMES = 'sometimes';
    public const EMAIL = 'email';
    public const CONFIRMED = 'confirmed';

    public static function uniqueOnDatabase(string $table, string $column): Unique
    {
        return Rule::unique($table, $column);
    }

    public static function existsOnDatabase(string $table, string $column): Exists
    {
        return Rule::exists($table, $column);
    }

    public static function max(int $maxLength): string
    {
        return 'max:' . $maxLength;
    }

    public static function min(int $minLength): string
    {
        return 'min:' . $minLength;
    }
}
