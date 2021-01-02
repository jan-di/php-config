<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use InvalidArgumentException;

abstract class AbstractEntry {
    private string $key;

    protected function __construct(string $key)
    {
        $this->setKey($key);
    }

    public function getKey(): string {
        return $this->key;
    }

    private function setKey(string $key) {
        if (strpos($key, '=') !== false) {
            throw new InvalidArgumentException("Key must not contain '='");
        }

        $this->key = $key;
    }

    public abstract function checkValue(string $value);
    public abstract function getDefaultValue();
}