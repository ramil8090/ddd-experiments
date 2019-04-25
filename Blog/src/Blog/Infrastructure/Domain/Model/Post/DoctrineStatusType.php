<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 16:07
 */

namespace Blog\Infrastructure\Domain\Model\Post;


use Blog\Domain\Model\Post\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class StatusType extends Type
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
        return 'status';
    }

    /**
     * @param Status $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->status();
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Status
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        switch ($value) {
            case Status::RECENT:
                return  Status::recent();
            case Status::PUBLISHED:
                return Status::published();
            case Status::DELETED:
                return Status::deleted();
        }
    }
}