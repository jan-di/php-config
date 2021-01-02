<?php

use Dotenv\Dotenv;
use Jandi\Config\Dotenv\DotenvDotenvAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Dotenv\DotenvDotenvAdapter
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 */
final class DotenvDotenvAdapterTest extends TestCase
{
    public function testCallsLoadMethod(): void {
        $dotenv = $this->createMock(Dotenv::class);
        $dotenv->expects($this->once())
            ->method('load')
            ->with();

        $adapter = new DotenvDotenvAdapter($dotenv);
        $adapter->load();
    }
}