<?php

declare(strict_types=1);

namespace App\Enums\Users;

enum UserRoleEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}