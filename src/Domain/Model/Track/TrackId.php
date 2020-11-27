<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Track;

use SpotifyTest\Domain\Foundation\Entity\EntityId;
use SpotifyTest\Domain\Model\EntityType;

class TrackId extends EntityId
{
    public function isValid($id) : bool
    {
        if(!is_string($id)) {
            return false;
        }

        return true;
    }

    public function getType(): string
    {
        return EntityType::ALBUM;
    }


}