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

class UninstallProgramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Program $program
    )
    {
    }

    public function handle(): void
    {
        $program = $this->program->data();

        $this->program->update(['status' => ProgramStatus::Uninstalling]);

        if ($this->program->server->os()->uninstallProgram($program))
            $this->program->update(['status' => ProgramStatus::Uninstalled]);
        else
            $this->program->update(['status' => ProgramStatus::Installed]);
    }
}
