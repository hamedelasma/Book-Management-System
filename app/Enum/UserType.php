<?php

namespace App\Enum;

enum UserType: string
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
