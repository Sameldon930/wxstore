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
    return "首页";
});


Route::get('pay', 'PaymentController@index');
Route::get('success', 'PaymentController@success');
Route::get('loading', 'PaymentController@loading');
Route::get('oauth_callback', 'PaymentController@wxOauthCallback');
Route::get('test', 'TestController@index');
Route::any('/', 'DashboardController@index');
