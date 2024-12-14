<?php

namespace App\Repositories;

use App\Contracts\Program;
use App\Programs\Certbot;
use App\Programs\MySql;
use App\Programs\Nginx;
use App\Programs\Php;
use App\Programs\PhpFpm;
use App\Programs\Redis;
use App\Programs\Supervisor;
use Illuminate\Support\Collection;

class ProgramRepository
{
    protected array $programs = [
        Nginx::class,
        Php::class,
        PhpFpm::class,
        MySql::class,
        Redis::class,
        Supervisor::class,
        Certbot::class
    ];

    public function all(): Collection
    {
        return collect($this->programs)
            ->map(fn($program) => $program::make());
    }

    public function find(string $name): ?Program
    {
        foreach ($this->programs as $program) {
            $program = $program::make();

            if ($program->name() == $name)
                return $program;
        }

        return null;
    }
}
