<?php
namespace Payment\Common\Ali\Data\Charge;

use Payment\Common\PayException;

/**
 * 支付宝 统一收单交易创建接口 用于JSAPI支付
 * Class JsChargeData
 * @package Payment\Common\Ali\Data\Charge
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 *
 * @property string $operator_id  商户操作员编号
 * @property string $terminal_id 商户机具终端编号
 * @property string $buyer_id 买家ID
 *
 */
class JsChargeData extends ChargeBaseData
{
    /**
     * 业务请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递
     *
     * @return string
     */
    protected function getBizContent()
    {
        $content = [
            'out_trade_no'  => strval($this->order_no),
            'total_amount'  => strval($this->amount),
            'subject'       => strval($this->subject),
            'body'          => strval($this->body),
            'buyer_id'      => strval($this->buyer_id),
            'extend_params' => ['sys_service_provider_id' => '2088131243752343']
            // TODO 支付宝用户ID
            // 'seller_id' => $this->partner,

            // TODO 折扣金额
            // 'discountable_amount' => '',
            // TODO  业务扩展参数 订单商品列表信息，待支持
            // 'extend_params => '',
            // 'goods_detail' => '',

            // 'operator_id' => $this->operator_id,
            // 'store_id' => $this->store_id,
            // 'terminal_id' => $this->terminal_id,
        ];

        $timeExpire = $this->timeout_express;
        if (! empty($timeExpire)) {
            $express = floor(($timeExpire - strtotime($this->timestamp)) / 60);
            ($express > 0) && $content['timeout_express'] = $express . 'm';// 超时时间 统一使用分钟计算
        }

        return $content;
    }

    protected function checkDataParam()
    {

        $buyer_id = $this->buyer_id;

        if (empty($buyer_id)) {
            throw new PayException('请提供买家ID');
        }

        parent::checkDataParam(); // TODO: Change the autogenerated stub
    }
}
