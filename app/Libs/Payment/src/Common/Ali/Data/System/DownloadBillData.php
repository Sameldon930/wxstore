<?php
namespace Payment\Common\Ali\Data\System;

use Payment\Common\Ali\Data\AliBaseData;
use Payment\Common\PayException;
use Payment\Utils\ArrayUtil;

/**
 * 支付查询数据构造
 * Class ChargeQueryData
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 *
 * @property string $bill_type 支付宝的订单号，优先使用
 * @property string $bill_date 商户系统内部的订单号
 *
 * @package Payment\Common\Ali\Data\Query
 * anthor helei
 */
class DownloadBillData extends AliBaseData
{
    /**
     * 构建业务数据
     * @return string
     */
    protected function getBizContent()
    {
        $content = [
            'bill_type'    => $this->bill_type,
            'bill_date'        => $this->bill_date,
        ];

        return $content;
    }

    /**
     * 检查参数
     * @author helei
     */
    protected function checkDataParam()
    {
        $billType = $this->bill_type;// 支付宝交易号，查询效率高
        $billDate = $this->bill_date;// 商户订单号，查询效率低，不建议使用

        // 二者不能同时为空
        if (empty($billType) && empty($billDate)) {
            throw new PayException('必须提供账单类型和账单时间');
        }
    }
}
