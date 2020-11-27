<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

use ArrayIterator;
use IteratorAggregate;

abstract class EntityCollection implements IteratorAggregate, EntityCollectionInterface
{
    protected $entities;

    public function __construct(array $entities = [])
    {
        $this->entities = $entities;
    }

    public function getEntities() : array
    {
        return $this->entities;
    }

    public function addEntity(EntityInterface $entity) : void
    {
        $this->entities[] = $entity;
    }

    public function addEntities(array $entities_to_add): void
    {
        foreach ($entities_to_add as $entity) {
            $this->addEntity($entity);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getEntities());
    }

    public function isEmpty() : bool
    {
        return empty($this->entities);
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->entities as $entity) {
            $data[] = $entity->toArray();
        }

        return $data;
    }
}