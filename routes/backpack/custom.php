<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('c-building-type', 'CBuildingTypeCrudController');
    Route::crud('model-has-permission', 'ModelHasPermissionCrudController');
    Route::crud('model-has-role', 'ModelHasRoleCrudController');
    Route::crud('permission', 'PermissionCrudController');
    Route::crud('role', 'RoleCrudController');
    Route::crud('role-has-permission', 'RoleHasPermissionCrudController');
    Route::crud('c-building-project-type', 'CBuildingProjectTypeCrudController');
    Route::crud('estate', 'EstateCrudController');
}); // this should be the absolute last line of this file