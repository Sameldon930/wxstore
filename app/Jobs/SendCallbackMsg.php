<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class SendCallbackMsg implements ShouldQueue
{
    //发送给下游异步通知内容
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $send_data = [];
    public $time = 0;

    /**
     * Create a new job instance.
     * @param array $data 要发送的数据
     * @param number $time =0 要发送的数据
     * @return void
     */
    public function __construct(Array $data, $time = 0)
    {
        //
        $this->send_data = $data;
        $this->time = $time;
    }

    /**
     * Execute the job.
     * @param number $data 发送异步通知的订单号
     * @param number $time 已经发送的次数
     * @return object
     */
    public function handle()
    {
        //执行异步通知内容
        $data = $this->send_data;
        $time = $this->time;

        $result = $this->sendData($data);
        if ($result != 'success' && $result != 'SUCCESS') {
            return false;
        } else {
            return true;
        }

        /*原为多次发送回调代码
         * $time++;
        //dd($data);
        $result = $this->sendData($data);
        if ($result!='ok') {
            //未的到指定的内容,次数未超过
            //根据次数，指定队列时间
            switch($time){
                case 1:
                    $seconds=10;
                    break;
                case 2:
                    $seconds=30;
                    break;
                case 3:
                    $seconds=120;
                    break;
                case 4:
                    $seconds=300;
                    break;
                case 5:
                    $seconds=600;
                    break;
                case 6:
                    $seconds=1800;
                    break;
                case 7:
                    $seconds=3600;
                    break;
                default:
                    $seconds='404';
            }
            if($seconds!='404'){
                return dispatch(new SendCallbackMsg($data,$time))->delay(Carbon::now()->addSecond($seconds));
            }

        } else {
            return true;
        }*/
    }


    /**
     * Execute the send.
     * @param array $data 发送异步通知的订单号
     * @return object
     */
    protected function sendData(Array $data)
    {

        if (!array_key_exists('notify_url', $data) || $data['notify_url'] == '') {
            return 'ok';
        }
        $url = $data['notify_url'];
        unset($data['notify_url']);
        $output = $this->postData($url, $data);

        return $output;
        //return json_decode($output);
    }

    /**
     * 发送url post 信息.
     * @param string $url 发送异步通知的订单号
     * @param string $postData 发送异步通知的订单号
     * @return object
     */
    protected function postData($url, $postData = '')
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

}
