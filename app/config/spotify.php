<?php
declare(strict_types=1);
return [
    'accounts_base_uri' => isset($_ENV['SPOTIFY_ACCOUNTS_BASE_URI']) ? $_ENV['SPOTIFY_ACCOUNTS_BASE_URI'] : null,
    'api_base_uri' => isset($_ENV['SPOTIFY_API_BASE_URI']) ? $_ENV['SPOTIFY_API_BASE_URI'] : null,
    'client_id' => isset($_ENV['SPOTIFY_CLIENT_ID']) ? $_ENV['SPOTIFY_CLIENT_ID'] : null,
    'client_secret' => isset($_ENV['SPOTIFY_CLIENT_SECRET']) ? $_ENV['SPOTIFY_CLIENT_SECRET'] : null,
    'redirect_uri' => isset($_ENV['SPOTIFY_REDIRECT_URI']) ? $_ENV['SPOTIFY_REDIRECT_URI'] : null,
    'scope' => isset($_ENV['SPOTIFY_SCOPE']) ? $_ENV['SPOTIFY_SCOPE'] : null,
];