<?php

namespace ABARROTES\Admin\User\Application\UseCases;

use ABARROTES\Admin\Shared\Domain\ValueObjects\Uuid;
use ABARROTES\Admin\User\Domain\Contracts\IUserRepository;
use ABARROTES\Admin\User\Domain\Entities\User;
use ABARROTES\Admin\User\Domain\ValueObjects\UserEmail;
use ABARROTES\Admin\User\Domain\ValueObjects\UserName;
use ABARROTES\Admin\User\Domain\ValueObjects\UserPassword;

class UserCreatorUseCase
{
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id, string $name, string $firstName, string $lastName, string $phone,
        string $email, string $password, string $role, int $status)
    {
        $userId        = $id;
        $userName      = $name;
        $userFirstName = $firstName;
        $userLastName  = $lastName;
        $userPhone     = $phone;
        $userEmail     = $email;
        $userPassword  = $password;
        $userRole      = $role;

        $user = new User($userId, $userName, $userFirstName, $userLastName, $userPhone, $userEmail, $userRole, $status);
        $user->setPassword($userPassword);
        
        return $this->repository->save($user);
    }
}
