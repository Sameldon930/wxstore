<?php

namespace App;
use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Side extends Model
{
    //
    use Searchable,CommonExportTrait;
    protected $table='side';
    protected  $fillable =[
        'image','group_id','url','orderby','note','status'
    ];
    const GROUP_ONE = 1;
    const GROUP_TWO = 2;
    const GROUP_THREE = 3;

    const GROUP_ID =[
        self::GROUP_ONE =>'商户端',
        self::GROUP_TWO =>'代理端',
        self::GROUP_THREE=>'门店端'
    ];

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS = [
        self::STATUS_ENABLED => '已启用',
        self::STATUS_DISABLED => '已禁用',
    ];

    public function scopeEnabled($query){
        return $query->where('status', self::STATUS_ENABLED);
    }

}
