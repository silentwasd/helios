<?php

namespace App\Models;

use App\Enums\PhpExtensionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhpExtension extends Model
{
    protected $fillable = [
        'program_id',
        'name',
        'status'
    ];

    protected $casts = [
        'status' => PhpExtensionStatus::class
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function transform(): array
    {
        $data = config('programs.php.extensions');

        return [
            'name'   => $this->name,
            'status' => $this->status,
            ...$data[$this->name]
        ];
    }
}
