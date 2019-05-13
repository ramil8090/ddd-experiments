<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 16:42
 */

namespace Blog\Infrastructure\Persistence\Doctrine\Type\Common;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getId();
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace().'\\'.$this->getName();
        return new $className($value);
    }
}