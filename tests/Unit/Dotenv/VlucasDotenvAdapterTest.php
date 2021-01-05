<?php

namespace Jandi\Config\Test\Unit\Dotenv;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Jandi\Config\Dotenv\VlucasDotenvAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Dotenv\VlucasDotenvAdapter
 *
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 */
final class VlucasDotenvAdapterTest extends TestCase
{
    public function testCallsLoadMethod(): void
    {
        $dotenv = $this->createMock(Dotenv::class);
        $dotenv->expects($this->once())->method('load')->with();

        $adapter = new VlucasDotenvAdapter($dotenv);
        $adapter->load();
    }

    public function testIgnoresMissingFile(): void
    {
        $dotenv = $this->createMock(Dotenv::class);
        $dotenv->expects($this->once())->method('load')->willThrowException(new InvalidPathException());

        $adapter = new VlucasDotenvAdapter($dotenv);
        $adapter->load();
    }
}
