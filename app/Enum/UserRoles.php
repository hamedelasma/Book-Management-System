<?php

namespace App\Enum;

enum UserRoles: string
{
    case Admin = 'admin';
    case User = 'user';

    public static function getValues(): array
    {
        return [
            self::Admin,
            self::User,
        ];
    }
}
