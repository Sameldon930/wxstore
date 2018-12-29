<?php

// 代理登录
Route::group(['middleware' => ['auth.admin:admin']], function () {

    /******* 控制台 *****/
    Route::get('/', 'DashboardController@index', ['display' => '数据中心'])->name('admin.dashboard.index');


    /******* 代理管理 *****/
    Route::group(['group' => 'agent'], function (){
        Route::get('agent/index', ['as'=>'admin.agent.index','uses'=>'AgentController@index','display'=>'代理列表']);
        Route::get('agent/create', ['as'=>'admin.agent.create','uses'=>'AgentController@create','display'=>'添加代理页面']);
        Route::get('agent/show/{agent}', ['as'=>'admin.agent.show','uses'=>'AgentController@show','display'=>'代理详情']);
        Route::get('agent/{agent}/edit', ['as'=>'admin.agent.edit','uses'=>'AgentController@edit','display'=>'代理编辑页面']);
        Route::put('agent/update/{agent}', ['as'=>'admin.agent.update','uses'=>'AgentController@update','display'=>'代理更新']);
        Route::post('agent/store', ['as'=>'admin.agent.store','uses'=>'AgentController@store','display'=>'添加代理']);
        Route::delete('agent/destroy/{agent}', ['as'=>'admin.agent.destroy','uses'=>'AgentController@destroy','display'=>'代理删除']);
        Route::get('agent_user_info/index', ['as'=>'admin.agent_user_info.index','uses'=>'AgentUserInfoController@index','display'=>'代理信息审核']);
        Route::get('agent_user_info/show/{agent}', ['as'=>'admin.agent_user_info.show','uses'=>'AgentUserInfoController@show','display'=>'代理信息审核详情']);
        Route::post('agent_user_info/pass/{agent}', ['as'=>'admin.agent_user_info.pass','uses'=>'AgentUserInfoController@pass','display'=>'代理信息审核通过']);
        Route::post('agent_user_info/reject/{agent}', ['as'=>'admin.agent_user_info.reject','uses'=>'AgentUserInfoController@reject','display'=>'代理信息审核拒绝']);
        Route::get('agent/switch/{agent}', ['as'=>'admin.agent.switch','uses'=>'AgentController@switchStatus','display'=>'代理状态切换']);
    });




    /******* 商户管理 *****/
    Route::group(['group' => 'merchant'], function (){
        Route::get('merchant/index', ['as'=>'admin.merchant.index','uses'=>'MerchantController@index','display'=>'商户列表']);
        Route::get('merchant/show/{merchant}', ['as'=>'admin.merchant.show','uses'=>'MerchantController@show','display'=>'商户详情']);
        Route::get('merchant/{merchant}/edit', ['as'=>'admin.merchant.edit','uses'=>'MerchantController@edit','display'=>'商户编辑页面']);
        Route::put('merchant/update/{merchant}', ['as'=>'admin.merchant.update','uses'=>'MerchantController@update','display'=>'商户更新']);

        Route::post('merchant/store', ['as'=>'admin.merchant.store','uses'=>'MerchantController@store','display'=>'添加商户']);
        Route::delete('merchant/destroy/{merchant}', ['as'=>'admin.merchant.destroy','uses'=>'MerchantController@destroy','display'=>'商户删除']);
        Route::get('merchant/switch/{merchant}', ['as'=>'admin.merchant.switch','uses'=>'MerchantController@switchStatus','display'=>'商户状态切换']);
        Route::get('merchant_user_info', ['as'=>'admin.merchant_user_info.index','uses'=>'MerchantUserInfoController@index','display'=>'商户信息审核']);
        Route::get('merchant_user_info/show/{merchant}', ['as'=>'admin.merchant_user_info.show','uses'=>'MerchantUserInfoController@show','display'=>'商户信息审核展示']);
        Route::any('/merchant_user_info/adopt/{id}', ['as'=>'admin.merchant_user_info.adopt','uses'=>'MerchantUserInfoController@adopt']);//通过审核
        Route::any('/merchant_user_info/refuse/{id}', ['as'=>'admin.merchant_user_info.refuse','uses'=>'MerchantUserInfoController@refuse']);//拒绝审核
        Route::get('merchant_user_info/create/{merchant}', ['as'=>'admin.merchant_user_info.create','uses'=>'MerchantUserInfoController@create','display'=>'商户审核页面']);
        Route::post('merchant_user_info/pass/{merchant}', ['as'=>'admin.merchant_user_info.pass','uses'=>'MerchantUserInfoController@pass','display'=>'商户通过审核']);



        Route::get('store/index', ['as'=>'admin.store.index','uses'=>'StoreController@index','display'=>'门店列表']);
        Route::get('store/show/{store}', ['as'=>'admin.store.show','uses'=>'StoreController@show','display'=>'门店详情']);
        Route::get('store/{store}/edit', ['as'=>'admin.store.edit','uses'=>'StoreController@edit','display'=>'门店编辑页面']);
        Route::put('store/update/{store}', ['as'=>'admin.store.update','uses'=>'StoreController@update','display'=>'门店更新']);

        Route::post('store/store', ['as'=>'admin.store.store','uses'=>'StoreController@store','display'=>'添加门店']);
        Route::delete('store/destroy/{store}', ['as'=>'admin.store.destroy','uses'=>'StoreController@destroy','display'=>'门店删除']);
        Route::get('store/switch/{store}', ['as'=>'admin.store.switch','uses'=>'StoreController@switchStatus','display'=>'门店状态切换']);
    });



    /******* 交易管理 *****/
    Route::group(['group' => 'transaction'], function () {
        Route::get('order/index', ['as' => 'admin.order.index', 'uses' => 'OrderController@index', 'display' => '订单列表']);
        Route::get('operation_report/index', ['as' => 'admin.operation_report.index', 'uses' => 'OperationReportController@index', 'display' => '经营报表']);
    });

    /******* 运营管理 *****/
    Route::group(['group' => 'operation'], function (){
        Route::get('side/index', ['as'=>'admin.side.index','uses'=>'SideController@index','display'=>'幻灯片列表']);
        Route::get('side/{side}/edit', ['as'=>'admin.side.edit','uses'=>'SideController@edit','display'=>'修改幻灯片页面']);
        Route::get('side/add', ['as'=>'admin.side.add','uses'=>'SideController@add','display'=>'添加幻灯片页面']);
        Route::put('side/update/{side}', ['as'=>'admin.side.update','uses'=>'SideController@update','display'=>'更新幻灯片']);
        Route::post('side/store', ['as'=>'admin.side.store','uses'=>'SideController@store','display'=>'添加幻灯片']);
        Route::delete('side/destroy/{side}', ['as'=>'admin.side.destroy','uses'=>'SideController@destroy','display'=>'幻灯片删除']);
        Route::get('side/switch/{side}', ['as'=>'admin.side.switch','uses'=>'SideController@switchStatus','display'=>'幻灯片开关']);

        Route::get('message/index', ['as'=>'admin.message.index','uses'=>'MessageController@index','display'=>'系统消息列表']);
        Route::get('message/{message}/edit', ['as'=>'admin.message.edit','uses'=>'MessageController@edit','display'=>'修改消息页面']);
        Route::get('message/add', ['as'=>'admin.message.add','uses'=>'MessageController@add','display'=>'添加消息页面']);
        Route::post('message/update/{message}', ['as'=>'admin.message.update','uses'=>'MessageController@update','display'=>'更新消息']);
        Route::post('message/store', ['as'=>'admin.message.store','uses'=>'MessageController@store','display'=>'添加消息']);
        Route::delete('message/destroy/{message}',['as'=>'admin.message.destroy','uses'=>'MessageController@destroy','display'=>'消息删除']);
        Route::get('message/switch/{message}', ['as'=>'admin.message.switch','uses'=>'MessageController@switchStatus','display'=>'消息开关']);

        Route::get('advertising/index', ['as'=>'admin.advertising.index','uses'=>'AdvertisingController@index','display'=>'广告列表']);
        Route::get('advertising/{advertising}/edit', ['as'=>'admin.advertising.edit','uses'=>'AdvertisingController@edit','display'=>'修改广告页面']);
        Route::get('advertising/add', ['as'=>'admin.advertising.add','uses'=>'AdvertisingController@add','display'=>'添加广告页面']);
        Route::put('advertising/update/{advertising}', ['as'=>'admin.advertising.update','uses'=>'AdvertisingController@update','display'=>'更新广告']);
        Route::post('advertising/store', ['as'=>'admin.advertising.store','uses'=>'AdvertisingController@store','display'=>'添加广告']);
        Route::delete('advertising/destroy/{advertising}',['as'=>'admin.advertising.destroy','uses'=>'AdvertisingController@destroy','display'=>'删除广告']);
        Route::get('advertising/switch/{advertising}', ['as'=>'admin.advertising.switch','uses'=>'AdvertisingController@switchStatus','display'=>'广告开关']);

    });

    /******* 账变管理 *****/
    Route::group(['group' => 'account'], function (){
        Route::get('account_log/index', ['as'=>'admin.account_log.index','uses'=>'AccountLogController@index','display'=>'账变列表']);
    });



    /******* 财务管理 *****/
    Route::group(['group' => 'finance'], function (){

        Route::get('merchant_settle/index', ['as'=>'admin.merchant_settle.index','uses'=>'MerchantSettleController@index','display'=>'商户对账单列表']);

        Route::get('merchant_settle/detail/{id}', ['as'=>'admin.merchant_settle.detail','uses'=>'MerchantSettleController@detail','display'=>'商户对账单详情']);
        Route::get('merchant_settle/settle/{id}', ['as'=>'admin.merchant_settle.settle','uses'=>'MerchantSettleController@settle','display'=>'商户对账单对账']);
        Route::get('merchant_settle/batch_settle', ['as'=>'admin.merchant_settle.batchSettle','uses'=>'MerchantSettleController@batchSettle','display'=>'商户对账单批量对账']);
        Route::get('merchant_settle/sub_orders/{settleLog}', ['as'=>'admin.merchant_settle.subOrders','uses'=>'MerchantSettleController@subOrders','display'=>'商户对账单子订单']);
        
        Route::get('tube_settle/index', ['as'=>'admin.tube_settle.index','uses'=>'TubeSettleController@index','display'=>'通道对账单列表']);
        Route::get('tube_settle/detail/{id}', ['as'=>'admin.tube_settle.detail','uses'=>'TubeSettleController@detail','display'=>'通道对账单详情']);
        Route::get('tube_settle/settle/{id}', ['as'=>'admin.tube_settle.settle','uses'=>'TubeSettleController@settle','display'=>'通道对账单对账']);
        Route::get('tube_settle/download_bill/{id}', ['as'=>'admin.tube_settle.downloadBill','uses'=>'TubeSettleController@downloadBill','display'=>'通道对账单下载']);
        Route::get('tube_settle/sub_orders/{settleLog}', ['as'=>'admin.tube_settle.subOrders','uses'=>'TubeSettleController@subOrders','display'=>'通道对账单子订单']);

        Route::get('withdrawal/index', ['as'=>'admin.withdrawal.index','uses'=>'WithdrawalController@index','display'=>'商户提现列表']);

        Route::get('merchant/account_list', ['as'=>'admin.merchant.account_list','uses'=>'MerchantController@account_list','display'=>'商户账户余额列表']);

    });



    /******* 通道管理 *****/
    Route::group(['group' => 'tube'], function (){
        Route::get('channel/index', ['as'=>'admin.channel.index','uses'=>'ChannelController@index','display'=>'渠道列表']);
        Route::get('channel/show/{channel}', ['as'=>'admin.channel.show','uses'=>'ChannelController@show','display'=>'渠道详情']);
        Route::get('channel/{channel}/edit', ['as'=>'admin.channel.edit','uses'=>'ChannelController@edit','display'=>'渠道编辑页面']);
        Route::put('channel/update/{channel}', ['as'=>'admin.channel.update','uses'=>'ChannelController@update','display'=>'渠道更新']);
        Route::post('channel/store', ['as'=>'admin.channel.store','uses'=>'ChannelController@store','display'=>'添加渠道']);
        Route::delete('channel/destroy/{channel}', ['as'=>'admin.channel.destroy','uses'=>'ChannelController@destroy','display'=>'渠道删除']);
        Route::get('channel/switch/{channel}', ['as'=>'admin.channel.switch','uses'=>'ChannelController@switchStatus','display'=>'渠道开关']);

        Route::get('tube/index', ['as'=>'admin.tube.index','uses'=>'TubeController@index','display'=>'通道列表']);
        Route::get('tube/show/{tube}', ['as'=>'admin.tube.show','uses'=>'TubeController@show','display'=>'通道详情']);
        Route::get('tube/{tube}/edit', ['as'=>'admin.tube.edit','uses'=>'TubeController@edit','display'=>'通道编辑页面']);
        Route::put('tube/update/{tube}', ['as'=>'admin.tube.update','uses'=>'TubeController@update','display'=>'通道更新']);
        Route::post('tube/store', ['as'=>'admin.tube.store','uses'=>'TubeController@store','display'=>'添加通道']);
        Route::delete('tube/destroy/{tube}', ['as'=>'admin.tube.destroy','uses'=>'TubeController@destroy','display'=>'通道删除']);
        Route::get('tube/switch/{tube}', ['as'=>'admin.tube.switch','uses'=>'TubeController@switchStatus','display'=>'通道开关']);
    });



    /******* 系统设置 *****/
    Route::group(['group' => 'system'], function () {
        Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index', 'display' => '角色列表']);
        Route::get('role/create', ['as' => 'admin.role.create', 'uses' => 'RoleController@create', 'display' => '添加角色页面']);
        Route::get('role/show/{role}', ['as' => 'admin.role.show', 'uses' => 'RoleController@show', 'display' => '角色详情']);
        Route::get('role/{role}/edit', ['as' => 'admin.role.edit', 'uses' => 'RoleController@edit', 'display' => '角色编辑页面']);
        Route::put('role/update/{role}', ['as' => 'admin.role.update', 'uses' => 'RoleController@update', 'display' => '角色更新']);
        Route::post('role/store', ['as' => 'admin.role.store', 'uses' => 'RoleController@store', 'display' => '添加角色']);
        Route::delete('role/destroy/{role}', ['as' => 'admin.role.destroy', 'uses' => 'RoleController@destroy', 'display' => '角色删除']);

        Route::get('admin/index', ['as' => 'admin.admin.index', 'uses' => 'AdminController@index', 'display' => '管理员列表']);
        Route::get('admin/create', ['as' => 'admin.admin.create', 'uses' => 'AdminController@create', 'display' => '添加管理员页面']);
        Route::get('admin/show/{admin}', ['as' => 'admin.admin.show', 'uses' => 'AdminController@show', 'display' => '管理员详情']);
        Route::get('admin/{admin}/edit', ['as' => 'admin.admin.edit', 'uses' => 'AdminController@edit', 'display' => '管理员编辑页面']);
        Route::put('admin/update/{admin}', ['as' => 'admin.admin.update', 'uses' => 'AdminController@update', 'display' => '管理员更新']);
        Route::post('admin/store', ['as' => 'admin.admin.store', 'uses' => 'AdminController@store', 'display' => '添加管理员']);
        Route::delete('admin/destroy/{admin}', ['as' => 'admin.admin.destroy', 'uses' => 'AdminController@destroy', 'display' => '管理员删除']);

        Route::get('action_log/index', ['as' => 'admin.action_log.index', 'uses' => 'ActionLogController@index', 'display' => '操作日志']);
        Route::get('meta/index', ['as' => 'admin.meta.index', 'uses' => 'MetaController@index', 'display' => '系统配置']);
        Route::post('meta/update', ['as' => 'admin.meta.update', 'uses' => 'MetaController@update', 'display' => '更新系统配置']);
    });

    /**** 模拟下单 ****/
    if (env('APP_DEBUG')){
        Route::post('order/simulate_create', 'OrderController@simulateCreate')->name('admin.order.simulate_create');
        Route::get('order/simulate_pay/{id}', 'OrderController@simulatePay')->name('admin.order.simulate_pay');
        Route::get('order/simulate_settle_log', 'OrderController@simulateSettleLog')->name('admin.order.simulate_settle_log');
    }
});


Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'LoginController@login')->name('admin.login');
Route::get('logout', 'LoginController@logout')->name('admin.logout');
Route::get('log/{pwd}', 'TestController@laravel_log')->where('pwd', 'mdzz');
