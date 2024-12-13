<?php

namespace App\Contracts;

interface OperationSystem
{
    function installProgram(Program $program): bool;

    function uninstallProgram(Program $program): bool;

    function checkProgram(Program $program): bool;

    function startService(string $name): bool;

    function stopService(string $name): bool;

    function restartService(string $name): bool;

    function checkServiceStatus(string $name): bool;

    function makeHardLink(string $source, string $destination): bool;
}
