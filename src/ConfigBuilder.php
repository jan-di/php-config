<?php

declare(strict_types=1);

namespace Jandi\Config;

use InvalidArgumentException;
use Jandi\Config\Dotenv\AdapterInterface;
use Jandi\Config\Entry\AbstractEntry;
use Jandi\Config\Exception\BuildException;
use Jandi\Config\Exception\InvalidValueException;
use Jandi\Config\Exception\MissingValueException;
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
            $config = require $this->cacheFile;
        } else {
            $values = [];
            $exceptions = [];

            // load .env files
            if ($this->dotenvAdapter !== null) {
                $this->dotenvAdapter->load();
            }

            // load and validate each entry
            foreach ($this->entries as $entry) {
                $stringDefaultValue = $entry->getDefaultValue();
                try {
                    $defaultValue = $stringDefaultValue !== null ? $entry->checkValue($stringDefaultValue, true) : null;
                } catch (InvalidValueException $e) {
                    $exceptions[] = $e;
                    $defaultValue = null;
                }
                $stringValue = $this->getEnv($entry->getKey());
                if ($stringValue !== null) {
                    try {
                        $value = $entry->checkValue($stringValue);
                    } catch (InvalidValueException $e) {
                        $exceptions[] = $e;
                        $value = null;
                    }
                    $userDefined = true;
                } else {
                    $value = $defaultValue;
                    $userDefined = false;
                    if ($value === null) {
                        $exceptions[] = new MissingValueException($entry);
                    }
                }

                $values[] = new Value($entry->getKey(), $value, $defaultValue, $userDefined);
            }

            if (count($exceptions) > 0) {
                throw new BuildException($exceptions);
            }

            $config = new Config($values);
        }

        return $config;
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
        $content = '<?php return '.var_export($config, true).';'.PHP_EOL;
        file_put_contents($this->cacheFile, $content);
    }

    public function addEntries(AbstractEntry ...$entries): self
    {
        foreach ($entries as $entry) {
            if (isset($this->entries[$entry->getKey()])) {
                throw new InvalidArgumentException('Duplicate entry for key "'.$entry->getKey().'"');
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
        return isset($_SERVER[$key]) ? strval($_SERVER[$key]) : null;
    }
}
