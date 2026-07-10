<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Mail\PendingEmailVerificationMail;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function get(array $filters = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15)
    {
        return $this->userRepository->get($filters, [], $sort, $direction, $perPage);
    }

    public function find($id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function updateRole(User $actor, User $target, UserRole $role): User
    {
        if ($actor->is($target) && $role !== UserRole::Admin) {
            throw ValidationException::withMessages([
                'role' => __('You cannot remove your own admin role.'),
            ]);
        }

        $minimumAdmins = (int) config('security.minimum_admins', 2);

        if ($target->role === UserRole::Admin && $role !== UserRole::Admin && User::where('role', UserRole::Admin)->count() <= $minimumAdmins) {
            throw ValidationException::withMessages([
                'role' => __('You cannot demote this admin: at least :count admins must remain.', ['count' => $minimumAdmins]),
            ]);
        }

        return $this->userRepository->updateRole($target, $role);
    }

    public function updateProfile(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    public function updateProfilePhoto(User $user, UploadedFile $photo): void
    {
        $photoUrl = $this->userRepository->updateProfilePhoto($user, $photo);
        $user->update(['profile_photo_path' => $photoUrl]);
    }

    public function removeProfilePhoto(User $user): void
    {
        $this->userRepository->removeProfilePhoto($user);
    }

    public function changePassword(User $user, string $password): void
    {
        $this->userRepository->update($user, ['password' => $password]);
    }

    public function setPendingEmail(User $user, string $pendingEmail): void
    {
        $this->userRepository->setPendingEmail($user, $pendingEmail);
    }

    public function confirmPendingEmail(User $user): void
    {
        $this->userRepository->confirmPendingEmail($user);
    }

    public function sendPendingEmailVerification(User $user, string $verificationUrl): void
    {
        if (! config('features.email_enabled')) {
            return;
        }

        Mail::to($user->pending_email)->send(new PendingEmailVerificationMail($user, $verificationUrl));
    }
}
