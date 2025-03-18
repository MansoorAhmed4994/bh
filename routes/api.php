<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\Orders\ManualOrdersController;
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

Route::get('/books', [ManualOrdersController::class, 'testingapi']);

//========== Manualorders API
Route::get('/GetLeopordDispatchOrderList', [ManualOrdersController::class, 'GetLeopordDispatchOrderList']);



// Route::post('manualorders/image/upload', 'Client\Orders\ManualOrdersController@ImageUpload123');
Route::resource('manualorders/image/upload', 'Client\Orders\ManualOrdersController@ImageUpload123');