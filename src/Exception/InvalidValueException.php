<?php

declare(strict_types=1);

namespace Jandi\Config\Exception;

use Jandi\Config\Entry\AbstractEntry;
use RuntimeException;

class InvalidValueException extends RuntimeException
{
    private AbstractEntry $entry;
    private string $value;

    public function __construct(string $message, AbstractEntry $entry, string $value)
    {
        parent::__construct($message);

        $this->entry = $entry;
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getEntry(): AbstractEntry
    {
        return $this->entry;
    }
}
