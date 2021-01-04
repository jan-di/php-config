<?php

namespace Jandi\Config\Test\Unit\Entry;

use Jandi\Config\Entry\StringEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\StringEntry
 *
 * @uses \Jandi\Config\Entry\AbstractEntry
 * @uses \Jandi\Config\Exception\InvalidValueException
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

    public function testMinLengthMethods(): void
    {
        $entry = new StringEntry('test1');

        $entry->setMinLength(40);
        $this->assertSame(40, $entry->getMinLength());
    }

    public function testMaxLengthMethods(): void
    {
        $entry = new StringEntry('test1');

        $entry->setMaxLength(50);
        $this->assertSame(50, $entry->getMaxLength());
    }

    public function testAllowedValuesMethods(): void
    {
        $entry = new StringEntry('test1');

        $entry->setAllowedValues(['value1', 'value2']);
        $this->assertEqualsCanonicalizing(['value1', 'value2'], $entry->getAllowedValues());
    }

    public function testValidAllowedValues(): void
    {
        $entry = (new StringEntry('VAL1'))->setAllowedValues(['development']);

        $this->assertSame('development', $entry->checkValue('development'));
    }

    public function testInvalidAllowedValues(): void
    {
        $entry = (new StringEntry('VAL1'))->setAllowedValues(['development']);

        $this->expectException(InvalidValueException::class);

        $entry->checkValue('value');
    }

    public function testRegexPatternMethods(): void
    {
        $entry = new StringEntry('test1');

        $entry->setRegexPattern('/pattern/');
        $this->assertSame('/pattern/', $entry->getRegexPattern());
    }

    public function testValidRegexPatternValue(): void
    {
        $entry = (new StringEntry('test1'))->setRegexPattern('/abc/');

        $this->assertSame('abc', $entry->checkValue('abc'));
    }

    public function testInvalidRegexPatternValue(): void
    {
        $entry = (new StringEntry('test1'))->setRegexPattern('/abc/');

        $this->expectException(InvalidValueException::class);

        $entry->checkValue('def');
    }
}
