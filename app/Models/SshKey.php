<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(UserScope::class)]
class SshKey extends Model
{
    protected $fillable = [
        'name',
        'private_key',
        'user_id'
    ];
}
