<?php

namespace App\Models;

use App\Enums\ApplicationType;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
