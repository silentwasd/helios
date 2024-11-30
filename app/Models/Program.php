<?php

namespace App\Models;

use App\Enums\ProgramStatus;
use App\Repositories\ProgramRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    protected $fillable = [
        'server_id',
        'name',
        'status'
    ];

    protected $casts = [
        'status' => ProgramStatus::class
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function data(): ?\App\Contracts\Program
    {
        $repo = new ProgramRepository();
        return $repo->find($this->name);
    }
}
