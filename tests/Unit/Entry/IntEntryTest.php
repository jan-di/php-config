<?php

namespace Jandi\Config\Test\Unit\Entry;

use Jandi\Config\Entry\IntEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\IntEntry
 *
 * @uses \Jandi\Config\Entry\AbstractEntry
 */
final class IntEntryTest extends TestCase
{
    /**
     * @dataProvider integerValidValueProvider
     */
    public function testIntegerValidValue(string $inputValue, int $expectedValue): void
    {
        $entry = new IntEntry('KEY');

        $this->assertSame($expectedValue, $entry->checkValue($inputValue));
    }

    public function integerValidValueProvider(): array
    {
        return [
            ["3", 3],
            ["-5", -5],
            ["0", 0],
        ];
    }

    /**
     * @dataProvider integerInvalidValueProvider
     */
    public function testIntegerInvalidValue(string $value): void
    {
        $entry = new IntEntry('KEY');

        $this->expectException(InvalidValueException::class);

        $entry->checkValue($value);
    }

    public function integerInvalidValueProvider(): array
    {
        return [
            ["5.5"],
            ["ABC"],
            [""],
        ];
    }

    public function testLowerLimitMethods(): void
    {
        $entry = (new IntEntry('KEY'))->setLowerLimit(7);

        $this->assertSame(7, $entry->getLowerLimit());
    }

    /**
     * @dataProvider lowerLimitValidValueProvider
     */
    public function testLowerLimitValidValue(?int $lowerLimit): void
    {
        $entry = (new IntEntry('KEY'))->setLowerLimit($lowerLimit);

        $this->assertSame(11, $entry->checkValue("11"));
    }

    
    public function lowerLimitValidValueProvider(): array
    {
        return [
            [null],
            [10],
        ];
    }

    public function testLowerLimitInvalidValue(): void
    {
        $entry = (new IntEntry('KEY'))->setLowerLimit(5);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue(3);
    }

    public function testUpperLimitMethods(): void
    {
        $entry = (new IntEntry('KEY'))->setUpperLimit(9);

        $this->assertSame(9, $entry->getUpperLimit());
    }

    /**
     * @dataProvider upperLimitValidValueProvider
     */
    public function testUpperLimitValidValue(?int $upperLimit): void
    {
        $entry = (new IntEntry('KEY'))->setUpperLimit($upperLimit);

        $this->assertSame(7, $entry->checkValue("7"));
    }

    
    public function upperLimitValidValueProvider(): array
    {
        return [
            [null],
            [8],
        ];
    }

    public function testUpperLimitInvalidValue(): void
    {
        $entry = (new IntEntry('KEY'))->setUpperLimit(5);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue(9);
    }
}
