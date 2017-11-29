<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/aa', 'FakeDbController@getData');
Route::post('/filterSaving', 'FakeDbController@filterSaving');

Route::post('/columnModelSaving', 'FakeDbController@columnModelSaving');

Route::post('/filterLoading', 'FakeDbController@filterLoading');
Route::post('/columnModelLoading', 'FakeDbController@columnModelLoading');

Route::get('/loadSavedFiltersName', 'FakeDbController@loadSavedFiltersName');

Route::get('/loadSavedColumnModelsName', 'FakeDbController@loadSavedColumnModelsName');