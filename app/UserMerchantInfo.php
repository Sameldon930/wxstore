<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMerchantInfo extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = ['user_id', 'wechat_merchant_no', 'ali_merchant_no', 'ali_auth_token',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
