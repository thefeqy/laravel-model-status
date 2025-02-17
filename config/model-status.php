<?php

use Illuminate\Support\Facades\Auth;

return [
    /*
    |--------------------------------------------------------------------------
    | Status Column Name
    |--------------------------------------------------------------------------
    |
    | Define the name of the column that will store the model's status.
    | This allows flexibility to use a different name instead of "status".
    |
    */
    'column_name' => 'status',

    /*
    |--------------------------------------------------------------------------
    | Default Active Status
    |--------------------------------------------------------------------------
    |
    | Define what value represents an "active" model in your application.
    | This value will be used when a model is activated.
    |
    */
    'default_value' => 'active',

    /*
    |--------------------------------------------------------------------------
    | Default Inactive Status
    |--------------------------------------------------------------------------
    |
    | Define what value represents an "inactive" model.
    | This value will be used when a model is deactivated.
    |
    */
    'inactive_value' => 'inactive',

    /*
    |--------------------------------------------------------------------------
    | Status Column Length
    |--------------------------------------------------------------------------
    |
    | Define the maximum length of the status column.
    | The default is set to 10 characters, which is enough for "active"/"inactive".
    |
    */
    'column_length' => 10,

    /*
    |--------------------------------------------------------------------------
    | Admin Detector
    |--------------------------------------------------------------------------
    |
    | Define a function that determines if the current user is an admin.
    | If the user is an admin, the active scope will be disabled automatically.
    | This function should return true if the user is an admin, and false otherwise.
    |
    | Example implementations:
    | - Using an `is_admin` flag in the users table.
    | - Using a `role` field to check if the user is "admin".
    | - Checking if the authenticated user belongs to a specific guard.
    |
    */
    'admin_detector' => function () {
        return Auth::check() && Auth::user()->is_admin;
    },
];
