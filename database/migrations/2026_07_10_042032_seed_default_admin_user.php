<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Self-registration only ever creates manager/cashier accounts (see
     * Finding 1 in the pentest report and the RBAC follow-up), so there is
     * no in-app way to bootstrap the first admin. This seeds one directly.
     *
     * Override DEFAULT_ADMIN_EMAIL / DEFAULT_ADMIN_PASSWORD in .env before
     * running this in a real environment, and change the password after
     * first login regardless.
     */
    public function up(): void
    {
        $email = env('DEFAULT_ADMIN_EMAIL', 'admin@possystem.test');

        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'name' => 'Default Admin',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD', 'Admin@123123')),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', env('DEFAULT_ADMIN_EMAIL', 'admin@possystem.test'))->delete();
    }
};
