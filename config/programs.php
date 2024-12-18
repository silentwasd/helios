<?php

return [
    'php' => [
        'cli_path' => '/etc/php/8.3/cli',

        'extensions' => [
            'bcmath' => [
                'label'       => 'BC Math',
                'description' => 'Provides precise arithmetic operations for large numbers and decimals.',
                'package'     => 'php8.3-bcmath'
            ],

            'curl' => [
                'label'       => 'CURL',
                'description' => 'Sends HTTP requests and handles headers, cookies, and SSL.',
                'package'     => 'php8.3-curl'
            ],

            'gd' => [
                'label'       => 'GD',
                'description' => 'Fast and lightweight for basic image creation and editing.',
                'package'     => 'php8.3-gd'
            ],

            'imagick' => [
                'label'       => 'Imagick',
                'description' => 'Feature-rich for advanced image processing and formats.',
                'package'     => 'php8.3-imagick'
            ],

            'mbstring' => [
                'label'       => 'Multibyte String',
                'description' => 'Handles multibyte string operations for non-ASCII character encoding.',
                'package'     => 'php8.3-mbstring'
            ],

            'mysql' => [
                'label'       => 'MySQL',
                'description' => 'Provides functions to interact with MySQL databases.',
                'package'     => 'php8.3-mysql'
            ],

            'pgsql' => [
                'label'       => 'PostgreSQL',
                'description' => 'Provides functions to interact with PostgreSQL databases.',
                'package'     => 'php8.3-pgsql'
            ],

            'redis' => [
                'label'       => 'Redis',
                'description' => 'Provides functions to interact with Redis databases.',
                'package'     => 'php8.3-redis'
            ],

            'xml' => [
                'label'       => 'XML',
                'description' => 'Parses and processes XML data.',
                'package'     => 'php8.3-xml'
            ],

            'yaml' => [
                'label'       => 'YAML',
                'description' => 'Parses and generates YAML data.',
                'package'     => 'php8.3-yaml'
            ],

            'zip' => [
                'label'       => 'ZIP',
                'description' => 'Handles ZIP archive creation and extraction.',
                'package'     => 'php8.3-zip'
            ]
        ]
    ]
];