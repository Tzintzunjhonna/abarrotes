<?php

namespace abarrotes\Admin\User\Application\UseCases;

use abarrotes\Admin\User\Domain\Contracts\IUserRepository;

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
