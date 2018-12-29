<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Debug\Dumper;

if (! function_exists('ndd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function ndd(...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }

        //die(1);
    }
}
if(!function_exists('chunk_string')) {
    function chunk_string($text, $positions)
    {
        $chunks = [];
        $index = 0;
        foreach ($positions as $position){
            $chunks[] = substr($text, $index, $position);
            $index += $position;
        }
        return trim(implode(" ", $chunks));
    }
}
/**
 * 去除字符内所有空格
 * @param string $amount 操作的数据
 * @return string
 */
if (!function_exists('removeSpace')) {
    function removeSpace($str)
    {
        if ($str == null) {
            return '';
        }
        return preg_replace('# #','',$str);
    }
}

/**
 * 去除字符内所有逗号
 * @param string $amount 操作的数据
 * @return string
 */
if (!function_exists('removeComma')) {
    function removeComma($str)
    {
        if ($str == null) {
            return '';
        }
        return preg_replace('#,#','',$str);
    }
}

/**
 * 调用的文件中需要 use Illuminate\Support\Facades\Input; Illuminate\Support\Facades\Storage;
 * save_image_file  保存图片文件 ,存在Storage::disk('uploads') 目录下
 * @var $file object 上传的图片文件,具体是在 request 中的 UploadedFile 类型的对象
 * @var $prefix_name string 可选保存的文件名前缀
 * @var $path string 文件路径
 * @return bool/string 如果通过验证 则返回在新的文件名
 */
if (!function_exists('save_image_file')) {

    function save_image_file(&$file, $prefix_name = '', $path = 'serve')
    {
        $file = isset($file) ? $file : null;
        if ($file != null && $file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            //dd($ext);
            $file->getClientOriginalName();

            if ($ext == "" && $file->getClientOriginalName() == 'blob') {
                $ext = 'png';
            }
            if (!preg_match('/jpg|png|gif$/is', $ext)) {
                return false;
            }
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            // 上传文件
            $filename = $prefix_name . '-' . date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            $bool = Storage::disk($path)->put($filename, file_get_contents($realPath));
            if (!$bool) return false;
            return $filename;
        }
        return false;
    }
}

/**
 * old_or_new_field 用于编辑页面显示原始数据, 如果编辑/新增失败时,优先使用old() 内容填充字段
 * @var $str string 要显示的字段名
 * @var $data object 存放字段集合的对象
 * @var $value object $data的子集合,如果数据在集合中,请输入改子集合名
 * @return bool/string 返回字段内容 否则false
 */
if (!function_exists('old_or_new_field')) {

    function old_or_new_field($str = null, $data = null, $value = null)
    {
        if (old($str) != null) {
            return old($str);
        } elseif (isset($data)) {
            if ($value != null) {
                return isset($data->$value->$str) ? $data->$value->$str : '';
            }
            return isset($data->$str) ? $data->$str : '';
        }
        return false;
    }

}


/**
 * 系统操作日志
 * @param array $data 操作的数据
 * @param string $note 操作备注
 * @return boolean
 */
if (!function_exists('admin_action_logs')) {
    function admin_action_logs($data, $note,$type=null)
    {

        if ($data == null || $note == null) {
            return false;
        }

        $data_arr = $data;
        $data_arr = json_encode($data_arr);

        $arr = array(
            'admin_id' => \Illuminate\Support\Facades\Auth::user()->id,
            'url' => $_SERVER['REQUEST_URI'],
            'data' => $data_arr,
            'note' => $note,
            'type' => $type??2
        );
        $result = \App\ActionLog::create($arr);

        if (!$result) {
            throw new \App\Exceptions\ErrorMessageException("系统日志插入失败，请联系管理员");
        }

        return $result;
    }
}

/**
 * 列表导出
 * @param array $cellData 导出的数据
 * @param string $title 列表名
 * @time null  时间
 */
