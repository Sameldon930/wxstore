<?php

// 商户登录
Route::group(['middleware' => ['auth.merchant:merchant']], function () {
    Route::get('/', 'DashboardController@index')->name('merchant.dashboard.index');

    /**** 成员管理 ****/
    Route::get('merchant/info_check', 'AgentInfoCheckController@index')->name('merchant.merchant.info_check');
    Route::post('merchant/info_store', 'AgentInfoCheckController@info_store')->name('merchant.agent.info_store');
    Route::get('merchant.info_check', 'MerchantInfoCheckController@index')->name('merchant.merchant.info_check');
    Route::resource('agent', 'AgentController', ['as' => 'merchant']);
    Route::resource('store', 'StoreController', ['as' => 'merchant']);
    Route::resource('merchant', 'MerchantController', ['as' => 'merchant']);

    /**** 账户管理 ****/
    Route::get('withdrawal/index', 'WithdrawalController@index')->name('merchant.withdrawal.index');
    Route::get('withdrawal/detail/{id}', 'WithdrawalController@detail')->name('merchant.withdrawal.detail');
    Route::post('withdrawal/withdrawal', 'WithdrawalController@withdrawal')->name('merchant.withdrawal.withdrawal');
    Route::get('account_log/index', 'AccountLogController@index')->name('merchant.account_log.index');


    /**** 交易管理 ****/
    Route::get('order/index', 'OrderController@index')->name('merchant.order.index');
    Route::get('store_order/index', 'StoreOrderController@index')->name('merchant.store_order.index');

    Route::get('settle_log/index', 'SettleLogController@index')->name('merchant.settle_log.index');
    Route::get('settle_log/detail/{id}', 'SettleLogController@detail')->name('merchant.settle_log.detail');
    Route::get('settle_log/sub_orders/{settleLog}', 'SettleLogController@subOrders')->name('merchant.settle_log.subOrders');

    Route::get('store_settle_log/index', 'StoreSettleLogController@index')->name('merchant.store_settle_log.index');
    Route::get('store_settle_log/detail/{id}', 'StoreSettleLogController@detail')->name('merchant.store_settle_log.detail');
    Route::get('store_settle_log/sub_orders/{settleLog}', 'StoreSettleLogController@subOrders')->name('merchant.store_settle_log.subOrders');


    /**** 系统管理 ****/
    Route::get('profile.index', 'ProfileController@index')->name('merchant.profile.index');
    Route::get('password.change_password', 'PasswordController@change_password')->name('merchant.password.change_password');
    Route::post('password.update_password', 'PasswordController@update_password')->name('merchant.password.update_password');



    Route::post('voice', 'VoiceController@index');
});




Route::get('login', 'LoginController@showLoginForm')->name('merchant.login');
Route::post('login', 'LoginController@login')->name('merchant.login');
Route::any('findPassword', 'LoginController@findPassword')->name('merchant.find.password');
Route::get('logout', 'LoginController@logout')->name('merchant.logout');

