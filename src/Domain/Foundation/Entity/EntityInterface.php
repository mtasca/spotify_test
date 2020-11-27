<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

interface EntityInterface
{
    public function getId();
    public function getType() : string;
    public function getEntityId() : EntityIdInterface;
    public function toArray() : array;
}