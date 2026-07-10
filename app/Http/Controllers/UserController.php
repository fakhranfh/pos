<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\User\UpdateUserRoleRequest;
use App\Http\Responses\PaginatedResponse;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index()
    {
        return view('app.user.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['name', 'email', 'role']);
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->userService->get($filters, $request->query('sort'), $request->query('direction', 'asc'), $perPage);

        $data = $items->getCollection()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'role' => $item->role->label(),
            'created_at' => $item->formatted_created_at ?? '',
            'actions' => [
                'edit' => route('users.edit', $item->id),
            ],
        ])->toArray();

        return new PaginatedResponse($data, $items);
    }

    public function edit(Request $request, $id)
    {
        $item = $this->userService->find($id);
        abort_if(! $item, 404);

        return view('app.user.edit', [
            'item' => $item,
            'isSelf' => $item->is($request->user()),
        ]);
    }

    public function update(UpdateUserRoleRequest $request, $id)
    {
        $target = $this->userService->find($id);
        abort_if(! $target, 404);

        try {
            $this->userService->updateRole($request->user(), $target, UserRole::from($request->validated()['role']));
        } catch (ValidationException $e) {
            return redirect()->route('users.edit', $id)->withErrors($e->errors());
        }

        return redirect()->route('users.index')->with('success', __('User role updated successfully.'));
    }
}
