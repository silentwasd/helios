<?php

namespace App\Programs;

use App\Contracts\Program;

class Redis implements Program
{
    public function name(): string
    {
        return 'redis';
    }

    public function label(): string
    {
        return 'Redis';
    }

    public function description(): string
    {
        return 'In-memory data store used as a database, cache, and message broker.';
    }

    public function hasService(): string|bool
    {
        return true;
    }

    public static function make(): self
    {
        return new Redis();
    }
}
