<?php

declare(strict_types=1);

namespace Jandi\Config\Dotenv;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

class SymfonyDotenvAdapter implements AdapterInterface
{
    private Dotenv $dotenv;
    private string $file;

    public function __construct(Dotenv $dotenv, string $file)
    {
        $this->dotenv = $dotenv;
        $this->file = $file;
    }

    public function load(): void
    {
        try {
            $this->dotenv->load($this->file);
        } catch (PathException $e) {
        }
    }
}
