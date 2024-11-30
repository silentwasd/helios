<?php

namespace App\Console\Commands;

use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Models\Server;
use Illuminate\Console\Command;

class UpdateProgramStatusCommand extends Command
{
    protected $signature = 'update:program-status';

    protected $description = 'Check and update program statuses';

    public function handle(): void
    {
        Server::all()->each(function (Server $server) {
            $this->info(sprintf('Server %s', $server->name));

            $server->programs->each(function (Program $program) use ($server) {
                $this->info(sprintf('> Checking %s...', $program->name));

                $result = $server->os()->checkProgram($program->data());

                if ($result === true) {
                    $program->status = ProgramStatus::Installed;
                    $program->save();

                    $this->info(sprintf('Program %s is installed', $program->name));
                } else {
                    $program->status = ProgramStatus::Uninstalled;
                    $program->save();

                    $this->error(sprintf('Program %s is uninstalled', $program->name));
                }
            });
        });
    }
}
