<?php

declare(strict_types=1);

namespace Jandi\Config\Exception;

use Jandi\Config\Entry\AbstractEntry;
use RuntimeException;

class InvalidValueException extends RuntimeException
{
    private AbstractEntry $entry;
    private string $value;
    private bool $default;

    public function __construct(string $reason, AbstractEntry $entry, string $value, bool $default)
    {
        $prefix = $default ? 'Default value' : 'Value';
        parent::__construct($prefix.' is invalid: '.$reason.'.');

        $this->entry = $entry;
        $this->value = $value;
        $this->default = $default;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getEntry(): AbstractEntry
    {
        return $this->entry;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }
}
