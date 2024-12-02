<?php

namespace App\Jobs;

use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Repositories\ProgramRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class StopProgramServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Program $program
    )
    {
    }

    public function handle(ProgramRepository $programRepo): void
    {
        $program = $programRepo->find($this->program->name);

        if (!$program->hasService())
            return;

        $this->program->update(['status' => ProgramStatus::Stopping]);

        if ($this->program->server->os()->stopService(is_string($program->hasService()) ? $program->hasService() : $program->name()))
            $this->program->update(['status' => ProgramStatus::Stopped]);
        else
            $this->program->update(['status' => ProgramStatus::Active]);
    }
}
