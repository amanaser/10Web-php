<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;


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

Route::group(['middleware' => ['api']], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::group(['middleware' => ['jwt.auth']], function ($router) {
        Route::get('me', [AuthController::class, 'me']);
        Route::get('logout', [AuthController::class, 'logout']);

        Route::group(['middleware' => 'userType:seller'], function ($type) {
            Route::put('users/{id}', [UserController::class, 'update']);
            Route::apiResource('users', UserController::class);
            Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
            Route::apiResource('shops', ShopController::class)->except(['index', 'show']);
            Route::apiResource('shops.products', ProductController::class)->except(['index', 'show']);
        });

        Route::group(['middleware' => 'userType:buyer'], function ($type) {

        });
        Route::get('carts/checkout', [CartController::class, 'checkout']);
        Route::resource('carts', CartController::class);


        Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
        Route::apiResource('shops', ShopController::class)->only(['index', 'show']);
        Route::apiResource('shops.products', ProductController::class)->only(['index', 'show']);
    });

});
