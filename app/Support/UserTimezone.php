<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserTimezone
{
    /**
     * Resolve the timezone that dates should be displayed in for the current user.
     */
    public static function resolve(): string
    {
        return Auth::user()?->timezone ?? config('app.timezone');
    }

    /**
     * Format a date in the current user's timezone.
     */
    public static function format(?CarbonInterface $date, string $format = 'd M Y H:i'): ?string
    {
        return $date?->copy()->setTimezone(self::resolve())->format($format);
    }

    /**
     * Convert every date/datetime attribute (including loaded relations) of the
     * given model, collection, or paginator to the current user's timezone.
     */
    public static function apply(mixed $result): mixed
    {
        if ($result instanceof LengthAwarePaginator) {
            $result->getCollection()->each(fn ($item) => self::apply($item));
        } elseif ($result instanceof EloquentCollection || $result instanceof Collection) {
            $result->each(fn ($item) => self::apply($item));
        } elseif ($result instanceof Model) {
            self::applyToModel($result);
        }

        return $result;
    }

    private static function applyToModel(Model $model): void
    {
        $timezone = self::resolve();

        $dateCasts = array_keys(array_filter($model->getCasts(), fn ($type) => self::isDateCast($type)));

        $attributes = $model->getAttributes();

        foreach ([...$model->getDates(), ...$dateCasts] as $attribute) {
            $value = $model->getAttribute($attribute);

            if ($value instanceof CarbonInterface) {
                // Write the converted Carbon instance directly into the raw attribute
                // bag: Model::setAttribute() would re-serialize dates through
                // fromDateTime(), which discards the timezone we just applied.
                $attributes[$attribute] = $value->copy()->setTimezone($timezone);
            }
        }

        $model->setRawAttributes($attributes, true);

        foreach ($model->getRelations() as $relation) {
            self::apply($relation);
        }
    }

    private static function isDateCast(string $type): bool
    {
        return $type === 'date'
            || $type === 'datetime'
            || $type === 'immutable_date'
            || $type === 'immutable_datetime'
            || str_starts_with($type, 'date:')
            || str_starts_with($type, 'datetime:')
            || str_starts_with($type, 'immutable_date:')
            || str_starts_with($type, 'immutable_datetime:');
    }
}
