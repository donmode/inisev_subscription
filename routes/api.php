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

Route::resource('website', App\Http\Controllers\API\WebsiteController::class);
Route::resource('post', App\Http\Controllers\API\PostController::class);
Route::resource('subscription', App\Http\Controllers\API\SubscriptionController::class);
