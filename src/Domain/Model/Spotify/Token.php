<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Spotify;

use SpotifyTest\Application\Exception\MissingArgumentException;
use SpotifyTest\Domain\Foundation\Entity\ValueObject;

final class Token extends ValueObject
{
    const BEARER_TOKEN = 'Bearer';

    public function __construct($value)
    {
        if(empty($value)) {
            throw new MissingArgumentException();
        }

        $this->value = $value;
    }

    public function getBearerToken() : string
    {
        return sprintf('%s %s', self::BEARER_TOKEN, $this->getValue());
    }
}