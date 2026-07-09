<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Sale = 'sale';
    case StockIn = 'stock_in';
    case Adjustment = 'adjustment';
    case Return = 'return';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'Sale',
            self::StockIn => 'Stock In',
            self::Adjustment => 'Adjustment',
            self::Return => 'Return',
        };
    }
}
