<?php

declare(strict_types=1);

namespace Jandi\Config\Dotenv;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class VlucasDotenvAdapter implements AdapterInterface
{
    private Dotenv $dotenv;

    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function load(): void
    {
        try {
            $this->dotenv->load();
        } catch (InvalidPathException $e) {
        }
    }
}
