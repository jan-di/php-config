<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class StringEntry extends AbstractEntry
{
    private ?int $minLength = null;
    private ?int $maxLength = null;
    private array $allowedValues = [];
    private ?string $regexPattern = null;

    public function checkValue(string $value): string
    {
        if ($this->minLength !== null && strlen($value) < $this->minLength) {
            throw new InvalidValueException('Value is too short. Minimum length: '.$this->minLength, $this, $value);
        }
        if ($this->maxLength !== null && strlen($value) > $this->maxLength) {
            throw new InvalidValueException('Value is too long. Maximum length: '.$this->maxLength, $this, $value);
        }
        if (count($this->allowedValues) > 0 && !in_array($value, $this->allowedValues)) {
            throw new InvalidValueException('Value is not allowed. Allowed values: '.implode(', ', $this->allowedValues), $this, $value);
        }
        if ($this->regexPattern !== null && preg_match($this->regexPattern, $value) !== 1) {
            throw new InvalidValueException('Value does not match regex pattern. Pattern: '.$this->regexPattern, $this, $value);
        }

        return $value;
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function setMinLength(?int $length): self
    {
        $this->minLength = $length;

        return $this;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function setMaxLength(?int $length): self
    {
        $this->maxLength = $length;

        return $this;
    }

    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }

    public function setAllowedValues(array $values): self
    {
        $this->allowedValues = $values;

        return $this;
    }

    public function getRegexPattern(): ?string
    {
        return $this->regexPattern;
    }

    public function setRegexPattern(?string $pattern): self
    {
        $this->regexPattern = $pattern;

        return $this;
    }
}
