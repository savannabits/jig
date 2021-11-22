<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return Inertia::render('AdminDashboard');
})->name('admin.dashboard');


/* Auto-generated users admin routes */
Route::group(["prefix" => "admin","as" => "admin.","middleware"=>['auth:sanctum', 'verified']], function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->parameters(["users" => "user"]);
});


/* Auto-generated permissions admin routes */
Route::group(["prefix" => "admin","as" => "admin.","middleware"=>['auth:sanctum', 'verified']], function () {
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->parameters(["permissions" => "permission"]);
});


/* Auto-generated roles admin routes */
Route::group(["prefix" => "admin","as" => "admin.","middleware"=>['auth:sanctum', 'verified']], function () {
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->parameters(["roles" => "role"]);
});
