<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class ProductSearchResponse implements Responsable
{
    public function __construct(
        protected Collection $data,
        protected ?int $nextPage,
    ) {}

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->data,
            'next_page' => $this->nextPage,
        ]);
    }
}
