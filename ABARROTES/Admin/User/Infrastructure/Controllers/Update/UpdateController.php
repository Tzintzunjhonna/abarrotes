<?php

namespace ABARROTES\Admin\User\Infrastructure\Controllers\Update;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Response;
use ABARROTES\Admin\Shared\Infrastructure\Services\Utils;
use ABARROTES\Admin\User\Application\UseCases\UserUpdateUseCase;
use ABARROTES\Admin\User\Application\UseCases\UserFinderUseCase;
use ABARROTES\Admin\User\Infrastructure\Persistence\EloquentUserRepository;

class UpdateController extends Controller
{
    private $repository;
    private $updateUserUseCase;
    private $findUserUseCase;

    public function __construct(EloquentUserRepository $repository)
    {
        $this->repository = $repository;
        $this->updateUserUseCase = new UserUpdateUseCase($this->repository);
        $this->findUserUseCase = new UserFinderUseCase($this->repository);
    }

    public function __invoke(UpdateUserRequest $request, string $id)
    {
        try {
            $name      = $request->name;
            $firstName = $request->first_name;
            $lastName  = $request->last_name ?? "";
            $phone     = $request->phone;
            $email     = $request->email;
            $password  = $request->password;
            $role      = $request->role;
            $status    = $request->status;

            if ( $password ) {
                $password = Utils::encryptPassword($password);
            }

            if ( !$this->findUserUseCase->execute($id) ) {
                return response()->error("No se encontró el usuario", Response::HTTP_NOT_FOUND);
            }

            return response()->success(
                $this->updateUserUseCase->execute($id, $name, $firstName, $lastName, $phone, $email,
                    $password, $role, $status)->toArray()
            );

        } catch (\Exception $exception) {
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ( $exception->getCode() ) {
                $code = $exception->getCode();
            }
            return response()->error('Error al actualizar usuario', $response, $code);
        }
    }
}
