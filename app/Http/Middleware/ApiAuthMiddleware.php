<?php

namespace App\Http\Middleware;
use Closure;
use Validator;


class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        /*$params = $request->all();

        // 1初步校验
        $rules = [
            'version' => 'required|regex:/1.0/',
            'merchant_no' => 'required|string',
            'sign' => 'required',
        ];
        $messages = [
            'sign.required' => '1001',
            'merchant_no.required' => '1003',
            'merchant_no.string' => '1004',
        ];

        $v = Validator::make($params, $rules, $messages);

        if ($v->fails()) {
            return [];
        }

        // 2校验签名
        if (!$this->checkSign($params, 'china')){
            return [];
        }*/

        return $next($request);
    }

    /**
     * 检验签名
     * @param $params
     * @param $key
     * @return bool
     */
    protected function checkSign($params, $key)
    {
        $sign = $params['sign'];

        unset($params['sign']);

        if ($sign != generateSign($params, $key)){
            return false;
        } else {
            return true;
        }
    }
}
