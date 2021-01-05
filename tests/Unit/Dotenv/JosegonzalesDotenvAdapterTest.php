<?php

namespace Jandi\Config\Test\Unit\Dotenv;

use Jandi\Config\Dotenv\JosegonzalesDotenvAdapter;
use josegonzalez\Dotenv\Loader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Dotenv\JosegonzalesDotenvAdapter
 *
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 */
final class JosegonzalesDotenvAdapterTest extends TestCase
{
    public function testCallsLoadMethod(): void
    {
        $dotenv = $this->createMock(Loader::class);
        $dotenv->expects($this->once())->method('parse')->with()->willReturn($dotenv);
        $dotenv->expects($this->once())->method('skipExisting')->with()->willReturn($dotenv);
        $dotenv->expects($this->once())->method('toServer')->with();

        $adapter = new JosegonzalesDotenvAdapter($dotenv);
        $adapter->load();
    }
}
