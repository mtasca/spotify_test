<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

interface ValueObjectInterface
{
    /**
     * Return the Value Object as a string.
     *
     * @return string
     */
    public function toString();

    /**
     * Return the Value Object with its original type.
     *
     * @return mixed
     */
    public function getValue();
}