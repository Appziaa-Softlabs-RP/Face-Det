<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\google\GoogleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'company'], function () {
    Route::get('/', [CompanyController::class, 'index'])->middleware('auth:api');
    Route::post('/login', [CompanyController::class, 'login']);
    Route::post('/register', [CompanyController::class, 'register']);

    Route::group(['prefix' => 'employee', 'middleware' => ['auth:api']], function () {
        Route::post('/register', [EmployeeController::class, 'register']);
        Route::post('/update', [EmployeeController::class, 'update']);
        Route::post('/detect', [EmployeeController::class, 'compare']);
    });
});

Route::post('google-leads/add-product', [GoogleController::class, 'addProduct']);

Route::fallback(function (Request $request) {
    return response()->json(['message' => 'Not found.'], 404);
});
