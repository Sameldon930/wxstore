<?php
namespace App\Libs\CloudPay;


use Illuminate\Support\Str;
use PhpParser\Node\Expr\Array_;


class CloudPay
{
    const KEY = "32GYvzsVkR7hi@`bUnTb!211|1nJ?D1C";
    const CHANNEL_NO = "8023506000102";
    const HOST = "http://cp.huanhe.pro";

    public $key = '';
    public $channel_no = '';
    private $commonParams = [
        'version' => "1.0",
        'merchant_no' => self::CHANNEL_NO,
        'sign_type' => 'MD5',
    ];

    public function __construct()
    {

        $this->key = "mAG=kAC83E=fD^&/wsFpcYK9lyNQN+c3";

        $this->channel_no = "8023506000156";

        $this->setCommonParams();
    }

    public function setCommonParams()
    {
        if ($this->channel_no != '') {
            $this->commonParams['merchant_no'] = $this->channel_no;
        }
    }

    //统一下单接口
    public function orders($params)
    {
        return $this->request(self::HOST . "/api/pay/unifiedorder", $params);

    }


    public function withdraw($params)
    {
        return $this->request(self::HOST . "/api/pay/unifiedpayorder", $params);
    }

    public function withdrawQuery($params)
    {
        return $this->request(self::HOST . "/api/pay/payorderquery", $params);
    }

    public function account($params)
    {
        return $this->request(self::HOST . "/api/pay/useraccountquery", $params);
    }

    protected function request($url, Array $params)
    {

        $params = array_merge($params, $this->commonParams);
        $strParams = $this->paramsToString($params);

        $params['sign'] = $this->getSign($strParams);

        return json_decode($this->curl($url, $params), true);
    }

    public function sign($params)
    {
        $params = array_merge($params, $this->commonParams);
        $strParams = $this->paramsToString($params);

        return $this->getSign($strParams);

    }


    protected function paramsToString($params)
    {
        ksort($params);

        $buff = "";
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    protected function curl($url, $params = '')
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    protected function getSign($params)
    {
        return strtoupper(md5($params . '&key=' . $this->key??self::KEY));
    }

    public function checkCallback(Array $params)
    {
        $sign1 = $params['sign'];
        unset($params['sign']);

        $strParams = $this->paramsToString($params);
        $sign2 = $this->getSign($strParams);

        if ($sign1 === $sign2) {
            return true;
        } else {
            return false;
        }
    }
}