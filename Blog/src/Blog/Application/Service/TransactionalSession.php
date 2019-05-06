<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 02.05.19
 * Time: 21:58
 */

namespace Blog\Application\Service;


interface TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}