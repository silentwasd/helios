<?php

namespace App\Console\Commands;

use App\Enums\ServerStatus;
use App\Models\Server;
use Illuminate\Console\Command;

class UpdateServerStatusCommand extends Command
{
    protected $signature = 'update:server-status';

    protected $description = 'Check and update server statuses';

    public function handle(): void
    {
        Server::all()->each(function (Server $server) {
            $this->info(sprintf('Checking %s...', $server->name));

            $result = $server->check();

            if ($result === true) {
                $server->status = ServerStatus::Online;
                $server->save();

                $this->info(sprintf('Server %s is online', $server->name));
            } else {
                $server->status = ServerStatus::Offline;
                $server->save();

                $this->error(sprintf('Server %s is offline', $server->name));
            }
        });
    }
}
