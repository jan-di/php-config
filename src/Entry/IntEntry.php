<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class IntEntry extends AbstractEntry
{
    private ?int $lowerLimit = null;
    private ?int $upperLimit = null;

    public function __construct(string $key, ?string $defaultValue = null)
    {
        parent::__construct($key, $defaultValue, 'int');
    }

    public function checkValue(string $value, bool $default = false): int
    {
        if (!is_numeric($value) || floatval(intval($value)) !== floatval($value)) {
            throw new InvalidValueException('not a valid integer', $this, $value, $default);
        }

        $intValue = intval($value);

        if ($this->lowerLimit !== null && $intValue < $this->lowerLimit) {
            throw new InvalidValueException('too low. Lower limit: '.$this->lowerLimit, $this, $value, $default);
        }
        if ($this->upperLimit !== null && $intValue > $this->upperLimit) {
            throw new InvalidValueException('too high. Upper limit: '.$this->upperLimit, $this, $value, $default);
        }

        return $intValue;
    }

    public function getLowerLimit(): ?int
    {
        return $this->lowerLimit;
    }

    public function setLowerLimit(?int $limit): self
    {
        $this->lowerLimit = $limit;

        return $this;
    }

    public function getUpperLimit(): ?int
    {
        return $this->upperLimit;
    }

    public function setUpperLimit(?int $limit): self
    {
        $this->upperLimit = $limit;

        return $this;
    }
}
