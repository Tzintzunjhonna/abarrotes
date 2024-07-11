<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->descriptiveResponseMethods();

    }

    protected function descriptiveResponseMethods()
    {
        $instance = $this;

        Response::macro('ok', function ($data = [], $message = 'Ok') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'errors' => []
            ], HttpResponse::HTTP_OK);
        });

        Response::macro('created', function ($data = [], $message = 'Ok') {
            if (count($data)) {
                return Response::json([
                    'success' => true,
                    'message' => $message,
                    'data' => $data,
                    'errors' => []
                ], HttpResponse::HTTP_CREATED);
            }

            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'errors' => []
            ], HttpResponse::HTTP_CREATED);
        });

        Response::macro('noContents', function ($data = [], $message = 'Ok') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'errors' => []
            ], HttpResponse::HTTP_NO_CONTENT);
        });

        Response::macro('badRequest', function ($message = 'Validation Failure', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, HttpResponse::HTTP_BAD_REQUEST);
        });

        Response::macro('unauthorized', function ($message = 'User unauthorized', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, HttpResponse::HTTP_UNAUTHORIZED);
        });

        Response::macro('forbidden', function ($message = 'Access denied', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, HttpResponse::HTTP_FORBIDDEN);
        });

        Response::macro('notFound', function ($message = 'Resource not found.', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, HttpResponse::HTTP_NOT_FOUND);
        });

        Response::macro('internalServerError', function ($message = 'Internal Server Error.', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        });
    }

    public function handleErrorResponse($message, $errors, $status)
    {
        $response = [
            'message' => $message,
        ];

        if (count($errors)) {
            $response['errors'] = $errors;
        }

        return Response::json($response, $status);
    }

}
