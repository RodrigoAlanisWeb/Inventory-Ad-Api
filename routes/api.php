<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, "login"]);
    Route::post('register', [AuthController::class, "register"]);
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('logout',[AuthController::class , 'logout'] );
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'inventory'], function () {
    Route::post('create',[InventoryController::class, 'create']);
    Route::get('/get',[InventoryController::class, 'get']);
    Route::post('/update/{inventory}',[InventoryController::class, 'update']);
    Route::delete('/delete/{inventory}',[InventoryController::class, 'delete']);
});
