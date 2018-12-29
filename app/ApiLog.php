<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use Searchable;

    protected $fillable = [
        'user_id', 'note', 'request', 'response',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
