<?php

namespace App\Programs;

use App\Contracts\Program;

class Php implements Program
{
    public function name(): string
    {
        return 'php';
    }

    public function label(): string
    {
        return 'PHP';
    }

    public function description(): string
    {
        return 'Server-side scripting language for dynamic web development.';
    }

    public function hasService(): string|bool
    {
        return false;
    }

    public static function make(): self
    {
        return new Php();
    }
}
