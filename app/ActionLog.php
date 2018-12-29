<?php

namespace App;

use App\Http\Traits\Searchable;
use function EasyWeChat\Payment\get_client_ip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionLog extends Model
{
    use Searchable;

    protected $fillable = [
        'admin_id', 'type', 'url', 'data', 'note',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    const TYPE_LOGIN = 1;
    const TYPE_ADMIN = 2;
    const TYPE_USER = 3;
    const TYPE_ROLE = 4;
    const TYPE_ORDER = 5;
    const TYPE_SETTLE = 6;
    const TYPE_META = 7;
    const TYPE_DOWNLOAD = 8;
    const TYPES = [
        self::TYPE_LOGIN => '登录',
        self::TYPE_ADMIN => '管理员',
        self::TYPE_USER => '用户',
        self::TYPE_ROLE => '角色',
        self::TYPE_ORDER => '订单',
        self::TYPE_SETTLE => '结算',
        self::TYPE_META => '配置',
        self::TYPE_DOWNLOAD => '下载',
    ];

    public static function log(Int $type, $data, String $note)
    {
        self::create([
            'admin_id' => Auth::user()->id,
            'type' => $type,
            'url' => get_client_ip(),
            'data' => json_encode($data),
            'note' => $note,
        ]);
    }
}
