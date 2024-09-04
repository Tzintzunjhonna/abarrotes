<?php

namespace abarrotes\Admin\User\Application\UseCases;

use abarrotes\Admin\Shared\Domain\ValueObjects\Uuid;
use abarrotes\Admin\User\Domain\Contracts\IUserRepository;

class UserDeleteUseCase
{
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id)
    {
        return $this->repository->delete(new Uuid($id));
    }
}
