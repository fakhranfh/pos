<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Other => 'Other',
        };
    }
}
