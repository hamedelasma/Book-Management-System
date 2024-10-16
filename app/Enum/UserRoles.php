<?php

namespace App\Enum;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case USER = 'user';


    public static function getValues(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];
    }
}
