<?php

use App\Http\Middleware\ModifyHeader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('api')
                ->namespace('App\\Http\\Controllers')
                ->prefix('')
                ->group(base_path('./routes/api.php'));
        },
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ModifyHeader::class);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'DNS1D' => 'Milon\Barcode\Facades\DNS1DFacade',
            'DNS2D' => 'Milon\Barcode\Facades\DNS2DFacade',
            'PDF' => Barryvdh\DomPDF\Facade\Pdf::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            return response()->json((['status' => 404, 'message' => 'The requested resource was not found.']), 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) {
            return response()->json((['status' => 405, 'message' => 'Method Not Allowed.']), 405);
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            return response()->json((['status' => 405, 'message' => 'Resource not found with the specific id.']), 405);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            return response()->json((['status' => 403, 'message' => $exception->getMessage() ? $exception->getMessage() : "You don't have access!"]), 403);
        });

        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $exception, Request $request) {
            return response()->json((['status' => 403, 'message' => $exception->getMessage() ? $exception->getMessage() : "You don't have access!"]), 403);
        });

    })->create();
