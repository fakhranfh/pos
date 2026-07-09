<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('app.notification.index');
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = $request->user()->notifications()->latest()->paginate($perPage);

        return response()->json([
            'data' => NotificationResource::collection($items->getCollection())->resolve(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
