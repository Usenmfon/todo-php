<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'auth'], function(){
    Route::post('/signup', [AuthController::class, 'createAccount']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/logout', [AuthController::class, 'signout'])->middleware('auth:sanctum');
});


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/profile', function(Request $request){
        return auth()->user();
    });

    Route::post('/todo', [TodoController::class, 'create']);
    Route::get('/todo', [TodoController::class, 'index']);
    Route::get('/todo/user', [TodoController::class, 'byUserId']);
    Route::get('/todo/{id}', [TodoController::class, 'show']);
    Route::put('/todo/{id}', [TodoController::class, 'update']);
    Route::delete('/todo/{id}', [TodoController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
