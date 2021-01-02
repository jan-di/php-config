<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use InvalidArgumentException;
use Jandi\Config\Exception\InvalidValueException;

class StringEntry extends AbstractEntry {
    private ?string $defaultValue = null;
    private ?int $minLength = null;
    private ?int $maxLength = null;

    public function __construct(string $key, ?string $defaultValue = null)
    {
        parent::__construct($key);

        $this->setDefaultValue($defaultValue);
    }

    public function checkValue(string $value): string
    {
        if ($this->minLength !== null && strlen($value) < $this->minLength) {
            throw new InvalidValueException("Value too short");
        }
        elseif ($this->maxLength !== null && strlen($value) > $this->maxLength) {
            throw new InvalidValueException("Value too long");
        }

        return $value;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): self {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getMinLength(): ?int {
        return $this->minLength;
    }

    public function setMinLength(?int $length): self {
        if ($this->maxLength !== null && $length !== null && $length > $this->maxLength) {
            throw new InvalidArgumentException('MinLength cannot be greater than MaxLength');
        }

        $this->minLength = $length;

        return $this;
    }

    public function getMaxLength(): ?int {
        return $this->maxLength;
    }

    public function setMaxLength(?int $length): self {
        if ($this->minLength !== null && $length !== null && $length < $this->minLength) {
            throw new InvalidArgumentException('MaxLength cannot be lower than MinLength');
        }

        $this->maxLength = $length;

        return $this;
    }
}