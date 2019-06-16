<?php

namespace Blog\Infrastructure\Persistence\Doctrine\Type\Blog;


use Blog\Domain\Model\Blog\Title;
use Blog\Domain\Model\Member\Owner;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineOwnerType extends Type
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
        return 'owner';
    }

    /**
     * @param Owner $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return json_encode([
            'username' => $value->username(),
            'email' => $value->email(),
            'full_name' => $value->fullName()
        ]);
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Owner
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = json_decode($value, true);
        return new Owner(
            $values['username'],
            $values['email'],
            $values['full_name']
        );
    }
}