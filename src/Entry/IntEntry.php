<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class IntEntry extends AbstractEntry
{
    private ?int $lowerLimit = null;
    private ?int $upperLimit = null;

    public function checkValue(string $value): int
    {
        if (!is_numeric($value) || floatval(intval($value)) !== floatval($value)) {
            throw new InvalidValueException('Value is not a valid integer', $this, $value);
        }

        $intValue = intval($value);

        if ($this->lowerLimit !== null && $intValue < $this->lowerLimit) {
            throw new InvalidValueException('Value is too low. Lower limit: '.$this->lowerLimit, $this, $value);
        }
        if ($this->upperLimit !== null && $intValue > $this->upperLimit) {
            throw new InvalidValueException('Value is too high. Upper limit: '.$this->upperLimit, $this, $value);
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
