<?php
namespace App\Services\WebServices;


use App\ActionLog;
class StatusSwitchService{

    /**
     * 更改模型类的 status 字段
     * @param $class
     * @param $id
     * @param $status
     * @return array
     */
    public static function change($class, $id, $status){

        try {
            $model = $class::findOrFail($id);
            $model->status = $status;
            $model->save();

            ActionLog::log(ActionLog::TYPE_ADMIN, $model, "开关切换（{$model->id}）");
        }catch (\Exception $e){
            return ['code' => 401, 'msg' => '切换失败，请刷新后重试'];
        }

        return ['code' => 200, 'msg' => '切换成功'];
    }
}