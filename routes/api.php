<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/* Auto-generated users api routes */
Route::group(["middleware"=>['auth:sanctum', 'verified'],'as' => 'api.'], function () {
    Route::post('/users/{user}/assign-role', [\App\Http\Controllers\API\UserController::class,'assignRole'])->name('users.assign-role');
    Route::get('/users/dt', [\App\Http\Controllers\API\UserController::class,'dt'])->name('users.dt');
    Route::apiResource('/users', \App\Http\Controllers\API\UserController::class)->parameters(["users" => "user"]);
});


/* Auto-generated permissions api routes */
Route::group(["middleware"=>['auth:sanctum', 'verified'],'as' => 'api.'], function () {
    Route::get('/permissions/dt', [\App\Http\Controllers\API\PermissionController::class,'dt'])->name('permissions.dt');
    Route::apiResource('/permissions', \App\Http\Controllers\API\PermissionController::class)->parameters(["permissions" => "permission"]);
});


/* Auto-generated roles api routes */
Route::group(["middleware"=>['auth:sanctum', 'verified'],'as' => 'api.'], function () {
    Route::post('/roles/{role}/assign-permission', [\App\Http\Controllers\API\RoleController::class,'assignPermission'])->name('roles.assign-permission');
    Route::get('/roles/dt', [\App\Http\Controllers\API\RoleController::class,'dt'])->name('roles.dt');
    Route::apiResource('/roles', \App\Http\Controllers\API\RoleController::class)->parameters(["roles" => "role"]);
});
