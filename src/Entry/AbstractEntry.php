<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use InvalidArgumentException;

abstract class AbstractEntry
{
    private string $key;
    private ?string $defaultValue;
    private string $friendlyType;

    protected function __construct(string $key, ?string $defaultValue, string $friendlyType)
    {
        $this->setKey($key);
        $this->setDefaultValue($defaultValue);
        $this->friendlyType = $friendlyType;
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

    final public function getFriendlyType(): string
    {
        return $this->friendlyType;
    }

    /**
     * @return mixed
     */
    abstract public function checkValue(string $value, bool $default = false);
}
