<?php
declare(strict_types=1);
return [
    'host' => isset($_ENV['REDIS_HOST']) ? $_ENV['REDIS_HOST'] : null,
    'port' => isset($_ENV['REDIS_PORT']) ? $_ENV['REDIS_PORT'] : null,
    'db' => isset($_ENV['REDIS_DB']) ? $_ENV['REDIS_DB'] : null,
];