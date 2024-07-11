<?php

namespace ABARROTES\Admin\User\Infrastructure\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ABARROTES\Admin\User\Application\UseCases\UserCollectionUseCase;
use ABARROTES\Admin\User\Infrastructure\Persistence\EloquentUserRepository;

class CollectionController extends Controller
{
    private $repository;
    private $collectionUserUseCase;

    public function __construct(EloquentUserRepository $repository)
    {
        $this->repository = $repository;
        $this->collectionUserUseCase = new UserCollectionUseCase($this->repository);
    }

    public function __invoke(Request $request)
    {
        try {
            $pageSize = $request->pageSize;
            $search   = $request->search ?? "";

            return response()->ok(
                $this->collectionUserUseCase->execute($pageSize, $search)
            );

        } catch (\Exception $exception) {
            return response()->internalServerError($exception->getMessage(), $request->all());
        }
    }
}
