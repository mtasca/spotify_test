<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Spotify;

use SpotifyTest\Application\Exception\MissingArgumentException;
use SpotifyTest\Domain\Foundation\Entity\ValueObject;

final class BaseUri extends ValueObject
{
    public function __construct($value)
    {
        if(empty($value)) {
            throw new MissingArgumentException();
        }

        $this->value = $value;
    }
}