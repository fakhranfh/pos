<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ErrorResponse implements Responsable
{
    /**
     * @param  array<string, mixed>  $errors
     */
    public function __construct(
        protected array $errors,
        protected int $status = 422,
    ) {}

    public function toResponse($request)
    {
        return response()->json(['errors' => $this->errors], $this->status);
    }
}
