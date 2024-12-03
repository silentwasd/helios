<?php

namespace App\Console\Commands;

use App\Enums\ProgramStatus;
use App\Enums\ServerStatus;
use App\Models\Program;
use App\Models\Server;
use Illuminate\Console\Command;

class UpdateServiceStatusCommand extends Command
{
    protected $signature = 'update:service-status';

    protected $description = 'Check and update service statuses';

    public function handle(): void
    {
        Server::where('status', ServerStatus::Online)
              ->get()
              ->each(function (Server $server) {
                  $this->info(sprintf('Server %s', $server->name));

                  $server->programs()->whereIn('status', [ProgramStatus::Installed, ProgramStatus::Active, ProgramStatus::Stopped])
                         ->get()
                         ->each(function (Program $program) use ($server) {
                             if ($program->data()->hasService()) {
                                 $status = $server->os()->checkServiceStatus(
                                     is_string($program->data()->hasService())
                                         ? $program->data()->hasService()
                                         : $program->data()->name()
                                 );

                                 $program->status = $status ? ProgramStatus::Active : ProgramStatus::Stopped;
                                 $program->save();

                                 if ($status)
                                     $this->info(sprintf('Program %s is active', $program->name));
                                 else
                                     $this->info(sprintf('Program %s is stopped', $program->name));
                             }
                         });
              });
    }
}
