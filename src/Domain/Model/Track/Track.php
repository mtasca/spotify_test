<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Track;

use SpotifyTest\Domain\Foundation\Entity\Entity;
use SpotifyTest\Domain\Foundation\Entity\EntityIdInterface;
use SpotifyTest\Domain\Model\EntityType;

class Track extends Entity
{
    public function __construct(EntityIdInterface $id, $data = [])
    {
        parent::__construct($id, $data);
    }

    public function getType(): string
    {
        return EntityType::TRACK;
    }
}