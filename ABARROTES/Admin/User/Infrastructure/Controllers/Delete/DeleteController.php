<?php

namespace ABARROTES\Admin\User\Infrastructure\Controllers\Delete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use ABARROTES\Admin\User\Application\UseCases\UserDeleteUseCase;
use ABARROTES\Admin\User\Application\UseCases\UserFinderUseCase;
use ABARROTES\Admin\User\Infrastructure\Persistence\EloquentUserRepository;

class DeleteController extends Controller
{
    private $repository;
    private $deleteUserUseCase;
    private $findUserUseCase;

    public function __construct(EloquentUserRepository $repository)
    {
        $this->repository = $repository;
        $this->deleteUserUseCase = new UserDeleteUseCase($this->repository);
        $this->findUserUseCase = new UserFinderUseCase($this->repository);
    }

    public function __invoke(string $id)
    {
        try {

            if (!$this->findUserUseCase->execute($id)) {
                return response()->error("No se encontró el usurio", Response::HTTP_NOT_FOUND);
            }

            $this->deleteUserUseCase->execute($id);

            return response()->success([], Response::HTTP_NO_CONTENT);

        } catch (\Exception $exception) {
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage()
            ];

            return response()->error('Error al eliminar usuario', $response);
        }
    }
}
