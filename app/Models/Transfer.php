<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $connection = 'mobile';
    protected $table = 'transfers';
    protected $guarded = [];

    protected $casts = [
        'sender' => 'array',
        'receiver' => 'array',
        'info' => 'array',
        'fields' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(ExternalUser::class, 'user_id');
    }
}
