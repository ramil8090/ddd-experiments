<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 02.05.19
 * Time: 22:00
 */

namespace Blog\Application\Service;


class TransactionalApplicationService implements ApplicationService
{
    /**
     * @var TransactionalSession
     */
    private $session;
    /**
     * @var ApplicationService
     */
    private $service;

    public function __construct(
        ApplicationService $service,
        TransactionalSession $session
    )
    {
        $this->session = $session;
        $this->service = $service;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $operation = function () use ($request) {
            return $this->service->execute($request);
        };

        return $this->session->executeAtomically($operation);
    }
}