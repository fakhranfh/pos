<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Responses\MessageResponse;
use App\Http\Responses\PaginatedResponse;
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
        $data = NotificationResource::collection($items->getCollection())->resolve();

        return new PaginatedResponse($data, $items);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return new MessageResponse(__('All notifications marked as read.'));
        }

        return back();
    }
}
