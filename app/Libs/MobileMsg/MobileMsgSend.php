<?php

namespace App\Libs\MobileMsg;

//use Illuminate\Database\Eloquent\Model;


class MobileMsgSend
{

    //短信供应商需要的秘钥信息
    private $appid = '35fd418a55aa453eb0cb9acfc1e50cfe';
    private $accountSid = 'b86646c5ae9609a2f8955a0589196abc';
    private $auth_token = 'b98d7f249a6886547e6b2005c1c821ea';

    private $mobile = '';
    private $msg = '';
    private $templateId = 0;

    private $database_log = 1; //是否开启API接口记录

    public function __construct()
    {
        //config('app.database_log'); 可以将数据库记录开关设置到config数组中
    }

    /**
     * 发送短信
     * @param  string $mobile 手机号码
     * @param  string $msg 短信内容
     * @param  string $templateId 短信模板
     * @param  boolean $status 是否真实发送
     * @return array
     */
    public function send($mobile, $msg, $templateId = null, $status = false)
    {
        if (!$status) {

            $this->mobile = $mobile;
            $this->msg = $msg;
            if ($this->mobile == '') {
                return false;
            }
            $this->templateId = $templateId ? $templateId : 376417;

            return $this->do_curl();
        } else {
            return ['respCode' => '000000'];
        }


    }

    /**
     * 生成xml
     * @return string
     */
    public function create_xmldate()
    {
        $appId = $this->appid;
        $templateId = $this->templateId;
        $mobile = $this->mobile;
        $msg = $this->msg;


        $xmldata = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <templateSMS>
                        <appId>' . $appId . '</appId>
                        <templateId>' . $templateId . '</templateId>
                        <to>' . $mobile . '</to>
                        <param>' . $msg . '</param>
                    </templateSMS>';

        return $xmldata;
    }

    /**
     * do_curl 请求短信接口
     * @return array
     */
    public function do_curl()
    {

        $xmldata = $this->create_xmldate();

        $accountSid = $this->accountSid;
        $auth_token = $this->auth_token;

        $time = date('YmdHms');
        $sig = strtoupper(md5($accountSid . $auth_token . $time));
        $Authorization = base64_encode($accountSid . ':' . $time);
        $url = 'https://api.ucpaas.com/2014-06-30/Accounts/' . $accountSid . '/Messages/templateSMS?sig=' . $sig;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/xml',
            'Content-Type: application/xml;charset=utf-8',
            'Authorization:' . $Authorization,
            'Content-Length: ' . strlen($xmldata)
        ));

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        $respond_data = json_encode(simplexml_load_string($result));

        return json_decode($respond_data, TRUE);

    }

}
