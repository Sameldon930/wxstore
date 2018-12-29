<?php
namespace App\ApiServices\OutServices;

use App\Libs\Error\Error;
use Request;

use App\ApiServices\ApiCommon;

/**
 * 统一查询总入口
 * @author linkin 2017-06-06
 */
class OrderQueryServer
{
    use ApiCommon;
    /**
     * 请求参数
     * @var array
     */
    protected $params = [];

    /**
     * 错误码
     * @var boolean
     */
    protected $error = '';

    /**
     * 初始化
     *
     */
    public function __construct()
    {
        $this->params = Request::all();
        $this->error  = new Error;
    }

    /**
     * api服务入口执行
     * @return array  执行结果
     */
    public function run()
    {
        //初步校验
        $result=$this->checkCommonParams();
        if (! $result['status']) {
            return $this->response(['status' => false, 'code' => $result['code']]);
        }

        //检查是否有权限执行通道
        //if(!$this->checkChannelAuth($this->user_id,$this->params['pay_type'])){

       // };

        return $this->runRoute('OrderQuery');

    }

    /*//检查是否有权限执行通道 --预留
    protected function checkChannelAuth($user_id,$pay_type){
        //预留以后分配通道用途
        return true;
    }*/

    /**
     * 执行路径
     * @param $route string 执行的路径名
     * @return array  执行结果
     */
    protected function runRoute($route){
        return $this->checkRoute($route, __NAMESPACE__);
    }


}
