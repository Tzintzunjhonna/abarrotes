<?php

namespace ABARROTES\Admin\User\Infrastructure\Controllers\Find;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use ABARROTES\Admin\User\Application\UseCases\UserFinderUseCase;
use ABARROTES\Admin\User\Infrastructure\Persistence\EloquentUserRepository;

class FindController extends Controller
{
    private $repository;
    private $userFinderUseCase;

    public function __construct(EloquentUserRepository $repository)
    {
        $this->repository = $repository;
        $this->userFinderUseCase = new UserFinderUseCase($this->repository);
    }

    public function __invoke(string $id)
    {
        try {
            $user = $this->userFinderUseCase->execute($id);
            if ( !$user ) {
                return response()->error("No se encontró el usuario", Response::HTTP_NOT_FOUND);
            }

            return response()->success($user->toArray());

        } catch (\Exception $exception) {
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage()
            ];
            return response()->error("Error al obtener usuario", $response);
        }
    }
}
