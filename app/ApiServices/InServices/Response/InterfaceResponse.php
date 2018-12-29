<?php
namespace App\ApiServices\InServices\Response;

/**
 * api接口类
 */
Interface InterfaceResponse
{
    /**
     * 执行接口
     * @return array
     */
    public function run(&$params);

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod();

    /**
     * 接口参数验证
     * @return string
     */
    public function checkParams(&$params);
}