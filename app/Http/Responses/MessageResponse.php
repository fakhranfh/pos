<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class MessageResponse implements Responsable
{
    public function __construct(
        protected string $message,
        protected int $status = 200,
    ) {}

    public function toResponse($request)
    {
        return response()->json(['message' => $this->message], $this->status);
    }
}
