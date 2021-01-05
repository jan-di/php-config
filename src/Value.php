<?php

declare(strict_types=1);

namespace Jandi\Config;

class Value
{
    private string $key;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var mixed
     */
    private $defaultValue;
    private bool $userDefined;

    /**
     * @param mixed $value
     * @param mixed $defaultValue
     */
    public function __construct(
        string $key,
        $value,
        $defaultValue,
        bool $userDefined
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->defaultValue = $defaultValue;
        $this->userDefined = $userDefined;
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['key'],
            $properties['value'],
            $properties['defaultValue'],
            $properties['userDefined']
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function isUserDefined(): bool
    {
        return $this->userDefined;
    }
}
