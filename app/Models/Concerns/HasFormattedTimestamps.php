<?php

namespace App\Models\Concerns;

use App\Support\UserTimezone;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFormattedTimestamps
{
    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => UserTimezone::format($this->created_at),
        );
    }

    protected function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => UserTimezone::format($this->updated_at),
        );
    }
}
