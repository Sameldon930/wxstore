<?php

// 代理登录
Route::group(['middleware' => ['auth.agent:agent']], function () {
    Route::get('/', 'DashboardController@index')->name('agent.dashboard.index');

    /**** 成员管理 ****/
    Route::get('agent/info_check', 'AgentInfoCheckController@index')->name('agent.agent.info_check');
    Route::post('agent/info_store', 'AgentInfoCheckController@info_store')->name('agent.agent.info_store');
    Route::get('merchant.info_check', 'MerchantInfoCheckController@index')->name('agent.merchant.info_check');
    Route::resource('agent', 'AgentController', ['as' => 'agent']);
    Route::resource('store', 'StoreController', ['as' => 'agent']);
    Route::resource('merchant', 'MerchantController', ['as' => 'agent']);

    /**** 账户管理 ****/
    Route::get('withdrawal/index', 'WithdrawalController@index')->name('agent.withdrawal.index');
    Route::get('withdrawal/detail/{id}', 'WithdrawalController@detail')->name('agent.withdrawal.detail');
    Route::post('withdrawal/withdrawal', 'WithdrawalController@withdrawal')->name('agent.withdrawal.withdrawal');
    Route::get('account_log/index', 'AccountLogController@index')->name('agent.account_log.index');


    /**** 交易管理 ****/
    Route::get('order/index', 'OrderController@index')->name('agent.order.index');
    Route::get('store_order/index', 'StoreOrderController@index')->name('agent.store_order.index');

    Route::get('settle_log/index', 'SettleLogController@index')->name('agent.settle_log.index');
    Route::get('settle_log/detail/{id}', 'SettleLogController@detail')->name('agent.settle_log.detail');
    Route::get('settle_log/sub_orders/{settleLog}', 'SettleLogController@subOrders')->name('agent.settle_log.subOrders');

    Route::get('store_settle_log/index', 'StoreSettleLogController@index')->name('agent.store_settle_log.index');
    Route::get('store_settle_log/detail/{id}', 'StoreSettleLogController@detail')->name('agent.store_settle_log.detail');
    Route::get('store_settle_log/sub_orders/{settleLog}', 'StoreSettleLogController@subOrders')->name('agent.store_settle_log.subOrders');


    /**** 系统管理 ****/
    Route::get('profile.index', 'ProfileController@index')->name('agent.profile.index');
    Route::get('password.change_password', 'PasswordController@change_password')->name('agent.password.change_password');
    Route::post('password.update_password', 'PasswordController@update_password')->name('agent.password.update_password');



    Route::post('voice', 'VoiceController@index');
});


Route::get('login', 'LoginController@showLoginForm')->name('agent.login');
Route::post('login', 'LoginController@login')->name('agent.login');
Route::any('findPassword', 'LoginController@findPassword')->name('agent.find.password');
Route::get('logout', 'LoginController@logout')->name('agent.logout');
