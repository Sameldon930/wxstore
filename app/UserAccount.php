<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use Searchable;
    protected $fillable = ['user_id', 'tube_id', 'balance', 'status', 'change',];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tube(){
        return $this->belongsTo(Tube::class);
    }

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

    public static function changeSettle(){

    }

    public static function getUserAccount($user_id, $tube_id){
        return self::enabled()->where('user_id', $user_id)->where('tube_id', $tube_id)->firstOrFail();
    }
    public static function getUserAccounts($user_id){
        return self::enabled()->where('user_id', $user_id)->firstOrFail();
    }

    public function getBalanceAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getChangeAttribute($value){
        return app()->resolved('blade.compiler') ? HaoToYuan($value) : $value;
    }

}
