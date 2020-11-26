<?php
declare(strict_types=1);
return [
    "name" => "SpotifyTest",
    "env" => isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : null,
    "debug" => filter_var(isset($_ENV['APP_DEBUG']) ? $_ENV['APP_DEBUG'] : false, FILTER_VALIDATE_BOOLEAN),
    "displayErrorDetails" => filter_var(isset($_ENV['APP_DISPLAY_ERROR_DETAILS']) ? $_ENV['APP_DISPLAY_ERROR_DETAILS'] : false, FILTER_VALIDATE_BOOLEAN),
];