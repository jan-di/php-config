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

    public function __construct(string $key, ?string $defaultValue = null)
    {
        parent::__construct($key, $defaultValue, 'string');
    }

    public function checkValue(string $value, bool $default = false): string
    {
        if ($this->minLength !== null && strlen($value) < $this->minLength) {
            throw new InvalidValueException('too short. Minimum length: '.$this->minLength, $this, $value, $default);
        }
        if ($this->maxLength !== null && strlen($value) > $this->maxLength) {
            throw new InvalidValueException('too long. Maximum length: '.$this->maxLength, $this, $value, $default);
        }
        if (count($this->allowedValues) > 0 && !in_array($value, $this->allowedValues)) {
            throw new InvalidValueException('not allowed. Allowed values: '.implode(', ', $this->allowedValues), $this, $value, $default);
        }
        if ($this->regexPattern !== null && preg_match($this->regexPattern, $value) !== 1) {
            throw new InvalidValueException('doesn\'t match regex pattern. Pattern: '.$this->regexPattern, $this, $value, $default);
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
