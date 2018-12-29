<?php

use Illuminate\Http\Request;

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

// Route::any('v1', ['middleware'=>'api','uses'=>'Api\Out\ApiController@index']);

// 站外接口
Route::group(['middleware' => ['auth.api'], 'namespace' => 'Api\Out'], function () {
    Route::post('action/{method}', ['uses'=>'ApiController@index']);
    Route::any('redirect/{order}', ['uses'=>'ApiController@redirect'])->name('api.redirect')->where('order', '[0-9]+');
});

// 站内接口
Route::group(['namespace' => 'Api\In'], function () {
    Route::post('pay', 'PaymentController@pay');
    Route::post('ali_auth', 'PaymentController@aliAuth');
    Route::post('v1', 'WebApiController@index');
});

Route::any('wechatPayCallback', 'PaymentCallbackController@wechatPayCallback');
Route::any('aliPayCallback', 'PaymentCallbackController@aliPayCallback');
Route::any('cloudpayCallback', 'PaymentCallbackController@cloudpayCallback')->name('callback.cloudpay_callback');;