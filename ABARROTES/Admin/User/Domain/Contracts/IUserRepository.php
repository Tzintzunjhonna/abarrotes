<?php

namespace abarrotes\Admin\User\Domain\Contracts;

use abarrotes\Admin\Shared\Domain\ValueObjects\Uuid;
use abarrotes\Admin\User\Domain\Entities\User;

interface IUserRepository
{
    public function all();
    public function paginate(int $pageNumber);
    public function save(User $user) : User;
    public function getById(Uuid $id) :? User;
    public function update(User $user, Uuid $id): User;
    public function delete(Uuid $id): void;
    public function validatePromoter(Uuid $id);
}
