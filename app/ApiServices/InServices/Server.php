<?php
namespace App\ApiServices\InServices;

use Request;
use Validator;
/*use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;*/

/**
 * API服务总入口
 * @author linkin 2017-06-06
 */
class Server
{
    /**
     * 请求参数
     * @var array
     */
    protected $params = [];

    /**
     * API请求Method名
     * @var string
     */
    protected $method;

    /**
     * app_secret
     * @var string
     */
    protected $app_secret = 'wis';

    /**
     * 回调数据格式
     * @var string
     */
    protected $format = 'json';

    /**
     * 签名方法
     * @var string
     */
    protected $sign_method = 'md5';

    /**
     * 是否输出错误码
     * @var boolean
     */
    protected $error_code_show = false;

    /**
     * 初始化
     * @param Error $error Error对象
     */
    public function __construct(Error $error)
    {
        $this->params = Request::all();
        $this->error  = $error;
    }

    /**
     * api服务入口执行
     * @param  Request $request 请求参数
     * @return [type]           [description]
     */
    public function run()
    {
        // 1初步校验
        $rules    = [
            'method'      => 'required',
            'nonce'       => 'required|string|min:1|max:32|',
            'sign'        => 'required',
        ];
        $messages = [
            'sign.required'   => '1001',
            'nonce.required'  => '1003',
            'nonce.string'    => '1004',
            'nonce.min'       => '1005',
            'nonce.max'       => '1005',
            'method.required' => '1007',
        ];

        $v = Validator::make($this->params, $rules, $messages);

        if ($v->fails()) {
            return $this->response(['status' => false, 'code' => $v->messages()->first()]);
        }

        $this->method      = $this->params['method'];


        // 2校验签名
        if(!$this->params['sign']==='0000'){ //8-15为单元测试增加的免签测试通道
            $signRes = $this->checkSign($this->params);
            if (! $signRes || ! $signRes['status']) {
                return $this->response(['status' => false, 'code' => $signRes['code']]);
            }
        }



        // 3校验接口名
        $className = self::getClassName($this->method);

        $classPath = __NAMESPACE__ . '\\Response\\' . $className;
        if (!$className || !class_exists($classPath)) {
            return $this->response(['status' => false, 'code' => '1008']);
        }

        if (! method_exists($classPath, 'run')) {
            return $this->response(['status' => false, 'code' => '1009']);
        }

        $this->classname = $classPath;
        $class = new $classPath;


        // 判断方法是否存在 并分发接口
        if (method_exists($classPath, 'checkParams')) {
            return $this->response((array) $class->checkParams($this->params));
        }else{
            return $this->response((array) $class->run($this->params));
        }

    }

    /**
     * 校验签名
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    protected function checkSign($params)
    {

        $sign = array_key_exists('sign', $params) ? $params['sign'] : '';

        if (empty($sign))
            return array('status' => false, 'code' => '1001');

        unset($params['sign']);

        if ($sign != $this->generateSign($params))
            return array('status' => false, 'code' => '1002');

        return array('status' => true, 'code' => '200');
    }

    /**
     * 生成签名
     * @param  array $params 待校验签名参数
     * @return string|false
     */
    protected function generateSign($params)
    {

        if ($this->sign_method == 'md5')
            return $this->generateMd5Sign($params);

        return false;
    }

    /**
     * md5方式签名
     * @param  array $params 待签名参数
     * @return string
     */
    protected function generateMd5Sign($params)
    {
        ksort($params);

        $tmps = array();
        //dd($params);
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = $this->app_secret . implode('', $tmps) . $this->app_secret;
        //dd(strtoupper(md5($string)));
        return strtoupper(md5($string));
    }


    /**
     * 通过方法名转换为对应的类名
     * @param  string $method 方法名
     * @return string|false
     */
    protected function getClassName($method)
    {
        $methods = explode('.', $method);

        if (!is_array($methods))
            return false;

        $tmp = array();
        foreach ($methods as $value) {
            $tmp[] = ucwords($value);
        }

        $className = implode('', $tmp);
        return $className;
    }

    /**
     * 输出结果
     * @param  array $result 结果
     * @return response
     */
    protected function response(array $result)
    {
        if (! array_key_exists('msg', $result) && array_key_exists('code', $result)) {
            $result['msg'] = $this->getError($result['code']);
        }

        if ($this->format == 'json') {
            return response()->json($result);
        }

        return false;
    }

    /**
     * 返回错误内容
     * @param  string $code 错误码
     * @return string
     */
    protected function getError($code)
    {
        return $this->error->getError($code, $this->error_code_show);
    }


}
