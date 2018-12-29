<?php
namespace App\Http\Controllers\Api\In;


use App\ApiServices\InServices\Error;
use App\ApiServices\InServices\Server;
use App\Http\Controllers\Controller;

/**
 * 公共Api入口控制器
 * @author linkin 2017-06-06
 */
class WebApiController extends Controller
{
    /**
     * API总入口
     * @return [type] [description]
     */
    public function index()
    {

        /*if(!empty($_FILES)){
            $server = new ServerSaveImg(new Error);
            return $server->run();
        }*/

        $server = new Server(new Error);
        return $server->run();
    }

}
