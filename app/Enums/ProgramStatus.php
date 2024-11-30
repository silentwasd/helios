<?php

namespace App\Enums;

enum ProgramStatus: string
{
    case NotInstalled = 'not-installed';
    case Installing = 'installing';
    case Installed = 'installed';
    case Uninstalling = 'uninstalling';
    case Uninstalled = 'uninstalled';
    case Running = 'running';
    case Active = 'active';
    case Restarting = 'restarting';
    case Stopping = 'stopping';
    case Stopped = 'stopped';
}
