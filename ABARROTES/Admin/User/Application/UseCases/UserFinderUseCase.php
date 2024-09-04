<?php

namespace abarrotes\Admin\User\Application\UseCases;

use abarrotes\Admin\Shared\Domain\ValueObjects\Uuid;
use abarrotes\Admin\User\Domain\Contracts\IUserRepository;
use abarrotes\Admin\User\Domain\Entities\User;

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
