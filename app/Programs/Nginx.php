<?php

namespace App\Programs;

use App\Contracts\Program;

class Nginx implements Program
{
    public function name(): string
    {
        return 'nginx';
    }

    public function label(): string
    {
        return 'Nginx';
    }

    public function description(): string
    {
        return 'High-performance webserver.';
    }

    public static function make(): self
    {
        return new Nginx();
    }
}
