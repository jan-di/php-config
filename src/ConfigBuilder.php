<?php

declare(strict_types=1);

namespace Jandi\Config;

use InvalidArgumentException;
use Jandi\Config\Dotenv\AdapterInterface;
use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\KeyNotFoundException;
use LogicException;

class ConfigBuilder
{
    private ?AdapterInterface $dotenvAdapter = null;
    private ?string $cacheFile = null;
    private array $entries = [];

    public function __construct(array $entries)
    {
        $this->addEntries(...$entries);
    }

    /**
     * @psalm-suppress UnresolvableInclude
     */
    public function build(): Config
    {
        if ($this->cacheFile !== null && file_exists($this->cacheFile)) {
            $values = require $this->cacheFile;
        } else {
            $values = [];

            // load .env files
            if ($this->dotenvAdapter !== null) {
                $this->dotenvAdapter->load();
            }

            // load and validate each entry
            foreach ($this->entries as $entry) {
                $value = $this->getEnv($entry->getKey());
                if ($value === null) {
                    if ($entry->getDefaultValue() === null) {
                        throw new KeyNotFoundException('Mandatory Variable '.$entry->getKey().' is missing!');
                    }
                    $value = $entry->getDefaultValue();
                }

                $values[$entry->getKey()] = $entry->checkValue($value);
            }
        }

        return new Config($values);
    }

    public function dumpCache(Config $config): void
    {
        if ($this->cacheFile === null) {
            throw new LogicException('Must enable caching before creating a cached config.');
        }

        $cacheDir = dirname($this->cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $content = '<?php return '.var_export($config->export(), true).';'.PHP_EOL;
        file_put_contents($this->cacheFile, $content);
    }

    public function addEntries(AbstractEntry ...$entries): self
    {
        foreach ($entries as $entry) {
            if (isset($this->entries[$entry->getKey()])) {
                throw new InvalidArgumentException('There is already an Entry defined for key "'.$entry->getKey().'"');
            }
            $this->entries[$entry->getKey()] = $entry;
        }

        return $this;
    }

    public function enableCaching(string $cacheFile): self
    {
        $this->cacheFile = $cacheFile;

        return $this;
    }

    public function enableDotEnv(AdapterInterface $dotenvAdapter): self
    {
        $this->dotenvAdapter = $dotenvAdapter;

        return $this;
    }

    private function getEnv(string $key): ?string
    {
        return $_SERVER[$key] ?? null;
    }
}
