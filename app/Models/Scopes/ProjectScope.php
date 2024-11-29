<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProjectScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check())
            return;

        $builder->whereHas('project', fn(Builder $has) => $has->where('user_id', auth()->id()));
    }
}
