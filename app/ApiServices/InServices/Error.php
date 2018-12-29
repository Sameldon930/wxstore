<?php
namespace App\ApiServices\InServices;

/**
 * API服务端 - 错误码
 */
class Error
{
    /**
     * 错误码
     * @var [type]
     */
    public static $errCodes = [
        // 系统码
        '200' => '成功',
        '400' => '未知错误',
        '401' => '无此权限',
        '500' => '服务器异常',

        // 公共错误码
        '1001' => '[sign]缺失',
        '1002' => '[sign]签名错误',
        '1003' => '[nonce]缺失',
        '1004' => '[nonce]必须为字符串',
        '1005' => '[nonce]长度必须为1-32位',
        '1006' => '[method]缺失',
        '1007' => '[method]方法不存在',
        '1008' => 'run方法不存在，请联系管理员',

        //业务公共错误码
        '2001' => '必要参数缺失',
    ];

    /**
     * 返回错误码
     * @var string
     */
    public static function getError($code = '400', $_ = false)
    {
        if (! isset(self::$errCodes[$code])) {
            $code = '400';
        }

        return ($_ ? "[{$code}]" : '')
            . self::$errCodes[$code];
    }
}
