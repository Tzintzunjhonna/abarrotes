<?php

namespace ABARROTES\Admin\User\Application\UseCases;

use ABARROTES\Admin\User\Domain\Contracts\IUserRepository;

class UserCollectionUseCase
{
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($pageSize, $search = "")
    {
        return $this->repository->paginate($pageSize, $search);
    }
}
