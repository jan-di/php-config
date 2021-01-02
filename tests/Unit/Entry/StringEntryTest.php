<?php

namespace Jandi\Config\Test\Unit\Entry;

use InvalidArgumentException;
use Jandi\Config\Entry\StringEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\StringEntry
 * @uses \Jandi\Config\Entry\AbstractEntry
 */
final class StringEntryTest extends TestCase
{
    /**
     * @dataProvider validLengthProvider
     */
    public function testValidMaxLength(?int $maxLength): void
    {
        $entry = (new StringEntry('test'))->setMaxLength($maxLength);
        $checkedValue = $entry->checkValue('value');

        $this->assertSame('value', $checkedValue);
    }

    public function validLengthProvider(): array
    {
        return [
            [null],
            [5],
        ];
    }

    /**
     * @dataProvider invalidLengthProvider
     */
    public function testInvalidLengthValue(?int $minLength, ?int $maxLength): void
    {
        $entry = (new StringEntry('test'))->setMinLength($minLength)->setMaxLength($maxLength);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue('value');
    }

    public function invalidLengthProvider(): array
    {
        return [
            [10, null], // value too short
            [null, 3], // value too long
        ];
    }

    public function testDefaultValueMethods(): void
    {
        $entry = new StringEntry("test1");

        $entry->setDefaultValue('defaultValue1');
        $this->assertSame('defaultValue1', $entry->getDefaultValue());
    }

    public function testMinLengthMethods(): void
    {
        $entry = new StringEntry("test1");

        $entry->setMinLength(40);
        $this->assertSame(40, $entry->getMinLength());
    }

    public function testMaxLengthMethods(): void
    {
        $entry = new StringEntry("test1");

        $entry->setMaxLength(50);
        $this->assertSame(50, $entry->getMaxLength());
    }

    public function testMinLengthGreaterThanMaxLength(): void
    {
        $entry = new StringEntry("test");

        $this->expectException(InvalidArgumentException::class);

        $entry->setMaxLength(10)->setMinLength(11);
    }

    public function testMaxLengthLowerThanMinLength(): void
    {
        $entry = new StringEntry("test");

        $this->expectException(InvalidArgumentException::class);

        $entry->setMinLength(8)->setMaxLength(7);
    }
}