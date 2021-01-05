<?php

declare(strict_types=1);

namespace Jandi\Config\Exception;

use InvalidArgumentException;
use RuntimeException;

class BuildException extends RuntimeException
{
    private array $exceptions;

    public function __construct(array $exceptions)
    {
        if (count($exceptions) === 0) {
            throw new InvalidArgumentException('Must provide at least one sub-exception');
        }

        $this->exceptions = $exceptions;

        parent::__construct('Config cannot be built due to missing or invalid values.');
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function getTextSummary(): string
    {
        $result = 'There where '.count($this->exceptions).' error/s while building configuration:'.PHP_EOL.PHP_EOL;

        foreach ($this->exceptions as $exception) {
            $result .= $exception->getEntry()->getKey().' ['.$exception->getEntry()->getFriendlyType().'] '.$exception->getMessage().PHP_EOL;
        }

        return $result;
    }

    public function getHtmlSummary(): string
    {
        $result = '<p>There where '.count($this->exceptions).' error/s while building configuration:</p><ul>';

        foreach ($this->exceptions as $exception) {
            $result .= '<li><code>'.$exception->getEntry()->getKey().' ['.$exception->getEntry()->getFriendlyType().']</code> '.$exception->getMessage().'</li>';
        }

        $result .= '</ul>';

        return $result;
    }
}
