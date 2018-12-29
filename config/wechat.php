<?php

return [

    /*'aes_key' => 'oH2VVa6hQD6Ptsg730T3bkNdbhhpjJw66CgDVQegRbb',   //智世EncodingAESKey
    'token' => 'dotpay_test_message',  //智世token*/

    'app_id' => 'wx71b3c3e3ce5907f8',
    'mch_id' => '1503678491',
    'key' => 'dec123f7d052f15e62ea186760ee2d43',
    'secret' =>'dec123f7d052f15e62ea186760ee2d43',
    'payment' =>  [
        'merchant_id'        => '1503678491',
        'key'                => 'qwerqwerqwerqwerqwerqwerqwerqwer',
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！*/
        'notify_url'         => 'http://mrf.huanhe.pro/api/wechatPayCallback', // 你也可以在下单时单独设置来想覆盖它
        // 'sub_merchant_id' => '1504182561',
    ] ,
    'oauth' =>  [
        'scopes' => ['snsapi_base'],
        'callback' => 'oauth_callback',
    ],


    'debug'  => true,

    // 使用 Laravel 的缓存系统
    //'use_laravel_cache' => true,



    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    // 'oauth' => [
    //     'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
    //     'callback' => env('WECHAT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
    // ],

    /*
     * 微信支付
     */
    // 'payment' => [
    //     'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
    //     'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //     'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
    //     'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
    //     // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
    //     // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
    //     // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
    //     // ...
    // ],

    /*
     * 开发模式下的免授权模拟授权用户资料
     *
     * 当 enable_mock 为 true 则会启用模拟微信授权，用于开发时使用，开发完成请删除或者改为 false 即可
     */
    //'enable_mock' => env('WECHAT_ENABLE_MOCK', true),
    /*'enable_mock' =>true,
     'mock_user' => [
         "openid" =>"odh7zsgI75iT8FRh0fGlSojc9PWM",
         // 以下字段为 scope 为 snsapi_userinfo 时需要
         "nickname" => "overtrue",
         "sex" =>"1",
         "province" =>"北京",
         "city" =>"北京",
         "country" =>"中国",
         "headimgurl" => "http://wx.qlogo.cn/mmopen/C2rEUskXQiblFYMUl9O0G05Q6pKibg7V1WpHX6CIQaic824apriabJw4r6EWxziaSt5BATrlbx1GVzwW2qjUCqtYpDvIJLjKgP1ug/0",
     ],*/
];
