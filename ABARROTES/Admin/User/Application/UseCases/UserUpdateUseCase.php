<?php

namespace abarrotes\Admin\User\Application\UseCases;

use abarrotes\Admin\Shared\Domain\ValueObjects\Uuid;
use abarrotes\Admin\User\Domain\Contracts\IUserRepository;
use abarrotes\Admin\User\Domain\Entities\User;
use abarrotes\Admin\User\Domain\ValueObjects\UserEmail;
use abarrotes\Admin\User\Domain\ValueObjects\UserName;
use abarrotes\Admin\User\Domain\ValueObjects\UserPassword;

class UserUpdateUseCase
{
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id,string $name,string $firstName,string $lastName,
        string $phone,string $email, string $password = null,string $role, $status): User
    {
        $userId = new Uuid($id);
        
        $user = new User($userId, $name, $firstName, $lastName, $phone, $email, $role, $status);

        if ( $password ) {
            $user->setPassword(new UserPassword($password));
        }
        
        $this->repository->update($user, $userId);
        return $user;
    }
}