if (!function_exists('export_data')) {
    function export_data($cellData, $title)
    {
        $request = request();
        $start_at = $request->get('start_at');
        $end_at = $request->get('end_at');
        if($start_at == null && $end_at ==null){
            $time = '';
        }elseif($start_at != null && $end_at ==null){
            $time = $start_at.'至今';
        }elseif($start_at == null && $end_at !=null){
            $time = '截止至'.$end_at;
        }else{
            $time = $start_at.'至'.$end_at;
        }
        //判断一些字段，添加防科学计数法*
        $keyArray = [];
        $addStatrs = [' ','收款人银行卡','身份证','平台订单号','账号','到账银行卡'];
        foreach ($cellData[0] as $key=>$th){
            foreach ($addStatrs as $addStatr){
                if(strpos($th,$addStatr) !== false){
                    array_push($keyArray,$key);
                }
            }

        }
        if(count($keyArray) !== 0){
            foreach ($cellData as $key=>&$item){
                if($key == 0){
                    continue;
                }
                foreach ($keyArray as $value){
                    $item[$value] = '*'.$item[$value];
                }
            }
        }
        unset($item);

        if (array_key_exists('accept', $request->headers->all())){
            Excel::create($title . $time, function ($excel) use ($cellData,$title) {
                $excel->sheet('score', function ($sheet) use ($cellData,$title) {
                    $count = count($cellData)-1;
                    admin_action_logs("列表数（".$count ."）", "导出".$title.",列表数（".$count ."）",\App\ActionLog::TYPE_DOWNLOAD);
                    $sheet->rows($cellData);
                });
            })->export('xls');
        }

        return $msg = '导出'.$title.'成功';
    }
}

/**
 * 数据格式化
 * echart插件需要数据格式为 ["2017.01.01","2017.01.02"] ["1", "2"] 两个数组形式
 * 先将开始日期和结束日期之间的所有日期初始化为0天 ["2017.01.01" => 0, "2017.01.02" => 0]
 * 再遍历数据库获取的数据 找到日期相同的键 将值加一 ["2017.01.01" => 1, "2017.01.02" => 2]
 * 最后将其拆开成两个数组
 * @return array
 */
if (!function_exists('format_chart_data')) {
    function format_chart_data(\Carbon\Carbon $start_at, \Carbon\Carbon $end_at)
    {
        $chart_base_data = []; // 总数据
        $temp_start_at = $start_at->copy(); // 由于Carbon->addDay会将原实例值改变 故复制一份
        //时间转化为2000-1-11格式
        $str_start_at = $temp_start_at->toDateString();
        $str_end_at = $end_at->toDateString();
        // 开始日期小等于结束日期
        while ($str_start_at <= $str_end_at) {
            $chart_base_data[$str_start_at] = 0; // 日期初始化为0
            $str_start_at = $temp_start_at->addDay()->toDateString(); // 加一天继续初始化
        }
        return $chart_base_data;
    }
}

/**
 * @param $chart_base_datas array 使用format_chart_data构建的收益图表数据
 * @param $datas array 待填充数据
 * @param $query string 要查找的字段
 * @param $request array 待填充数据
 * @return array
 */
if (!function_exists('fill_chart_datas')) {
    function fill_chart_datas($chart_base_datas, $datas, $query)
    {
        // 遍历数据库获取的数据 找到日期相同的键 将值加一
        $chart_count_data = $chart_base_datas;
        foreach ($datas as $data) {
            $created_at = $data->created_date??$data->created_at->toDateString();
            if (array_key_exists($created_at, $chart_base_datas)) {
                $chart_base_datas[$created_at] += sprintf("%01.2f", round($data->$query / 100, 2));
                $chart_count_data[$created_at] += 1;
                $chart_base_datas[$created_at] = round($chart_base_datas[$created_at], 2);
            }
        }

        //dd($chart_base_datas);
        // 分别获取数组的键和值
        $chart_dates = array_keys($chart_base_datas);  //时间
        $chart_nums = array_values($chart_base_datas);  //数据
        $chart_count = array_values($chart_count_data);  //数量
        return array($chart_dates, $chart_nums, $chart_count);
    }
}

