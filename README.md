<h1 align="center">jan-di/config</h1>
<p align="center">
    <a alt="Packagist" href="https://packagist.org/packages/jan-di/config"><img src="https://img.shields.io/packagist/v/jan-di/config"></a>
    <a alt="Packagist" href="https://packagist.org/packages/jan-di/config"><img src="https://img.shields.io/packagist/php-v/jan-di/config"/></a>
    <a alt="LICENSE" href="https://github.com/jan-di/php-config/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/jan-di/config"/></a>
</p>

## Install

Install via composer:

`composer require jan-di/config`

## Usage

### Basic Usage:

When creating the Config Builder, specify all definitions for the config values. You can use certain type specific constraints to validate the values later. By default, values are read from the environment variables. Optionally you can enable a Dotenv Adapter to add variables from a .env file to the environment before building the config.

```php
use Dotenv\Dotenv;
use Jandi\Config\ConfigBuilder;
use Jandi\Config\Dotenv\VlucasDotenvAdapter;
use Jandi\Config\Entry\StringEntry;

// define all config entries via a fluent API:
$configBuilder = new ConfigBuilder([
    (new StringEntry('APP_ENV'))->setDefaultValue('development'),
    (new StringEntry('APP_TOKEN'))->setMinLength(20)
]);

// optional: Add a Dotenv loader to load .env files in environment before building config
$dotenv = Dotenv::createImmutable(__DIR__, '');
$configBuilder->enableDotEnv(new VlucasDotenvAdapter($dotenv));

// build config array from environment variables
// The values will be fetched validated against the rules from the entries.
$config = $configBuilder->build();
```

### Configuration Cache

To improve performance and prevent that .env files are parsed on every request, the config values should be cached. This will create a compiled PHP file that holds all fetched values. This file should be cached by Opcache to optimize further.

```php
use Dotenv\Dotenv;
use Jandi\Config\ConfigBuilder;
use Jandi\Config\Dotenv\VlucasDotenvAdapter;
use Jandi\Config\Entry\StringEntry;

// build config, see above
$configBuilder = new ConfigBuilder([
    (new StringEntry('APP_ENV'))->setDefaultValue('development'),
    (new StringEntry('APP_TOKEN'))->setMinLength(20)
]);
$configBuilder
    ->enableCaching('/path/to/cache/file') // Activate caching
    ->enableDotEnv(new VlucasDotenvAdapter(Dotenv::createImmutable(__DIR__, '')));

// Since caching is enabled, the builder will check if the cache file exists.
// if the cache file exists, no values are read from .env or the real environment.
$config = $configBuilder->build();

// The cache file is not created automatically. Instead you have to provide a condition,
// when the cache has to be written. Often this is related to the values inside the config.
if ($config->get('APP_ENV') === 'production') {
    $configBuilder->dumpCache($config);
}
```

## Appendix

### DotEnv Adapters

Currently, the following Dotenv Libraries are supported out of the box:

- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
- [symfony/dotenv](https://github.com/symfony/dotenv)
- [josegonzalez/dotenv](https://github.com/josegonzalez/php-dotenv)

Alternatively you can provide you own by implementing [AdapterInterface](https://github.com/jan-di/php-config/blob/main/src/Dotenv/AdapterInterface.php)

### Entry Types

- StringEntry (MinLength, MaxLength, AllowedValues, RegexPattern)