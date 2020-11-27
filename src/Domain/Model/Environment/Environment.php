<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Environment;

use SpotifyTest\Application\Exception\InvalidArgumentValueException;
use SpotifyTest\Application\Exception\MissingArgumentException;
use SpotifyTest\Domain\Foundation\Entity\ValueObject;

final class Environment extends ValueObject
{
    const DEV  = 'dev';
    const PROD = 'prod';
    const TEST = 'test';

    public function __construct($value)
    {
        if(empty($value)) {
            throw new MissingArgumentException();
        }

        if(!in_array($value, $this->getValidEnvironments())){
            throw new InvalidArgumentValueException();
        }

        $this->value = $value;
    }

    private function getValidEnvironments()
    {
        return [
            self::DEV,
            self::PROD,
            self::TEST,
        ];
    }

    public function isProduction()
    {
        return $this->getValue() === self::PROD;
    }

    public function isDevelopment()
    {
        return $this->getValue() === self::DEV;
    }

    public function isTest()
    {
        return $this->getValue() === self::TEST;
    }

}