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

    private function setKey(string $key): void {
        if (strpos($key, '=') !== false) {
            throw new InvalidArgumentException("Key must not contain '='");
        }

        $this->key = $key;
    }

    /**
     * @return mixed 
     */
    public abstract function checkValue(string $value);

    /**
     * @return mixed 
     */
    public abstract function getDefaultValue();
}