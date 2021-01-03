<?php

declare (strict_types=1);

namespace Jandi\Config\Dotenv;

interface AdapterInterface {
    public function load(): void;
}