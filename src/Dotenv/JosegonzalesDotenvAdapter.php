<?php

declare(strict_types=1);

namespace Jandi\Config\Dotenv;

use josegonzalez\Dotenv\Loader;

class JosegonzalesDotenvAdapter implements AdapterInterface {
    private Loader $dotenv;

    public function __construct(Loader $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function load() { 
        $this->dotenv->parse()->toServer();
    }
}