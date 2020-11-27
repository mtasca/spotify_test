<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

use Traversable;

interface EntityCollectionInterface extends Traversable
{

    public function getEntities() : array;

    public function addEntity(EntityInterface $entity) : void;

    public function addEntities(array $entities) : void;

    public function isEmpty() : bool;
}