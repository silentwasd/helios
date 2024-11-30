<?php

namespace App\Programs;

use App\Contracts\Program;

class Supervisor implements Program
{
    public function name(): string
    {
        return 'supervisor';
    }

    public function label(): string
    {
        return 'Supervisor';
    }

    public function description(): string
    {
        return 'Manages and monitors background processes, ensuring reliability and auto-restarts.';
    }

    public static function make(): self
    {
        return new Supervisor();
    }
}
