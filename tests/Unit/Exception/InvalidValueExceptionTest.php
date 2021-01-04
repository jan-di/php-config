<?php

namespace Jandi\Config\Test\Unit;

use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Exception\InvalidValueException
 */
final class InvalidValueExceptionTest extends TestCase
{
    public function testGetter(): void
    {
        $entry = $this->createMock(AbstractEntry::class);
        $exception = new InvalidValueException('message', $entry, 'value');

        $this->assertSame($entry, $exception->getEntry());
        $this->assertSame('value', $exception->getValue());
    }
}
