<?php

namespace Jandi\Config\Test\Unit\Entry;

use Jandi\Config\Entry\FloatEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\FloatEntry
 *
 * @uses \Jandi\Config\Entry\AbstractEntry
 */
final class FloatEntryTest extends TestCase
{
    /**
     * @dataProvider floatValidValueProvider
     */
    public function testFloatValidValue(string $inputValue, float $expectedValue): void
    {
        $entry = new FloatEntry('KEY');

        $this->assertSame($expectedValue, $entry->checkValue($inputValue));
    }

    public function floatValidValueProvider(): array
    {
        return [
            ["3", 3.0],
            ["3.4", 3.4],
            ["-5.6", -5.6],
            ["0", 0],
        ];
    }

    /**
     * @dataProvider floatInvalidValueProvider
     */
    public function testFloatInvalidValue(string $value): void
    {
        $entry = new FloatEntry('KEY');

        $this->expectException(InvalidValueException::class);

        $entry->checkValue($value);
    }

    public function floatInvalidValueProvider(): array
    {
        return [
            ["ABC"],
            [""],
        ];
    }

    public function testLowerLimitMethods(): void
    {
        $entry = (new FloatEntry('KEY'))->setLowerLimit(7.9);

        $this->assertSame(7.9, $entry->getLowerLimit());
    }

    /**
     * @dataProvider lowerLimitValidValueProvider
     */
    public function testLowerLimitValidValue(?float $lowerLimit): void
    {
        $entry = (new FloatEntry('KEY'))->setLowerLimit($lowerLimit);

        $this->assertSame(11.111, $entry->checkValue("11.111"));
    }

    
    public function lowerLimitValidValueProvider(): array
    {
        return [
            [null],
            [10.0],
        ];
    }

    public function testLowerLimitInvalidValue(): void
    {
        $entry = (new FloatEntry('KEY'))->setLowerLimit(5.4);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue(5.3);
    }

    public function testUpperLimitMethods(): void
    {
        $entry = (new FloatEntry('KEY'))->setUpperLimit(9.7);

        $this->assertSame(9.7, $entry->getUpperLimit());
    }

    /**
     * @dataProvider upperLimitValidValueProvider
     */
    public function testUpperLimitValidValue(?float $upperLimit): void
    {
        $entry = (new FloatEntry('KEY'))->setUpperLimit($upperLimit);

        $this->assertSame(7.5, $entry->checkValue("7.5"));
    }

    
    public function upperLimitValidValueProvider(): array
    {
        return [
            [null],
            [7.6],
        ];
    }

    public function testUpperLimitInvalidValue(): void
    {
        $entry = (new FloatEntry('KEY'))->setUpperLimit(5.5);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue(5.6);
    }
}
