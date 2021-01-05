<?php

namespace Jandi\Config\Test\Unit;

use InvalidArgumentException;
use Jandi\Config\Exception\BuildException;
use Jandi\Config\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Exception\BuildException
 */
final class BuildExceptionTest extends TestCase
{
    public function testGetter(): void
    {
        $childException = $this->createStub(InvalidValueException::class);
        $exception = new BuildException([$childException]);

        $this->assertEqualsCanonicalizing([$childException], $exception->getExceptions());
    }

    public function testTextSummary(): void
    {
        $childException = $this->createStub(InvalidValueException::class);
        $exception = new BuildException([$childException]);

        $this->assertTrue(strlen($exception->getTextSummary()) > 0);
    }

    public function testHtmlSummary(): void
    {
        $childException = $this->createStub(InvalidValueException::class);
        $exception = new BuildException([$childException]);

        $this->assertTrue(strlen($exception->getHtmlSummary()) > 0);
    }

    public function testWithoutExceptions(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $exception = new BuildException([]);
    }
}
