<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class FloatEntry extends AbstractEntry
{
    private ?float $lowerLimit = null;
    private ?float $upperLimit = null;

    public function checkValue(string $value): float
    {
        if (!is_numeric($value)) {
            throw new InvalidValueException('Value is not a valid float');
        }

        $floatValue = floatval($value);

        if ($this->lowerLimit !== null && $floatValue < $this->lowerLimit) {
            throw new InvalidValueException('Value is too low');
        }
        if ($this->upperLimit !== null && $floatValue > $this->upperLimit) {
            throw new InvalidValueException('Value is too high');
        }

        return $floatValue;
    }

    public function getLowerLimit(): ?float
    {
        return $this->lowerLimit;
    }

    public function setLowerLimit(?float $limit): self
    {
        $this->lowerLimit = $limit;

        return $this;
    }

    public function getUpperLimit(): ?float
    {
        return $this->upperLimit;
    }

    public function setUpperLimit(?float $limit): self
    {
        $this->upperLimit = $limit;

        return $this;
    }
}
