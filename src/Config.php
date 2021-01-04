<?php

declare(strict_types=1);

namespace Jandi\Config;

use OutOfBoundsException;

class Config
{
    private array $values = [];

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    public function export(): array
    {
        return $this->values;
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        if (!isset($this->values[$key])) {
            throw new OutOfBoundsException('There is no value with key "'.$key.'"');
        }

        return $this->values[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->values[$key]);
    }

    /**
     * @param mixed $value
     */
    private function set(string $key, $value): void
    {
        $this->values[$key] = $value;
    }
}
