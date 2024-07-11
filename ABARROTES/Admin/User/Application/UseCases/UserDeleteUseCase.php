<?php

namespace ABARROTES\Admin\User\Application\UseCases;

use ABARROTES\Admin\Shared\Domain\ValueObjects\Uuid;
use ABARROTES\Admin\User\Domain\Contracts\IUserRepository;

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
