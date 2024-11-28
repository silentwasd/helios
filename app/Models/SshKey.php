<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SshKey extends Model
{
    protected $fillable = [
        'name',
        'private_key'
    ];
}
