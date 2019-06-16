<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 6/16/19
 * Time: 7:35 PM
 */

namespace Blog\Infrastructure\Persistence\Doctrine\Type\Blog;


use Blog\Domain\Model\Member\Author;

class DoctrineAuthorsType extends Type
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
        return 'authors';
    }

    /**
     * @param Author[] $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return json_encode($value);
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Author[]
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = json_decode($value, true);

        return array_map(function($author) {
            return new Author(
                $author['username'],
                $author['email'],
                $author['fullName']
            );
        }, $values);
    }
}