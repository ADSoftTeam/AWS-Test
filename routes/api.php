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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'storage', 'middleware' => ['onlyjson']], function() {
    
	Route::get('file/{id}', 'FileController@get_file');	
	Route::get('info/{id}', 'FileController@get_info');
	Route::post('upload', 'FileController@store');
	Route::delete('delete/{id}', 'FileController@destroy');
});

// Если нет роутов
Route::fallback(function(){
    return response()->json(['message' => 'Not found route'], 404);
})->name('api.fallback.404');


