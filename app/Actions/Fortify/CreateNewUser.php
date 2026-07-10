<?php

namespace App\Actions\Fortify;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            // Admin is deliberately not selectable here — the first admin
            // is bootstrapped via the seed_default_admin_user migration,
            // and every other admin is promoted from the user management
            // screen, never self-assigned at registration.
            'role' => ['required', Rule::in([UserRole::Manager->value, UserRole::Cashier->value])],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // `role` is deliberately excluded from User's mass-assignable
        // attributes, so it's set explicitly here rather than via create().
        $user->role = UserRole::from($input['role']);
        $user->save();

        return $user;
    }
}
