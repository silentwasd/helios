<?php

namespace App\Models;

use App\Enums\ApplicationType;
use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//#[ScopedBy(ProjectScope::class)]
class Application extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'application_type',
        'config'
    ];

    protected $casts = [
        'application_type' => ApplicationType::class,
        'config'           => AsCollection::class
    ];

    protected $attributes = [
        'config' => '{}'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
