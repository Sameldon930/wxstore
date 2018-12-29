<?php
namespace App\ApiServices\InServices\Response;
/**
 * api基础类
 */
abstract class BaseResponse
{
    /**
     * 接口名称
     *
     * @var [type]
     */
    protected $method;

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


}