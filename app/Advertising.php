<?php

namespace App;
use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    //
    use Searchable,CommonExportTrait;
    protected $table = 'advertising';
    protected $fillable = [
        'image','url','orderby','note','status'
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
    public function scopeShow($query)
    {
        return $query->where('status','1');
    }
}
