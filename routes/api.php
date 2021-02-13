<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
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

Route::group(['middleware' => 'auth:api', 'prefix' => 'category'], function () {
    Route::post('create/{inventory}',[CategoryController::class, 'create']);
    Route::get('get/{inventory}',[CategoryController::class, 'get']);
    Route::delete('/delete/{category}',[CategoryController::class, 'delete']);
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'product'], function () {
    Route::post('create',[ProductController::class, 'create']);
    Route::get('/get/{product}',[ProductController::class, 'get']);
    Route::get('/getall/{category}',[ProductController::class, 'getAll']);
    Route::post('/update/{product}',[ProductController::class, 'update']);
    Route::delete('/delete/{product}',[ProductController::class, 'delete']);
    Route::get('/add/{product}/{count}',[ProductController::class, 'add']);
    Route::get('/remove/{product}/{count}',[ProductController::class, 'remove']);
});
