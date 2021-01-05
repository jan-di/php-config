<?php

namespace Jandi\Config\Test\Unit;

use Dotenv\Dotenv as VlucasDotenv;
use Jandi\Config\ConfigBuilder;
use Jandi\Config\Dotenv\JosegonzalesDotenvAdapter;
use Jandi\Config\Dotenv\SymfonyDotenvAdapter;
use Jandi\Config\Dotenv\VlucasDotenvAdapter;
use Jandi\Config\Entry\IntEntry;
use josegonzalez\Dotenv\Loader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv as SymfonyDotenv;

/**
 * @coversNothing
 */
final class DotEnvLoadingTest extends TestCase
{
    private vfsStreamDirectory $vfs;
    private ConfigBuilder $builder;

    protected function setUp(): void
    {
        unset($_SERVER['APP_VAR']);
        $this->vfs = vfsStream::setup();
        file_put_contents($this->vfs->url().'/.env', 'APP_VAR=1450');

        $this->builder = new ConfigBuilder([
            new IntEntry('APP_VAR', '11'),
        ]);
    }

    /**
     * @backupGlobals enabled
     */
    public function testVlucasDotEnvAdapterLoadValue(): void
    {
        $dotenv = VlucasDotenv::createImmutable($this->vfs->url());
        $this->builder->enableDotEnv(new VlucasDotenvAdapter($dotenv));
        $config = $this->builder->build();

        $this->assertSame(1450, $config->getValue('APP_VAR'));
    }

    public function testVlucasDotEnvAdapterIgnoreMissingFile(): void
    {
        $dotenv = VlucasDotenv::createImmutable($this->vfs->url().'/not-found');
        $this->builder->enableDotEnv(new VlucasDotenvAdapter($dotenv));
        $config = $this->builder->build();

        $this->assertSame(11, $config->getValue('APP_VAR'));
    }

    /**
     * @backupGlobals enabled
     */
    public function testSymfonyDotenvAdapterLoadValue(): void
    {
        $dotenv = new SymfonyDotenv();
        $this->builder->enableDotEnv(new SymfonyDotenvAdapter($dotenv, $this->vfs->url().'/.env'));
        $config = $this->builder->build();

        $this->assertSame(1450, $config->getValue('APP_VAR'));
    }

    public function testSymfonyDotenvAdapterIgnoreMissingFile(): void
    {
        $dotenv = new SymfonyDotenv();
        $this->builder->enableDotEnv(new SymfonyDotenvAdapter($dotenv, $this->vfs->url().'/not.found'));
        $config = $this->builder->build();

        $this->assertSame(11, $config->getValue('APP_VAR'));
    }

    /**
     * @backupGlobals enabled
     */
    public function testJosegonzalesDotenvAdapterLoadValue(): void
    {
        $dotenv = new Loader($this->vfs->url().'/.env');
        $this->builder->enableDotEnv(new JosegonzalesDotenvAdapter($dotenv));
        $config = $this->builder->build();

        $this->assertSame(1450, $config->getValue('APP_VAR'));
    }

    public function testJosegonzalesDotenvAdaptergnoreMissingFile(): void
    {
        $dotenv = new Loader($this->vfs->url().'/not.found');
        $this->builder->enableDotEnv(new JosegonzalesDotenvAdapter($dotenv));
        $config = $this->builder->build();

        $this->assertSame(11, $config->getValue('APP_VAR'));
    }
}
