<?php

namespace App;
use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    use Searchable,CommonExportTrait;
    protected $table = 'messages';
    protected $fillable = [
        'title','content','top','orderby','status','now','text','is_read'
    ];
    const YES = 1;
    const NO = 2;
    const MESSAGE_READ =[
        self::YES =>'已读',
        self::NO =>'未读'

    ];
    const TOP_SWITCH = [
        self::YES => '置顶',
        self::NO => '不置顶',
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
    public function scopeRead($query){
        return $query->where('is_read',self::YES);
    }


}
