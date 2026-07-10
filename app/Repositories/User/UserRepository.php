<?php

namespace App\Repositories\User;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Concerns\Sortable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserRepositoryInterface
{
    use Sortable;

    protected array $sortable = ['id', 'name', 'email', 'role', 'created_at'];

    public function query(array $filters = [])
    {
        $query = User::query();

        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            if (in_array($key, ['name', 'email'], true)) {
                $query->where($key, 'like', "%{$value}%");

                continue;
            }

            $query->where($key, $value);
        }

        return $query;
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15)
    {
        $query = $this->applySort($this->query($filters), $sort, $direction, $this->sortable);

        return $query->with($with)->paginate($perPage);
    }

    public function find($id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    /**
     * `role` is deliberately excluded from User's mass-assignable attributes
     * (see #[Fillable] on the model) so profile self-updates can never touch
     * it; role changes go through this explicit, single-purpose method instead.
     */
    public function updateRole(User $user, UserRole $role): User
    {
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function updateProfilePhoto(User $user, UploadedFile $photo): string
    {
        $this->removeProfilePhotoFile($user);

        $path = $photo->store('profile-photos', 'public');

        return Storage::url($path);
    }

    public function removeProfilePhoto(User $user): void
    {
        $this->removeProfilePhotoFile($user);
        $user->update(['profile_photo_path' => null]);
    }

    public function setPendingEmail(User $user, string $pendingEmail): void
    {
        $user->update(['pending_email' => $pendingEmail]);
    }

    public function confirmPendingEmail(User $user): void
    {
        $user->update([
            'email' => $user->pending_email,
            'pending_email' => null,
            'email_verified_at' => now(),
        ]);
    }

    private function removeProfilePhotoFile(User $user): void
    {
        if ($user->profile_photo_path) {
            $path = str_replace('/storage/', '', $user->profile_photo_path);
            Storage::disk('public')->delete($path);
        }
    }
}
