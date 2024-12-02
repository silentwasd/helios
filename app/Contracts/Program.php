<?php

namespace App\Contracts;

interface Program
{
    function name(): string;

    function label(): string;

    function description(): string;

    function hasService(): string|bool;

    static function make(): Program;
}
