<?php

namespace Jandi\Config\Test\Unit;

use Jandi\Config\Value;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\Value
 */
final class ValueTest extends TestCase
{
    public function testAccessors(): void
    {
        $value = new Value('KEY1', 'value1', 'defaultValue1', true);

        $this->assertSame('KEY1', $value->getKey());
        $this->assertSame('value1', $value->getValue());
        $this->assertSame('defaultValue1', $value->getDefaultValue());
        $this->assertSame(true, $value->isUserDefined());
    }

    public function testSetState(): void
    {
        $value = Value::__set_state([
            'key' => 'KEY2',
            'value' => 'value2',
            'defaultValue' => 'defaultValue2',
            'userDefined' => true,
        ]);

        $this->assertSame('KEY2', $value->getKey());
        $this->assertSame('value2', $value->getValue());
        $this->assertSame('defaultValue2', $value->getDefaultValue());
        $this->assertSame(true, $value->isUserDefined());
    }
}
