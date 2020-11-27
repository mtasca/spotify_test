<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

use SpotifyTest\Application\Exception\InvalidArgumentValueException;
use SpotifyTest\Application\Exception\MissingArgumentException;

abstract class EntityId implements EntityIdInterface
{
    protected $id;

    abstract protected function isValid($id) : bool;
    abstract public function getType(): string;

    public function __construct($id)
    {
        if (empty($id)) {
            throw new MissingArgumentException('Missing id');
        }

        if (!$this->isValid($id)) {
            throw new InvalidArgumentValueException(sprintf(
                'invalid id %s', $id
            ));
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function equals(EntityIdInterface $entity_id) : bool
    {
        return $entity_id instanceof $this
            && $entity_id->getType() === $this->getType()
            && $entity_id->getId() === $this->getId();
    }

    public function __toString()
    {
        return strval($this->getId());
    }
}