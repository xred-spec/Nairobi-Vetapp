<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Token extends Model
{
    protected $table = 'tokens_users';
    protected $fillable = [
        'user_id',
        'token',
        'type',
        'expired_at',
        'state',
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
