<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;

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

Route::post('register',[UserController::class, 'register']);
Route::post('login',[UserController::class, 'login']);


Route::group(['middleware' =>["auth:sanctum"]], function (){
    Route::get('logout',[UserController::class, 'logout']);


    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('incomes', IncomeController::class);
    Route::get('summary', [ExpenseController::class, 'summary']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
