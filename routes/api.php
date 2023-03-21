<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
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

Route::group(['middleware' => ['auth:api']], function (){
    Route::get('/user', [UserController::class, 'profile']);
    Route::patch('/user', [UserController::class, 'patch']);
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::post('/user/delete', [UserController::class, 'delete']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::get('customer',[CustomerController::class,'index']);
Route::get('/customer/{id?}',[CustomerController::class,'detail']);
Route::post('customer',[CustomerController::class,'create']);
Route::put('/customer/{id}',[CustomerController::class,'update']);
Route::delete('/customer/{id}',[CustomerController::class,'delete']);

Route::post('/payment/snap_token', [MidtransController::class, 'snapToken']);