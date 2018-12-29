<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use Searchable;

    protected $fillable = ['name', 'tube_id', 'status'];

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS = [
        self::STATUS_ENABLED => '已启用',
        self::STATUS_DISABLED => '已禁用',
    ];

    public function scopeEnabled($query){
        return $query->where('status', self::STATUS_ENABLED);
    }
    public function scopeDisabled($query){
        return $query->where('status', self::STATUS_DISABLED);
    }

    public function tube(){
        return $this->belongsTo(Tube::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public static function getChannelByName($name){
        return self::where('name', $name)->enabled()->with('tube')->firstOrFail();
    }
}
