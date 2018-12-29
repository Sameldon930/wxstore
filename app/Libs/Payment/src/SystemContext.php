<?php
namespace Payment;

use Payment\Common\BaseStrategy;
use Payment\Common\PayException;
use Payment\System\Ali\AliDownloadBill;
use Payment\System\Ali\AliOauthToken;

/**
 * @author: helei
 * @createTime: 2016-07-14 17:42
 * @description: 支付上下文
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 *
 * Class ChargeContext
 *
 * 支付的上下文类
 *
 * @package Payment
 */
class SystemContext
{
    /**
     * 支付的渠道
     * @var BaseStrategy
     */
    protected $method;


    /**
     * 设置对应的支付渠道
     * @param string $channel 支付渠道
     *  - @see Config
     * @param array $config 配置文件
     * @throws PayException
     * @author helei
     */
    public function initMethod($method, array $config)
    {
        // 初始化时，可能抛出异常，再次统一再抛出给客户端进行处理
        try {
            switch ($method) {
                case Config::ALI_SYSTEM_OAUTH_TOKEN:
                    $this->method = new AliOauthToken($config);
                    break;
                case Config::ALI_SYSTEM_DOWNLOAD_BILL:
                    $this->method = new AliDownloadBill($config);
                    break;
                default:
                    throw new PayException('不支持的方法');
            }
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 通过环境类调用支付
     * @param array $data
     *
     * ```php
     * $payData = [
     *      "order_no" => createPayid(),
     *      "amount" => '0.01',// 单位为元 ,最小为0.01
     *      "client_ip" => '127.0.0.1',
     *      "subject" => '测试支付',
     *      "body" => '支付接口测试',
     *      "extra_param"   => '',
     * ];
     * ```
     *
     * @return array
     * @throws PayException
     * @author helei
     */
    public function request(array $data)
    {
        if (! $this->method instanceof BaseStrategy) {
            throw new PayException('请检查初始化是否正确');
        }

        try {
            return $this->method->handle($data);
        } catch (PayException $e) {
            throw $e;
        }
    }
}
