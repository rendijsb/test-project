<?php

declare(strict_types=1);

namespace App\Enums\Users;

enum UserRoleEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}