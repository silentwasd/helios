<?php

namespace App\Contracts;

interface OperationSystem
{
    function installProgram(Program $program): bool;

    function uninstallProgram(Program $program): bool;

    function checkProgram(Program $program): bool;
}
