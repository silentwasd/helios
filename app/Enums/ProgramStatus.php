<?php

namespace App\Enums;

enum ProgramStatus: string
{
    case NotInstalled = 'not-installed';
    case Initializing = 'initializing';
    case Installing = 'installing';
    case Installed = 'installed';
    case Uninstalling = 'uninstalling';
    case Uninstalled = 'uninstalled';
    case Running = 'running';
    case Restarting = 'restarting';
    case Stopped = 'stopped';
}
