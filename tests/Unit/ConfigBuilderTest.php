<?php

namespace Jandi\Config\Test\Unit;

use InvalidArgumentException;
use Jandi\Config\ConfigBuilder;
use Jandi\Config\Dotenv\AdapterInterface;
use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\BuildException;
use Jandi\Config\Exception\InvalidValueException;
use LogicException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\ConfigBuilder
 *
 * @uses \Jandi\Config\Entry\AbstractEntry
 * @uses \Jandi\Config\Config
 * @uses \Jandi\Config\Value
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 * @uses \Jandi\Config\Exception\MissingValueException
 * @uses \Jandi\Config\Exception\InvalidValueException
 * @uses \Jandi\Config\Exception\BuildException
 */
final class ConfigBuilderTest extends TestCase
{
    /**
     * @backupGlobals enabled
     */
    public function testWithExistingValue(): void
    {
        $_SERVER['APP_VAR1'] = 'valuex';

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR1');
        $entry->expects($this->once())->method('checkValue')->with('valuex')->willReturn('valuex');

        $builder = new ConfigBuilder([$entry]);
        $config = $builder->build();

        $this->assertSame('valuex', $config->getValue('APP_VAR1'));
    }

    public function testWithDefaultValue(): void
    {
        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR2');
        $entry->method('getDefaultValue')->willReturn('valuey');
        $entry->expects($this->once())->method('checkValue')->with('valuey')->willReturn('valuey');

        $builder = new ConfigBuilder([$entry]);
        $config = $builder->build();

        $this->assertSame('valuey', $config->getValue('APP_VAR2'));
    }

    public function testWithMissingValue(): void
    {
        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR3');
        $entry->method('getDefaultValue')->willReturn(null);

        $builder = new ConfigBuilder([$entry]);
        $this->expectException(BuildException::class);
        $builder->build();
    }

    /**
     * @backupGlobals enabled
     */
    public function testWithInvalidValue(): void
    {
        $_SERVER['APP_VAR4'] = 'valuex';

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR4');
        $entry->method('getDefaultValue')->willReturn('value2');
        $entry->method('checkValue')->will($this->returnCallback(function (string $value) use ($entry) {
            if ($value === 'value2') {
                return 'value2';
            }
            throw new InvalidValueException('invalid', $entry, $value, false);
        }));

        $builder = new ConfigBuilder([$entry]);
        $this->expectException(BuildException::class);
        $builder->build();
    }

    public function testWithInvalidDefaultValue(): void
    {
        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR5');
        $entry->method('getDefaultValue')->willReturn('invalid');
        $entry->method('checkValue')->with('invalid')->willThrowException(new InvalidValueException('reason', $entry, 'invalid', true));

        $builder = new ConfigBuilder([$entry]);
        $this->expectException(BuildException::class);
        $builder->build();
    }

    public function testNoDuplicateEntries(): void
    {
        $entry1 = $this->createStub(AbstractEntry::class);
        $entry1->method('getKey')->willReturn('UNIQUE');
        $entry2 = clone $entry1;

        $this->expectException(InvalidArgumentException::class);

        new ConfigBuilder([$entry1, $entry2]);
    }

    public function testEnableDotenvFluent(): void
    {
        $builder = new ConfigBuilder([]);
        $adapter = $this->createMock(AdapterInterface::class);

        $this->assertSame($builder, $builder->enableDotEnv($adapter));
    }

    public function testDotenvAdapterIsUsed(): void
    {
        $adapter = $this->createMock(AdapterInterface::class);
        $adapter->expects($this->once())->method('load');

        $builder = new ConfigBuilder([]);
        $builder->enableDotEnv($adapter);
        $builder->build();
    }

    public function testEnableCachingFluent(): void
    {
        $builder = new ConfigBuilder([]);

        $this->assertSame($builder, $builder->enableCaching('path'));
    }

    public function testCachedFileIsRead(): void
    {
        $vfs = vfsStream::setup();

        $content = <<<'EOT'
            <?php return Jandi\Config\Config::__set_state(array(
                'values' => 
               array (
                 'APP_ENV' => 
                 Jandi\Config\Value::__set_state(array(
                    'key' => 'APP_ENV',
                    'value' => 'development',
                    'defaultValue' => 'development',
                    'userDefined' => false,
                 )),
               ),
                'cached' => false,
             ));
            EOT;

        file_put_contents($vfs->url().'/cache', $content);

        $builder = new ConfigBuilder([]);
        $builder->enableCaching($vfs->url().'/cache');
        $config = $builder->build();

        $this->assertSame('development', $config->getValue('APP_ENV'));
    }

    public function testNoCacheDumpWithoutCachePath(): void
    {
        $builder = new ConfigBuilder([]);
        $config = $builder->build();

        $this->expectException(LogicException::class);
        $builder->dumpCache($config);
    }

    /**
     * @psalm-suppress UnresolvableInclude
     */
    public function testCacheDumpIsCreated(): void
    {
        $vfs = vfsStream::setup();

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR20');
        $entry->method('getDefaultValue')->willReturn('variable');
        $entry->method('checkValue')->willReturn('variable');

        $builder = new ConfigBuilder([$entry]);
        $builder->enableCaching($vfs->url().'/cache');
        $config = $builder->build();
        $builder->dumpCache($config);

        $this->assertFileExists($vfs->url().'/cache');
    }

    public function testCacheParentDirIsCreated(): void
    {
        $vfs = vfsStream::setup();

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR20');
        $entry->method('getDefaultValue')->willReturn('variable');
        $entry->method('checkValue')->willReturn('variable');

        $builder = new ConfigBuilder([$entry]);
        $builder->enableCaching($vfs->url().'/path/to/cache/file');
        $config = $builder->build();
        $builder->dumpCache($config);

        $this->assertDirectoryExists($vfs->url().'/path/to/cache');
    }
}
