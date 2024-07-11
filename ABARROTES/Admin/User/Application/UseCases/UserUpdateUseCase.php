<?php

namespace ABARROTES\Admin\User\Application\UseCases;

use ABARROTES\Admin\Shared\Domain\ValueObjects\Uuid;
use ABARROTES\Admin\User\Domain\Contracts\IUserRepository;
use ABARROTES\Admin\User\Domain\Entities\User;
use ABARROTES\Admin\User\Domain\ValueObjects\UserEmail;
use ABARROTES\Admin\User\Domain\ValueObjects\UserName;
use ABARROTES\Admin\User\Domain\ValueObjects\UserPassword;

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
