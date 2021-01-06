<?php

namespace Jandi\Config\Test\Unit;

use InvalidArgumentException;
use Jandi\Config\Config;
use Jandi\Config\Value;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Config
 *
 * @uses \Jandi\Config\Value
 */
final class ConfigTest extends TestCase
{
    public function testGetExistingValue(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getValue')->willReturn('foo');
        $config = new Config([$value]);

        $this->assertSame('foo', $config->getValue('KEY'));
    }

    public function testGetMissingValue(): void
    {
        $config = new Config([]);

        $this->expectException(OutOfBoundsException::class);

        $config->getValue('NOT_FOUND');
    }

    public function testGetExistingDefaultValue(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getDefaultValue')->willReturn('bar');
        $config = new Config([$value]);

        $this->assertSame('bar', $config->getDefaultValue('KEY'));
    }

    public function testGetMissingDefaultValue(): void
    {
        $config = new Config([]);

        $this->expectException(OutOfBoundsException::class);

        $config->getDefaultValue('NOT_FOUND');
    }

    public function testExistingIsUserDefined(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('isUserDefined')->willReturn(true);
        $config = new Config([$value]);

        $this->assertTrue($config->isUserDefined('KEY'));
    }

    public function testMissingIsUserDefined(): void
    {
        $config = new Config([]);

        $this->expectException(OutOfBoundsException::class);

        $config->isUserDefined('NOT_FOUND');
    }

    public function testHasValue(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getValue')->willReturn('foo');
        $config = new Config([$value]);

        $this->assertTrue($config->has('KEY'));
        $this->assertFalse($config->has('NOT_FOUND'));
    }

    public function testSetDuplicateKey(): void
    {
        $value1 = $this->createMock(Value::class);
        $value1->method('getKey')->willReturn('KEY');
        $value2 = clone $value1;

        $this->expectException(InvalidArgumentException::class);

        new Config([$value1, $value2]);
    }

    public function testSetState(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');

        $config = Config::__set_state([
            'values' => ['KEY' => $value],
        ]);

        $this->assertTrue($config->has('KEY'));
        $this->assertTrue($config->isCached());
    }

    public function testExportValues(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getValue')->willReturn(5.7);

        $config = new Config([$value]);

        $this->assertEqualsCanonicalizing(['KEY' => 5.7], $config->exportValues());
    }

    public function testExportValuesWithPrefix(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getValue')->willReturn(5.7);

        $config = new Config([$value]);

        $this->assertEqualsCanonicalizing(['config:KEY' => 5.7], $config->exportValues('config:'));
    }

    public function testExportDefaultValues(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getDefaultValue')->willReturn(5.9);

        $config = new Config([$value]);

        $this->assertEqualsCanonicalizing(['KEY' => 5.9], $config->exportDefaultValues());
    }

    public function testExportDefaultValuesWithPrefix(): void
    {
        $value = $this->createMock(Value::class);
        $value->method('getKey')->willReturn('KEY');
        $value->method('getDefaultValue')->willReturn(5.9);

        $config = new Config([$value]);

        $this->assertEqualsCanonicalizing(['config:KEY' => 5.9], $config->exportDefaultValues('config:'));
    }
}
