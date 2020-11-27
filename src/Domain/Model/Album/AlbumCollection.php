<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Album;

use SpotifyTest\Application\Exception\InvalidArgumentException;
use SpotifyTest\Domain\Foundation\Entity\EntityCollection;
use SpotifyTest\Domain\Foundation\Entity\EntityInterface;

class AlbumCollection extends EntityCollection
{
    public function addEntity(EntityInterface $entity) : void
    {
        if(!($entity instanceof Album)){
            throw new InvalidArgumentException();
        }
        parent::addEntity($entity);
    }
}