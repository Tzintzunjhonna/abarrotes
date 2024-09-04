<?php

namespace abarrotes\Admin\User\Infrastructure\Controllers\Create;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use abarrotes\Admin\Shared\Infrastructure\Services\Utils;
use abarrotes\Admin\User\Application\UseCases\UserCreatorUseCase;
use abarrotes\Admin\User\Infrastructure\Persistence\EloquentUserRepository;

class CreateController extends Controller
{
    private $repository;
    private $createUserUseCase;

    public function __construct(EloquentUserRepository $repository)
    {
        $this->repository = $repository;
        $this->createUserUseCase = new UserCreatorUseCase($this->repository);
    }

    public function __invoke(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $id        = Utils::generateUuid();
            $name      = $request->name;
            $firstName = $request->first_name;
            $lastName  = $request->last_name ?? "";
            $phone     = $request->phone;
            $email     = $request->email;
            $password  = Utils::encryptPassword($request->password);
            $role      = $request->role;
            $status    = 1;

            $user = $this->createUserUseCase->execute($id, $name, $firstName, $lastName, $phone, $email,
                $password, $role, $status);

            DB::commit();
            return response()->success(
                $user->toArray(),
                Response::HTTP_CREATED
            );

        } catch (\InvalidArgumentException $exception) {
            DB::rollBack();
            return response()->fail('Fail', $exception->getMessage());

        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage()
            ];
           
            return response()->error('Error al crear usuario', $response);
        }
    }
}
