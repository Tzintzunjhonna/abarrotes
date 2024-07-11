<?php

namespace ABARROTES\Admin\User\Application\UseCases;

use ABARROTES\Admin\Shared\Domain\ValueObjects\Uuid;
use ABARROTES\Admin\User\Domain\Contracts\IUserRepository;
use ABARROTES\Admin\User\Domain\Entities\User;

class UserFinderUseCase
{
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id) :? User
    {
        return $this->repository->getById(new Uuid($id));
    }
}
