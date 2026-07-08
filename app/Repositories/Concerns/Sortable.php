<?php

namespace App\Repositories\Concerns;

trait Sortable
{
    /**
     * Apply a whitelisted sort to the query, falling back to the default order.
     *
     * @param  array<int, string>  $sortable  Column names allowed to be sorted on.
     */
    protected function applySort($query, ?string $sort, string $direction, array $sortable, string $defaultColumn = 'created_at', string $defaultDirection = 'desc')
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        if ($sort && in_array($sort, $sortable, true)) {
            return $query->orderBy($sort, $direction);
        }

        return $query->orderBy($defaultColumn, $defaultDirection);
    }
}
