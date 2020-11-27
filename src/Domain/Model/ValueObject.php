<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model;

use SpotifyTest\Domain\Foundation\Entity\ValueObjectInterface;

abstract class ValueObject implements ValueObjectInterface
{

    protected $value;

    /**
     * Return the object as a string.
     *
     * @return string
     */
    public function toString()
    {
        return is_array($this->getValue())
            ? json_encode($this->getValue())
            : strval($this->getValue());
    }

    /**
     * Return the Value Object with its original type.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return the object as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}