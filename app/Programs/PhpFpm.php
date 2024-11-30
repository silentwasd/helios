<?php

namespace App\Programs;

use App\Contracts\Program;

class PhpFpm implements Program
{
    public function name(): string
    {
        return 'php-fpm';
    }

    public function label(): string
    {
        return 'PHP FPM';
    }

    public function description(): string
    {
        return 'Advanced PHP handler designed for high-performance and scalable web applications.';
    }

    public static function make(): self
    {
        return new PhpFpm();
    }
}
