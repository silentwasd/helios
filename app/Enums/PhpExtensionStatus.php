<?php

namespace App\Enums;

enum PhpExtensionStatus: string
{
    case NotInstalled = 'not-installed';
    case Installing = 'installing';
    case Installed = 'installed';
    case Uninstalling = 'uninstalling';
}
