<?php

declare(strict_types=1);

namespace Jandi\Config\Dotenv;

use InvalidArgumentException;
use josegonzalez\Dotenv\Loader;

class JosegonzalesDotenvAdapter implements AdapterInterface
{
    private Loader $dotenv;

    public function __construct(Loader $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function load(): void
    {
        try {
            $this->dotenv->parse()->skipExisting()->toServer();
        } catch (InvalidArgumentException $e) {
        }
    }
}
