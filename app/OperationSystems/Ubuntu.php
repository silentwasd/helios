<?php

namespace App\OperationSystems;

use App\Contracts\OperationSystem;
use App\Contracts\Program;
use App\Models\Server;
use Illuminate\Support\Facades\Log;

class Ubuntu implements OperationSystem
{
    public function __construct(
        protected Server $server
    )
    {
    }

    function installProgram(Program $program): bool
    {
        return $this->server->executeSsh([
            "apt update",
            "apt install -y {$program->name()}"
        ])->isSuccessful();
    }

    function uninstallProgram(Program $program): bool
    {
        $process = $this->server->executeSsh([
            "apt purge {$program->name()} -y",
            "apt purge {$program->name()}* -y",
            "apt autoremove --purge -y"
        ]);

        //Log::info($process->getOutput());

        return $process->isSuccessful();
    }

    function checkProgram(Program $program): bool
    {
        return $this->server->executeSsh([
            "dpkg -l | grep {$program->name()}"
        ])->isSuccessful();
    }

    function startService(string $name): bool
    {
        return $this->server->executeSsh([
            "systemctl start $name"
        ])->isSuccessful();
    }

    function stopService(string $name): bool
    {
        return $this->server->executeSsh([
            "systemctl stop $name"
        ])->isSuccessful();
    }

    function restartService(string $name): bool
    {
        return $this->server->executeSsh([
            "systemctl restart $name"
        ])->isSuccessful();
    }

    function checkServiceStatus(string $name): bool
    {
        $process = $this->server->executeSsh([
            "systemctl is-active --quiet $name && echo 'active' || echo 'inactive'"
        ]);

        if (!$process->isSuccessful())
            return $process->isSuccessful();

        return trim($process->getOutput()) == 'active';
    }

    public function makeHardLink(string $source, string $destination): bool
    {
        $process = $this->server->executeSsh([
            "ln $source $destination"
        ]);

        return $process->isSuccessful();
    }
}
