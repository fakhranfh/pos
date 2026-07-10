<?php

namespace App\Repositories\User;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15);

    public function find($id): ?User;

    public function update(User $user, array $data): User;

    public function updateRole(User $user, UserRole $role): User;

    public function updateProfilePhoto(User $user, UploadedFile $photo): string;

    public function removeProfilePhoto(User $user): void;

    public function setPendingEmail(User $user, string $pendingEmail): void;

    public function confirmPendingEmail(User $user): void;
}
