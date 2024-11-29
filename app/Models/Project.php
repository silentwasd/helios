<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(UserScope::class)]
class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name'
    ];
}
