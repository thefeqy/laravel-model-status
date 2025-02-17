<?php

use Illuminate\Support\Facades\Auth;

return [

    /*
    |--------------------------------------------------------------------------
    | Status Column Name
    |--------------------------------------------------------------------------
    |
    | This is the column that will store the model's status.
    | You can customize this column name if your database uses a different name.
    |
    | Default: 'status'
    |
    */

    'column_name' => env('MODEL_STATUS_COLUMN', 'status'),

    /*
    |--------------------------------------------------------------------------
    | Status Column Length
    |--------------------------------------------------------------------------
    |
    | Define the maximum length of the status column.
    | The default is set to 10 characters, which is enough for "active"/"inactive".
    |
    */
    'column_length' => env('MODEL_STATUS_COLUMN_LENGTH', 'inactive'),

    /*
    |--------------------------------------------------------------------------
    | Default Active Status Value
    |--------------------------------------------------------------------------
    |
    | This value represents the "active" status of a model.
    | It will be used as the default status when a model is activated.
    |
    | Default: 'active'
    |
    */

    'default_value' => env('MODEL_STATUS_ACTIVE', 'active'),

    /*
    |--------------------------------------------------------------------------
    | Default Inactive Status Value
    |--------------------------------------------------------------------------
    |
    | This value represents the "inactive" status of a model.
    | It will be used as the default status when a model is deactivated.
    |
    | Default: 'inactive'
    |
    */

    'inactive_value' => env('MODEL_STATUS_INACTIVE', 'inactive'),

    /*
    |--------------------------------------------------------------------------
    | Admin Detector for Active Scope Bypass
    |--------------------------------------------------------------------------
    |
    | If the authenticated user is an admin, they should be able to see all
    | records, including inactive ones. Define the logic to detect admin users.
    |
    | Example:
    | function () {
    |     return auth()->check() && auth()->user()->is_admin;
    | }
    |
    */
    'admin_detector' => function () {
        return Auth::check() && Auth::user()->is_admin;
    },
];
