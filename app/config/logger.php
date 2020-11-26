<?php
declare(strict_types=1);
return [
    "name" => isset($_ENV['LOG_NAME']) ? $_ENV['LOG_NAME'] : null,
    "path" => isset($_ENV['LOG_PATH']) ? $_ENV['LOG_PATH'] : null,
    "level" => isset($_ENV['LOG_LEVEL']) ? $_ENV['LOG_LEVEL'] : null,
];