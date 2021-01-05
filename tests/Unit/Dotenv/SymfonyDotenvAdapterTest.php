<?php

namespace Jandi\Config\Test\Unit\Dotenv;

use Jandi\Config\Dotenv\SymfonyDotenvAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

/**
 * @covers \Jandi\Config\Dotenv\SymfonyDotenvAdapter
 *
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 */
final class SymfonyDotenvAdapterTest extends TestCase
{
    public function testCallsLoadMethod(): void
    {
        $dotenv = $this->createMock(Dotenv::class);
        $dotenv->expects($this->once())->method('load')->with('/path/to/file');

        $adapter = new SymfonyDotenvAdapter($dotenv, '/path/to/file');
        $adapter->load();
    }

    public function testIgnoresMissingFile(): void
    {
        $dotenv = $this->createMock(Dotenv::class);
        $dotenv->expects($this->once())->method('load')->willThrowException(new PathException(''));

        $adapter = new SymfonyDotenvAdapter($dotenv, '/path/to/file');
        $adapter->load();
    }
}
