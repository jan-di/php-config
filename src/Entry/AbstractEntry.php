<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use InvalidArgumentException;

abstract class AbstractEntry
{
    private string $key;
    private ?string $defaultValue;

    public function __construct(string $key, ?string $defaultValue = null)
    {
        $this->setKey($key);
        $this->setDefaultValue($defaultValue);
    }

    final public function getKey(): string
    {
        return $this->key;
    }

    private function setKey(string $key): void
    {
        if (strpos($key, '=') !== false) {
            throw new InvalidArgumentException("Key must not contain '='");
        }

        $this->key = $key;
    }

    final public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    final public function setDefaultValue(?string $value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function checkValue(string $value);
}
