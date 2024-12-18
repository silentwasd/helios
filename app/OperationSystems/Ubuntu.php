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

    public function installPackage(string|array $name): bool
    {
        $name = is_string($name) ? [$name] : $name;

        return $this->server->executeSsh([
            "apt update",
            ...array_map(fn(string $_name) => "apt install -y $_name", $name)
        ])->isSuccessful();
    }

    public function uninstallPackage(string|array $name): bool
    {
        $name = is_string($name) ? [$name] : $name;

        $process = $this->server->executeSsh([
            ...array_map(fn(string $_name) => "apt purge $_name -y", $name),
            "apt autoremove --purge -y"
        ]);

        return $process->isSuccessful();
    }

    function installProgram(Program $program): bool
    {
        return $this->installPackage($program->name());
    }

    function uninstallProgram(Program $program): bool
    {
        return $this->uninstallPackage([$program->name(), $program->name() . '*']);
    }

    function checkProgram(Program $program): bool
    {
        return $this->server->executeSsh([
                "dpkg -l | grep {$program->name()}"
            ])->isSuccessful() || $this->server->executeSsh([
                "command -v {$program->name()}"
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
