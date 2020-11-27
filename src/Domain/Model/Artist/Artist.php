<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Artist;

use SpotifyTest\Domain\Foundation\Entity\Entity;
use SpotifyTest\Domain\Foundation\Entity\EntityIdInterface;
use SpotifyTest\Domain\Model\EntityType;

class Artist extends Entity
{
    public function __construct(EntityIdInterface $id, $data = [])
    {
        parent::__construct($id, $data);
    }

    public function getType(): string
    {
        return EntityType::ARTIST;
    }
}