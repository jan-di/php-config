<?php

declare(strict_types=1);

namespace Jandi\Config;

use InvalidArgumentException;
use OutOfBoundsException;

class Config
{
    private array $values = [];
    private bool $cached = false;

    public function __construct(array $values)
    {
        foreach ($values as $value) {
            $this->set($value->getKey(), $value);
        }

        return $this;
    }

    public static function __set_state(array $properties): self
    {
        $config = new Config([]);
        $config->values = $properties['values'];
        $config->cached = true;

        return $config;
    }

    /**
     * @return mixed
     */
    public function getValue(string $key)
    {
        if (!isset($this->values[$key])) {
            throw new OutOfBoundsException('There is no value with key "'.$key.'"');
        }

        return $this->values[$key]->getValue();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(string $key)
    {
        if (!isset($this->values[$key])) {
            throw new OutOfBoundsException('There is no value with key "'.$key.'"');
        }

        return $this->values[$key]->getDefaultValue();
    }

    public function isUserDefined(string $key): bool
    {
        if (!isset($this->values[$key])) {
            throw new OutOfBoundsException('There is no value with key "'.$key.'"');
        }

        return $this->values[$key]->isUserDefined();
    }

    public function has(string $key): bool
    {
        return isset($this->values[$key]);
    }

    public function exportValues(string $prefix = ''): array
    {
        $result = [];

        foreach ($this->values as $value) {
            $result[$prefix.$value->getKey()] = $value->getValue();
        }

        return $result;
    }

    public function exportDefaultValues(string $prefix = ''): array
    {
        $result = [];

        foreach ($this->values as $value) {
            $result[$prefix.$value->getKey()] = $value->getDefaultValue();
        }

        return $result;
    }

    /**
     * @param mixed $value
     */
    private function set(string $key, Value $value): void
    {
        if (isset($this->values[$key])) {
            throw new InvalidArgumentException('Duplicate value for key "'.$key.'"');
        }

        $this->values[$key] = $value;
    }

    public function isCached(): bool
    {
        return $this->cached;
    }
}
