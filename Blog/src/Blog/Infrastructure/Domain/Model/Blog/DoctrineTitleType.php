<?php

namespace Blog\Infrastructure\Domain\Model\Blog;


use Blog\Domain\Model\Blog\Title;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineTitleType extends Type
{

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return 'title';
    }

    /**
     * @param Title $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->title();
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Title
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Title($value);
    }
}