if (!function_exists('old_or_new_img')) {
    function old_or_new_img($field, $data, $as_url)
    {
        $old_field = 'old_' . $field;
        $old_session = session($old_field);
        if ($old_session) {
            return $as_url ? asset('storage/' . $old_session) : $old_session;
        }

        if (isset($data->$field)) {
            return $as_url ? asset('storage/' . $data->$field) : $data->$field;
        }
        return false;
    }
}


if (!function_exists('links_custom')) {
    function links_custom(\Illuminate\Pagination\LengthAwarePaginator $data, $search_items = []){
        $searches = [];
        $request = request();
        foreach ($search_items as $k => $v){
            if ($v['type'] === 'date') {
                $searches['start_at'] = $request->get('start_at');
                $searches['end_at'] = $request->get('end_at');
            } else {
                $searches[$k] = $request->get($k);
            }
        }
        return $data->appends($searches)->links('vendor/pagination/custom');
    }
}

if (!function_exists('_view')) {
    function _view($view = null, $data = [], $mergeData = []){
        $objView = view($view, $data, $mergeData);

        if (env('APP_DEBUG')){
            $action = Route::getCurrentRoute()->getAction()['controller'];
            list($class, $method) = explode('@', $action);
            $reflectionMethod = new ReflectionMethod($class, $method);

            $objView['__view'] = $objView->getPath();
            $objView['__controller'] = $reflectionMethod->getFileName() . ':' . $reflectionMethod->getStartLine();
        }

        return $objView;
    }
}

/**
 * 分单位转元单位
 * @param number $amount 操作的数据
 * @return number|boolean
 */
if (!function_exists('fenToYuan')) {
    function fenToYuan($amount)
    {
        if ($amount == null) {
            return 0;
        }

        $number=sprintf("%01.2f", round($amount / 100, 2));
        return number_format ( $number , 2 , '.' , ',' );
    }
}

/**
 * 毫单位转元单位
 * @param number $amount 操作的数据
 * @return number|boolean
 */
if (!function_exists('haoToYuan')) {
    function haoToYuan($amount)
    {
        if ($amount == null) {
            return 0;
        }
        $number=sprintf("%01.4f", round(($amount / 100) / 100, 4));
        return number_format ( $number , 4 , '.' , ',' );
    }
}

/**
 * 分单位转元单位
 * @param number $amount 操作的数据
 * @return number|boolean
 */
if (!function_exists('yuanToFen')) {
    function yuanToFen($amount)
    {
        if ($amount == null) {
            return 0;
        }
        return (int)($amount * 100);
    }
}

if (!function_exists('generateSign')){
    function generateSign($params, $key){
        ksort($params);

        $buff = "";
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&") . '&key=' . $key;

        $sign2 = strtoupper(md5($buff));
        return $sign2;
    }
}

/**
 * 判断是否为支付宝客户端
 * @return bool
 */
if (!function_exists('isAliClient')){
    function isAliClient(){
        $ua = request()->server('HTTP_USER_AGENT');
        return strpos($ua, 'AlipayClient') !== false;
    }
}

/**
 * 判断是否为微信客户端
 * @return bool
 */
if (!function_exists('isWechatClient')){
    function isWechatClient(){
        $ua = request()->server('HTTP_USER_AGENT');
        return strpos($ua, 'MicroMessenger') !== false;
    }
}

/**
 * 格式化数字，不足补零操作
 * @param number $number 要格式化的数字
 * @param number $length =6 数字长度
 * @return boolean
 */
if (!function_exists('formatNumber')) {
    function formatNumber($number, $length = 6)
    {
        if ($number == null) {
            return '';
        }
        $str = "%0" . $length . "d";
        return sprintf($str, $number);
    }
}
/**
 * 验证手机号是否正确
 * @author honfei
 * @param number $mobile
 */
if (!function_exists('isMobile')) {
    function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}

?>