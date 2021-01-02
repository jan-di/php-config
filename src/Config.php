<?php

declare(strict_types=1);

namespace Jandi\Config;

use Jandi\Config\Exception\KeyNotFoundException;

class Config {
    private array $values = [];

    public function __construct(array $values) {
        foreach($values as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    public function export(): array
    {
        return $this->values;
    }

    public function get(string $key)
    {
        if (!isset($this->values[$key])) {
            throw new KeyNotFoundException('There is no value with key "'.$key.'"');
        }

        return $this->values[$key];
    }

    public function has(string $key)
    {
        return isset($this->values[$key]);
    }

    private function set(string $key, $value)
    {
        $this->values[$key] = $value;
    }
}