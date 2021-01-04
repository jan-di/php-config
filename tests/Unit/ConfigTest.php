<?php

namespace Jandi\Config\Test\Unit;

use Jandi\Config\Config;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Config
 */
final class ConfigTest extends TestCase
{
    public function testGetValue(): void
    {
        $config = new Config(['KEY' => 'value']);

        $this->assertSame('value', $config->get('KEY'));
    }

    public function testKeyNotFound(): void
    {
        $config = new Config([]);

        $this->expectException(OutOfBoundsException::class);

        $config->get('NOT');
    }

    public function testHasValue(): void
    {
        $config = new Config(['KEY' => 'value']);

        $this->assertTrue($config->has('KEY'));
        $this->assertFalse($config->has('NOT'));
    }

    public function testExportIsUnchanged(): void
    {
        $config = new Config([
            'KEY1' => 'val1',
            'KEY3' => 'val3',
            'KEY2' => 'val2',
        ]);

        $this->assertEqualsCanonicalizing([
            'KEY1' => 'val1',
            'KEY2' => 'val2',
            'KEY3' => 'val3',
        ], $config->export());
    }

    public function testExportIsImportable(): void
    {
        $config1 = new Config([
            'KEY1' => 'val1',
            'KEY2' => 2,
        ]);
        $config2 = new Config($config1->export());

        $this->assertEqualsCanonicalizing($config1->export(), $config2->export());
    }
}
