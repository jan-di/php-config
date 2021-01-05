<?php

declare(strict_types=1);

namespace Jandi\Config\Entry;

use Jandi\Config\Exception\InvalidValueException;

class BoolEntry extends AbstractEntry
{
    private static array $valueTable = [
        'true' => true,
        'false' => false,
        '1' => true,
        '0' => false,
        'on' => true,
        'off' => false,
        'yes' => true,
        'no' => false,
    ];

    public function __construct(string $key, ?string $defaultValue = null)
    {
        parent::__construct($key, $defaultValue, 'bool');
    }

    public function checkValue(string $value, bool $default = false): bool
    {
        $key = strtolower($value);
        if (!isset(self::$valueTable[$key])) {
            throw new InvalidValueException('cannot convert to a boolean', $this, $value, $default);
        }

        return self::$valueTable[$key];
    }
}
