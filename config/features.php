<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email Features
    |--------------------------------------------------------------------------
    |
    | Controls whether SMTP-email-dependent features are active: Fortify's
    | email verification, password reset emails, and the profile pending
    | email change confirmation. Disable this when no mail server is
    | configured, then re-enable it later without touching any code.
    |
    */

    'email_enabled' => env('FEATURE_EMAIL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Registration Feature
    |--------------------------------------------------------------------------
    |
    | Controls whether anonymous self-registration (Fortify's register routes)
    | is active. Defaults to disabled: this is an internal, single-tenant POS
    | app with no RBAC, so any self-registered account gets full operational
    | access. Provision accounts manually (e.g. via `php artisan tinker` or a
    | seeder) unless open registration is explicitly required.
    |
    */

    'registration_enabled' => env('FEATURE_REGISTRATION_ENABLED', false),

];
