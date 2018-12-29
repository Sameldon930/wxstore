<?php
namespace Payment\Client;

use Payment\Common\PayException;
use Payment\Config;
use Payment\SystemContext;

/**
 * @author: helei
 * @createTime: 2017-09-02 18:20
 * @description: 支付的客户端类
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 * Class Charge
 * @package Payment\Client
 *
 */
class System
{
    /**
     * 支付实例
     * @var SystemContext
     */
    protected static $instance;

    protected static function getInstance($method, $config)
    {
        /* 设置内部字符编码为 UTF-8 */
        mb_internal_encoding("UTF-8");
        
        if (is_null(self::$instance)) {
            static::$instance = new SystemContext();
        }

        try {
            static::$instance->initMethod($method, $config);
        } catch (PayException $e) {
            throw $e;
        }

        return static::$instance;
    }

    /**
     * @param string $channel
     * @param array $config
     * @param array $metadata
     *
     * @return mixed
     * @throws PayException
     */
    public static function run($method, $config, $metadata)
    {
        try {
            $instance = self::getInstance($method, $config);

            $ret = $instance->request($metadata);
        } catch (PayException $e) {
            throw $e;
        }

        return $ret;
    }
}
