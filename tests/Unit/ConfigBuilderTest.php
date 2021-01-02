<?php

namespace Jandi\Config\Test\Unit;

use InvalidArgumentException;
use Jandi\Config\ConfigBuilder;
use Jandi\Config\Dotenv\AdapterInterface;
use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\KeyNotFoundException;
use LogicException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jandi\Config\ConfigBuilder
 * @uses \Jandi\Config\Entry\AbstractEntry
 * @uses \Jandi\Config\Config
 * @uses \Jandi\Config\Dotenv\AdapterInterface
 */
final class ConfigBuilderTest extends TestCase
{
    /**
     * @backupGlobals enabled
     */
    public function testWithExistingValue(): void {
        $_SERVER['APP_VAR1'] = 'valuex';

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR1');
        $entry->expects($this->once())->method('checkValue')->with('valuex')->willReturn('valuex');

        $builder = new ConfigBuilder([$entry]);
        $config = $builder->build();

        $this->assertSame('valuex', $config->get('APP_VAR1'));
    }

    public function testWithDefaultValue(): void {
        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR2');
        $entry->method('getDefaultValue')->willReturn('valuey');
        $entry->expects($this->once())->method('checkValue')->with('valuey')->willReturn('valuey');

        $builder = new ConfigBuilder([$entry]);
        $config = $builder->build();

        $this->assertSame('valuey', $config->get('APP_VAR2'));
    }

    public function testWithMissingValue(): void {
        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR3');
        $entry->method('getDefaultValue')->willReturn(null);

        $builder = new ConfigBuilder([$entry]);
        $this->expectException(KeyNotFoundException::class);
        $builder->build();
    }

    public function testNoDuplicateEntries(): void {
        $entry1 = $this->createStub(AbstractEntry::class);
        $entry1->method('getKey')->willReturn('UNIQUE');
        $entry2 = clone $entry1;

        $this->expectException(InvalidArgumentException::class);

        new ConfigBuilder([$entry1, $entry2]);
    }

    public function testDotenvAdapterIsUsed(): void {
        $adapter = $this->createMock(AdapterInterface::class);
        $adapter->expects($this->once())->method('load');

        $builder = new ConfigBuilder([]);
        $builder->enableDotEnv($adapter);
        $builder->build();
    }

    public function testCachedFileIsRead(): void {
        $vfs = vfsStream::setup();

        $content = '<?php return '.var_export(['VAR80' => 'value80'], true).';'.PHP_EOL;
        file_put_contents($vfs->url().'/cache', $content);

        $builder = new ConfigBuilder([]);
        $builder->enableCaching($vfs->url().'/cache');
        $config = $builder->build();

        $this->assertSame('value80', $config->get('VAR80'));
    }

    public function testNoCacheDumpWithoutCachePath(): void {
        $builder = new ConfigBuilder([]);
        $config = $builder->build();

        $this->expectException(LogicException::class);
        $builder->dumpCache($config);
    }

    public function testCacheDumpIsCreated(): void {
        $vfs = vfsStream::setup();

        $entry = $this->createMock(AbstractEntry::class);
        $entry->method('getKey')->willReturn('APP_VAR20');
        $entry->method('getDefaultValue')->willReturn('variable');
        $entry->method('checkValue')->willReturn('variable');

        $builder = new ConfigBuilder([$entry]);
        $builder->enableCaching($vfs->url().'/cache');
        $config = $builder->build();
        $builder->dumpCache($config);

        $cachedConfig = require $vfs->url().'/cache';
        $this->assertEqualsCanonicalizing(['APP_VAR20' => 'variable'], $cachedConfig);
    }

    public function testCacheParentDirIsCreated(): void {
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