<?php

namespace Jandi\Config\Test\Unit\Entry;

use Jandi\Config\Entry\BoolEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\BoolEntry
 *
 * @uses \Jandi\Config\Entry\AbstractEntry
 */
final class BoolEntryTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testValidValues(string $value, bool $expected): void
    {
        $entry = new BoolEntry('KEY');
        $checkedValue = $entry->checkValue($value);

        $this->assertSame($expected, $checkedValue);
    }

    public function validValueProvider(): array
    {
        return [
            ['true', true],
            ['true', true],
            ['false', false],
            ['1', true],
            ['0', false],
            ['on', true],
            ['off', false],
            ['yes', true],
            ['no', false],
        ];
    }

    public function testInvalidValue(): void
    {
        $entry = new BoolEntry('KEY');

        $this->expectException(InvalidValueException::class);

        $entry->checkValue('nope');
    }
}
