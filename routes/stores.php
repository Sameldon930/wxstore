<?php

// 代理登录
Route::group(['middleware' => ['auth.stores:stores']], function () {
    Route::get('/', 'DashboardController@index')->name('stores.dashboard.index');

    /****收款记录****/
    Route::get('/order/index', 'OrderController@index')->name('stores.order.index'); //记录列表


    /****系统管理****/
    Route::get('profile.index', 'ProfileController@index')->name('stores.profile.index');
    Route::get('password.change_password', 'PasswordController@change_password')->name('stores.password.change_password');
    Route::post('password.change_password', 'PasswordController@update_password')->name('stores.password.update_password');


});


Route::get('login', 'LoginController@showLoginForm')->name('stores.login');
Route::post('login', 'LoginController@login')->name('stores.login');
Route::get('logout', 'LoginController@logout')->name('stores.logout');
Route::any('findPassword', 'LoginController@findPassword')->name('stores.find.password');
