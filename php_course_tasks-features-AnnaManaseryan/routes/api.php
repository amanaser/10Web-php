<?php

use Illuminate\Http\Request;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

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

Route::post('/users/create', [UserController::class, 'create']);
Route::post('/users/{id}', [UserController::class, 'update']);

Route::get('/users/showAll', [UserController::class, 'showAll']);
Route::get('/users/delete/{id}', [UserController::class, 'delete']);
Route::get('/users/find/{id}', [UserController::class, 'find']);

Route::resource('/categories', CategoryController::class);
Route::resource('/shops', ShopController::class);
Route::resource('/products', ProductController::class);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('registration', [AuthController::class, 'registration']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::group(['middleware' => 'UserType:buyer'], function () {
        Route::get('/test', [UserController::class, 'index']);
    });
    Route::group(['middleware' => 'userType:seller'], function ($type) {
        Route::post('/test', [\App\Http\Controllers\ProductController::class, 'index']);
    });
});


