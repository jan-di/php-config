<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class FloatEntry extends AbstractEntry
{
    private ?float $lowerLimit = null;
    private ?float $upperLimit = null;

    public function __construct(string $key, ?string $defaultValue = null)
    {
        parent::__construct($key, $defaultValue, 'float');
    }

    public function checkValue(string $value, bool $default = false): float
    {
        if (!is_numeric($value)) {
            throw new InvalidValueException('not a valid float', $this, $value, $default);
        }

        $floatValue = floatval($value);

        if ($this->lowerLimit !== null && $floatValue < $this->lowerLimit) {
            throw new InvalidValueException('too low. Lower limit: '.$this->lowerLimit, $this, $value, $default);
        }
        if ($this->upperLimit !== null && $floatValue > $this->upperLimit) {
            throw new InvalidValueException('too high. Upper limit: '.$this->upperLimit, $this, $value, $default);
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
