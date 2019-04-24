<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 14:56
 */

namespace Blog\Domain\Model\Common;


class Id
{
    /**
     * @var string
     */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId() :string
    {
        return $this->id;
    }

    public function equals(self $id): bool
    {
        return $this->getId() === $id->getId();
    }

    ### Doctine need methods
    public function __toString()
    {
        return $this->id;
    }
}