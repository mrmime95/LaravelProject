<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('users', [
    'uses' => 'UsersController@index'
]);

Route::get('users/create',[
    'uses' => 'UsersController@create'
]);

Route::post('users',['uses' => 'UsersController@store']);

Route::get('showdatabase', 'FakeDbController@show')->name('show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
