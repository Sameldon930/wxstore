<?php
namespace App\Libs\Mrf;

/**
 * 每人付支付API实例
 * Class Mrf
 * @package App\Libs\Mrf
 */
class Mrf{
    const KEY = 'asdf';
    const MERCHANT_NO = '10001';

    const API_ORDER = 'http://wxstore.dev/api/v1';

    public function order($data){

        $data = array_merge($data, [
            'version' => '1.0',
            'merchant_no' => self::MERCHANT_NO,
            'method' => 'pay',
        ]);

        $data['sign'] = $this->sign($data);

        dump($data);

        $result = $this->cUrl(self::API_ORDER, [], $data);
        dd($result);
        $result = json_decode($result, true);
        dd($result);
    }

    private function paramsToString($params, $key = self::KEY)
    {
        ksort($params);

        $buff = '';

        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = $buff . '&key=' . $key;

        return $buff;
    }

    private function sign($data, $key = self::KEY)
    {
        $strData = $this->paramsToString($data, $key);

        $sign = strtoupper(md5($strData));

        return $sign;
    }

    private function cUrl($url, $header = null, $data = null)
    {
        //初始化curl
        $curl = curl_init();
        //设置cURL传输选项

        if (is_array($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {//post方式
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //获取采集结果
        $output = curl_exec($curl);


        //关闭cURL链接
        curl_close($curl);

        return $output;
    }
}