<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalUser extends Model
{
    protected $connection = 'mobile';

    protected $table = 'users';

    public $timestamps = true;

    protected $guarded = [];

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'user_id');
    }
}
