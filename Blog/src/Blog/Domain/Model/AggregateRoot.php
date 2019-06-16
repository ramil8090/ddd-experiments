<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 07.06.19
 * Time: 16:22
 */

namespace Blog\Domain\Model;


interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}