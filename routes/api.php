<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CategoryController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/websites', [WebsiteController::class, 'websites']);
Route::post('/search', [WebsiteController::class, 'search']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/directory/store', [WebsiteController::class, 'store']);
    Route::post('/website/vote', [WebsiteController::class, 'vote']);
    Route::post('/websites', [WebsiteController::class, 'websites']);
    Route::post('/categories', [CategoryController::class, 'categories']);
});

Route::group(['prefix' => 'admin','middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::post('/websites', [WebsiteController::class, 'admin_websites']);
    Route::post('/manage/website', [WebsiteController::class, 'edit']);
    Route::post('/delete/website', [WebsiteController::class, 'destroy']);
});

