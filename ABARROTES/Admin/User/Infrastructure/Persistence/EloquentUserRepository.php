<?php

namespace abarrotes\Admin\User\Infrastructure\Persistence;

use abarrotes\Admin\Shared\Domain\Enum\Status;
use abarrotes\Admin\Shared\Domain\ValueObjects\Uuid;
use abarrotes\Admin\User\Domain\Contracts\IUserRepository;
use abarrotes\Admin\User\Domain\Entities\User;
use abarrotes\Admin\User\Infrastructure\Models\UserEloquentModel;
use App\Models\User as ModelsUser;

class EloquentUserRepository implements IUserRepository
{
    private $model;

    public function __construct()
    {
        //$this->model = new UserEloquentModel();
        $this->model = new ModelsUser();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $pageNumber, string $search = "")
    {
        return $this->model->with('roles')->where(function ($query) use ($search) {
            if($search){
                $strtolower = strtolower($search);
                $query->whereRaw('LOWER(name) LIKE (?) ',["%{$strtolower}%"]);
            }
        })->orWhere(function ($query) use ($search) {
            if($search){
                $strtolower = strtolower($search);
                $query->whereRaw('LOWER(first_name) LIKE (?) ',["%{$strtolower}%"]);
            }
        })->orWhere(function ($query) use ($search) {
            if($search){
                $strtolower = strtolower($search);
                $query->whereRaw('LOWER(last_name) LIKE (?) ',["%{$strtolower}%"]);
            }
        })->orWhere(function ($query) use ($search) {
            if($search){
                $strtolower = strtolower($search);
                $query->whereRaw('LOWER(email) LIKE (?) ',["%{$strtolower}%"]);
            }
        })->orWhere(function ($query) use ($search) {
            if($search){
                $strtolower = strtolower($search);
                $query->whereRaw('LOWER(phone) LIKE (?) ',["%{$strtolower}%"]);
            }
        })->orderBy(UserEloquentModel::CREATED_AT, 'asc')->paginate($pageNumber);

    }

    public function getById(Uuid $id) :? User
    {
        $user = $this->model->firstWhere(UserEloquentModel::ID, $id->value());

        if ( !$user) {
            return null;
        }

        return new User(
            $user->id,
            $user->name,
            $user->first_name,
            $user->last_name,
            $user->phone,
            $user->email,
            $user->role,
            $user->status,
        );
    }
    
    public function save(User $user): User
    {
        $data = [
            'id'         => $user->id(),
            'name'       => $user->name(),
            'first_name' => $user->firstName(),
            'last_name'  => $user->lastName(),
            'phone'      => $user->phone(),
            'email'      => $user->email(),
            'password'   => $user->password(),
            'role'       => $user->role()
        ];
        
        $newUser = $this->model->create($data);
        //$newUser->assignRole($user->role());
        $token = $newUser->createToken('auth_token')->plainTextToken;
        $user->setToken($token);

        return $user;
    }
    
    public function update(User $user, Uuid $id): User
    {
        $data = [
            'name'       => $user->name(),
            'first_name' => $user->firstName(),
            'last_name'  => $user->lastName(),
            'phone'      => $user->phone(),
            'email'      => $user->email(),
            'role'       => $user->role(),
            'status'  => $user->status()
        ];

        if ($user->password()) {
            $data['password'] = $user->password()->value();
        }
        
        $currentUser = $this->model->firstWhere(UserEloquentModel::ID, $id->value());
        $currentUser->update($data);

        return $user;
    }
    
    public function delete(Uuid $id): void
    {
        $this->model->where(UserEloquentModel::ID, $id->value())->delete();
    }

    public function validatePromoter(Uuid $userId)
    {
        return $this->model->where(UserEloquentModel::ID, $userId->value())
                ->where(UserEloquentModel::ROLE, 'Promotor')
                ->first();
    }
}
