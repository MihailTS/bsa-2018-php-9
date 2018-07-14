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
    return view('main');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('currencies', 'Currency\CurrencyController');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/{providerName}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{providerName}/callback', 'Auth\LoginController@handleProviderCallback');