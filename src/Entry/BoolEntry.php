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

    public function checkValue(string $value): bool
    {
        $key = strtolower($value);
        if (!isset(self::$valueTable[$key])) {
            throw new InvalidValueException('Specified Value cannot be converted to boolean');
        }

        return self::$valueTable[$key];
    }
}
