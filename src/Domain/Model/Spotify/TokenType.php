<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Spotify;

use SpotifyTest\Application\Exception\InvalidArgumentValueException;
use SpotifyTest\Application\Exception\MissingArgumentException;
use SpotifyTest\Domain\Foundation\Entity\ValueObject;

final class TokenType extends ValueObject
{
    const USER_TOKEN = 'user';
    const APP_TOKEN  = 'token';

    public function __construct($value)
    {
        if(empty($value)) {
            throw new MissingArgumentException();
        }

        if(!in_array($value, [self::USER_TOKEN, self::APP_TOKEN]))
        {
            throw new InvalidArgumentValueException();
        }

        $this->value = $value;
    }
}