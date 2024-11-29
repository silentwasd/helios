<?php

namespace App\Enums;

enum ServerStatus: string
{
    case Offline = 'offline';
    case Online = 'online';
}
