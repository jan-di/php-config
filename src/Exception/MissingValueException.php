<?php

declare(strict_types=1);

namespace Jandi\Config\Exception;

use Jandi\Config\Entry\AbstractEntry;
use RuntimeException;

class MissingValueException extends RuntimeException
{
    private AbstractEntry $entry;

    public function __construct(AbstractEntry $entry)
    {
        parent::__construct('Variable is missing and has no default value.');

        $this->entry = $entry;
    }

    public function getEntry(): AbstractEntry
    {
        return $this->entry;
    }
}
