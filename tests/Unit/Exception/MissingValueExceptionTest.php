<?php

namespace Jandi\Config\Test\Unit;

use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\MissingValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Exception\MissingValueException
 */
final class MissingValueExceptionTest extends TestCase
{
    public function testGetter(): void
    {
        $entry = $this->createMock(AbstractEntry::class);
        $exception = new MissingValueException('message', $entry);

        $this->assertSame($entry, $exception->getEntry());
    }
}
