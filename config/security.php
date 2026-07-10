<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Minimum Admin Count
    |--------------------------------------------------------------------------
    |
    | The minimum number of Admin-role users that must remain after any role
    | change away from Admin. Set to 2 (not 1) by default so that a single
    | admin can never unilaterally demote every other admin down to just
    | themselves — at least one other admin must always survive a demotion,
    | requiring a second admin's cooperation (or a promotion first) to
    | reduce the admin count further.
    |
    */

    'minimum_admins' => (int) env('MINIMUM_ADMIN_COUNT', 2),

];
