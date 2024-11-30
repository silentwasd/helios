<?php

namespace App\Contracts;

interface Program
{
    function name(): string;

    function label(): string;

    function description(): string;

    static function make(): Program;
}
