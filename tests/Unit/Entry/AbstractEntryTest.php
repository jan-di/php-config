<?php

namespace Jandi\Config\Test\Unit\Entry;

use InvalidArgumentException;
use Jandi\Config\Entry\StringEntry;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Entry\AbstractEntry
 *
 * @uses \Jandi\Config\Entry\StringEntry
 */
final class AbstractEntryTest extends TestCase
{
    public function testValidKey(): void
    {
        $entry = new StringEntry('TEST_KEY');

        $this->assertSame('TEST_KEY', $entry->getKey());
    }

    public function testInvalidKey(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new StringEntry('KEY=');
    }

    public function testDefaultValueMethods(): void
    {
        $entry = (new StringEntry('TEST_KEY'))->setDefaultValue('xyz');

        $this->assertSame('xyz', $entry->getDefaultValue());
    }
}
