<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\CustomPasswordResetLinkResponse;
use App\Http\Responses\CustomPasswordResetResponse;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\PasswordResetResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where(Fortify::username(), $request->input(Fortify::username()))->first();

            // Always run a hash comparison against a real bcrypt hash, even
            // when no user matches, so response timing can't be used to
            // enumerate registered emails.
            $passwordMatches = Hash::check(
                (string) $request->input('password'),
                $user->password ?? self::dummyPasswordHash(),
            );

            return $user && $passwordMatches ? $user : null;
        });
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject('Reset Your Password')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', url(config('app.url').'/reset-password?token='.$token.'&email='.urlencode($notifiable->getEmailForPasswordReset())))
                ->line('This link will expire in 60 minutes.')
                ->line('If you did not request a password reset, please ignore this email.');
        });

        $this->app->singleton(
            PasswordResetResponse::class,
            CustomPasswordResetResponse::class
        );

        $this->app->singleton(
            SuccessfulPasswordResetLinkRequestResponse::class,
            CustomPasswordResetLinkResponse::class
        );

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }

    /**
     * A fixed, precomputed bcrypt hash used only to keep the "user not
     * found" login path's timing comparable to a real password check.
     */
    private static function dummyPasswordHash(): string
    {
        return '$2y$12$C6UzMDM.H6dfI/f/IKcEeOh27j5CKn0oSXfsHtA9x9vsdKZAxCNQK';
    }
}
