<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatedResponse implements Responsable
{
    /**
     * @param  array<int, array<string, mixed>>  $data
     */
    public function __construct(
        protected array $data,
        protected LengthAwarePaginator $paginator,
    ) {}

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->data,
            'meta' => [
                'current_page' => $this->paginator->currentPage(),
                'last_page' => $this->paginator->lastPage(),
                'per_page' => $this->paginator->perPage(),
                'total' => $this->paginator->total(),
            ],
        ]);
    }
}
