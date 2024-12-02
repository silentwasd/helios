<?php

namespace App\Programs;

use App\Contracts\Program;

class MySql implements Program
{
    public function name(): string
    {
        return 'mysql-server';
    }

    public function label(): string
    {
        return 'MySQL Server';
    }

    public function description(): string
    {
        return 'Relational database system for managing and storing structured data.';
    }

    public function hasService(): string|bool
    {
        return 'mysql';
    }

    public static function make(): self
    {
        return new MySql();
    }
}
