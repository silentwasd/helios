<?php

namespace App\Programs;

use App\Contracts\Program;

class Certbot implements Program
{
    public function name(): string
    {
        return 'certbot';
    }

    public function label(): string
    {
        return 'Certbot';
    }

    public function description(): string
    {
        return 'Certbot automates SSL/TLS certificate management with Lets Encrypt.';
    }

    public function hasService(): string|bool
    {
        return false;
    }

    public static function make(): self
    {
        return new Certbot();
    }
}
