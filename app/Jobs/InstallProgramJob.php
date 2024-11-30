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

class InstallProgramJob implements ShouldQueue
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

        $this->program->update(['status' => ProgramStatus::Installing]);

        if ($this->program->server->os()->installProgram($program))
            $this->program->update(['status' => ProgramStatus::Installed]);
        else
            $this->program->update(['status' => ProgramStatus::NotInstalled]);

        Artisan::call('update:program-status');
    }
}
