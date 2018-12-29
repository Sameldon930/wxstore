<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Exceptions\ErrorMessageException;
use App\Http\Controllers\Controller;
use App\MetaData;
use App\UserMerchantTube;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(){

        $metadata = MetaData::get();
        $data = [];

        foreach($metadata as $metadatum){
            $data[$metadatum->key] = $metadatum->value;
        }

        return _view('admin.meta.index', compact('data'));
    }

    public function update(){
        $keys = $this->request->except('_token');


        foreach ($keys as $key => $value){
            $method = 'update_' . $key;
            if (method_exists(__CLASS__, $method)){
                $metaData = MetaData::getByKey($key);

                $this->$method($metaData, $value);

                $display = MetaData::META_DATA[$metaData->key] ?? '未配置';
                ActionLog::log(ActionLog::TYPE_META, $metaData, "系统配置（{$display}）");

            }else {
                throw new ErrorMessageException('配置不存在');
            }
        }

        return back()->with('msg', '更新成功');
    }
    //微信最高利润率
    public function update_MERCHANT_WECHAT_MAX_PROFIT_RATE($metaData, $value){

//        $this->validate($this->request, [$metaData->key => 'integer|min:0']);

        // TODO 2写死
        $maxProfitRate = UserMerchantTube::where('tube_id', 1)->max('profit_rate') ?: 0;

        if (intval($value) < $maxProfitRate){
            throw new ErrorMessageException("最高利润率不能低于商户已配置的利润率（{$maxProfitRate}）");
        }

        if (!$metaData){
            MetaData::create([
                'key' => $metaData->key,
                'value' => $value
            ]);
        } else {
            $metaData->value = $value;
            $metaData->save();
        }
    }
    //支付宝最高利润率
    public function update_MERCHANT_ALI_MAX_PROFIT_RATE($metaData, $value){

        $this->validate($this->request, [$metaData->key => 'integer|min:0']);

        // TODO 2写死
        $maxProfitRate = UserMerchantTube::where('tube_id', 2)->max('profit_rate') ?: 0;

        if (intval($value) < $maxProfitRate){
            throw new ErrorMessageException("最高利润率不能低于商户已配置的利润率（{$maxProfitRate}）");
        }

        if (!$metaData){
            MetaData::create([
                'key' => $metaData->key,
                'value' => $value,
            ]);
        } else {
            $metaData->value = $value;
            $metaData->save();
        }
    }
    //更新后台版本
    public function update_VERSION_BACKEND($metaData, $value){

        if (!$metaData){
            MetaData::create([
                'key' => $metaData->key,
                'value' => $value,
            ]);
        } else {
            $metaData->value = $value;
            $metaData->save();
        }
    }
    //更新前台版本
    public function update_VERSION_FRONTEND($metaData, $value){

        if (!$metaData){
            MetaData::create([
                'key' => $metaData->key,
                'value' => $value,
            ]);
        } else {
            $metaData->value = $value;
            $metaData->save();
        }
    }
}
