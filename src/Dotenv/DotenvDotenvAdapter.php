<?php

declare(strict_types=1);

namespace Jandi\Config\Dotenv;

use Dotenv\Dotenv;

class DotenvDotenvAdapter implements AdapterInterface {
    private Dotenv $dotenv;

    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function load() { 
        $this->dotenv->load();
    }
}