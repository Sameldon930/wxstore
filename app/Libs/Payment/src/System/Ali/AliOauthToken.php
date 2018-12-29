<?php
namespace Payment\System\Ali;

use Payment\Common\Ali\AliBaseStrategy;
use Payment\Common\Ali\System\OauthTokenData;
use Payment\Common\PayException;

/**
 * 支付宝授权码获取用户信息
 *
 * Class AliBarCharge
 * @package Payment\Charge\Ali
 *
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 */
class AliOauthToken extends AliBaseStrategy
{
    // app 支付接口名称
    protected $method = 'alipay.system.oauth.token';

    public function getBuildDataClass()
    {
        $this->config->method = $this->method;
        return OauthTokenData::class;
    }


    protected function retData(array $ret)
    {
        $reqData = parent::retData($ret);

        // 发起网络请求
        try {
            $data = $this->sendReq($reqData);
        } catch (PayException $e) {
            throw $e;
        }

        // 检查是否报错
        /*if ($data['code'] !== '10000') {
            new PayException($data['sub_msg']);
        }*/

        return $data;
    }
}
